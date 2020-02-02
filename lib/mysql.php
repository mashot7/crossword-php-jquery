<?php

//        MySQL databases connectivity

class MySQL {

  var $conn;

  function __construct($host = _MYSQL_HOST, $db = _MYSQL_DB, $user = _MYSQL_USER, $passwd = _MYSQL_PASS) {
    $this->conn = mysqli_connect($host, $user, $passwd) or die ('Unable to connect to server.');
    mysqli_select_db($this->conn, $db) or die ('Unable to select database.');
  }

  // close connection
  function close() {
    mysqli_close($this->conn) or die ('Unable to close.');
  }

  // execute single query w/o returning results or if $ret = 'id' return index of new insertion
  function sql_query($query, $ret = "") {
    mysqli_query($this->conn, $query) or die("query error($query): " . mysqli_error($this->conn));
    if ($ret == 'id') {
      return mysqli_insert_id($this->conn);
    }
  }

  function sql_result($query) {
    $dbR = mysqli_query($this->conn, $query) or die("query error($query): " . mysqli_error($this->conn));

    return ($dbR);
  }

  // returning one(first) row object type results from DB for pref. $query
  function sql_object($query) {
    $dbR = mysqli_query($this->conn, $query) or die("object query error($query): " . mysqli_error($this->conn));
    $resL = mysqli_fetch_object($dbR);
    mysqli_free_result($dbR);
    return ($resL);
  }

  // returning one(first) row array type results from DB for pref. $query
  function sql_row($query) {
    $dbR = mysqli_query($this->conn, $query) or die("row query error($query): " . mysqli_error($this->conn));
    $resL = mysqli_fetch_row($dbR);
    mysqli_free_result($dbR);
    return ($resL);
  }

  function sql_array($query) {
    $dbR = mysqli_query($this->conn, $query) or die("row query error($query): " . mysqli_error($this->conn));
    $resL = mysqli_fetch_assoc($dbR);
    mysqli_free_result($dbR);
    return ($resL);
  }

  function sql_all_arrays($query) {
    $dbR = mysqli_query($this->conn, $query) or die("query error($query): " . mysqli_error($this->conn));
    while ($row = mysqli_fetch_assoc($dbR)) {
      $rows[] = $row;
    }
    return ($rows);
  }

  function sql_all_rows($query) {
    $dbR = mysqli_query($this->conn, $query) or die("query error($query): " . mysqli_error($this->conn));
    while ($row = mysqli_fetch_row($dbR)) {
      $rows[] = $row;
    }
    return ($rows);
  }

  function sql_all_objects($query, $type = "") {
    $dbR = mysqli_query($this->conn, $query) or die("all obj. error($query): " . mysqli_error($this->conn));

    $i = 1;
    if (mysqli_num_rows($dbR) > 0) {
      if (empty($type)) {
        while ($resL = mysqli_fetch_object($dbR)) {
          $resArray[$i] = $resL;
          $i++;
        }
      }
      else {
        while ($resL = mysqli_fetch_row($dbR)) {
          $resArray[$i] = $resL;
          $i++;
        }
      }
      mysqli_free_result($dbR);
    }
    else {
      return (FALSE);
    }

    return ($resArray);
  }

  // returning number of rows of selected query
  function sql_num_rows($query) {
    $dbR = mysqli_query($this->conn, $query) or die("num rows obj. error($query): " . mysqli_error($this->conn));
    return mysqli_num_rows($dbR);
  }

}
