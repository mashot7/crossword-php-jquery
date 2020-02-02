<?php
$words = [
  'Microsoft',
  'Windows',
  'Office',
  'Excel',
  'World',
//  'Google',
  'Galaxy',
  'Several',
  'House',
  'Honey',
];
foreach ($words as &$word) {
  $word = strtoupper($word);
}
require 'init.php';

use core\ArrayFilter;

//$filter = new ArrayFilter();
//$count = count($filter->doFilter($words, '<=', 6));
//var_dump($count);
if (!empty($words)) {
  //  var_dump($words);

  $success = $pc->generateFromWords();//$words
  if (!$success) {

    echo 'SORRY, UNABLE TO GENERATE CROSSWORD FROM YOUR WORDS';
  }
  else {
    $words = $pc->getWords();
    echo json_encode($words);
   ?>
	  <div id="puzzle-wrapper"></div>
		<script>
			let arr = '[{"word":"WINDOWS","x":5,"y":8,"axis":1},{"word":"WORLD","x":8,"y":4,"axis":2},{"word":"HONEY","x":7,"y":5,"axis":1},{"word":"HOUSE","x":10,"y":1,"axis":2},{"word":"MICROSOFT","x":6,"y":2,"axis":1},{"word":"OFFICE","x":13,"y":1,"axis":2},{"word":"SEVERAL","x":11,"y":8,"axis":2},{"word":"EXCEL","x":7,"y":14,"axis":1},{"word":"GALAXY","x":8,"y":10,"axis":2}]';
			arr = JSON.parse(arr);
			document.write('a');
			document.body.innerHTML = '<div id="puzzle-wrapper"></div>';
			console.log(arr);
			// A javascript-enhanced crossword puzzle [c] Jesse Weisbeck, MIT/GPL
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
					console.log(puzzleData);

					$('#puzzle-wrapper').crossword(puzzleData);

				})

			})(jQuery)

		</script>
    <?php
    echo '<prev>';
    echo json_encode($words);

    //    var_dump($words);
    echo '</prev>';
  }
}