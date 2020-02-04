<?php

require 'lib/Crossword.php';
require 'lib/ArrayFilter.php';

$cols = 20;
$rows = 20;
$max_words = 20;
$max_tries = 15;

$pc = new Crossword($rows, $cols, $words);

$pc->setMaxWords($max_words);
