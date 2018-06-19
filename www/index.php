<?php $ROOT = '../';
include "{$ROOT}config.php";
include "{$ROOT}tools/Printer.php";
include "{$ROOT}tools/Explorer.php";

@$q = $_GET['q'] ?: '/';
$servedir = CONFIG_SERVEDIR;
$printer = new Raamen\Printer();

$explorer = new Raamen\Explorer("{$servedir}$q");
if (count($explorer->error))
	$printer->error($explorer->error);
if (isset($_GET['dl'])) {
	$explorer->dl();
	if (count($explorer->error))
		$printer->error($explorer->error);
}
$printer->setLang('en');
$printer->toHtml($explorer);
