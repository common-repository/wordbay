=== WordBay ===
Contributors: markowe
Donate link: http://www.thewordbay.com
Tags: ebay, EPN, affiliate, listing, product
Requires at least: 2.7
Tested up to: 3.1
Stable tag: 1.2.9
name: WordBay

WordBay allows you to easily insert eBay product listings into your WP posts and pages, and earn commission from sales.

== Description ==

__NOTE: This version of Wordbay is A TIME BOMB!!! Sorry to be dramatic, but due to changes to the eBay RSS feed, IT MAY STOP WORKING on June 30th 2011, or at a time not long after that! Unfortunately I do not have time to upgrade it as there are many many others things that would need improving too.__ If there is still demand for this free version then I would urge a developer to take this old version and continue its development under GNU/GPL. As I have said, I unfortunately cannot do this. I have for some time been working on a new version which is better in every way and also compatible with the new RSS feed (and backwards compatible with the old version of Wordbay). Find out more about the new version at [The Word Bay](http://www.thewordbay.com/ "The Word Bay").

To recap, this plugin allows you to insert a listing of related eBay products into your Wordpress pages and posts, according to parameters defined in the admin panel. If you include an eBay Partner Network campaign ID, you can earn commission from every sale! However, this old version has some performance issues since it uses the old Magpie RSS library that came with older versions of Wordpress and this fills up your database with cache files and doesn't see fit to delete them after. Your host can actually suspend you if this gets out of hand. THIS HAS HAPPENED TO WORDBAY USERS (and users of other Magpie-based solutions). The new version resolves all this, runs much faster (full caching, on DISK, not in your dbase) but most important, LOOKS FANTASTIC, nothing like those shoddy old eBay RSS listings that frankly leave a footprint the size of Belgium. And, as I said, it is compatible with the new eBay RSS feed. So go get the new version now at [The Word Bay](http://www.thewordbay.com/ "The Word Bay")! 



== Installation ==

__These instructions relate to this repository version of Wordbay. Use at your own risk - it will likely STOP WORKING some times after June 30th 2011 and has been replaced with a new version (details at: [The Word Bay](http://www.thewordbay.com/ "The Word Bay"))__ 

WordBay is installed like any other Wordpress plugin.

1) Download the latest package.

2) Upload the whole directory into your wordpress plugins directory (usually, yoursite/wp-content/plugins). The plugin must reside in a sub-folder called "wordbay" (lower-case!) as supplied in the zip file, else it WON'T WORK - the obfuscation file (buy.php) won't be accessible. If you want to move this, you need to change the relevant code in the plugin.

3) Go into the Wordpress admin panel, find Plugins and WordBay should be listed. Click Activate to activate!

4) You are ready to roll!


__Using WordBay - quick start__

Please see detailed instructions in the settings once you have installed it.

1) You need to configure things in the Options/Settings panel, so find Settings -> WordBay (you will see a BIG warning about the new version of Wordbay and it won't go away, sorry!) and tailor things to your own requirements. Most of the options are self-explanatory so I won’t repeat things here, there are quite detailed instructions in the admin panel, BUT…:

2) …if you want to have a search page and use the search box and “expanded search” link, you MUST create a search page. This is done simply by creating a New Page called Product Search, Aquarium Search, or whatever else you want. You don’t need any content on that page, though you can put some short text or something.

You must then copy/paste the permalink to this page into the field provided in the admin panel, where there is lots of red text! Something like http://www.mysite.com/search-page/. As it says in the admin panel, and I repeat, you MUST have SEF urls activated for this to work!!


3) To insert eBay listings into your posts or pages is really simple: write a new Page or Post in Wordpress. Let’s call it Green vintage lamps and let’s write a few lines of text as our content - “Lovely green vintage lamps”. We are also going to add the following code in the content:

[wordbay]green vintage lamp[/wordbay]

This is our search code, and is pretty self-explanatory, but we will look at this in more detail in a minute. First hit Publish and go to your site and have a look at the post or page you have created.

You should get an eBay listing corresponding to your search terms, and to the parameters you set in the admin panel. If not… erm… go back and check the instructions. They are pretty straightforward, so there should not be anything that can’t be solved. If your search terms are too narrow, that could be a problem, and the category you picked may limit things too. If you chose to display the search box and “expanded search” link, try these to see if they are working.

