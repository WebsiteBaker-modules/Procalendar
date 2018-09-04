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

$usecustom1   		= $admin->get_post_escaped('usecustom1');
$custom1      		= $admin->get_post_escaped('custom1');
$customtemplate1	= $admin->get_post_escaped('customtemplate1');
$usecustom2   		= $admin->get_post_escaped('usecustom2');
$custom2      		= $admin->get_post_escaped('custom2').'&nbsp;';
$customtemplate2	= $admin->get_post_escaped('customtemplate2');
$usecustom3   		= $admin->get_post_escaped('usecustom3');
$custom3      		= $admin->get_post_escaped('custom3').'&nbsp;';
$customtemplate3	= $admin->get_post_escaped('customtemplate3');
$usecustom4   		= $admin->get_post_escaped('usecustom4');
$custom4      		= $admin->get_post_escaped('custom4').'&nbsp;';
$customtemplate4	= $admin->get_post_escaped('customtemplate4');
$usecustom5   		= $admin->get_post_escaped('usecustom5');
$custom5      		= $admin->get_post_escaped('custom5').'&nbsp;';
$customtemplate5	= $admin->get_post_escaped('customtemplate5');
$usecustom6   		= $admin->get_post_escaped('usecustom6');
$custom6      		= $admin->get_post_escaped('custom6').'&nbsp;';
$customtemplate6	= $admin->get_post_escaped('customtemplate6');
$usecustom7   		= $admin->get_post_escaped('usecustom7');
$custom7      		= $admin->get_post_escaped('custom7').'&nbsp;';
$customtemplate7	= $admin->get_post_escaped('customtemplate7');
$usecustom8   		= $admin->get_post_escaped('usecustom8');
$custom8      		= $admin->get_post_escaped('custom8').'&nbsp;';
$customtemplate8	= $admin->get_post_escaped('customtemplate8');
$usecustom9   		= $admin->get_post_escaped('usecustom9');
$custom9      		= $admin->get_post_escaped('custom9').'&nbsp;';
$customtemplate9	= $admin->get_post_escaped('customtemplate9');
$resize      		= $admin->get_post_escaped('resize');


$sql = "UPDATE ";
$sql .= TABLE_PREFIX."mod_procalendar_settings SET "; // create rest of the sql-query
$sql .= "usecustom1='$usecustom1', ";
$sql .= "customtemplate1='$customtemplate1', ";
$sql .= "custom1='$custom1', ";
$sql .= "usecustom2='$usecustom2', ";
$sql .= "customtemplate2='$customtemplate2', ";
$sql .= "custom2='$custom2', ";
$sql .= "usecustom3='$usecustom3', ";
$sql .= "customtemplate3='$customtemplate3', ";
$sql .= "custom3='$custom3', ";
$sql .= "usecustom4='$usecustom4', ";
$sql .= "customtemplate4='$customtemplate4', ";
$sql .= "custom4='$custom4', ";
$sql .= "usecustom5='$usecustom5', ";
$sql .= "customtemplate5='$customtemplate5', ";
$sql .= "custom5='$custom5', ";
$sql .= "usecustom6='$usecustom6', ";
$sql .= "customtemplate6='$customtemplate6', ";
$sql .= "custom6='$custom6', ";
$sql .= "usecustom7='$usecustom7', ";
$sql .= "customtemplate7='$customtemplate7', ";
$sql .= "custom7='$custom7', ";
$sql .= "usecustom8='$usecustom8', ";
$sql .= "customtemplate8='$customtemplate8', ";
$sql .= "custom8='$custom8', ";
$sql .= "usecustom9='$usecustom9', ";
$sql .= "customtemplate9='$customtemplate9', ";
$sql .= "custom9='$custom9', "; 
$sql .= "resize='$resize' ";        
$sql .= " WHERE section_id=$section_id";
     
$database->query($sql);

if($database->is_error()) {
  $admin->print_error($database->get_error(), WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&section_id=$section_id");
} else {
  $admin->print_success($TEXT['SUCCESS'], WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&section_id=$section_id");
}

$admin->print_footer();

?>