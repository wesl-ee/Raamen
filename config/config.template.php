<?php
/* CONFIG_SERVEDIR
* It's where PHP looks for the files to list. Make sure that the PHP
* user can actually read *all* the files here!
* Also, you should not use a trailing slash here.
*/
define("CONFIG_SERVEDIR", "/path/to/files/you/want/to/serve/desu");
/* CONFIG_WEBROOT
* The place where you can access your Raamen instance on the Internet.
* Also, the trailing slash is important.
*/
define("CONFIG_WEBROOT", "https://example.com/path/to/raamen/");
/* CONFIG_LOCALROOT
* Where this git repo lives locally.
* Also, the trailing slash is important.
*/
define("CONFIG_LOCALROOT", "/local/path/to/raamen/");
/* CONFIG_DL_VALID_HOURS
* Maximum users listed in robocheck.users file. They're rotated every hour by a
* cron job so 8 means download links are valid for only 8 hours
*/
define("CONFIG_DL_VALID_HOURS", 8);
/* CONFIG_DMCA_MAIL
* They're going to come after you, it's only a matter of time!
*/
define("CONFIG_DMCA_MAIL", "/dev/null");
/* CONFIG_LIGHTTPD_SEND
* Attach X-LIGHTTPD-send-file header when serving dl links (for lighttpd)
* You probably want one of the two following enabled otherwise you can't serve
* large files as PHP will run out of memory. Use the Docker container! :)
*/
define("CONFIG_X_LIGHTTPD_SEND", false);
/* CONFIG_X_SENDFILE
* Attach X-SENDFILE header when serving dl links (for apache)
*/
define("CONFIG_X_SENDFILE", false);
