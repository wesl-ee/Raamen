<?php
/* CONFIG_SERVEDIR
* It's where PHP looks for the files to list. Make sure that the PHP
* user can actually read *all* the files here!
* Also, you should not use a trailing slash here.
*/
define("CONFIG_SERVEDIR", "/mnt");
/* CONFIG_WEBROOT
* The place where you can access your Raamen instance on the Internet.
* Also, the trailing slash is important.
*/
define("CONFIG_WEBROOT", "/");
/* CONFIG_LOCALROOT
* Where this git repo lives locally.
* Also, the trailing slash is important.
*/
define("CONFIG_LOCALROOT", "/app/");
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
*/
define("CONFIG_X_LIGHTTPD_SEND", true);
/* CONFIG_X_SENDFILE
* Attach X-SENDFILE header when serving dl links (for apache)
*/
define("CONFIG_X_SENDFILE", false);
