Raamen File Server
==================

Dockerized bare-bones file server written in PHP.

Executive Summary
-----------------

Raamen (sometimes stylized Ra~men) serves files from the host computer,
allowing you to access them from the Internet.

Installation / Dependencies
---------------------------

I've bundled this software into a container with lighttpd configured perfectly
for serving large files and host this container on ghcr.io:

```
docker run \
	-p 8000:80 \
	--mount type=bind,source=/path/to/share,target=/mnt \
	ghcr.io/wesl-ee/raamen:v1.0
```

Then visit [localhost:8000](http://localhost:8000) to see it serving files from
`/path/to/share`!

For some customization one can mount the config directory and change things as
desired:

```
git clone https://github.com/wesl-ee/Raamen.git
cd Raamen
docker build . -t raamen
cp config/config.docker.php config.php
docker run \
	-p 8000:80 \
	--mount type=bind,source=/path/to/share,target=/mnt \
	--mount type=bind,source=/absolute/path/to/Raamen/config,target=/app/config \
	raamen:latest
```

Example lighttpd.conf in the config directory shows basic user authentication
using `door.users` where the default username is `root` and the password is
`hackme`.

This software can still be configured to run on bare-metal it's just more
tedious to set everything up. Use the lighttpd and php files in `config/` as
reference. Software is as below:

* Webserver (I use lighttpd because X-LIGHTTPD-Send-File)
* PHP >= 5.1 configured for CGI (I use php-cgi and php-fpm)
* php-gd
* php-gettext
* php-fileinfo

Ensure your webserver can serve PHP files. Then clone Raamen onto your
server by running:

```
git clone https://github.com/wesl-ee/Raamen.git
```

... and make the resulting directory accessible by your webserver. Make sure
`tmp` and `www/robocheck` are owned by the user php-fpm is configured to use.

Finally, `cd` into the Raamen directory and copy the example configuration
file:

```
cp config/config.template.php config/config.php
```

Contributing
------------

If you feel like you have something to contribute open a pull request.

License
-------

Modified BSD License (see `LICENSE`)
