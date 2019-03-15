# AbterPHP

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

## Stack

AbterPHP is based on [Opulence](https://www.opulencephp.com/), but uses a few more projects alongside Opulence:
 - [Monolog](https://seldaek.github.io/monolog/) for logging
 - [Whoops](https://filp.github.io/whoops/) for error reporting in non-production environments
 - [Flysystem](https://flysystem.thephpleague.com/docs/) for handling filesystem (incomplete implementation)
 - [Casbin](https://casbin.org/) for authorization
 - [Minify](https://www.minifier.org/) for minifying assets (Website module)
 - [Swiftmailer](https://swiftmailer.symfony.com/) for sending emails (Contact module)
 - [Slugify](https://github.com/cocur/slugify) for creating web-safe identifiers (Admin module)
 
If you want to contribute code you'll also need to get familiar with these tools:
 - [PhpUnit](https://phpunit.de/) for unit tests
 - [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for code formatting
 - [PHPMD - PHP Mess Detector](https://phpmd.org/) for code quality
 
AbterPHP currently only supports MySQL as the database system, although PostgreSQL support is very likely to happen
before the first stable version.

## Roadmap

### First alpha musts:

1. Make the frontend framework decoupled from the backend system
2. Pick new frontend framework for backend system and implement it
3. Use ILogger instead of Logger
4. More fine grained authorization
   - Read / write roles separated for files
   - Read / write roles implemented for pages
   - Advanced settings access for pages
   - Multiple user group for one user
   - New user roles: file uploader, designer
5. Improve security implementing related headers as recommended:
   - https://medium.freecodecamp.org/secure-your-web-application-with-these-http-headers-fd66e0367628
   - https://medium.freecodecamp.org/web-security-hardening-http-cookies-be8d8d8016e1
6. Improve validation
   - Review validation factories
   - Create new validation rules where needed
7. 70%+ of source code unit tested, except for bootstrappers
8. Verify that localhost_router.php works as expected or remove it if too hard to fix
9. Modular asset management
10. Complete test automation and automatic reviews set up
11. Refactor module manager

### First beta musts:

1. API implemented
2. 1 grid is covered with acceptance tests (filters, pagination included)
3. 1 form is covered with acceptance tests (displaying with new and existing entity and saving included)
4. Documentation page exists
5. Highlight form fields with validation errors
6. 12-factor compliance reviewed and improvements planned
7. Existing phpmd ignore cases reviewed
8. Review and refactor global `$abterModuleManager` usage

### 1.0 musts:

1. At least 1 use cases for each module is implemented in acceptance tests
2. All grids, paginations, filters covered with acceptance tests
3. Displaying and saving of all forms and proper returns covered with acceptance tests
4. API test complete
5. Refactor helpers
6. Refactor configs
7. Refactor bootstrappers (again)
8. Review all constants
9. Documentation complete
10. 12-factor compliance clearly stated

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
 - Serverless support
   - S3 usage as filesystem?

### Ideas for 1.1:
 - WYSIWYG file selector
 - WYSIWYG image upload
 - User image upload
 - Cleanup HTML templates
 - Protected pages (Pages only accessable logged in)
 
### Long tail:
 - Opulence authorization (instead of or on top of Casbin)
