<?php
/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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


//require('functions.php');

function procalendar_search($func_vars) {
  extract($func_vars, EXTR_PREFIX_ALL, 'func');

  // how many lines of excerpt we want to have at most
  $max_excerpt_num = $func_default_max_excerpt;
  $divider = ".";
  $result  = false;

  // Set start- and end date for query
  $year  = date('Y', time());
  $month = date('n', time());
  $datestart = "$year-$month-1";
  $dateend = "$year-$month-".cal_days_in_month(CAL_GREGORIAN, $month,$year);

  $table = TABLE_PREFIX."mod_procalendar_actions";
  $query = $func_database->query("
      SELECT *
      FROM $table
      WHERE section_id='$func_section_id'  
	  	AND date_start <='$dateend' AND date_end >='$datestart' AND public_stat = 0
    ");

  $PageName = $func_page_title;
  
  if($query->numRows() > 0) 
  {
    
    while($res = $query->fetchRow()) 
    {
      $text = "";
      
      $text .= $res['name'].$divider.$res['description'].$divider; // Default search: only the WYSIWYG-fields
		//$text .= $res['name'].$divider.$res['description'].$divider.$res['custom1'].$divider.$res['custom2'].$divider.$res['custom3'].$divider; 
	  // Use the line above to add 1, 2 or 3 Custom fields to the search
	  
      $func_page_title = $PageName.":<br/>".$res['name'];
      
      
      $link = "&amp;page_id=".$res['page_id']."&amp;id=".$res['id']."&amp;detail=1";
            
      //$func_page_description = "func_page_description is not used";

      $mod_vars = array
                  (
                    'page_link'          => $func_page_link,
                    'page_link_target'   => $link,
                    'page_title'         => $func_page_title,
                    'page_description'   => $func_page_description,
                    'page_modified_when' => $func_page_modified_when,
                    'page_modified_by'   => $func_page_modified_by,
                    'text'               => $text,
                    'max_excerpt_num'    => $max_excerpt_num
                  );
      
      if(print_excerpt2($mod_vars, $func_vars)) 
      {
        $result = true;
      }
    }
  }
  return $result;
}

?>
