<?php $ROOT = '../';
include "{$ROOT}config.php";
include "{$ROOT}tools/Printer.php";
include "{$ROOT}tools/Explorer.php";
include "{$ROOT}tools/Authenticator.php";

@$q = $_GET['q'] ?: '/';
$servedir = CONFIG_SERVEDIR;
$printer = new Raamen\Printer();
$authenticator = new Raamen\Authenticator(@$_COOKIE['user'], @$_COOKIE['pass']);

$explorer = new Raamen\Explorer("{$servedir}$q", $authenticator);
if (count($explorer->error))
	$printer->error($explorer->error);
if ($authenticator->isAuthAttempt($_POST)) {
	$creds = $authenticator->auth($_POST);
	if ($creds) {
		setcookie('user', $creds['user']);
		setcookie('pass', $creds['pass']);
	}
}
if (isset($_GET['dl'])) {
	$explorer->dl();
	if (count($explorer->error))
		$printer->error($explorer->error);
}
$printer->setLang('en');
$printer->toHtml($explorer);
