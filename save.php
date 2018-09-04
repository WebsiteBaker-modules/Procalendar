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

if (!isset($_POST['cal_id'])) exit("Cannot access this file directly");

require('../../config.php');

$update_when_modified = true;
require(WB_PATH.'/modules/admin.php');
// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');


$success = true;
$out = "";
$cal_id         = $admin->get_post('cal_id');
$deleteaction   = $admin->get_post('delete');
$SaveAsNew      = $admin->get_post('saveasnew');
$overwrite      = $admin->get_post('overwrite');
$edit_overwrite      = $admin->get_post('edit_overwrite');
$js_start_time  = $admin->get_post('date1');
$js_end_time    = $admin->get_post('date2');
$time_start     = $admin->get_post('time_start');
$time_end       = $admin->get_post('time_end');
$jscal_format   = $admin->get_post('jscal_format');
$rec_by					= $admin->get_post('rec_by');
$rec_id_overwrite					= $admin->get_post('rec_id');
$rec_id = $rec_count = 0;
$rec_type = $rec_days = $rec_weeks = $rec_months = $rec_years = $rec_exclude = "";
$jscalFormat = preg_split( "#\s|/|\.|-#", $jscal_format);


//formats jscal date string to standard mysql format
function jscal_to_date($strDate, $jscalFormat) {
  $formatedDate = "";
	if ($strDate != ""){
	  $dateParts = preg_split( "#\s|/|\.|-#", $strDate);
	  $dateCombined = array_combine($jscalFormat, $dateParts);
	  $formatedDate = $dateCombined['yyyy']."-".$dateCombined['mm']."-".$dateCombined['dd'];
	};
	return $formatedDate;
};

if (isset($overwrite)) {
	$SaveAsNew = 1;
	$rec_id = $rec_id_overwrite;
};

if (isset($edit_overwrite)) $rec_id = $rec_id_overwrite;

if (isset($rec_by)) { 
	$rec_id = time();
	switch ($rec_by[0]) {
    case 1:
        $rec_type = "rec_day";
        $rec_days = $admin->get_post('rec_day_days');
        break;
    case 2:
        $rec_type = "rec_week";
        $rec_weeks = $admin->get_post('rec_week_weeks').'+';
        $rec_weeks .= implode(";",$admin->get_post('rec_week_weekday'));
        break;
    case 3:
        $rec_type = "rec_month";
        if ($admin->get_post('rec_month_days') != "") {
	        $rec_months = $admin->get_post('rec_month_days').'+';
	        $rec_months .= $admin->get_post('rec_month_month');
	      } else {
	        $rec_months = $admin->get_post('rec_month_option_count').'+';
	        $rec_months .= implode(";",$admin->get_post('rec_month_weekday')).'+';  
	        $rec_months .= $admin->get_post('rec_month_weekday_month');   	
	      }
        break;
    case 4:
        $rec_type = "rec_year";
        if ($admin->get_post('rec_year_days') != "") {
	        $rec_years = $admin->get_post('rec_year_days').'+';
	        $rec_years .= $admin->get_post('rec_year_option_month');
	      } else {
	        $rec_years = $admin->get_post('rec_year_option_count').'+';
	        $rec_years .= implode(";",$admin->get_post('rec_year_weekday')).'+'; 
	        $rec_years .= $admin->get_post('rec_year_option_month_weekday');  	
	      }
        break;
	};
	
	if (count($rec_by) == 2) {
		for ($i=1; $i < 4; $i++)
			$rec_exclude .= jscal_to_date($admin->get_post('date_exclude'.$i), $jscalFormat).';';
	};
	
	$rec_count = $admin->get_post('rec_rep_count');
	$rec_never = $admin->get_post('rec_never');
	if (isset($rec_never))
	  $rec_count = -1;

}


