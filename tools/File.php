<?php namespace RAL;
class File {
	private $path;
	private $relpath;
	private $basename;
	private $stat;
	public function __construct($path) {
		if (!is_file($path)) $path .= '/';
		$this->path = $path;
		$this->relpath = substr($path,
			strlen(CONFIG_SERVEDIR));
		$this->basename = basename($path);
		$this->stat = stat($path);
	}
	public function toHtml() {
		$ctime = date(DATE_RSS, $this->stat['ctime']);
		$size = $this->stat['size'];
		$href = $this->resolve();
		print <<<HTML
<tr>
	<td><a href="$href">{$this->basename}</a></td>
	<td>$size</td>
	<td>$ctime</td>
</tr>

HTML;
	}
	public function openingHtml() {
		print <<<HTML
<table><tr>
	<th></th>
	<th>Bytes</th>
	<th>Created</th>
</tr>
HTML;
	}
	public function closingHtml() {
		print <<<HTML
</table>
HTML;
	}
	public function resolve() {
		return CONFIG_WEBROOT . "?q={$this->relpath}";
	}
	public function display() {
		$atime = date(DATE_RSS, $this->stat['atime']);
		$ctime = date(DATE_RSS, $this->stat['ctime']);
		$size = $this->stat['size'];
		$mimetype = mime_content_type($this->path);
		$dllink = $this->dllink();
		print <<<HTML
<table>
<tr><th>Mimetype</th><td>$mimetype</td></tr>
<tr><th>Created</th><td>$ctime</td></tr>
<tr><th>Bytes</th><td>$size</td></tr>
</table>
<a href="$dllink">Download</a><br />
HTML;
	}
	public function dl() {
		header("Content-Type:".mime_content_type($this->path));
		header("Content-Disposition: inline; filename=\"" . $this->basename . "\"");
		readfile($this->path);
	}
	public function dllink() {
		return CONFIG_WEBROOT . "?q={$this->relpath}&dl";
	}
}