By the way, you will probably want to change the colours - these are set in the WordBay.css file - I want to make this possible through the backend in the future, but for now, remember to save this somewhere separate so that when you install a new version of WordBay it will not overwite your file and it's settings.

By using eBay search terms and creating new pages/posts for different items/products, you can quickly build up an eBay niche site, and if you have entered your EPN details properly in the admin panel you can earn commission from any sales you make. By the way, when you do this, please consider setting the Generosity quotient to something, well, symbolic (more than 0!) at least. In doing so a small percentage of your impressions will go to the author of WordBay (me), who has invested a lot of time in the project, when I really should have been doing something else, and it would warm the cockles of my heart to see a few pennies as a result of my hard work!


__WordBay search terms__

As you probably figured out, the tags [wordbay]fish[/wordbay] inserted anywhere in your page body text will search for all products with “fish” in the title. However, it is difficult to target searches with just this, so here are some more tips :

[wordbay]green lamp[/wordbay] - this is logical AND – space between the words means all products with “green” and “lamp” in the title.
[wordbay]”green lamp”[/wordbay] – exact phrase match – means the exact phrase “green lamp” must be found in the product title
[wordbay]lamp -green[/wordbay] – AND NOT – searches for all occurrences of ”lamp”, but minus sign excludes any titles with “green”. No green lamps here!
[wordbay](lamp,“bedside table”)[/wordbay] – OR – brackets apply logical OR (also AND/OR I think) to all items in the brackets, whether single keywords or phrases, i.e. this will find all items with either “lamp” OR “bedside table”.

By the way, supposedly you are not suppose to put spaces after commas or minus signs, so it’s best to stick to that just in case. Here are a couple more examples:

[wordbay](“old lamp”,“old telephone”) -green[/wordbay] – find all items with either “old lamp” or “old telephone” in the title, but excluding “green”.
[wordbay]lamp –(book,CD)[/wordbay] – this will search on “lamp” BUT will exclude “book” and/or “CD”. This type of search is especially useful as it allows you to eliminate most of the junk/irrelevant items like “My Old Lamp CD” and narrow the search down to just actual lamps! Though some of these searches CAn get quite long, and there is supposedly a limit to the number of terms. Just experiment, what can I say.

