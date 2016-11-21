Build Instructions for ShortScience
-----------------------------------

You'll need `docker` (in Ubuntu, package `docker.io`).

Just run `make build` (with `sudo`), which will:

- build the docker image (`shortscience`) from `Dockerfile`
- copy the current codebase (parent directory of `docker` folder)
- run the short science codebase on local port 9999

TODO:
- mount current codebase instead of copy, allow for live changes

Details
-------

Base image is Ubuntu 16.04 with packages:
- `apache2`
- `mysql-server`
- `libapache2-mod-php`
- `php-mysql`
- `php-xml`
and package dependencies.

Apache requirements:
- `rewrite` mod (default on)
- `headers` mod
- config allows `.htaccess` override

PHP requirements:
- short open tags
