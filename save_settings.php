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

if (!defined('SYSTEM_RUN')) {require( (dirname(dirname((__DIR__)))).'/config.php');}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$type         = $admin->get_post('type');
$page_id      = $admin->get_post('page_id');
$section_id   = $admin->get_post('section_id');

switch ($type) {

case "change_eventgroup":
    $group_id    = $admin->get_post('group_id');
    $group_name  = $admin->StripCodeFromText(($admin->get_post('group_name')));
    $delete      = $admin->get_post('delete');
    $format      = $admin->StripCodeFromText($admin->get_post('action_background'));
    $format      = (($format[0] !== "#")?'#'.$format:$format);

    $dayformat   = $admin->StripCodeFromText($admin->get_post('dayformat'));
    if (!isset($dayformat)){ $dayformat = 0;}

    if ($delete)
    {
        $sql = 'DELETE FROM `'.TABLE_PREFIX.'mod_procalendar_eventgroups` WHERE `id`='.$group_id;
        $database->query($sql);
    }
    else
    {
    if($group_name != "")
    {
        $sqlSet = 'section_id='.$section_id.', '
        . '`name`= \''.$database->escapeString($group_name).'\', '
        . '`format`= \''.$database->escapeString($format).'\', '
        . '`format_days`= '.$dayformat.' '
        . ' ';
       if (($group_id == 0))
       {
            $sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_procalendar_eventgroups` SET '.$sqlSet;
       }
       else
       {
        //echo "UPDATE -> group_id: <br>";
                $sql  = 'UPDATE `'.TABLE_PREFIX.'mod_procalendar_eventgroups` SET '.$sqlSet
                      . 'WHERE id='.$group_id;
            }
            if (!$database->query($sql)) {echo $sql.' ';}
        }
    }
#    break;

    case "startd":
        $startday     = (int)$admin->get_post('startday');
        $onedate      = (int)$admin->get_post('onedate');
        $usetime      = (int)$admin->get_post('usetime');
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

        $sql  = 'UPDATE `'.TABLE_PREFIX.'mod_procalendar_settings` SET '
              . '`startday`    = '.$startday.', '
              . '`usetime`     = '.$usetime.', '
              . '`onedate`     = '.$onedate.', '
              . '`useformat`   = \''.$database->escapeString($useformat).'\', '
              . '`useifformat` = \''.$database->escapeString($useifformat).'\' '
              . 'WHERE `section_id`='.$section_id;
        $database->query($sql);
    break;

}

if($database->is_error()) {
  $admin->print_error($database->get_error().'<br />'.$sql, $js_back);
} else {
    if ($type == "change_eventgroup" ) {
      $admin->print_success($TEXT['SUCCESS'], WB_URL."/modules/procalendar/modify_settings.php?page_id=".$page_id."&section_id=".$section_id);
    } else {
      $admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
    }
}

$admin->print_footer();
