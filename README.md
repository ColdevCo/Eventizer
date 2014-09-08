Eventizer
========
A simple wordpress plugin to help you manage your events.
 

How to use
==========
* Download the plugin on github
* Extract the zip file under wordpress plugin folder
* Activate the plugin in admin panel
* (_optional_) Enable the ticket extension in Event/Setting admin menu

**Note:** When Eventizer activated, it automatically add a new page titled "Events". The page will show all available upcoming events. If by any chances the page isn't there, you could add a new page manually and put a shortcode "[eventlist]" in the content section.

---------------------------------------------------------------------------------------------------


For Developer Only
===============
If you are developer who knew some PHP and WP codes, you can extends the plugin by making your own extension or widget. We provide you custom hooks and functions that you can use.

Custom Hooks:

* eventizer_init
* eventizer_render
* eventizer_save

Custom Filter:

* ev\_setting\_tabs

Functions:

* add\_event\_options
* get\_event\_options
* update\_event\_options
* delete\_event\_options

#

* add\_mail
* update\_mail
* delete\_mail
* get\_all\_mails
* get\_mail
* get\_mail\_by\_context

Helpers:

* label
* hidden
* text
* textarea
* radio
* checkbox
* dropdown
* button
* datepicker
* timepicker
* geoinput


**Note:** Even though the core plugin is ready to use, the plugin development is still running. So, if you find something unnecessary or you want to add some features or maybe you found an errors or bugs, please let us know via github or email, we really appreciate your feedback. Thanks.
