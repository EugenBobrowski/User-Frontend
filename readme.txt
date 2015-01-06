=== User Frontend ===
Contributors: wpcodingde, inpsyde, HerrLlama
Donate Link: http://wpcoding.de
Tags: user, profile, login, logout, frontend, theme, multisite
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 1.0.4
License: GPLv3

This plugin restricts the access to the admin panel and provides register, profile, login and logout features inside of the theme. This plugin is multisite-ready.

== Description ==

This plugin restricts the access to the admin panel and provides register, profile, login and logout features inside of the theme. This plugin is multisite-ready.

= Languages =

This plugin is polyglott. If you want to submit a language, drop a not at "hallo@wpcoding.de" with the subject "User Frontend - Add language $language".

Currently available languages:

* English
* German

= Support =

At any questions: Please keep in mind that this tool is free. Therefore we can't offer free support. Of course we'll see through the [issue tracker](https://github.com/wpcodingde/User-Frontend/issues/) but we only answer feature requests and critical bugs. Besides this you can use the [wordpres.org forums](https://wordpress.org/support/plugin/user-frontend/) for technical questions.

= Planned Features =

This plugin already has many features. But there are always more ideas to develope. Some of theme are:

* Google Authentication Support
* Better Basic Templates (more standard filters, better css-classes)
* Basic User-Profile
* ...

== Frequently Asked Questions ==

= What links uses this plugin? =

The user frontend works directly after activation. Every user will be redirected to the specific pages in the frontend. These are:

*	'http://yourdomain.tld/user-action/' - This is a custom URL which no user usually sees. It performs some actions, like updating profiles etc.
*	'http://yourdomain.tld/user-error/' - A custom error page for user mistakes, e.g. wrong nonces
*	'http://yourdomain.tld/user-login/' - The login page
*	'http://yourdomain.tld/user-profile/' - The profil edit page
*	'http://yourdomain.tld/user-register/' - The user registration page
*	'http://yourdomain.tld/user-reset-password/' - The reset password page
*	'http://yourdomain.tld/user-forgot-password/' - The forgot password page
*	'http://yourdomain.tld/user-activation/' - The user activation page

= Can I style my own pages? =

Yes you can. As written in the 'Installation'-tab you only have to copy (donot move, just to be sure to have a backup) the templates from <code>/wp-content/plugins/user-frontend/templates/</code> to <code>wp-content/themes/your-theme/user-frontend/</code>. After that edit it wisely.

= Where is the logout located? =

The logout is a special case. It doesn't have a page to be displayed it is just an action performed by the system. However you can retrive the URL with the function <code>uf_logout_url()</code>. See its documentation for more information.

== Installation ==

1. Install and activate the plugin the known WordPress-Ways
2. Refresh your permalinks via Options -> Permalinks and click "Save"
3. For Developers: Copy the .php files from the template folder to your active theme folder and change the markup your way

== Screenshots ==

1. Profile edition with the twentythirteen theme

== Upgrade Notice ==

= 1.0.3 =

Due to changes in the template include functions you need to move the user frontend specific templates (user-activation.php, user-forgot-password.php, user-login.php, user-profile.php, user-register.php and user-reset-password.php) from the theme root directory to a new folder 'user-frontend' in your theme.

== Changelog ==

= 1.0.4 =

* Fixed critical bug with the version checkup, [#16](https://github.com/wpcodingde/User-Frontend/issues/16) 

= 1.0.3 =

* Implemented better Template include, [#10](https://github.com/wpcodingde/User-Frontend/issues/10)
* Added /user-error/ as special page, [#11](https://github.com/wpcodingde/User-Frontend/issues/11)
* Fixed redirection issues, [#11](https://github.com/wpcodingde/User-Frontend/issues/11)
* Disable register if the WordPress Settings tell so, [#12](https://github.com/wpcodingde/User-Frontend/issues/12)
* Add filter for the redirection after login, [#13](https://github.com/wpcodingde/User-Frontend/issues/13)

= 1.0.2 =
* Fixed problems with redirection and templates
* Implemented more API-Filters
* Added Doc-Block

= 1.0.1 =
* Update-Tests for WordPress 3.8
