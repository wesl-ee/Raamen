<?php namespace Raamen;
class File {
	private $path;
	private $relpath;
	private $basename;
	private $stat;
	private $isfile;
	private $authenticator;
	public function __construct($path, $authenticator) {
		$this->isfile = is_file($path);
		if (!$this->isfile) $path .= '/';
		$this->path = $path;
		$this->relpath = substr($path,
			strlen(CONFIG_SERVEDIR));
		$this->basename = basename($path);
		if (!$this->isfile) $this->basename .= '/';
		$this->stat = stat($path);
		$this->authenticator = $authenticator;
	}
	public function toHtml() {
		$datetime = htmlspecialchars(date(DATE_W3C,
			$this->stat['mtime']), ENT_QUOTES);
		$prettymtime = htmlspecialchars(date('d M Y',
			$this->stat['mtime']));
		if ($this->isfile)
			$size = htmlspecialchars($this->human_filesize($this->stat['size']));
		else
			$size = '';
		$href = htmlspecialchars($this->resolve(), ENT_QUOTES);
		print <<<HTML
<tr>
	<td><a href="$href">{$this->basename}</a></td>
	<td>$size</td>
	<td><date datetime="$datetime">$prettymtime</date></td>
</tr>

HTML;
	}
	public function openingHtml() {
		 print <<<HTML
<table><tr>
	<th>File</th>
	<th>Size</th>
	<th>Last Modified</th>
</tr>

HTML;
	}
	public function closingHtml() {
		print <<<HTML
</table>
HTML;
	}
	public function resolve() {
		return str_replace('%2F', '/',
			CONFIG_WEBROOT . "?q=" . urlencode($this->relpath));
	}
	public function display() {
		$datetime = htmlspecialchars(date(DATE_W3C,
			$this->stat['ctime']), ENT_QUOTES);
		$prettyctime = htmlspecialchars(date('d M Y',
			$this->stat['ctime']));
		$size = htmlspecialchars($this->human_filesize($this->stat['size']));
		$href = htmlspecialchars($this->resolve());
		$mimetype = mime_content_type($this->path);

		print <<<HTML
<table>
<tr><th>Mimetype</th><td>$mimetype</td></tr>
<tr><th>Last Modified</th><td><date datetime="$datetime">$prettyctime</date></td></tr>
<tr><th>Size</th><td>$size</td></tr>
</table>
HTML;
		if ($this->authenticator->authed()) {
			$dllink = htmlspecialchars(
				$this->dllink($this->authenticator->userstring()),
				ENT_QUOTES
			);
			print <<<HTML
<a href="$dllink">Download File</a><br />
HTML;
		} else {
			$this->authenticator->htmlChallenge();
		}
	}
	public function dl() {
		header("Content-Type:".mime_content_type($this->path));
		header("Content-Disposition: inline; filename=\"" . $this->basename . "\"");
		readfile($this->path);
		die;
	}
	public function dllink($userstring = '') {
		if (CONFIG_CDN_SERVEDIR) {
			return CONFIG_CDN_PROTOCOL . "$userstring@"
			. CONFIG_CDN_SERVEDIR . $this->relpath;
		}
		return CONFIG_WEBROOT . "?q={$this->relpath}&dl";
	}
	function human_filesize($bytes, $decimals = 2) {
		$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}
}
