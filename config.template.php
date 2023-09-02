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
* Where this git repo lives locally
*/
define("CONFIG_LOCALROOT", "/local/path/to/raamen");
/* CONFIG_CDN_SERVEDIR and CONFIG_CDN_PROTOCOL
* If set, expect the files mentioned in CONFIG_SERVEDIR to be available here
* Set to false if you do not have these files on a content distribution
* network.
* Operator be aware that serving large files without a cdn may prove
* devastating to your server's performance, since the file is (in this
* case) being served by PHP (via readfile()) rather than a proper web
* server.
* Make sure that the  protocol you pick supports appending credentials
* via the URL string like https://user:pw@localhost/path/to/file
*/
define("CONFIG_CDN_SERVEDIR", "cdn.raamen.org");
define("CONFIG_CDN_PROTOCOL", "https://");

/* CONFIG_PW_FILE
* The flat-file for CDN authentication. Point your CDN to check for valid
* users here and make sure both PHP and your CDN can read from it
*/
define("CONFIG_PW_FILE", "/path/to/plain/file.users");

/* CONFIG_MAX_CREDS
* Maximum users listed in CONFIG_PW_FILE. They're rotated every hour by a
* cron job (make sure you set this up!)
*/
define("CONFIG_MAX_CREDS", 8);

/* CONFIG_DMCA_MAIL
* They're going to come after you, it's only a matter of time!
*/
define("CONFIG_DMCA_MAIL", "/dev/null");
