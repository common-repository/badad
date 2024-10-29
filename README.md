# badAd
The official badAd.one API plugin for WordPress

To use this as a WordPress plugin:
1. rename this directory to: badad
2. place it in the wp-content/plugins directory

Fork and view on [GitHub](https://github.com/badAd/wordpress)!

TGIFOSS!

# Additional add-on plugins and themes (for developers)
This plugin is prepared to be built upon. In terms of code, the primary purpose of this plugin is to establish the connection with the badAd API. Any other functionality is secondary in terms of roadmap and future stack reliability.
In the initial release, beyond the badAd API connection, this plugin only employs shortcodes, though this could expand in the future into OOB widgets with settings or "smart" insertion into post content.
However, any future product roadmap beyond shortcodes may likely be packaged into other dependent plugins, whether officially written for badAd or otherwise. This includes theme integration, widgets, and working with content in posts.

For now, the roadmap intends that there will be ONLY ONE Dev API set of keys and ONLY ONE Partner Connection per site. (On multisite, each site manages keys and connections independently.)
You may consider your plugin or theme hereby licensed, pursuant to the WordPress.org plugin guidelines, to list this plugin as a "dependency" or "base" or "core" or similar terms as may be normal in the WordPress.org plugin directory.
You may not list your plugin as "powered by" (specifically) this badad plugin. If you use this badad plugin as a dependency for your plugin or theme, note that your plugin/theme has not and will not be reviewed by the badad plugin developer.

The primary purpose of building upon this plugin should be to utilize the API connection to a badAd Partner project. Building on other parts of this plugin's functionality, such as shortcode framework, could break down the road.

# GitHub vs WordPress directory
The WordPress plugin Directory version of this plugin does not include this README.md file, nor the assets folder. Both of these may be deleted if you plan to use this plugin from GitHub.

# Development and stability
This GitHub repo is the development channel for this plugin and it is not recommended to use this to supply your plugin for a production site. For the current, stable plugin, download the [WordPress Directory version](https://wordpress.org/plugins/badad/).
