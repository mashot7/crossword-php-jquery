<?php
$words = [
  0 => [
    'word' => 'Space',
    'question' => 'Type Space',
  ],
  1 => [
    'word' => 'Moon',
    'question' => 'Type Moon',
  ],
  2 => [
    'word' => 'Unbelievable',
    'question' => 'Type Unbelievable',
  ],
  3 => [
    'word' => 'Table',
    'question' => 'Type Table',
  ],
  4 => [
    'word' => 'Bottle',
    'question' => 'Type Bottle',
  ],
  5 => [
    'word' => 'Signature',
    'question' => 'Type Signature',
  ],
  6 => [
    'word' => 'Washington',
    'question' => 'Type Washington',
  ],
  7 => [
    'word' => 'Germany',
    'question' => 'Type Germany',
  ],
  8 => [
    'word' => 'Google',
    'question' => 'Type Google',
  ],
  9 => [
    'word' => 'Washing',
    'question' => 'Type Washing',
  ],
  10 => [
    'word' => 'Winston',
    'question' => 'Type Winston',
  ],
  11 => [
    'word' => 'Tesla',
    'question' => 'Type Tesla',
  ],
  12 => [
    'word' => 'Rammstein',
    'question' => 'Type Rammstein',
  ],
  13 => [
    'word' => 'Trump',
    'question' => 'Type Trump',
  ],
  14 => [
    'word' => 'Wilmington',
    'question' => 'Type Wilmington',
  ],
  15 => [
    'word' => 'Village',
    'question' => 'Type Village',
  ],
  16 => [
    'word' => 'Angel',
    'question' => 'Type Angel',
  ],
  17 => [
    'word' => 'Arrive',
    'question' => 'Type Arrive',
  ],
  18 => [
    'word' => 'Mirage',
    'question' => 'Type Mirage',
  ],
];
$count = count($words);
foreach ($words as &$word) {
  $word['word'] = strtoupper($word['word']);
}
require 'init.php';

if (!empty($words)) {

  $success = $pc->generateFromWords();
  if (!$success) {

    echo 'SORRY, UNABLE TO GENERATE CROSSWORD FROM YOUR WORDS';
  }
  else {
    $words = $pc->getWords();
    ?>
		<div id="puzzle-wrapper"></div>
		<script>
			let arr = <?= json_encode($words) ?>;

			(function ($) {
				$(function () {
					let puzzleData = [];
					arr.map(function (item, index) {
						puzzleData.push({
							clue: 'Clue: ' + item.question,
							answer: item.word,
							position: index + 1,
							orientation: item.axis === 1 ? 'across' : 'down',
							startx: item.x,
							starty: item.y
						})
					});
					$('#puzzle-wrapper').crossword(puzzleData);
				})
			})(jQuery)

		</script>
    <?php
  }
}