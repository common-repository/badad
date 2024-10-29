=== badAd ===

Contributors:      <a href="https://profiles.wordpress.org/jesselsteele/">jesselsteele</a>
Plugin Name:       badAd
Plugin URI:        https://github.com/badAd/wordpress
Tags:              advertise, monetize, ads, embed, ad shortcode
Author URI:        https://badad.one
Author:            badAd
Requires at least: 5.3.2
Tested up to:      6.3.0
Stable tag:        1.2.3
Version:           1.2.3
Requires PHP:      7.2.0
Donate link:       https://jesse.coffee/paypal
License:           GPLv3 or later
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

The official plugin from badAd.one, this can help monetize your WordPress site by embedding badAd advertisements with shortcodes.

Once connected, you can use two shortcodes:

1. To embed ads for your badAd Partner account
2. To embed a styled referral link for you and others to receive free ad credits, also adding to your Partner click count

All the settings are on one page in your WordPress Dashboard with an easy walk-through.

This plugin is intended for badAd Partners, but it is easy to become one. Once you are a badAd monetizing Partner, this is plugin connects your WordPress site to the badAd "Dev API" mentioned in the <a href="https://badad.one/444/site.html"> help videos</a>.

badAd.one is an advertising network that started in early 2020.

Requires WordPress 5.3 and PHP 7 or newer.

== Installation ==

1. Upload 'badad' to the '/wp-content/plugins/' directory,

2. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. What ads and your referral links look like on a website (inheriting the same font)

2. Various shortcodes used in Widgets

3. Simple shortcode example inside a post

4. Shortcode tips in the admin area once connected to the badAd.one website

5. Admin area when just installed, input your badAd Dev API keys here

6. After Dev keys added, two simple ways to connect a specific monetizing Partner project

7. Some more advanced settings availabe once connected

== Frequently Asked Questions ==

= Do I need a badAd.one account? =

Yes. Signup is easy and there are help videos available at the [badAd Help](https://badad.one/help_videos.php) page.

= Will this slow down my WordPress site? =

In short: not as much.

Technically, everything you add to any website will slow it down. But, badAd takes two steps to speed things up:

1. Less is more: We minimize code, files, and things like database calls. We also don't mine information about visitors to your WordPress site. We just deliver the ads, short and simple.

2. badAd uses text ads. These load faster than ads with pictures.

= Do these ads make my website look ugly? =

They shouldn't. Our text ads try their best to inherit the styling of your WordPress theme, hopefully having some of the same fonts, etc.

We try to keep things organized and grouped so that badAd ads aren't confused with your WordPress site's content, but still should fit nicely alongside your content.

= Does this work on multisite? =

Yes, as of version 1.1 it works on multisite.

= It doesn't say it is tested with the current version of WordPress. Will it still work? =

Note this light-weight plugin easily endures WordPress updates, so it may not be updated with each minor WP update. This is intended to cause less work on your back end, but know that we are actively watching and testing this plugin!

== Changelog ==

= 1.2 =

1. Support for multisite

2. Shortcode defaults changed
- To settings more likely to be common

3. Settings page improvements
- More shortcode examples and explanation
- Styling is more readable
- Some explanations are more clearly worded
- Layout is unchanged

4. Streamlined database workflow for storing keys
- This is backend behavior which web users won't notice
- Reduces security risk
- Porting database to new web hosting or refreshing plugin installation should preserve the API connection

5. Developer notes
- Multisite: Callback files are prefixed with the site ID, seamlessly working with both multisite and single sites
- All keys and settings are stored in the database
- The only key stored in the file system is the current test/live public API key, cached in the "callback" subdirectory
- Callback files are created automatically when visiting the admin dashboard, which is the only time they are needed
- Creating callback files via `put_contents()` is less cost and databse size than creating a custom post type
- Porting the database to a new cloud location should preserve the API connection, whether or not the old plugin folder is ported also
- Callback files are cached in the "callback" subdirectory for API use, but they are largely superflous to web host admins since they are only-always confirmed/created only-always when they are needed
- Visiting the admin dashboard will automatically confirm and/or create the callback file, but the callback is only needed if making or checking the API connection, which requires visiting the plugin settings page in admin dashboard anyway. So, this is moot, but may be useful information for some developers.
- Security improvement: The callback file simply captures and redirects the API connection response to the admin dashboard, which guarantees more security and level permissions checks so script kiddies have less room to mess

= 1.2.1 =

- Backend tweaks so both shortcodes render more similarly in HTML
- Tested wtih WP 5.6.1

= 1.2.2 =

- Note this light-weight plugin easily endures WordPress updates, so it may not be updated with each minor WP update. This is intended to cause less work on your back end, but know that we are actively watching and testing this plugin!
- Tested wtih WP 5.8.3

= 1.2.3 =

- Tested wtih WP 6.3
