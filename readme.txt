=== ComparePress ===
Contributors: TheBlogHouse
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: price comparison, affiliate, mobile phones, UK, video games
Requires at least: 2.8
Tested up to: 3.9.1
Stable tag: 2.0.8

Use ComparePress to create or add a full SEO comparison website to your WordPress web site using our affiliate plugin.

== Description ==
Use ComparePress to quickly add and manage a full SEO price comparison system on your existing blog or web site. ComparePress currently has 1 module available that allows you to access our mobile phone database of over 1 million UK mobile phone deals which already powers some of the UK’s leading comparison sites. The ComparePress plugin with the mobile phone module activated will allow you to easily add handset, manufacturer and specification specific pages to your site. The plugin also comes with customisable widgets allowing your users the ability to search all the deals as well as show the best selling and latest released mobile phone handsets.

We also have a UK Video Games module due for release which compares the prices of UK Video games at the leading online retailers with plans to release UK computer, books and DVD price comparison modules soon and are keen to target other markets.

= More ComparePress info =
Check out [http://ComparePress.com](http://comparepress.com) for the full overview.

= Example ComparePress sites =
1. UK Mobile Phones - http://www.mobilechecker.co.uk/
1. UK Video Games - http://gamesprices.co.uk/

= Further Reading =
For more info, check out the following:

* Other [WordPress Articles](http://thebloghouse.com/) by the same author
* Follow me TheBlogHouse on [Twitter](http://twitter.com/thebloghouse)

== Installation ==
Installation is easy:

1. Download the plugin.
1. Copy the folder to the plugins directory of your blog.
1. In your *WordPress Administration Area*, go to the *Plugins* page and click *Activate* for *ComparePress*
1. Sign up to the ComparePress [affiliate programme](http://comparepress.com/sign-up) to get access the full deals database.

= Updating from a ComparePress version that is before version 2.0? =

1. Deactivate your current ComparePress plugin
1. Delete the ComparePress plugin from the plugins directory of your blog either via your WordPress admin area of manually via FTP
1. Download version 2.0 or greater of the ComparePress plugin from WordPress.org
1. In your *WordPress Administration Area*, go to the *Plugins* page and click *Activate* for *ComparePress*
1. Sign up to the ComparePress [affiliate programme](http://comparepress.com/sign-up) to get access the full deals database.

== Initialising ==
* Once the plugin has been activated, click on the new ComparePress menu that appears.
* Enter the access details you have been sent after registering for free here [ComparePress Sign Up](http://comparepress.com/sign-up/) – this is your Token and ComparePress ID. NOTE: Without these access details you will not be able to get the full ComparePress experience.
* The next step is to activate the widgets that will allow the customer to search the deals and / or add the ComparePress shortcodes to display specific handsets which is detailed here: [ComparePress Widgets](http://comparepress.com/support/knowledgebase.php?article=2)
* That's all that is required to start using ComparePress on your site. If you would like to customise ComparePress further please see here: [ComparePress Customisation](http://comparepress.com/support/knowledgebase.php?category=3)

== Frequently Asked Questions ==
* Check out our [ComparePress Knowledgebase](http://comparepress.com/support/knowledgebase.php) which shows all the various configurable options and if you get stuck please log a support request here: [ComparePress Support Ticket](http://comparepress.com/support/).

= Support =
This plugin features built-in helpful hints however you may also find the [FAQ](http://comparepress.com/support/knowledgebase.php "ComparePress FAQ") 's area useful.

== Changelog ==
= 2.0.8 =
* Updated nusoap.php and getmodeals.php for hosts not setting timezone correctly - php 5.3 related
= 2.0.7 =
* Added EE Mobile network options to UK Mobile Phones module deals pages and widget
* Added new 2GB internet data option to UK Mobile Phones module deals pages
= 2.0.6 =
* Added new 1GB internet data option to UK Mobile Phones module deals pages
= 2.0.5 =
* Added Sony to the custom handset descriptions area
= 2.0.4 =
* Fix for bug due to line wrapping on last upload within mobile deal search widget
= 2.0.3 =
* Fix for new WordPress jQuery version so deal sliders work on mobile deals pages
= 2.0.2 =
* Added $priority to main ComparePress content filter for some shortcode issues with certain themes
* Corrected formatting of readme.txt
= 2.0.1 =
* Few bug fixes if a WordPress site is located in a sub folder install instead of a web root
* Images used for retailers, mobile networks and See-Mobile-Deal.jpg button are now within the plugin folder so you can overwrite these with your own if required
* Few small CSS tweaks

= 2.0 =
* Major changes to how the plugin works - modularised functions etc
* Mobiles Module Changes:
* ---------------------------------------------------------------
* Layout changed to accommodate responsive WordPress themes better
* New Ajax deal search sliders for amount of internet data required and change deal results sort method
* New shortcodes added:
* [showphones network_deals='payg'] - shows all handsets available as PAYG
* [showphones network_deals='paygtmobile'] - shows all handsets available as PAYG with T-Mobile*
* [showphones network_deals='simfree'] - shows all handsets available as Sim Free
* [showphones network_deals='tmobile'] - shows all handsets available on a network e.g. T-Mobile
* Ability to filter top and latest widget content e.g. show best selling google android mobile phones not the best selling of all phone types

= 1.0 =
* Private Test Version
* New major milestone reached with module registration code changes in the backend.
* New Ajax based handset deals pages
* New Ajax based deals search shortcodes
* New shortcodes added

= 0.7 =
* Private Test Version
* New major milestone reached with module registration code changes in the backend.

= 0.6.9.6 =
* Added Dell to the list of handset manufacturers due to the launch of the new Dell Streak

= 0.6.9.5 =
* Fixed a rare issue when the PHP Exec plugin was also installed

= 0.6.9 =
* Added ability to filter deals via networks available. So can now just show deals on 3, orange, o2, vodafone, t-mobile, virgin
* e.g. [showphones handsetid ='Sony Ericsson Satio Black' network='orange'] will show all Satio black deals on Orange
* e.g. [showphones handsetid ='Sony Ericsson Satio Black' network='o2' number ='5'] will show top 5 cheapest deals on O2

= 0.6.8 =
* Fixed a rare theme specific issue

= 0.6.7 =
* Changed CSS for Total cost OR Phone price filters so they use same style rules as the rest of the Mobile Search widget.
* Updated default CSS for narrow themes
* Fixed a rogue closing div that was causing some issues.

= 0.6.6 =
* Added ability to filter ComparePress Mobile Search results via Total cost OR Phone price. This means if you are happy paying for the handset (phone) you might be able to get a cheaper overall deal.
* Added ability to turn on or off the Networks and Data drop down boxes that display within the ComparePress Mobile Search
* Couple of other minor tweaks

= 0.6.5 =
* Added ability to control number of results shown when using shortcodes

= 0.6.4 =
* Fixed issue where the deal search would not work with 'ugly' urls

= 0.6.3 =
* New manufacturers added
* Custom Handset admin interface changed
* Admin tabs interface fixed in IE

= 0.6.2 =
* jQuery fix

= 0.6.1 =
* New directory stucture to match WordPress naming conventions

= 0.6 =
* Initial release