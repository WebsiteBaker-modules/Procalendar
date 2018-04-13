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

*/

if (!defined('SYSTEM_RUN')) {header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); echo '404 File not found'; flush(); exit;}


$ct1 = '<div class="field_line">
  <div class="field_title">[CUSTOM_NAME]</div>
  [CUSTOM_CONTENT]
</div>
';

$ct2 = '<div class="field_line">
  <div class="field_title">[CUSTOM_NAME]</div>
  [CUSTOM_CONTENT]
</div>
';

$ct3 = '<div class="field_line">
    <a href="[wblink[CUSTOM_CONTENT]]">[CUSTOM_NAME]</a>
</div>
';

$ct4 = '<div class="field_line">
    <img src="[CUSTOM_CONTENT]" border ="0" alt="[CUSTOM_NAME]" />
</div>
';

$ct5 = '[[[CUSTOM_CONTENT]]]';

$ct6 = '
<div class="field_line">
  <div class="field_title">[CUSTOM_NAME]</div>
  <a href="mailto:[CUSTOM_CONTENT]">[CUSTOM_NAME]</a>
</div>
';

$header = '
<div style="font-size:80%; text-align:right;">
[NEW_ENTRY]
</div>
[CALENDAR]';
$footer = '';
$posttempl = '<div class="event_details">
  <h2>[NAME]</h2>
  <div class="info_block">
    [DATE_FULL]
    [CUSTOM1]
    [CUSTOM2]
    [CUSTOM3]
    [CUSTOM4]
    [CUSTOM5]
    [CUSTOM6]
    [CUSTOM7]
    [CUSTOM8]
    [CUSTOM9]
    [CATEGORY]
  </div>
[CONTENT]
</div>
[BACK]
';

// insert data into pages table
$database->query(
"INSERT INTO ".TABLE_PREFIX."mod_procalendar_settings SET page_id = '$page_id',
    section_id = '$section_id',
    settings = '',
    startday = 0,
    usetime = 0,
    useformat = 'yyyy-mm-dd',
    useifformat = 'Y-m-d',
    usecustom1 = 1,
    custom1 = 'Text field',
    customtemplate1 = '$ct1',
    usecustom2 = 2,
    custom2 = 'Text area',
    customtemplate2 = '$ct2',
    usecustom3 = 3,
    custom3 = 'WB link',
    customtemplate3 = '$ct3',
    usecustom4 = 4,
    custom4 = 'Image',
    customtemplate4 = '$ct4',
    usecustom5 = 0,
    custom5 = 'Droplet',
    customtemplate5 = '$ct5',
    usecustom6 = 0,
    custom6 = 'E-Mail',
    customtemplate6 = '$ct6',
    usecustom7 = 0,
    custom7 = '',
    customtemplate7 = '',
    usecustom8 = 0,
    custom8 = '',
    customtemplate8 = '',
    usecustom9 = 0,
    custom9 = '',
    customtemplate9 = '',
    resize = 0,
    header = '$header',
    footer = '$footer',
    posttempl = '$posttempl'
");
