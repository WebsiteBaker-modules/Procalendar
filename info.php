<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2006, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

changelog

version 1.3.9 27.07.2018
! fix: Countable() error message(s) on empty calendar

version 1.3.8 xx.xx.2016
+ font color in eventlist depends on background color now (thx to gnom: http://forum.websitebaker.org/index.php/topic,28883.msg202558.html#msg202558)
+ added user visibility settings for logged in and members of a certain group (thx for suggesting to pfreud1 http://forum.wbce.org/viewtopic.php?id=346)

version 1.3.7 30.12.2015
! debug call in save.php caused memory overflow (reprted by hgs at http://forum.websitebaker.org/index.php/topic,28423.msg201765.html#msg201765)
! make save.php work with wbce 1.2.x

version 1.3.6 25.11.2015
! again show correct date on details view (thx to ichmusshierweg)
! some code cleaning

version 1.3.5 26.09.2015
! field enddate stays hidden also on rec series if "only use startdate" option is selected
! show correct date on details view (reported bei Tomno339 at http://forum.websitebaker.org/index.php/topic,28423.msg200586.html#msg200586)

version 1.3.4 01.07.2015
! rec monthly events: no more missing dates on seies which last over more than one calendar year; first date can be at start date; 
  (thanks for reporting to Tomno399 @ http://forum.websitebaker.org/index.php/topic,28423.msg199017.html#msg199017)
! error in upgrade.php (reported and fixed by dbs http://forum.websitebaker.org/index.php/topic,27782.msg193372.html#msg193372)
! end time is no longer set for date series with fixed number of dates or infinite series (reported by dbs http://forum.websitebaker.org/index.php/topic,27782.msg193372.html#msg193372)

version 1.3.3 10.08.2014
! fourth criteria on rec events now working
! past start date does no longer create date range when startdate only option is selected
+ current day gets class procal_today now
! details view back link ist browser history back now (makes sense for usage in droplets)

version 1.3.2 19.02.2014
! changed css classname to make procal work in wb 2.8.4

version 1.3.1 09.05.2013
! some notices fixed
+ kat id is shown on options page in categorie dropdown (might help using droplets)

version 1.3 27.02.2013
! notice message when not category is set
! problems when module or droplet is used on muliple sections on the same page

version 1.3 17.05.2012
! fixed a problem regarding endtime used in recurring events
+ added possibility for easy overwriting individual events of a date series

version 1.3 01.04.2012
! fixed an include path error to mColorPicer which was introduced in version 1.2
+ added recurring date support and therefore added some fields in actions table and new file modify_recurrent_inc.php

version 1.2 22.03.2012
! fixed some format problems with year and month navigation by putting them in the same table field (functions.php, frontend.css, backend.css
- deleted some $colcount stuff, isn't used as colcount counts rows and not columns (functions.php)
+ added color support for categories by using an adapted version of mColorPicker, thanks to meta100.com (new directory "images", fields "format" and "format_days" in eventgrups table)
+ added upgrade.php

*/

$module_directory    = 'procalendar';
$module_name         = 'ProCalendar';
$module_function     = 'page';
$module_version      = '1.3.9';
$module_designed_for = '2.8';
$module_author       = 'David Ilicz Klementa, Burkhard Hekers, Jurgen Nijhuis, John Maats';
$module_license      = 'GNU General Public License';
$module_description  = 'Event calendar, based on MyCalendar by Burkhard Hekers (that was based on Calendar Module from David Ilicz Klementa)';
$module_home         = 'http://www.argosmedia.net/wb/pages/procalendar.php';