<?php

require 'config.php';
require 'lib/Crossword.php';
require 'lib/ArrayFilter.php';

$cols = isset($_REQUEST['cols']) ? (int) $_REQUEST['cols'] : 20;
$rows = isset($_REQUEST['rows']) ? (int) $_REQUEST['rows'] : 20;
$max_words = isset($_REQUEST['max_words']) ? (int) $_REQUEST['max_words'] : 20;
$max_tries = isset($_REQUEST['max_tries']) ? (int) $_REQUEST['max_tries'] : 15;

$pc = new Crossword($rows, $cols, $words);

$pc->setMaxWords($max_words);
