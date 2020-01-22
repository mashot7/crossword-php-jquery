<?php
// ----------------------------------------------------------------------------
// This file is part of PHP Crossword.
//
// PHP Crossword is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
// 
// PHP Crossword is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with Foobar; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
// ----------------------------------------------------------------------------

/**
 * PHP Crossword Generator
 *
 * @package    PHP_Crossword
 * @copyright  Laurynas Butkus, 2004
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    0.2
 */

define("_PC_DIR", dirname(__FILE__) . "/");

require_once _PC_DIR . "php_crossword_grid.class.php";
require_once _PC_DIR . "php_crossword_cell.class.php";
require_once _PC_DIR . "php_crossword_word.class.php";

define("PC_AXIS_H", 1);
define("PC_AXIS_V", 2);
define("PC_AXIS_BOTH", 3);
define("PC_AXIS_NONE", 4);
define("PC_WORDS_FULLY_CROSSED", 10);

class PHP_Crossword {

  var $rows = 15;

  var $cols = 15;

  var $grid;

  var $max_full_tries = 10;

  var $max_words = 15;

  var $max_tries = 50;

  var $table = "words";

  var $groupId = "common";

  var $db;

  var $_match_line;

  var $_full_tries = 0;

  var $_tries = 0;

  var $_debug = FALSE;

  var $_items;

  /**
   * Constructor
   *
   * @param int $rows
   * @param int $cols
   */
  function __construct($rows = 15, $cols = 15) {
    $this->rows = (int) $rows;
    $this->cols = (int) $cols;

    // connect to the database
    $this->db = new MySQL;
  }

  /**
   * Set words group ID
   *
   * @param string $groupId
   */
  function setGroupID($groupId) {
    $this->groupId = $groupId;
  }

  /**
   * Enable / disable debugging
   *
   * @param boolean $debug
   */
  function setDebug($debug = TRUE) {
    $this->_debug = (boolean) $debug;
  }

  /**
   * Set number of words the crossword shoud have
   *
   * @param int $max_words
   */
  function setMaxWords($max_words) {
    $this->max_words = (int) $max_words;
  }

  /**
   * Set maximum number of tries to generate full crossword
   *
   * @param int $max_full_tries
   */
  function setMaxFullTries($max_full_tries) {
    $this->max_full_tries = (int) $max_full_tries;
  }

  /**
   * Set max tries to pick the words
   *
   * @param int $max_tries
   */
  function setMaxTries($max_tries) {
    $this->max_tries = (int) $max_tries;
  }

  /**
   * Generate crossword
   *
   * @return boolean TRUE - if succeeded, FALSE - if unable to get required
   *   number of words
   */
  function generate() {
    // set the number of full tries
    $this->_full_tries = 0;

    // try to generate until we get required number of words
    while ($this->_full_tries < $this->max_full_tries) {
      // reset grid
      $this->reset();

      // count number of tried to generate crossword
      // with required number of words
      $this->_full_tries++;

      // pick and place first word
      $this->__placeFirstWord();

      // try to find other words and place them
      $this->__autoGenerate();

      //dump($this->grid->countWords());

      // if we have enough words -
      if ($this->grid->countWords() == $this->max_words) {
        $this->_items = $this->__getItems();
        return TRUE;
      }
    }

    if ($this->_debug) {
      echo "ERROR: unable to generate {$this->max_words} words crossword (tried {$this->_full_tries} times)";
    }

    return FALSE;
  }

  /**
   * Reset grid
   */
  function reset() {
    // create new grid object
    $this->grid = new PHP_Crossword_Grid($this->rows, $this->cols);

    // reset number of tries to pick words
    $this->_tries = 0;

    // reset crossword items
    $this->_items = NULL;
  }

  /**
   * Get crossword HTML (useful for generator debugging)
   *
   * @param array params
   *
   * @return string HTML
   */
  function getHTML($params = []) {
    return $this->grid->getHTML($params);
  }

  /**
   * Get crossword items
   *
   * @return array
   */
  function getWords() {
    return $this->_items;
  }

