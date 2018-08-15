<?php namespace Raamen;
class File {
	private $path;
	private $relpath;
	private $basename;
	private $stat;
	private $isfile;
	public function __construct($path) {
		$this->isfile = is_file($path);
		if (!$this->isfile) $path .= '/';
		$this->path = $path;
		$this->relpath = substr($path,
			strlen(CONFIG_SERVEDIR));
		$this->basename = basename($path);
		if (!$this->isfile) $this->basename .= '/';
		$this->stat = stat($path);
	}
	public function toHtml() {
		$datetime = htmlspecialchars(date(DATE_W3C,
			$this->stat['ctime']), ENT_QUOTES);
		$prettyctime = htmlspecialchars(date('d M Y',
			$this->stat['ctime']));
		if ($this->isfile)
			$size = htmlspecialchars($this->human_filesize($this->stat['size']));
		else
			$size = '';
		$href = htmlspecialchars($this->resolve());
		print <<<HTML
<tr>
	<td><a href="$href">{$this->basename}</a></td>
	<td>$size</td>
	<td><date datetime="$datetime">$prettyctime</date></td>
</tr>

HTML;
	}
	public function openingHtml() {
		print <<<HTML
<table><tr>
	<th>File</th>
	<th>Size</th>
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
	public function display($authenticator) {
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
<tr><th>Created</th><td><date datetime="$datetime">$prettyctime</date></td></tr>
<tr><th>Size</th><td>$size</td></tr>
</table>
HTML;
		if ($authenticator->authed()) {
			$dllink = htmlspecialchars(
				$this->dllink($authenticator->userstring()),
				ENT_QUOTES
			);
			print <<<HTML
<a href="$dllink">Download</a><br />
HTML;
		} else {
			$authenticator->htmlChallenge();
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
