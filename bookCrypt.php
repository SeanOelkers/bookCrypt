<?php
function toNumber($letter) {
	if(!ctype_alpha($letter)) {
		if(is_numeric($letter)) {$letter = "_{$letter}";}
		return $letter;
	} else {
		return ord(strtolower($letter)) - 96;
	}
}
function toLetter($number) {
	if(!is_numeric($number)) {
		if(strlen($number)==2) {
			$number = substr($number,1,1);
		}
		return $number;
	} else {
		return chr($number+96);
	}
}
if($_POST['action']) {$action = $_POST['action'];}
if($_POST['cryptThis']) {$cryptThis = $_POST['cryptThis'];}
if($_POST['cryptKey']) {$cryptKey = $_POST['cryptKey'];}
if((!$cryptThis || !$cryptKey) && $action) {
	// error if submitting the form with a missing message or encryption key
	$warningMessage = "Nothing to {$action}; missing message or key!";
}
$messageLength = strlen(preg_replace("/[^A-Za-z]/", '', $cryptThis));
$splitMessage = str_split($cryptThis);
foreach($splitMessage AS $splitMessageCharacter) {
	$theMessage[] = toNumber($splitMessageCharacter);
	$showMessage .= toNumber($splitMessageCharacter)." ";
}
$cleanKey = strtolower(preg_replace("/[^A-Za-z]/", '', $cryptKey));
$keyLength = strlen($cleanKey);
$splitKey = str_split($cleanKey);
foreach($splitKey AS $keyCharacter) {
	$theKey[] = toNumber($keyCharacter);
	$showKey .= toNumber($keyCharacter)." ";
}

$offset = 0;
foreach($theMessage AS $messageCharacter) {
	if (is_numeric($messageCharacter)) {
		$calcCharacter = $messageCharacter + $theKey[$offset];
		if ($calcCharacter > 26) {$calcCharacter = $calcCharacter - 26;}
		$message['Encoded'] .= toLetter($calcCharacter);
		$offset++;
		if ($offset == $keyLength){$offset = 0;}
	} else {
		$message['Encoded'] .= toLetter($messageCharacter);
	}
}
$offset = 0;
foreach($theMessage AS $messageCharacter) {
	if (is_numeric($messageCharacter)) {
		$calcCharacter = $messageCharacter - $theKey[$offset];
		if ($calcCharacter < 1) {$calcCharacter = $calcCharacter + 26;}
		$message['Decoded'] .= toLetter($calcCharacter);

		$offset++;
		if ($offset == $keyLength){$offset = 0;}
	} else {
		$message['Decoded'] .= toLetter($messageCharacter);
	}
}
?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8'>
		<title>bookCrypt</title>
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/bootstrap/3.3.5/css/bootstrap.min.css'>
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/bootstrap/3.3.5/css/bootstrap-theme.min.css'>
		<link href='https://fonts.googleapis.com/css?family=Fira+Mono:400,700' rel='stylesheet' type='text/css'>
		<style>
			body{margin-bottom:30px;font-family: 'Fira Mono';}
		</style>
	</head>
	<body>
		<div class='container theme-showcase' role='main'>
			<div class='page-header'>
				<h1>bookCrypt</h1>
			</div>
			<div class='row'>
				<form action='<?=$_SERVER['PHP_SELF'];?>' class='form-horizontal' method='post'>
					<div class='col-sm-6'>
						<div class='form-group'>
							<h4 class='text-center'>Message to Encrypt/Decrypt</h4>
							<textarea name='cryptThis' id='cryptThis' rows='10' class='form-control' placeholder='Message to encode/decode...'><?=$cryptThis;?></textarea>
						</div>
					</div>
					<div class='col-sm-6'>
						<div class='form-group'>
							<h4 class='text-center'>Encryption Key</h4>
							<textarea name='cryptKey' id='cryptKey' rows='10' class='form-control' placeholder='Encode/decode key...'><?=$cryptKey;?></textarea>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-4 col-sm-offset-2'>
							<button type='submit' name='action' value='Encoded' class='btn btn-success btn-lg btn-block'>Encode</button>
						</div>
						<div class='col-sm-4'>
							<button type='submit' name='action' value='Decoded' class='btn btn-danger btn-lg btn-block'>Decode</button>
						</div>
					</div>
				</form>
			</div>
			<hr style='border-width:3px;' />
			<div class='row'>
				<div class='col-sm-8 col-sm-offset-2'>
					<h3 class='text-center'>The <?=$action?> Message</h3>

					<? if($warningMessage){
						echo "<p class='alert alert-danger'>{$warningMessage}</p>";
					} else {
						if($messageLength > $keyLength) {
							echo "<p class='alert alert-warning text-center'><strong>The Encryption Key is shorter than the message length.</strong><br>Consider adding additional text to the encryption key to make it more secure...</p>";
						}
					?>
						<textarea name='theMessage' id='theMessage' class='form-control' rows='10' placeholder='Encrypted/Decrypted message will appear here...'><?=$message[$action]?></textarea>
						<button class='btn btn-primary btn-lg btn-block'onclick="copyMessage()">Copy text</button>
					<? } ?>

				</div>
			</div>
		</div>
<script>
function copyMessage() {
	var copyText = document.getElementById("theMessage");
	copyText.select();
	document.execCommand("copy");
}
</script>

	</body>
</html>
