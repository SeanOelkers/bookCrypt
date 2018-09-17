<?php
function toNumber($letter) {
	if($letter == " ") {
		return 0;
	} else {
		return ord(strtolower($letter)) - 96;
	}
}
function toLetter($number) {
	if($number == 0) {
		return " ";
	} else {
		return chr($number+96);
	}
}

if($_POST['encodeThis']) {$encodeThis = $_POST['encodeThis'];}
if($_POST['encodeKey']) {$encodeKey = $_POST['encodeKey'];}
$string = "This Is A String! 0123456789";
$cleanString = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
$brokenString = str_split($cleanString);
foreach($brokenString AS $character) {
	$results .= toNumber($character)." ";
	$resultArray[] = toNumber($character);
}
foreach($resultArray AS $number) {
	$unResults .= toLetter($number);
}

if($_POST['action'] == 'encode') {
	$action = 'encode';
}
if($_POST['action'] == 'decode') {
	$action = 'decode';
}
if((!$encodeThis || !$encodeKey) && $action) {
	$message = "Nothing to {$action}; missing message or key!";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>bookCrypt</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<link href='https://fonts.googleapis.com/css?family=Fira+Mono:400,700' rel='stylesheet' type='text/css'>
		<style>
			body{margin-bottom:30px;font-family: 'Fira Mono';}
		</style>
	</head>
	<body>
		<div class="container theme-showcase" role="main">
			<div class="page-header">
				<h1>hi! bookCrypt</h1>
			</div>
			<form action="<?=$_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post">
				<div class='col-sm-6'>
					<div class="form-group">
						<textarea name="encodeThis" id="encodeThis" rows="8" class="form-control" placeholder="Message to encode/decode..."><?=$encodeThis;?></textarea>
					</div>
				</div>
				<div class='col-sm-6'>
					<div class="form-group">
						<textarea name="encodeKey" id="encodeKey" rows="8" class="form-control" placeholder="Encode/decode key..."><?=$encodeKey;?></textarea>
					</div>
				</div>
				<div class="row">
					<div class='col-sm-4 col-sm-offset-2'>
						<button type="submit" name="action" value="encode" class="btn btn-success btn-lg btn-block">Encode</button>
					</div>
					<div class='col-sm-4'>
						<button type="submit" name="action" value="decode" class="btn btn-danger btn-lg btn-block">Decode</button>
					</div>
				</div>
			</form>
			<div class="row">

				<p><?=$string?></p>
				<p><?=$cleanString?></p>
				<p><?=$results?></p>
				<p><?=$unResults?></p>
				<p><?=$message?></p>
			</div>
		</div>
	</body>
</html>
