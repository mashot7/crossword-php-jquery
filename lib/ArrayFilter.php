<?php

namespace core;

class ArrayFilter {

  private $_num;

  private $_operator;

  private $_list;

  //  public function __construct($list) {
  //    $this->_list = $list;
  //  }

  private function _operators($a, $b, $char) {
    switch ($char) {
      case '==':
        return strlen($a) == $b;
      case '===':
        return strlen($a) === $b;
      case '!==':
        return strlen($a) !== $b;
      case '!=':
        return strlen($a) != $b;
      case '<=':
        return strlen($a) <= $b;
      case '>=':
        return strlen($a) >= $b;
      case '>':
        return strlen($a) > $b;
      case '<':
        return strlen($a) < $b;
      default:
      {
        return FALSE;
      }
    }
  }

  /**
   * @param $array
   * @param $operator
   * @param $num
   *
   * @return array
   */
  public function doFilter($array, $operator, $num) {
    $this->_list = $array;
    $this->_operator = $operator;
    $this->_num = $num;
    return array_filter($array, [$this, 'callbackMethodName']);
  }

  function removeFromArray(&$array, $item) {
    $new_array = [];
    foreach ($array as $value) {
      if ($value !== strtoupper($item)) {
        $new_array[] = $value;
      }
    }
    $array = $new_array;
  }

  protected function callbackMethodName($element) {
    return $this->_operators($element, $this->_num, $this->_operator);
  }

}