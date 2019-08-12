# AbterPHP

[![Build Status](https://travis-ci.com/abtercms/abterphp.svg?branch=master)](https://travis-ci.com/abtercms/abterphp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abtercms/abterphp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abtercms/abterphp/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/abtercms/abterphp/badges/build.png?b=master)](https://scrutinizer-ci.com/g/abtercms/abterphp/build-status/master)
[![Coverage Status](https://coveralls.io/repos/github/abtercms/abterphp/badge.svg)](https://coveralls.io/github/abtercms/abterphp)

AbterPHP is the first and currently the only implementation of AbterCMS, providing backend, frontend and a REST API for it.
It does not require much JavaScript knowledge as it uses jQuery instead of some nodejs based solution, therefore many might
find it easier to learn, maintain or deploy.

It is based on the excellent [Opulence](https://www.opulencephp.com/) framework.

## Why another CMS?

The system has 3 reasons to exist:
1. Provide an easily accessible alternative to anyone who needs a truly simple website.
2. Educational purposes.
3. Provide a reasonably solid base for dev-shops that have PHP resources now, but want to keep their tech stack open for change.

You'll find more information on design decisions on the website.

## AbterCMS

AbterCMS is a polyglot CMS system which is meant to provide both easily customizable, maintainable and deployable website
solutions for simple use cases, and also a solid base for startups iterating over ideas fast.

It is planned that there will be at least a Go API and some nodejs based backend and frontend solution. (Elm, Vue or React most likely.) 

## Status

AbterPHP backend and frontend are almost feature complete, but the API hardly exists, the code coverage is far from even
acceptable and there's hardly any documentation for it. Therefore the current status is **Early Preview**.

Expect this to change rapidly though as there's a lot of ground work already done for good code coverage and proper API 
to be implemented.

## Installation

### Development

#### Pre-requisite: Grab the source code

This should be fairly obvious if you're reading it, but feel free to download the code from Github or clone the repository.
Just use the "Clone or download" button to get you started.

#### Pre-requisite: Install [docker](https://docker.com/)

The recommended way of getting started with the AbterPHP is via docker. While it is not necessarily mandatory, some of the
documentation might assume that all developers use `docker` for development. If you want to run the code, you'll have to
ensure that you have the right version of PHP, with the neccessary modules and that you have at least a supported version
of MySQL (or later PostgreSQL). While having Redis or Memcached is great, those are not mandatory.

#### Pre-requisite: Install [mkcert](https://mkcert.dev/)

Since security is a top priority, pure http is not supported out of the box, therefore you'll need to install a certificate. 
The recommended way is using `mkcert`. While it is not necessarily mandatory, some of the
documentation might assume that all developers use `mkcert` for development. 

#### Pre-requisite: Open the project in a console

The rest of the installation documentation will assume that `.` is the root directory of the project.

#### Pre-requisite: Add abtercms.test as localhost in `/etc/hosts` on Linux and OSX or `???` on Windows.

```bash
# /etc/hosts
# [...]
127.0.0.1	abtercms.test
```

#### Create certificate

Since security is a top priority, you'll need to create a certificate and move it into

```bash
mkcert abtercms.test "*.abtercms.test"
mv abtercms.test+1* ./docker/nginx/certs/
```

#### Set some permissions

```bash
chmod -R 0777 ./tmp ./public/tmp
chmod +x apex

```

#### Spin up the containers

```
docker-compose pull
docker-compose up -d
```

#### Install the dependencies

You first need to install composer.phar locally and than use that to install dependencies.

You need to log into the PHP container to do this:

```bash
docker-compose exec php sh
> php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
> php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
> php composer-setup.php
> php -r "unlink('composer-setup.php');"
> php composer.phar install
> exit
```

#### Ensure your settings are sane

Although we try to provide a reasonable set of settings for getting started quickly, at this point you may want to edit
`config/environment/.env.app.php` to make your settings sane for your needs. Please note however that these values can
be and in some cases will be overwritten by your environment variables. This means that if you are running the system
with `docker-compose` than you might need to edit some of these values in `docker-compose.yml`.

If you do not have the file `config/environment/.env.app.php` then something must have gone wrong in the previous step,
because it should be created during `php composer.phar install`.

More documentation on the settings will be written later.

#### Install the db schema and create a new admin user

You need to log into the PHP container (again) to do this:

```
docker-compose exec php sh
> ./apex migrations:up
> ./apex user:create {username} {email} {strongPassword} admin en
> exit
```

If everything went well, you should be able to log in with your new user at `https://abtercms.test/login-iddqd`, given
that you haven't yet changed your `ADMIN_LOGIN_PATH` environment variable in `config/environment/.env.app.php`.

### Production

Since AbterPHP is in **Early Preview** state, you probably shouldn't deploy it to production at the moment.

## Stack

AbterPHP is based on [Opulence](https://www.opulencephp.com/), but uses a few more projects alongside Opulence:
 - [Monolog](https://seldaek.github.io/monolog/) for logging
 - [Whoops](https://filp.github.io/whoops/) for error reporting in non-production environments
 - [Flysystem](https://flysystem.thephpleague.com/docs/) for handling filesystem (incomplete implementation)
 - [Casbin](https://casbin.org/) for authorization
 - [Minify](https://www.minifier.org/) for minifying assets (Website module)
 - [Swiftmailer](https://swiftmailer.symfony.com/) for sending emails (Contact module)
 - [Slugify](https://github.com/cocur/slugify) for creating web-safe identifiers (Admin module)
 - [jQuery](https://jquery.com/) for most of the JavaScript in place (Admin module)
 - [js-sha3](https://github.com/emn178/js-sha3) for browser-side encryption (Admin module)
 - [Trumbowyg](https://alex-d.github.io/Trumbowyg/documentation/) as a wysiwyg solution (Admin module)
 - [zxcvbn](https://github.com/dropbox/zxcvbn) from Dropbox for password strength estimations (Admin module)
 - [zxcvbn-php](https://github.com/bjeavons/zxcvbn-php) PHP version of `zxcvbn` (Admin module)
 
If you want to contribute code you'll also need to get familiar with these tools:
 - [PhpUnit](https://phpunit.de/) for unit tests
 - [vfsStream](https://github.com/mikey179/vfsStream) for mocking the filesystem
 - [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for code formatting
 - [PHPMD - PHP Mess Detector](https://phpmd.org/) for code quality
 
AbterPHP currently only supports MySQL as the database system, although PostgreSQL support is very likely to happen
before the first stable version.

## Roadmap

### First alpha musts:

1. ~~Make the frontend framework decoupled from the backend system~~
1. ~~Use ILogger instead of Logger~~
1. More fine grained authorization
   - ACL implemented for pages
   - ~~Advanced settings access role for pages~~
   - ~~Multiple user group for one user~~
   - ~~New user roles: file uploader, layout designer, page writer~~
1. Improved security implementing related headers as recommended:
   - https://medium.freecodecamp.org/secure-your-web-application-with-these-http-headers-fd66e0367628
   - https://medium.freecodecamp.org/web-security-hardening-http-cookies-be8d8d8016e1
1. 70%+ of PHP source code unit tested, except for bootstrappers and console commands
1. Verified that `localhost_router.php` works as expected or remove it if too hard to fix
1. ~~Modular asset management~~
1. ~~Completed test automation and automatic reviews set up~~
1. ~~Refactored module manager~~
1. ~~Ensured that user creation enforces good passwords in CLI.~~
1. ~~Uuid ids~~
1. ~~Removed $value in Select constructor~~
1. ~~Switch `$attribute` and `$tag` order~~
1. Tested project on OSX and Windows 10.
1. Initial API defined and published
1. Documentation page exists
1. ~~1 nice website module~~
1. ~~Page category~~
1. Enable/Disable modules from console
1. ~~Re-add navigation item filtering by enforcer~~

### First beta musts:

1. API fully defined
1. API implemented
1. 1 grid is covered with acceptance tests (filters, pagination included)
1. 1 form is covered with acceptance tests (displaying with new and existing entity and saving included)
1. Documentation page exists
1. Highlight form fields with validation errors
1. 12-factor compliance reviewed and improvements planned
1. ~~Existing phpmd ignore cases reviewed~~
1. Reviewed and refactored global `$abterModuleManager` usage
1. Must-accept-cookie module
1. Test loading entities that don't exist or faulty
1. Fix empty exceptions (\LogicException, \RuntimeException, \InvalidArgumentException)
1. Ensure identifiers do not contain a comma (explode issue)
1. 70%+ of JS source code unit tested
1. Enable `Generic.Commenting.DocComment` phpcs rules
1. Improve validation
   - Review validation factories
   - Create new validation rules where needed
1. Cached translations
1. Documentation covers getting started and main design goals
1. 3 nice website modules
1. Fix sidebar propeller "bug"
1. Cache navigation for user

### 1.0 musts:

1. API designed finalized (community input?)
1. API implemented
1. API test complete
1. Acceptance tests for main use cases of each module
1. All grids, paginations, filters covered with acceptance tests
1. Displaying and saving of all forms and proper returns covered with acceptance tests
1. Refactored helpers
1. AbterPhp\Framework\Form\Factory\Base::getMultiSelectSize -> move to helper or Select
1. Refactored configs
1. Refactored bootstrappers (again)
1. All constants reviewed
1. Documentation complete
1. 12-factor compliance clearly stated
1. More useful dashboard (community input?)
1. Enable `ONLY_FULL_GROUP_BY` in `mysql.conf`
1. Refactor
   - `AbterPhp\Framework\Html\Collection`
   - `AbterPhp\Framework\Html\Helper\ArrayHelper::formatAttribute`
   - Classes with CouplingBetweenObjects over a 15-20 (TBD)
   - `AbterPhp\Framework\Module\Manager`
1. Proper maintenance handling
1. Consider caching processed module data

## Ideas

### Ideas for 1.0:

 - Smoke tests with data generators
 - PostgreSQL support
 - Forgotten password feature
 - 2FA feature
   - https://www.neonwiz.com/blog/two-factor-authentication-2fa-in-php/
   - https://github.com/RobThree/TwoFactorAuth
   - https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/
   - https://medium.com/@richb_/easy-two-factor-authentication-2fa-with-google-authenticator-php-108388a1ea23
   - https://medium.com/s/the-firewall/episode-3-multifactor-authentication-b25e9e1d2c18
 - Serverless support
   - S3 usage as filesystem?
 - Contact form table holding contact details
 - Simple blog module
 - Pick new frontend framework for backend system and implement it
 - Actions to extend Cell
 - `binary(16)` ids instead of `char(36)`
   - Needs MySQL 8.0 `BIN_TO_UUID` and `UUID_TO_BIN` support
   - Needs query builder support (although not a must)
 - Setup [codeclimate.com](https://codeclimate.com/) properly

### Ideas for 1.1:
 - WYSIWYG file selector
 - WYSIWYG image upload
 - User image upload
 - Cleanup HTML templates
 - Protected pages (Pages only accessable logged in)
 
### Long tail:
 - Opulence authorization (instead of or on top of Casbin)
