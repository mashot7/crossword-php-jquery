<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>PHP Crossword Generator</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
require 'init.php';
$_REQUEST['words'] = 'Microsoft
Windows
Office
Exel
World
Google';
?>

<div align="center">

	<form method="post">
		Add some words: <br/>
		<textarea name="words" cols="30"
		          rows="10"></textarea>
		<br/><br/>
		<input type="submit" value="Generate"/>
	</form>

  <?php if (!empty($_REQUEST['words'])): ?>

    <?php
  var_dump($_REQUEST['words']);
    $success = $pc->generateFromWords($_REQUEST['words']);
    ?>

    <?php if (!$success): ?>

			SORRY, UNABLE TO GENERATE CROSSWORD FROM YOUR WORDS

    <?php else: ?>

      <?php
      $html = $pc->getHTML($_REQUEST['colors']);
      $words = $pc->getWords();
      var_dump($words);
      ?>

			<p><?= $html ?></p>

			<p><b>Words: <?= count($words) ?></b></p>

			<table border=1 align="center">
				<tr>
					<th>Nr.</th>
					<th>Word</th>
					<th>X</th>
					<th>Y</th>
					<th>Axis</th>
				</tr>
        <?php foreach ($words as $key => $word): ?>
					<tr>
						<td><?= $key + 1 ?>.</td>
						<td><?= $word["word"] ?></td>
						<td><?= $word["x"] ?></td>
						<td><?= $word["y"] ?></td>
						<td><?= $word["axis"] ?></td>
					</tr>
        <?php endforeach; ?>
			</table>

    <?php endif; ?>

  <?php endif; ?>

  <?= sprintf("<p>Generated in %.4f sec.</p>", (getmicrotime() - $script_start)) ?>

</div>

</body>
</html>