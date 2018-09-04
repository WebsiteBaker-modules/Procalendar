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

$type         = $admin->get_post('type');
$page_id      = $admin->get_post('page_id');
$section_id   = $admin->get_post('section_id');

switch ($type) {

case "change_eventgroup":
	$group_id    = $admin->get_post('group_id');
	$group_name  = $admin->get_post('group_name');
	$delete      = $admin->get_post('delete');
	$format 	   = $admin->get_post('action_background');
	$dayformat 	 = $admin->get_post('dayformat');
	if (!isset($dayformat)) $dayformat = 0;

	if ($delete) 
  {
		$sql = "DELETE FROM ".TABLE_PREFIX."mod_procalendar_eventgroups WHERE id=$group_id";
		$database->query($sql);
	} 
	else 
  {
    if($group_name != "") 
    {
 			if (($group_id == 0)) 
      {
        //echo "INSERT -> page_id: $page_id - group_name: $group_name  <br>";
				$sql = "INSERT INTO ";	
				$sql .= TABLE_PREFIX."mod_procalendar_eventgroups SET ";	
				$sql .= "section_id='$section_id', ";
				$sql .= "name='$group_name', ";
				$sql .= "format='$format', ";
				$sql .= "format_days='$dayformat' ";
			} 
      else 
      {
        //echo "UPDATE -> group_id: <br>"; 
				$sql = "UPDATE ";
				$sql .= TABLE_PREFIX."mod_procalendar_eventgroups SET ";	
				$sql .= "section_id='$section_id', ";
				$sql .= "name='$group_name', ";
				$sql .= "format='$format', ";
				$sql .= "format_days='$dayformat' ";
				$sql .= " WHERE id=$group_id";
			}
			
			$database->query($sql);
		}
	}
	break;
		
	case "startd":
		$startday     = $admin->get_post('startday');
		$onedate      = $admin->get_post('onedate');
		$usetime      = $admin->get_post('usetime');
		$useformat    = $admin->get_post('useformat');
		switch ($useformat) {
		  case "dd.mm.yyyy":
		     $useifformat = "d.m.Y";
			 break;
		  case "dd-mm-yyyy":
		     $useifformat = "d-m-Y";
			 break;
		  case "dd/mm/yyyy":
		     $useifformat = "d/m/Y";
			 break;
		  case "dd mm yyyy":
		     $useifformat = "d m Y";
			 break;
		  case "mm.dd.yyyy":
		     $useifformat = "m.d.Y";
			 break;
			case "mm. dd. yyyy":
		     $useifformat = "m. d. Y";
			 break;
		  case "mm-dd-yyyy":
		     $useifformat = "m-d-Y";
			 break;
		  case "mm/dd/yyyy":
		     $useifformat = "m/d/Y";
			 break;
		  case "mm dd yyyy":
		     $useifformat = "m d Y";
			 break;
		  case "yyyy.mm.dd":
		     $useifformat = "Y.m.d";
			 break;
		  case "yyyy-mm-dd":
		     $useifformat = "Y-m-d";
			 break;
		  case "yyyy/mm/dd":
		     $useifformat = "Y/m/d";
			 break;
		  case "yyyy mm dd":
		     $useifformat = "Y m d";
			 break;
		  default:
		     $useifformat = "Y/m/d";
		}

		$sql = "UPDATE ";
        $sql .= TABLE_PREFIX."mod_procalendar_settings SET "; // create rest of the sql-query
        $sql .= "startday='$startday', ";
        $sql .= "usetime='$usetime', ";
        $sql .= "onedate='$onedate', ";
        $sql .= "useformat='$useformat', ";
        $sql .= "useifformat='$useifformat' ";
        $sql .= " WHERE section_id=$section_id";
      
        $database->query($sql);
	break;  		
}

if($database->is_error()) {
  $admin->print_error($database->get_error(), $js_back);
} else {
	if ($type == "change_eventgroup" ) { 
	  $admin->print_success($TEXT['SUCCESS'], WB_URL."/modules/procalendar/modify_settings.php?page_id=".$page_id."&section_id=".$section_id);
	} else {
	  $admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}


$admin->print_footer();

?>