  /**
   * Get crossword items array
   *
   * @private
   * @return array
   */
  function __getItems() {
    $items = [];

    for ($i = 0; $i < count($this->grid->words); $i++) {
      $w =& $this->grid->words[$i];

      $items[] = [
        "word" => $w->word,
        "question" => $this->getQuestion($w->word),
        "x" => $w->getStartX() + 1,
        "y" => $w->getStartY() + 1,
        "axis" => $w->axis,
      ];
    }

    return $items;
  }

  /**
   * Get question for the word
   *
   * @param string $word
   *
   * @return string $question
   */
  function getQuestion($word) {
    $sql = "SELECT question FROM {$this->table} WHERE groupid='{$this->groupId}' AND word = '{$word}'";
    $row = $this->db->sql_row($sql);
    return $row[0];
  }

  /**
   * Try to generate crossword automatically
   * (until we get enough word or reach number of maximum tries
   *
   * @private
   */
  function __autoGenerate() {
    while ($this->grid->countWords() < $this->max_words && $this->_tries < $this->max_tries) {
      $this->_tries++;

      // dump( "Words: " . $this->grid->countWords() . ", Tries: $this->_tries" );

      $w =& $this->grid->getRandomWord();

      if ($w == PC_WORDS_FULLY_CROSSED) {
        // echo "NOTE: All words fully crossed...";
        break;
      }

      $axis = $w->getCrossAxis();
      $cells =& $w->getCrossableCells();

      // dump( "TRYING WORD: ".$w->word );

      while (count($cells)) {
        $n = array_rand($cells);
        $cell =& $cells[$n];

        //dump( "TRYING CELL: [$cell->x/$cell->y]:". $cell->letter );
        //dump( "COUNT CELLS: ". count($cells) );

        $list =& $this->__getWordWithStart($cell, $axis);
        $word = $list[0];
        $start =& $list[1];

        if ($start) {
          $this->grid->placeWord($word, $start->x, $start->y, $axis);
          break;
        }

        //dump( "CAN'T FIND CROSSING FOR: ".$cells[$n]->letter );
        $cells[$n]->setCanCross($axis, FALSE);
        unset($cells[$n]);
      }
    }
  }

  /**
   * Try to pick the word crossing the cell
   *
   * @private
   *
   * @param object $cell Cell object to cross
   * @param int $axis
   *
   * @return array Array of 2 items - word and start cell object
   */
  function __getWordWithStart(&$cell, $axis) {
    $start = &$this->grid->getStartCell($cell, $axis);
    $end = &$this->grid->getEndCell($cell, $axis);

    $word = $this->__getWord($cell, $start, $end, $axis);

    if (!$word) {
      return NULL;
    }

    $pos = NULL;

    do {
      // dump( $this->_match_line );
      $s_cell = &$this->__calcStartCell($cell, $start, $end, $axis, $word, $pos);
      $can = $this->grid->canPlaceWord($word, $s_cell->x, $s_cell->y, $axis);

      //if ( !$can )
      // dump(strtoupper("Wrong start position [{$s_cell->x}x{$s_cell->y}]! Relocating..."));

    } while (!$can);

    return [$word, &$s_cell];
  }

  /**
   * Calculate starting cell for the word
   *
   * @private
   *
   * @param object $cell crossing cell
   * @param object $start minimum starting cell
   * @param object $end maximum ending cell
   * @param int $axis
   * @param string $word
   * @param int $pos last position
   * return object|FALSE starting cell object or FALSE ir can't find
   *
   * @return bool
   */
  function &__calcStartCell(&$cell, &$start, &$end, $axis, $word, &$pos) {
    $x = $cell->x;
    $y = $cell->y;

    if ($axis == PC_AXIS_H) {
      $t =& $x;
      $s = $cell->x - $start->x;
      $e = $end->x - $cell->x;
    }
    else {
      $t =& $y;
      $s = $cell->y - $start->y;
      $e = $end->y - $cell->y;
    }

    $l = strlen($word);

    do {
      $offset = isset($pos) ? $pos + 1 : 0;
      $pos = strpos($word, $cell->letter, $offset);
      $a = $l - $pos - 1;
      if ($pos <= $s && $a <= $e) {
        $t -= $pos;
        return $this->grid->cells[$x][$y];
      }
    } while ($pos !== FALSE);

    return FALSE;
  }

