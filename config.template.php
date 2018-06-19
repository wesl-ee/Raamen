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
/* CONFIG_CDN_SERVEDIR
* If set, expect the files mentioned in CONFIG_SERVEDIR to be available here
* Set to false if you do not have these files on a content distribution
* network.
* Operator be aware that serving large files without a cdn may prove
* devastating to your server's performance, since the file is (in this
* case) being served by PHP (via readfile()) rather than a proper web
* server.
* Also, you should not use a trailing slash here.
*/
define("CONFIG_CDN_SERVEDIR", "https://cdn.example.com/to/serve/desu");
