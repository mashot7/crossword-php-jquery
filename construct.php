<?php
$words = [
  'Space',
  'Moon',
  'Unbelievable',
  'Table',
  'Bottle',
  'Signature',
  'Washington',
  'Germany',
  'Google',
  'Washing',
  'Winston',
  'Tesla',
  'Rammstein',
  'Trump',
  'Wilmington',
  'Village',
  'Angel',
  'Arrive',
  'Mirage',
];
$count = count($words);
foreach ($words as &$word) {
  $word = strtoupper($word);
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
						console.log(index, item);
						puzzleData.push({
							clue: 'Clue: ' + item.word,
							answer: item.word,
							position: index + 1,
							orientation: item.axis == '1' ? 'across' : 'down',
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