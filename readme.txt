=== Plugin Name ===
Contributors: justinticktock
Tags: author, enforce, overwrite, publish
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Overwrite Author to a consistent name when publishing a post.

== Description ==

Are you currently giving multiple users the same login details?  
If 'yes' then 'Overwrite Author Name' is for you.

'Overwrite Author Name' is a plugin to ensure when publishing a post the author name will be replaced. This allows the site to have a consistent/clean authorship and protects individual users from leaving their user-name as author.  This is important as users can then login with their own user-name/password using capabilities allocated, however the published content will have a corporate author name against site content.

supports standard and custom post types.

[Plugin site](http://justinandco.com/plugins/overwrite-author-name/).  	
[GitHub page](https://github.com/justinticktock/overwrite-author-name).

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Goto "Settings".."Overwrite Author" Settings page, select the user name to replace others.

== Frequently Asked Questions ==

= Once I publish my post I can't edit it? =

The standard wordpress capabilities define if you can edit a post/page once you have posted.  This plugin, if active, will overwrite the author name, to re-edit the post/page users will need the 'edit_others_posts' or 'edit_others_pages' wordpress capability respectively.

== Screenshots ==

1. The Settings Screen.

== Changelog ==

= 1.2 =
* 2013-12-12
* fixed the 'publish_post' action hook for page and all custom post types
* attachment post type is now on the 'edit_attachment' hook

= 1.1 =
* 2013-12-04
* Changed the hook to the 'publish_post' action hook, this is so that contributors can continue to edit/save their draft content until the post is finally published.

= 1.0 =
* 2013-11-28
* First release 

== Upgrade Notice ==