  /**
   * Try to get the word
   *
   * @private
   *
   * @param object $cell crossing cell
   * @param object $start minimum starting cell
   * @param object $end maximum ending cell
   * @param int $axis
   *
   * @return string word
   */
  function __getWord(&$cell, &$start, &$end, $axis) {
    $this->_match_line = $this->__getMatchLine($cell, $start, $end, $axis);
    $match = $this->__getMatchLike($this->_match_line);
    $min = $this->__getMatchMin($this->_match_line);
    $max = strlen($this->_match_line);
    $regexp = $this->__getMatchRegexp($this->_match_line);

    $rs = $this->__loadWords($match, $min, $max);

    return $this->__pickWord($rs, $regexp);
  }

  /**
   * Pick the word from the mysqli_result
   *
   * @private
   *
   * @param mysqli_result $rs
   * @param string $regexp Regexp to match
   * return string|NULL word or NULL if couldn't find
   *
   * @return mixed|null
   */
  function __pickWord(&$rs, $regexp) {
    $n = mysqli_num_rows($rs);
    if (!$n) {
      return NULL;
    }

    $list = range(0, $n - 1);

    while (count($list)) {
      $i = array_rand($list);
      mysqli_data_seek($rs, $i);
      $row = mysqli_fetch_row($rs);

      if (preg_match("/{$regexp}/", $row[0])) {
        mysqli_free_result($rs);
        return $row[0];
      }

      unset($list[$i]);
    }

    mysqli_free_result($rs);

    return NULL;
  }

  /**
   * Generate word matching line
   *
   * @private
   *
   * @param object $cell crossing cell
   * @param object $start minimum starting cell
   * @param object $end maximum ending cell
   * @param int $axis
   *
   * @return string matching line
   */
  function __getMatchLine(&$cell, &$start, &$end, $axis) {
    $x = $start->x;
    $y = $start->y;

    if ($axis == PC_AXIS_H) {
      $n =& $x;
      $max = $end->x;
    }
    else {
      $n =& $y;
      $max = $end->y;
    }

    $str = '';

    while ($n <= $max) {
      $cell =& $this->grid->cells[$x][$y];
      $str .= isset($cell->letter) ? $cell->letter : '_';
      $n++;
    }

    return $str;
  }

  /**
   * Get minimum match string
   *
   * @private
   *
   * @param string $str match string
   *
   * @return string
   */
  function __getMatchMin($str) {
    $str = preg_replace_callback("/^_+/", function () {
      return "";
    }, $str, 1);
    $str = preg_replace_callback("/_+$/", function () {
      return "";
    }, $str, 1);
    return strlen($str);
  }

  /**
   * Get SQL LIKE match for the match string
   *
   * @private
   *
   * @param string $str match string
   *
   * @return string
   */
  function __getMatchLike($str) {
    $str = preg_replace_callback("/^_+/", function () {
      return "%";
    }, $str, 1);
    $str = preg_replace_callback("/_+$/", function () {
      return "%";
    }, $str, 1);
    return $str;
  }

  /**
   * Get REGEXP for the match string
   *
   * @private
   *
   * @param string $str match string
   *
   * @return string
   */
  function __getMatchRegexp($str) {
    $str = preg_replace_callback("/^_*/", function ($matches) {
      return '^.{0,' . strlen($matches[0]) . '}';
    }, $str, 1);
    $str = preg_replace_callback("/_*$/", function ($matches) {
      return '.{0,' . strlen($matches[0]) . '}$';
    }, $str, 1);
    $str = preg_replace_callback("/_+/", function ($matches) {
      return '.{' . strlen($matches[0]) . '}';
    }, $str);
    return $str;
  }

