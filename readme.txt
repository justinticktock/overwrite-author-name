=== Plugin Name ===
Contributors: justinticktock
Tags: author, enforce, overwrite, publish
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Overwrite Author on save to a consistent name.

== Description ==

Are you currently giving multiple users the same login details?  If 'yes' then 'Overwrite Author Name' is for you.

Overwrite Author Name is a plugin to ensure on save of a post the author name will be replaced. This allows the site to have a consistent/clean authorship and protects individual users from leaving their user-name as author.  This is important as users can then login with their own user-name/password using capabilities allocated, however, published content will have a corporate author name.

[Plugin site](http://justinandco.com/plugins/overwrite-author-name/).  	
[GitHub page](https://github.com/justinticktock/overwrite-author-name).

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Goto "Settings".."Overwrite Author" Settings page, select the user name to replace others.

== Frequently Asked Questions ==

= Once I save I can't edit the post? =

The standard wordpress capabilities define if you can edit a post/page once you have posted.  This plugin if enabled will overwrite the author name, to re-edit the post/page users will need the 'edit_others_posts' or 'edit_others_pages' wordpress capability respectively.

== Screenshots ==

1. The Settings Screen.

== Changelog ==

= 1.0 =
* 2013-11-28
* First release 

== Upgrade Notice ==

