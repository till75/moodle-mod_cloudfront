This moodle module plays .mp4 videos hosted on Amazon's CloudFront service.

It uses private streaming distributions.

It's based on the template module moodle-mod_newmodule.

I modified the following files of the moodle_mod-newmodule:
- view.php (some very ugly echos in there!)
- version.php
- locallib.php (100% copy from an example given by Amazon's Lance Byrd)
- db/install.xml
- lang/en/cloudfront.php (but it was not working correctly so I used
directly coded strings in view.php)

I used JW Player 5

I added swfobjects.js, also provided by Lance Byrd

Lance Byrd's tutorial (careful: huge, contains video instructions for
setup):
http://d2930476l2fsmh.cloudfront.net/cloudfront-private-stream-with-php.zip


======================== ORIGINAL README ==========================


The following steps should get you up and running with
this module template code.

* DO NOT PANIC!

* Unzip the archive and read this file

* Rename the newmodule/ folder to the name of your module (eg "widget").
  The module folder MUST be lower case. You should check the CVS contrib
  area at http://cvs.moodle.org/contrib/plugins/mod/ to make sure that
  your name is not already used by an other module.

* Edit all the files in this directory and its subdirectories and change
  all the instances of the string "newmodule" to your module name
  (eg "widget"). If you are using Linux, you can use the following command
  $ find . -type f -exec sed -i 's/newmodule/widget/g' {} \;

* Rename the file lang/en/newmodule.php to lang/en/widget.php
  where "widget" is the name of your module

* Place the widget folder into the /mod folder of the moodle
  directory.

* Go to Settings > Site Administration > Development > XMLDB editor
  and modify the module's tables.

* Modify version.php and set the initial version of you module.

* Visit Settings > Site Administration > Notifications, you should find
  the module's tables successfully created

* Go to Site Administration > Plugins > Activity modules > Manage activities
  and you should find that this newmodule has been added to the list of
  installed modules.

* You may now proceed to run your own code in an attempt to develop
  your module. You will probably want to modify mod_form.php and view.php
  as a first step. Check db/access.php to add capabilities.

We encourage you to share your code and experience - visit http://moodle.org

Good luck!
