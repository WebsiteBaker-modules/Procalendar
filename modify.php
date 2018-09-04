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

if (!defined('WB_PATH')) exit("Cannot access this file directly");


require_once(WB_PATH.'/framework/functions.php');
require_once('functions.php');

$month=date("n");
$year=date("Y");
$day=date("j");
// $day = "";
if ((isset($_GET['day']))and($_GET['day']!="")) {
  $day = $_GET['day'];
}

if (isset($_GET['edit'])) {
  $editMode = $_GET['edit'];
  } else {
  $editMode = "no";
}
if (isset($_GET['dayview'])) {
  $dayview = (int)$_GET['dayview'];
} else {
  $dayview = (int)0;
}
if ((isset($_GET['month']))and($_GET['month']!="")) {
  $month = (int)$_GET['month'];
} 
if ((isset($_GET['year']))and((int)$_GET['year']!="-")) {
  $year = (int)$_GET['year'];
} 
if (isset($_GET['show'])) {
  $show = (int)$_GET['show'];
} else {
  $show = 0;
}
if (isset($_GET['id'])) {
  $edit_id = (int)$_GET['id'];
} else {
  $edit_id = 0;
}


$tm_zacatek = "$year-$month-1";
$tm_konec = "$year-$month-".DaysCount($month,$year);
$actions = fillActionArray($tm_zacatek, $tm_konec, $section_id);
$action_types = fillActionTypes($section_id);

// For some php reason this must be here and not in the functions file where it was.
// If in functions the ckeditor will error with array_key_exists() expects parameter 2 to be array, null given in .../modules/ckeditor/include.php on line 182
// It seems like global doesn't work from a included function.

  if(!isset($wysiwyg_editor_loaded)) {
    $wysiwyg_editor_loaded=true;  
    if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
      function show_wysiwyg_editor($name,$id,$content,$width,$height) {
        echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
      }
    } else {
      $id_list=array("short","long");
      require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
    }
  }
?>

<div class="modify_content">
<?php 
  ShowCalendar($month, $year, $actions,$section_id,true);
  ShowActionListEditor($actions, $day, $page_id, $dayview );
  ShowActionEditor($actions, $day, $show, $dayview, $editMode, $month, $year, $edit_id); 
 ?>
</div>