// Added PCWacht
// Didn't think of anything nicer, but this works as well
// First get first letter of date format, since we have only 3 choices, d, m, Y and rebuild date as yyyy-mm-dd
$format = substr($jscal_format,0,1);
if ($format == 'd') {
	$js_end_day    = substr($js_end_time,0,2);
	$js_end_month  = substr($js_end_time,3,2);
	$js_end_year   = substr($js_end_time,6,4);

	$js_start_day    = substr($js_start_time,0,2);
	$js_start_month  = substr($js_start_time,3,2);
	$js_start_year   = substr($js_start_time,6,4);
	
} elseif ($format == 'm') { 
	$js_end_day    = substr($js_end_time,3,2);
	$js_end_month  = substr($js_end_time,0,2);
	$js_end_year   = substr($js_end_time,6,4);

	$js_start_day    = substr($js_start_time,3,2);
	$js_start_month  = substr($js_start_time,0,2);
	$js_start_year   = substr($js_start_time,6,4);
	
} else { 
	$js_end_day    = substr($js_end_time,8,2);
	$js_end_month  = substr($js_end_time,5,2);
	$js_end_year   = substr($js_end_time,0,4);

	$js_start_day    = substr($js_start_time,8,2);
	$js_start_month  = substr($js_start_time,5,2);
	$js_start_year   = substr($js_start_time,0,4);
}	

