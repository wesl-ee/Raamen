<?php namespace Raamen;
class Authenticator {
	private $user;
	private $pass;
	private $authed;
	public function __construct($user = false, $pass = false) {
		$this->user = $user;
		$this->pass = $pass;

		// Check if our creds. are already in the file
		if ($this->verify())
			$this->authed = true;
		else
			$this->authed = false;
	}
	public function authed() { return $this->authed; }
	public function genAuth() {
		$height = 70; $width = 165;
		$centerX = $width / 2; $centerY = $height / 2;


		$id = uniqid();
		$imgfile = "$id.jpg";
		$keyfile = "$id.txt";

		$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/"
		. substr($id, 0, 2) . '/';
		$imgpath = CONFIG_LOCALROOT . "www/robocheck/"
		. substr($id, 0, 2) . '/';
		if (!is_dir($keypath)) mkdir($keypath);
		if (!is_dir($imgpath)) mkdir($imgpath);

		$a = rand(0, 100); $b = rand(0, 100);
		$answer = $a + $b; $text = "$a + $b";
		$lines = 5; $angle = 0;
		$font = CONFIG_LOCALROOT . "www/res/mouthbrebb.ttf";

		$im = imagecreatetruecolor($width, $height);
		$bg = imagecolorallocate($im, 230, 80, 0);
		$fg = imagecolorallocate($im, 255, 255, 255);
		$ns = imagecolorallocate($im, 200, 200, 200);
		imagefill($im, 0, 0, $bg);

		$centerX = $width / 2;
		$centerY = $height / 2;
		list($left, $bottom, $right, , , $top) = imageftbbox(20, $angle, $font, $text);
		$left_offset = ($right - $left) / 2;
		$top_offset = ($bottom - $top) / 2;
		$x = $centerX - $left_offset;
		$y = $centerY + $top_offset;
		imagettftext($im, 20, $angle, $x, $y, $fg, $font, $text);
		while ($lines--) {
			imageline($im, 0, rand(0, $height), $width, rand(0, $height), $fg);
		}
		for($i=0;$i<1000;$i++) {
			imagesetpixel($im,rand()%$width,rand()%$height,$fg);
		}
		imagegif($im, $imgpath . $imgfile);
		imagedestroy($im);

		// Stash the answer in tmp/robocheck-answers/uniqid().txt
		file_put_contents($keypath . $keyfile, $answer);

		return [
			"id" => $id,
			"src" => CONFIG_WEBROOT . "robocheck/"
			. substr($id, 0, 2) . "/$imgfile",
			"height" => $height,
			"width" => $width
		];
	}
	public function checkAuth($id, $answer) {
        $imgfile = CONFIG_LOCALROOT . "www/robocheck/"
        . substr($id, 0, 2) . "/$id.jpg";
		$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/"
		. substr($id, 0, 2) . "/$id.txt";

		if (strpos($this->get_absolute_path($keypath)
		, CONFIG_LOCALROOT . "tmp/robocheck-answers") !== 0)
			return false;
		if (!is_file($keypath)) return false;

		$a = chop(file_get_contents($keypath));

    	unlink($imgfile);
		unlink($keypath);

		return ($a == $answer);
	}
	public function verify() {
		$fh = fopen(CONFIG_LOCALROOT . "robocheck.users", 'r');
		while ($line = fgets($fh)) {
			list($user, $pass) = explode(':', trim($line));
			if ($this->user == $user
			&& $this->pass == $pass)
				return true;
		} return false;
	}
	public function htmlChallenge() {
		$robocheck = $this->genAuth();
		$robosrc = $robocheck['src'];
		$robocode = $robocheck['id'];
		$height = $robocheck['height'];
		$width = $robocheck['width'];
		print <<<HTML
		<p>Please solve the following:</p>
<form method=POST><div class=robocheck>
		<img height=$height width=$width src="$robosrc">
		<input name=robocheckid type=hidden value=$robocode>
		<input name=robocheckanswer
		tabindex=1
		placeholder="Verify Humanity"
		autocomplete=off>
		<button class=button name=post value=post type=submit
		tabindex=2>Next</button>
</div></form>
HTML;
	}
	public function isAuthAttempt($POST) {
		return @$POST['robocheckanswer'] && @$POST['robocheckid'];
	}
	public function auth($POST) {
		if ($this->checkauth($POST['robocheckid'], $POST['robocheckanswer'])) {
			$this->authed = true;
			$creds = $this->getCreds();
			$this->user = $creds['user'];
			$this->pass = $creds['pass'];
			return $creds;
		} return false;
	}
	public function getCreds() {
		$fh = fopen(CONFIG_LOCALROOT . "robocheck.users", 'r');
		while ($a = fgets($fh)) { $line = $a; }
		list($user, $pass) = explode(':', trim($line));
		$ret = [
			'user' => $user,
			'pass' => $pass
		]; fclose($fh); return $ret;
	}
	public function extractRobocheck($POST) {
		if (!$POST['robocheckanswer']
		|| !$POST['robocheckid']) return false;
		return [
			'answer' => $POST['robocheckanswer'],
			'id' => $POST['robocheckid']
		];
	}
	public function userstring() {
		return "{$this->user}:{$this->pass}";
	}
	/*
	 * D-R-A-K-E THAT'S M-E
	*/
	function get_absolute_path($path)
	{
		$parts = explode('/', $path);
		$absolutes = [];
		foreach($parts as $part) {
			if ($part == '.') continue;
			if ($part == '..') array_pop($absolutes);
			else $absolutes[] = $part;
		}
		return implode('/', $absolutes);
	}
}
