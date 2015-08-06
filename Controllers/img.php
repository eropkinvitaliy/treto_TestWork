<?php
$src = $_POST['src'];
session_start();
$arrmonitor = $_SESSION['url'];
$tmppath = tempnam(sys_get_temp_dir(), 'Pic');
$tmpfile = fopen($tmppath, 'w');
fwrite($tmpfile, $src);
fwrite($tmpfile, PHP_EOL);
$arr = file($tmppath);
fclose($tmpfile);

if ($arrmonitor !== null) {
    $arr = array_merge($arrmonitor, $arr);
}
$_SESSION['url'] = $arr;
var_dump($_SESSION['url']);

return $arr;