  /**
   * Place first word to the cell
   *
   * @private
   */
  function __placeFirstWord() {
    $word = $this->__getRandomWord($this->grid->getCols());

    $x = $this->grid->getCenterPos(PC_AXIS_H, $word);
    $y = $this->grid->getCenterPos(PC_AXIS_V);

    $this->grid->placeWord($word, $x, $y, PC_AXIS_H);
  }

  /**
   * Load words for the match
   *
   * @private
   *
   * @param string $match SQL LIKE match
   * @param int $len_min minimum length of the word
   * @param int $len_max maximum length of the word
   *
   * @return bool|\mysqli_result
   */
  function __loadWords($match, $len_min, $len_max) {
    $used_words_sql = $this->__getUsedWordsSql();

    $sql = "SELECT word FROM {$this->table} WHERE
			groupid='{$this->groupId}' AND
            LENGTH(word)<={$len_max} AND
            LENGTH(word)>={$len_min} AND
            word LIKE '{$match}'
            {$used_words_sql}
            ";

    // dump($sql);
    return $this->db->sql_result($sql);
  }

  /**
   * Get used word SQL
   *
   * @private
   * return string
   */
  function __getUsedWordsSql() {
    $sql = '';

    for ($i = 0; $i < count($this->grid->words); $i++) {
      $sql .= "AND word!='" . $this->grid->words[$i]->word . "' ";
    }

    return $sql;
  }

  /**
   * Get random word
   *
   * @private
   *
   * @param int $max_length maximum word length
   *
   * @return string word
   */
  function __getRandomWord($max_length) {
    $where = "LENGTH(word)<={$max_length}";

    $count = $this->__getWordsCount($where);

    if (!$count) {
      die("ERROR: there is no words to fit in this grid");
    }

    $n = rand(0, $count - 1);

    $sql = "SELECT word FROM {$this->table}
            WHERE groupid='{$this->groupId}' AND {$where}
            LIMIT {$n}, 1";

    $row = $this->db->sql_row($sql);

    return $row[0];
  }

  /**
   * Count words
   *
   * @private
   *
   * @param string $where SQL where
   *
   * @return int
   */
  function __getWordsCount($where = NULL) {
    $where_sql = $where ? "AND {$where}" : "";

    $sql = "SELECT COUNT(word) FROM {$this->table} 
			WHERE groupid='{$this->groupId}' {$where_sql}";

    $row = $this->db->sql_row($sql);
    return $row[0];
  }

  /**
   * Check if the word already exists in the database
   *
   * @param string $word
   *
   * @return boolean
   */
  function existsWord($word) {
    $sql = "SELECT word FROM {$this->table} WHERE 
			groupid = '{$this->groupId}' AND
			UPPER(word) = UPPER('{$word}')";
    $obj = $this->db->sql_object($sql);
    return $obj->word ? TRUE : FALSE;
  }

  function preg_callback($arg) {
    return $arg;
  }

  /**
   * Insert word into database
   *
   * @param string $word
   * @param string $question
   *
   * @return bool
   */
  function insertWord($word, $question) {
    $word = trim($word);
    $word = preg_replace_callback("/[\_\'\"\%\*\+\\\\\/\[\]\(\)\.\{\}\$\^\,\<\>\;\:\=\?\#\-]/", function () {
      return '';
    }, $word);
    if (empty($word)) {
      return FALSE;
    }
    if ($this->existsWord($word)) {
      return FALSE;
    }

    $sql = "INSERT INTO {$this->table}(groupid, word, question) 
			VALUES('{$this->groupId}', UPPER('{$word}'),'{$question}')";

    $this->db->sql_query($sql);
  }

  /**
   * Get generated crossword XML
   *
   * @return string XML
   */
  function getXML() {
    $words = $this->getWords();

    if (!count($words)) {
      return "<error>There are no words in the grid.</error>";
    }

    $xml = [];
    $xml[] = "<crossword>";

    $xml[] = "	<grid>";
    $xml[] = "		<cols>{$this->cols}</cols>";
    $xml[] = "		<rows>{$this->rows}</rows>";
    $xml[] = "		<words>" . count($words) . "</words>";
    $xml[] = "	</grid>";

    $xml[] = "	<items>";

    foreach ((array) $words as $item) {
      $xml[] = $this->__wordItem2XML($item, "\t\t");
    }

    $xml[] = "	</items>";

    if ($this->_debug) {
      $xml[] = "	<html>" . htmlspecialchars($this->grid->getHTML()) . "</html>";
    }

    $xml[] = "</crossword>";

    $xml = implode("\n", $xml);

    return $xml;
  }

