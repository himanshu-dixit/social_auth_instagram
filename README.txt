SOCIAL AUTH INSTAGRAM MODULE

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Recommended modules
 * Installation
 * Configuration
 * How it works
 * Support requests

INTRODUCTION
------------

Social Auth Instagram Module is a Instagram Authentication integration for Drupal.

REQUIREMENTS
------------

This module requires the following modules:

 * Social Auth (https://drupal.org/project/social_auth)
 * Social API (https://drupal.org/project/social_api)


RECOMMENDED MODULES
-------------------

 * Composer Manager (https://www.drupal.org/project/composer_manager):
   This module will help to install the League PHP library and Instagram base library for the league,
   which are the libraries required to make user authentication.

INSTALLATION
------------

 * Download Instagram Base Library for TheLeague OAuth2 PHP library
   (https://github.com/thephpleague/oauth2-google). We recommend to use
   Composer Manager module to install the library.

 * Install the dependencies: Social API and Social Auth.

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-8
   for further information.

CONFIGURATION
-------------

 * Add your Instagram project OAuth information in
   Configuration » User Authentication » Instagram.

 * Place a Social Auth Instagram block in Structure » Block Layout.

 * If you already have a Social Auth Login block in the site, rebuild the cache.


HOW IT WORKS
------------

User can click on the Instagram logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points
to /user/login/instagram, so theming and customizing the button or link
is very flexible.

When the user opens the /user/login/instagram link, it automatically takes
user to Instagram Accounts for authentication. Instagram then returns the user to
Drupal site. If we have an existing Drupal user with the same email address
provided by Instagram, that user is logged in. Otherwise a new Drupal user is
created.

SUPPORT REQUESTS
----------------

Before posting a support request, carefully read the installation
instructions provided in module documentation page.

Before posting a support request, check Composer Manager status report at
admin/reports/composer-manager. This status page will show the Instagram base library for Theleague OAuth2
PHP library version if Drupal can detect it.

Before posting a support request, check Recent log entries at
admin/reports/dblog

Once you have done this, you can post a support request at module issue queue:
https://www.drupal.org/node/2841076

When posting a support request, please inform what does the status report say
at admin/reports/composer-manager and if you were able to see any errors in
Recent log entries.

MAINTAINERS
-----------

Current maintainers:
 * Getulio Sánchez (gvso) - https://www.drupal.org/u/gvso
 * Himanshu Dixit (himanshu-dixit) - https://www.drupal.org/u/himanshu-dixit
