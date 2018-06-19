<?php namespace RAL;
class Printer {
	function setLang($lang) {
		$language = $lang;
		putenv("LANG=".$language);
		setlocale(LC_ALL, $language);

		$domain = "RAL";
		bindtextdomain($domain, "locale");
		textdomain($domain);
	}
	function toHtml($object) {
		$this->preambleHtml();
		$object->toHtml();
		$this->closingHtml();
	}
	function preambleHtml() {
		$stylesheet = CONFIG_WEBROOT . "style.css";
		print <<<HTML
<!DOCTYPE HTML>
<head>
	<link rel=stylesheet type="text/css" href="$stylesheet">
</head>
<body>
HTML;
	}
	function closingHtml() {
		print <<<HTML
<hr /><footer>

HTML;
		print "Raamen File Browser v0";
		print <<<HTML
</footer>
</body>

HTML;
	}
	function dl($object) {
		$object->dl();
	}
}