There is more help regarding seach criteria on the relevant eBay page (http://pages.ebay.com/help/find/search_commands.html?fromFeature=Advanced%20Search).



== Frequently Asked Questions ==

Q: I get 404 errors, or "Page not found"
A: You must have SEF urls activated if you are using the search option. Also, you must make sure that the buy.php file is located in the WordBay plugin directory. The plugin MUST reside in this directory, or a different path be specified to this file in the admin options.

A: Another reason could be that you do not have the WordBay plugin installed in a directory called "wordbay" (lower case!) - it is essential you do this, or change the code if you are confident in doing so, so that the path to the buy.php file is specified, otherwise the link obfuscation code won't work. The path to the buy.php file can now be specified in the backend.

Q: Can you add an option to...?
A: I get a lot of requests for new features, many of them very good and many of them have been implemented gradually. But please bear with me - I cannot commit much time to development, this is not a commercial product and I don't make my living from it. I will try to include all reasonable requests for features, just please don't expect anything overnight!

All comments and suggestions will be appreciated, to help iron out any bugs.

== Screenshots ==

No screenshots available.


== Upgrade Notice ==

= 1.2.9 =

This upgrade is merely to let you know that this old version of Wordbay will STOP working on June 30th 2011, or some time after! You must upgrade at [The Word Bay](http://www.thewordbay.com/ "The Word Bay").

= 1.28 =

Corrects a bug with 1.27 whereby links were not being properly formed for Buy Now/Add to watchlist links and lead to a blank page. NO need to install this if you stayed with 1.26 - the bug wasn't present there AFAIK.


== Changelog ==

= 1.28 =
- corrected bug with malformed links.

= 1.27 =
- added a notice that this version of WB is being discontinued
- removed the eBay generosity option
- added an Amazon affiliate link to 'empty listings' pages so that traffic is not wasted
- added Amazon tag and 'show Amazon link' options in the admin panel
- fixed some PHP error notices 

= 1.26 =
- changed default 302 redirect to 301 in order to comply with new EPN TOS enforcement

= 1.25 =
- rechecked all the RSS parameters and made some small changes to reflect the parameters used in the RSS generator tool
- corrected a small bug that seems WB was geotargetting even if you had that option turned off!
- tidied up the FeedBurner subscription option


= 1.22 =
- fixed a bug created in the last version, whereby "Sign up" links were not properly forwarded
- hurriedly added a FeedBurner subscription option in the back end


= 1.21 =
- small change to eliminate problem some users were having with path to the countries.txt file not working
- made some changes to ensure the WP path is got properly in other places too, like when saving the CSS file. Might be the cause of some errors people have been having with 404 errors, but need to continue to monitor that
- removed a spurious part of the url obfuscation routine that I realised COULD cause a potential bug (in a very small minority of cases)
- tacked the product title on to the affiliate link, might help with SEO (doubt it, mind...)

- similarly, added "title" tooltips to product links for SEO and user-friendliness purposes
- got rid of the silly bug that was adding slashes to single quotes in the CSS file

= 1.20 =

- corrected the bug where the "Click for details" icon was taking visitors to a signin page
- found some legacy Wordpress functions I was using that needed changing. You won't notice the difference!
- added rudimentary "widget" functionality - this is definitely BETA!
- corrected small potential security problem - not sanitising $_GET data. Big no-no usually, but it's unlikely this could have been exploited here. Still, better safe...

= 1.16 =

- corrected a small bug where the plugin was not getting the correct path to the countries file if it was in a different directory to that expected


= 1.15 =

- completely scrapped and reconceived the .css file retention feature introduced in the last version, since it didn't work!


= 1.1 =

- added feature to protect WordBay.css file from getting overwritten every time a new version of WordBay is installed.

= 1.05 =

- added option to specify seller IDs.

= 1.0 =

- primarily clarifications to instructions, no new functionality


= 0.999 =

- introduced individual category numbers for each country
- other minor changes not affecting functionality

= 0.998 =

- added a nice mouseover effect for products items
- added ability to move and rename "buy.php"
 file
- changed remote access method for hostip database to use CURL instead of fopen, and to include a 2 second timeout and revert to default locale if geo server
 not working

= 0.997 =

- fixed bug which was not setting the proper dispatch location for items when using geo-targetting
- added the option to override the geo-targetted "dispatch location"
- added style to the CSS for 'WBsearchbox', i.e. the search form DIV. This DIV previously had no ID which was very bad style!

= 0.996 =

- fixed a couple of minor bugs - US not appearing in SiteID dropdown and space at end of PHP file causing trouble

= 0.995 =

- country codes sorted out - all eBay countries now supported, though not all pay commissions, remember!
- admin backend select forms code optimised - just tidying up, no visible difference, except plugin size much smaller now due to removal of duplication


= 0.99 =


- added Jez's geotargetting routine!
- fixed bug where quotes in keywords were messing things up
- changed the function of "...click here to see similar items" - was usually not showing any different results before. Still not that happy with it though.
- tidied up comments in the code
 

= 0.96 =

- fixed a slight DIV problem which caused some sidebars to run over

= 0.95 =

- added support for multiple columns
- improved description of plugin for WordPress repository
- added missing text in back-end about Motors categories
- changed "Not found" text slightly - thanks Dave, grammar first
- changed default site ID to US, not UK, thus eliminating strange Brit bias
- added better disclosure of Generosity setting at the top (transparency is up there with grammar)

= 0.92 =

- changed plugin directory name to wordbay (lowercase), to make it compatible with the Wordpress repository.

= 0.91 =

- basically nothing, just updated some tags to comply with WordPress repository - no changes to code

= for 0.9 =

- added options to set minimum and maximum price
- added an (optional) "CLICK for details" button, to hopefully improve clickthrough

= 0.86 =

- not much, slight tidying of code, 0.85 BETA seems to have been stable


= 0.85BETA =

- fixed minor inconsistency - "No items to list" appearing when it wasn't supposed to
- fixed duff "go to eBay" link on search results page

= 0.8BETA =

- (0.7 skipped to avoid confusion with the old WordBay extension)

- added support for top-level eBay categories
- fixed bug where time zone display was hard-coded for PDT. Now displays local eBay site time.
- fixed a remaining small quirk with dollar sign display (surely it's gone now!)
- fixed a little bug where the "Sorry, no items to list" message was displayed when search results displayed less items than stipulated by the maximum items variable.


= 0.6BETA =

- fixed unclosed /<h2/> tag which was breaking templates
- Site ID properly implemented to include all eBay sites
- fixed quirk where dollar signs were getting eaten

= 0.5BETA =

- Major bug where content was not being displayed on pages with no [WordBay] tags.