  /**
   * Get XML of the word item
   *
   * @private
   *
   * @param object $item word item
   * @param string $ident
   *
   * @return string XML
   */
  function __wordItem2XML($item, $ident) {
    $xml = [];
    $xml[] = $ident . "<item>";

    foreach ((array) $item as $key => $val) {
      $key = htmlspecialchars($key);
      $val = htmlspecialchars($val);
      $xml[] = "	<{$key}>{$val}</{$key}>";
    }

    $xml[] = "</item>";

    $xml = implode("\n{$ident}", $xml);

    return $xml;
  }

  /**
   * Get number of words in the group
   *
   * @param string $groupId
   *
   * @return int
   */
  function countWordsInGroup($groupId = NULL) {
    if (empty($groupId)) {
      $groupId = $this->groupId;
    }
    $sql = "SELECT COUNT(*) FROM {$this->table} WHERE groupid='{$groupId}'";
    $row = $this->db->sql_row($sql);
    return (int) $row[0];
  }

  /**
   * Get list of available words' group ids
   *
   * @return array
   */
  function getGroupIDs() {
    $sql = "SELECT groupid FROM {$this->table} GROUP BY groupid ORDER BY groupid";
    $list = $this->db->sql_all_rows($sql);

    $ids = [];

    for ($i = 0; $i < count($list); $i++) {
      $ids[] = $list[$i][0];
    }

    return $ids;
  }

  /**
   * Check if the group id already exists in the database
   *
   * @param string $groupId
   *
   * @return boolean
   */
  function existsGroupID($groupId) {
    $sql = "SELECT groupid FROM {$this->table} WHERE groupid = '{$groupId}'";
    $row = $this->db->sql_row($sql);
    return !empty($row[0]) ? TRUE : FALSE;
  }

  /**
   * Generate temporary group id
   *
   * @return string group id
   */
  function createTempGroupID() {
    do {
      $groupId = rand(100000, 999999);
    } while ($this->existsGroupID($groupId));

    return $groupId;
  }

  /**
   * Remove all words from the group
   *
   * @param string $groupId
   */
  function removeGroup($groupId = NULL) {
    if (is_null($groupId)) {
      $groupId = $this->groupId;
    }

    $sql = "DELETE FROM {$this->table} WHERE groupid='{$groupId}'";

    $this->db->sql_query($sql);

    $sql = "OPTIMIZE TABLE {$this->table}";

    $this->db->sql_query($sql);
  }

  /**
   * Generate crossword from provided words list
   *
   * @param string $words_list
   *
   * @return boolean TRUE on success
   */
  function generateFromWords($words_list) {
    // save current settings
    $_tmp_groupid = $this->groupId;
    $_max_words = $this->max_words;

    // create temporary group
    $groupId = $this->createTempGroupID();

    // set temp group as current group
    $this->setGroupID($groupId);

    // split words list and  insert into temp group
    foreach (explode("\n", $words_list) as $line) {
      foreach (explode(" ", $line) as $word) {
        $this->insertWord($word, '');
      }
    }

    // try to generate crossword from all passed words
    $required_words = $this->countWordsInGroup();

    // if user entered more words then max_words - require max_words...
    if ($required_words > $_max_words) {
      $required_words = $_max_words;
    }

    $success = FALSE;

    while ($required_words > 1) {
      $this->setMaxWords($required_words);

      if ($success = $this->generate()) {
        break;
      }

      $required_words--;
    }

    // remove temporary group
    $this->removeGroup($groupId);

    // restore previous settings
    $this->setGroupID($_tmp_groupid);
    $this->setMaxWords($_max_words);

    return $success;
  }

}

