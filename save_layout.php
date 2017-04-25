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

require('../../config.php');


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$page_id      = $admin->get_post('page_id');
$section_id   = $admin->get_post('section_id');
$type          = $admin->get_post('type');

$header         = $admin->get_post_escaped('header');
$footer          = $admin->get_post_escaped('footer');
$posttempl      = $admin->get_post_escaped('posttempl');


$sql = "UPDATE ";
$sql .= TABLE_PREFIX."mod_procalendar_settings SET "; // create rest of the sql-query
$sql .= "header='$header', ";
$sql .= "footer='$footer', ";
$sql .= "posttempl='$posttempl' ";
$sql .= " WHERE section_id=$section_id";
     
$database->query($sql);

if($database->is_error()) {
  $admin->print_error($database->get_error(), WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&section_id=$section_id");
} else {
  $admin->print_success($TEXT['SUCCESS'], WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&section_id=$section_id");
}

$admin->print_footer();

?>