$date_start  = "$js_start_year-$js_start_month-$js_start_day";
$date_end    = "$js_end_year-$js_end_month-$js_end_day";
$date_start = (jscal_to_date($js_start_time, $jscalFormat));
$date_end = (jscal_to_date($js_end_time, $jscalFormat));

  	
if (isset($deleteaction)) 
{
	//if recurring, delete all overwrites too
	$sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_actions WHERE id='$cal_id'";
	$db = $database->query($sql);
	$rec = $db->fetchRow();
	if ($rec['rec_id'] > 0 && ($rec['rec_day'] != "" || $rec['rec_week'] != "" || $rec['rec_month'] != "" || $rec['rec_year'] != ""))
		$sql = "DELETE FROM ".TABLE_PREFIX."mod_procalendar_actions WHERE rec_id='".$rec['rec_id']."'";
	else
  	$sql = "DELETE FROM ".TABLE_PREFIX."mod_procalendar_actions WHERE id='$cal_id'";
  $database->query($sql);
  $success &= !$database->is_error();
} 
else 
{

  $sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_settings WHERE section_id='$section_id'";
  $db = $database->query($sql);
  if ($db->numRows() > 0) 
  	{
  		$rec = $db->fetchRow();
  		// Added PCWacht
  		// Need to invers the firstday for calendar   
  		$use_time       = $rec['usetime'];
		$onedate        = $rec["onedate"];
		$usecustom1     = $rec["usecustom1"];
		$usecustom2     = $rec["usecustom2"];
		$usecustom3     = $rec["usecustom3"];
		$usecustom4     = $rec["usecustom4"];
		$usecustom5     = $rec["usecustom5"];
		$usecustom6     = $rec["usecustom6"];
		$usecustom7     = $rec["usecustom7"];
		$usecustom8     = $rec["usecustom8"];
		$usecustom9     = $rec["usecustom9"];
		$resize         = $rec["resize"];
	} 
	
  $short = $admin->add_slashes($admin->get_post('short'));
  $long = $admin->add_slashes($admin->get_post('long'));
  
  if (isset($SaveAsNew)) 
   $cal_id=0;
  else
   $cal_id = $admin->get_post('cal_id');
  
  $section_id  = $admin->get_post('section_id');
  $page_id     = $admin->get_post('page_id');
  $name        = $admin->get_post_escaped('name');
  
  // Check if the user uploaded an image 
  function checkimage($checkname, $custom) {
    global $admin, $resize, $MESSAGE;
    if ($custom == '0') $custom='';	  
    if(isset($_FILES[$checkname]['tmp_name']) AND $_FILES[$checkname]['tmp_name'] != '')
    {
  	  // Get real filename and set new filename
	  $filename = $_FILES[$checkname]['name'];
	  $new_filename = WB_PATH.MEDIA_DIRECTORY.'/calendar/'.$filename;
	  $st_filename =  WB_URL.MEDIA_DIRECTORY.'/calendar/'.$filename;
	  // Make kinda sure the image is an image - there should be something better then just to test extention
	  $file4=strtolower(substr($filename, -4, 4));
	  if(($file4 != '.jpg')and($file4 != '.png')and($file4 !='jpeg') )
      {
		$admin->print_error($MESSAGE['GENERIC']['FILE_TYPE'].' JPG (JPEG) or PNG a');
	  }
	  // Make sure the target directory exists
	  make_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar');
	  // Upload image
	  move_uploaded_file($_FILES[$checkname]['tmp_name'], $new_filename);
	  // Check if we need to create a thumb
	  if($resize != 0)
      {
		// Resize the image
		$thumb_location = WB_PATH.MEDIA_DIRECTORY.'/calendar/thumb'.$filename.'.jpg';
		if(make_thumb($new_filename, $thumb_location, $resize))
        {
			// Delete the actual image and replace with the resized version
			unlink($new_filename);
			rename($thumb_location, $new_filename);
		}
	  }
	  return $st_filename;
    }
    return $custom;
  }    
  
  if ($usecustom1 <> 0) $custom1 = $admin->get_post_escaped('custom1'); 
  if ($usecustom1 == 4) $custom1 = checkimage('custom_image1', $custom1 );
  if ($usecustom2 <> 0) $custom2 = $admin->get_post_escaped('custom2'); 
  if ($usecustom2 == 4) $custom2 = checkimage('custom_image2', $custom2);
  if ($usecustom3 <> 0) $custom3 = $admin->get_post_escaped('custom3'); 
  if ($usecustom3 == 4) $custom3 = checkimage('custom_image3', $custom3);
  if ($usecustom4 <> 0) $custom4 = $admin->get_post_escaped('custom4'); 
  if ($usecustom4 == 4) $custom4 = checkimage('custom_image4', $custom4);
  if ($usecustom5 <> 0) $custom5 = $admin->get_post_escaped('custom5'); 
  if ($usecustom5 == 4) $custom5 = checkimage('custom_image5', $custom5);
  if ($usecustom6 <> 0) $custom6 = $admin->get_post_escaped('custom6'); 
  if ($usecustom6 == 4) $custom6 = checkimage('custom_image6', $custom6);
  if ($usecustom7 <> 0) $custom7 = $admin->get_post_escaped('custom7'); 
  if ($usecustom7 == 4) $custom7 = checkimage('custom_image7', $custom7);
  if ($usecustom8 <> 0) $custom8 = $admin->get_post_escaped('custom8'); 
  if ($usecustom8 == 4) $custom8 = checkimage('custom_image8', $custom8);
  if ($usecustom9 <> 0) $custom9 = $admin->get_post_escaped('custom9'); 
  if ($usecustom9 == 4) $custom9 = checkimage('custom_image9', $custom9);

  $acttype     = $admin->get_post('acttype');
  $public_stat = $admin->get_post('public_stat');

  if (strlen($date_start) == 0) 
  {
    $date_start = date("Y-m-d");
  }
  
  if (strlen($time_start) == 0) 
  {
    $time_start = "00:00";
  } 
  

  if((int)$js_end_day == 0 || (int)$js_end_month == 0 || (int)$js_end_year == 0)
  {
    $date_end = $date_start;
  }
  else 
  {
    $date_end = $date_end;
  }
  
  if (strlen($time_end) == 0) 
  {
    $time_end = "00:00";
  } 
  
  if (!$onedate)
    $date_end = $date_start;

  // Check dates, make end equal to start if start > end
  $begin = $date_start.' '.$time_start;
  $end   = $date_end.' '.$time_end;
  if ($begin>$end) {
	  $date_end = $date_start;
	  $time_end = $time_start;
  }	  
     
  $description = $admin->get_post('description');
  $owner       = $admin->get_post('owner');
  
  if(trim($name)!="")
  {
    if ($cal_id==0) 
    {
      $sql = "INSERT INTO ".TABLE_PREFIX."mod_procalendar_actions SET ";
      $sql .= "section_id='$section_id', ";
      $sql .= "page_id='$page_id', ";
      $sql .= "owner='$owner', ";
      $sql .= "name='$name', ";
	  if ($usecustom1 <> 0) $sql .= "custom1='$custom1', ";
	  if ($usecustom2 <> 0) $sql .= "custom2='$custom2', ";
	  if ($usecustom3 <> 0) $sql .= "custom3='$custom3', ";
	  if ($usecustom4 <> 0) $sql .= "custom4='$custom4', ";
	  if ($usecustom5 <> 0) $sql .= "custom5='$custom5', ";
	  if ($usecustom6 <> 0) $sql .= "custom6='$custom6', ";
	  if ($usecustom7 <> 0) $sql .= "custom7='$custom7', ";
	  if ($usecustom8 <> 0) $sql .= "custom8='$custom8', ";
	  if ($usecustom9 <> 0) $sql .= "custom9='$custom9', ";
      $sql .= "acttype='$acttype', ";
      $sql .= "public_stat='$public_stat', ";
      $sql .= "date_start='$date_start', ";
      $sql .= "date_end='$date_end', ";
      $sql .= "time_start='$time_start', ";
      $sql .= "time_end='$time_end', ";
      $sql .= "description='$short', ";
      $sql .= "rec_id='$rec_id', ";
      $sql .= "rec_day='$rec_days', ";
      $sql .= "rec_week='$rec_weeks', ";
      $sql .= "rec_month='$rec_months', ";
      $sql .= "rec_year='$rec_years', ";
      $sql .= "rec_count='$rec_count', ";
      $sql .= "rec_exclude='$rec_exclude'";
    } 
    else 
    {
      $sql = "UPDATE ".TABLE_PREFIX."mod_procalendar_actions SET ";
      $sql .= "name='$name', ";
	  if ($usecustom1 <> 0) $sql .= "custom1='$custom1', ";
	  if ($usecustom2 <> 0) $sql .= "custom2='$custom2', ";
	  if ($usecustom3 <> 0) $sql .= "custom3='$custom3', ";
	  if ($usecustom4 <> 0) $sql .= "custom4='$custom4', ";
	  if ($usecustom5 <> 0) $sql .= "custom5='$custom5', ";
	  if ($usecustom6 <> 0) $sql .= "custom6='$custom6', ";
	  if ($usecustom7 <> 0) $sql .= "custom7='$custom7', ";
	  if ($usecustom8 <> 0) $sql .= "custom8='$custom8', ";
	  if ($usecustom9 <> 0) $sql .= "custom9='$custom9', ";
      $sql .= "acttype='$acttype', ";
      $sql .= "public_stat='$public_stat', ";
      $sql .= "date_start='$date_start', ";
      $sql .= "date_end='$date_end', ";
      $sql .= "time_start='$time_start', ";
      $sql .= "time_end='$time_end', ";
      $sql .= "description='$short', ";
      $sql .= "rec_day='$rec_days', ";
      $sql .= "rec_week='$rec_weeks', ";
      $sql .= "rec_month='$rec_months', ";
      $sql .= "rec_year='$rec_years', ";
      $sql .= "rec_count='$rec_count', ";
      $sql .= "rec_exclude='$rec_exclude' ";
      $sql .= "WHERE id='$cal_id'";
    }

    $database->query($sql);
  }
}
require_once('functions.php');

//$all_actions = fillActionArray ("2011-1-1", "2099-12-31", $section_id);
//$active_actions = array();
//foreach($all_actions AS $action)
	//$active_actions[$action['id']] = $action['id'];
	//print_r($active_actions); die();
	//$database->query("DELETE FROM ".TABLE_PREFIX."mod_procalendar_actions WHERE rec_id='".$rec['rec_id']."'");
//Check if there is a database error, otherwise say successful
if($database->is_error()) {
  $admin->print_error($database->get_error(), $js_back);
  flush();
  sleep(5);
} else {
 
  $admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='."$page_id&month=$js_start_month&year=$js_start_year");
  //flush();
  //sleep(3);
}

// Print admin footer

$admin->print_footer()

?>