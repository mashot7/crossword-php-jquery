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
use core\ArrayFilter;

/**
 * PHP Crossword Generator
 *
 * @package    PHP_Crossword
 * @copyright  Laurynas Butkus, 2004
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    0.2
 */

define("_PC_DIR", dirname(__FILE__) . "/");

require_once _PC_DIR . "CrosswordGrid.php";
require_once _PC_DIR . "CrosswordCell.php";
require_once _PC_DIR . "CrosswordWord.php";

define("PC_AXIS_H", 1);
define("PC_AXIS_V", 2);
define("PC_AXIS_BOTH", 3);
define("PC_AXIS_NONE", 4);
define("PC_WORDS_FULLY_CROSSED", 10);

class Crossword {

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

  var $_words = [];

  private $_currentWord;

  private $_used_words = [];

  /**
   * Constructor
   *
   * @param int $rows
   * @param int $cols
   * @param array $words
   */
  function __construct($rows = 15, $cols = 15, $words = []) {
    $this->rows = (int) $rows;
    $this->cols = (int) $cols;
    $this->_words = $words;
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
    $this->grid = new CrosswordGrid($this->rows, $this->cols);

    // reset number of tries to pick words
    $this->_tries = 0;

    // reset crossword items
    $this->_items = NULL;
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
        //        "question" => $this->getQuestion($w->word),
        "x" => $w->getStartX() + 1,
        "y" => $w->getStartY() + 1,
        "axis" => $w->axis,
      ];
    }

    return $items;
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

      if (is_numeric($w) && $w == PC_WORDS_FULLY_CROSSED) {
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

        $list = $this->__getWordWithStart($cell, $axis);
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
      $s_cell = &$this->__calcStartCell($cell, $start, $end, $axis, $word, $pos);
      $can = $this->grid->canPlaceWord($word, $s_cell->x, $s_cell->y, $axis);

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
   * @return object|FALSE starting cell object or FALSE ir can't find
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
    $min = $this->__getMatchMin($this->_match_line);
    $max = strlen($this->_match_line);
    $regexp = $this->__getMatchRegexp($this->_match_line);

    $subject = $this->_words;
    $arrayFilter = new ArrayFilter();
    $subject = $arrayFilter->doFilter($subject, '<=', $max);
    $subject = $arrayFilter->doFilter($subject, '>=', $min);
    $matches = preg_grep('/' . $regexp . '/', $subject);

    $this->_used_words = $this->__getUsedWordsArray();
    foreach ($this->_used_words as $used_word) {
      $arrayFilter->removeFromArray($matches, $used_word);
    }

    return $this->__pickWord( $regexp, $matches);
  }

  /**
   * Pick the word from the mysqli_result
   *
   * @private
   *
   * @param string $regexp Regexp to match
   * @param array $matches
   * @return string|NULL word or NULL if couldn't find
   */
  function __pickWord($regexp, &$matches) {

    $n = count($matches);
    if (!$n) {
      return NULL;
    }

    $list = range(0, $n - 1);

    while (count($list)) {
      $i = array_rand($list);
      $item = $matches[$i];

      if (preg_match("/{$regexp}/", $item)) {
        $matches = [];
        return $item;
      }

      unset($list[$i]);
    }

    $matches = [];

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
   * Get used word array
   *
   * @private
   * @return array words
   */
  function __getUsedWordsArray() {
    $words = [];
    for ($i = 0; $i < count($this->grid->words); $i++) {
      $words[] = $this->grid->words[$i]->word;
    }

    return $words;
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
    $filter = new ArrayFilter();
    $row = $filter->doFilter($this->_words, '<=', $max_length);
    $count = count($row);
    $n = rand(0, $count - 1);

    if (!$count) {
      die("ERROR: there is no words to fit in this grid");
    }
    $this->_currentWord = $row[$n];
    return $row[$n];
  }

  function preg_callback($arg) {
    return $arg;
  }

  /**
   * Generate crossword from provided words list
   *
   * @return boolean TRUE on success
   */
  function generateFromWords() {
    // save current settings
    $_max_words = $this->max_words;

    // try to generate crossword from all passed words
    $required_words = count($this->_words);

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

    return $success;
  }

}

