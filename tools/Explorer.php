<?php namespace Raamen;
include 'File.php';
class Explorer {
	private $files;
	private $path;
	private $relpath;
	private $title;
	private $description;
	function __construct($path) {
		$this->path = $path;
		$this->relpath = substr($path,
			strlen(CONFIG_SERVEDIR));
		$this->title = <<<TITLE
$this->relpath - Raamen File Server
TITLE;
		$this->description = <<<DESC
$this->relpath
DESC;
		if (is_file($path)) $this->files = new File($path);
		else foreach (scandir($path) as $f) {
			if ($f == '.' || $f == '..') continue;
			$this->files[] = new File("{$path}$f");
		}
	}
	function toHtml() {
		$this->openingHtml();
		if (is_array($this->files)) {
			$this->files[0]->openingHtml();
			foreach ($this->files as $f) $f->toHtml();
			$this->files[0]->closingHtml();
		} else $this->files->display();
		$this->closingHtml();
	}
	function openingHtml() {
		print "<h1>";
		$chunks = explode('/', $this->relpath);
		if (empty($chunks[count($chunks)-1]))
			// Ignore the empty item for directories
			// (trailing slash)
			array_pop($chunks);
		foreach ($chunks as $n => $chunk) {
			if (!is_file(CONFIG_SERVEDIR
			. urldecode($chunkedpath) . $chunk)
			|| (count($chunks) - $n - 1))
				$chunk .= '/';
			$chunkedpath .= urlencode($chunk);
			$chunkedpath = str_replace("%2F", "/", $chunkedpath);
			if ($chunkedpath == '/')
				$href = htmlspecialchars(CONFIG_WEBROOT);
			else
				$href = htmlspecialchars(CONFIG_WEBROOT . "?q=$chunkedpath");
			print <<<BOX
<a href="$href">$chunk</a>
BOX;
		}
		print "</h1>\n";
	}
	function closingHtml() {
		if (is_array($this->files)) {
			$count = count($this->files);
			print "$count files\n";
		}
	}
	function title() { return $this->title; }
	function description() { return $this->description; }
	function dl() {
		if (is_array($this->files)) return;
		$this->files->dl();
	}
}
