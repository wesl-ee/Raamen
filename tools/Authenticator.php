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
	public function verify() {
		$fh = fopen(CONFIG_PW_FILE, 'r');
		while ($line = fgets($fh)) {
			list($user, $pass) = explode(':', trim($line));
			if ($this->user == $user
			&& $this->pass == $pass)
				return true;
		} return false;
	}
	public function htmlChallenge() {
		$robocode = 'robocode';
		print <<<HTML
<form method=POST>
		<input name=robocheckid type=hidden value=$robocode>
		<input name=robocheckanswer
		tabindex=1
		placeholder="Verify Humanity"
		autocomplete=off>
		<button class=button name=post value=post type=submit
		tabindex=2>Next</button>
</form>
HTML;
	}
	public function isAuthAttempt($POST) {
		return @$POST['robocheckanswer'] && @$POST['robocheckid'];
	}
	public function auth($POST) {
		if ($POST['robocheckanswer'] == CONFIG_PW) {
			$this->authed = true;
			$creds = $this->getCreds();
			$this->user = $creds['user'];
			$this->pass = $creds['pass'];
			return $creds;
		} return false;
	}
	public function getCreds() {
		$fh = fopen(CONFIG_PW_FILE, 'r');
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
}
