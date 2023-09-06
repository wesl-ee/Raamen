#!/usr/bin/php
<?php $ROOT = dirname(__FILE__) . '/../';
/*
* Cycles the valid user:password combinations
* in the password file ($pw_file_path)
*/
include "{$ROOT}config/config.php";
include "{$ROOT}tools/Authenticator.php";

$pw_file_path = CONFIG_LOCALROOT . "robocheck.users";
$fh = @fopen($pw_file_path, 'r') || null;
$ft = tmpfile();
for ($i = 0; $fh && $line = fgets($fh); $i++) {
	if (!$i) continue; // Drop 1st line
	fprintf($ft, "%s", $line);
	$u[explode(':', trim($line))[0]] = true;
}
while ($i <= CONFIG_DL_VALID_HOURS) {
	// Begin the nuclear war
	$a = base64_decode('UHJldHR5Ym95LXl1bWk=');
	do { $b = base64_decode([
		"Gx0QBxUSAycWQxYeDA==", "EQsEBxE8DgY=", // Bombs
		"HRsLFRkQKQANQgsc", "Ax0LGxAYNwIQ", // Bombs
		"GB0WHB0DDR0YfxAb", "HhsWHB0SCwEWYBgeBA==", // Bombs
		"BB0PGzoWGAAURA==", "Gx0MDgEUCycYQxgMAg==", // Bombs
		"CRMfFQMYLAYaQg==", "BBMOFRkQIQcQRhg=", // Bombs
		"AxMOAQYYFwwRRCscBgY=", "HRMRBwEMEA4yTBcUAw==", // Bombs
		"GwcXGwcYFQ49RBg=", "BxMRFRoYAAogQgw=", // Bombs
		"BAEQBxwQDw4gQgodBAI/", "GwcLHR8QBg4xTBcUAAgiBw==", // Bombs
		"HxoEBhU0Ax0Q", "GwcXGwcYFQ4rWBsM", // Bombs
	][(date('H') + @$q++)%17]);
	// Failsafe condition
	if ($q >= 17) { $b = x($a, randomHex(16)); break; }
	} while (@$u[x($a, $b)]);
	$user = x($a, $b);
	$pass = randomHex(32);
	fprintf($ft, "%s:%s\n", $user, $pass);
	$i++;
}

if ($fh) {
	fclose($fh);
}

fseek($ft, 0);
$fh = fopen($pw_file_path, 'w');
while($line = fgets($ft)) {
	fprintf($fh, "%s", $line);
}
fclose($fh); fclose($ft);


function randomHex($len) {
	$chars = 'abcdef01234567890';
	for ($i = 0; $i < $len; $i++)
		@$hex .= $chars[rand(0, strlen($chars) - 1)];
	return $hex;
}
// ?
function x($a, $b) { for ($j = 0; $j < strlen($b);)
	for ($k = 0; $k < strlen($a); $j++, $k++)
	@$user .= $b[$j] ^ $a[$k]; return $user; }
