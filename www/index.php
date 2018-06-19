<?php $ROOT = '../';
include "{$ROOT}config.php";
include "{$ROOT}tools/Printer.php";
include "{$ROOT}tools/Explorer.php";

@$q = $_GET['q'] ?: '/';
$servedir = CONFIG_SERVEDIR;

if (strpos(
	realpath("{$servedir}$q"),
	realpath($servedir)
) === false) die;

$printer = new Raamen\Printer();
$explorer = new Raamen\Explorer("{$servedir}$q");

if (isset($_GET['dl'])) {
	$explorer->dl();
	die;
}

$printer->setLang('en');
$printer->toHtml($explorer);
