Events plugin 0.1.0
================
An Events plugin for [Yellow](https://github.com/datenstrom/yellow/). 

You might want to tell the world about the events you have organized. This plugin was made to manage these **events** in a convenient way.

Thanks to the excellent work of the Yellow guys, putting this together was not much more than a copy and paste job with a little tweaking.

Enjoy :)

To make the best out of this yellow-plugin you might also want to install these [yellow-plugins](https://github.com/datenstrom/yellow-plugins):
* [Yellow Fontawesome plugin](https://github.com/datenstrom/yellow-plugins/tree/master/fontawesome) which provides beautiful fontawesome icons for your website.
* [Yellow TOC plugin](https://github.com/datenstrom/yellow-plugins/tree/master/toc) for the agenda to your event, especially useful for meetings.

The following [custom navigation](https://developers.datenstrom.se/help/customising-templates) is recommended:

Navigation: navigation -> shows only top level pages

This is defined in the [settings](https://developers.datenstrom.se/help/markdown-cheat-sheet#settings) at the top of each page or in the config.ini file for the whole page.

How to install?
---------------

This plugin requieres at least Yellow 0.6.6!

1. [Download and install Yellow](https://github.com/datenstrom/yellow/).
2. [Download plugin](https://github.com/xrizzy/yellow-plugin-events/archive/master.zip). If you are using Safari, right click and select 'Download file as'.oO NOT unzip!).

To uninstall delete the plugin files.

How to use?
-----------
The main Events page will show the list of upcomming events and is available on your website as `http://website/events/`. To create a new event, add a new file to the events folder.

How to configure?
----------------- 

You can use shortcuts to show information about the Events:

`[eventsarchive LOCATION]` for a list of months  
`[eventsrecent LOCATION PAGESMAX]` for recently changed events  
`[eventsrelated LOCATION PAGESMAX]` for related events to current event  
`[eventstags LOCATION]` for a list of tags  

The following arguments are available, all but the first argument are optional:

`LOCATION` = events location  
`PAGESMAX` = number of events

Example
-------
Showing recently changed events:

    [eventsrecent]
    [eventsrecent /events/ 10]
    [eventsrecent / 10]

The events are sorted by date.
