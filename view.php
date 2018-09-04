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

global $wb, $day, $month, $year, $action_types, $monthnames, $weekdays, $CALTEXT;

require_once('functions.php');

$day = isset($_GET['day']) ? (int)$_GET['day'] : "";
$dayview = isset($_GET['dayview']) ? (int)$_GET['dayview'] : 0;
$month = isset($_GET['month']) ? (int)$_GET['month'] : date("n");
$year = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");
$show = isset($_GET['show']) ? (int)$_GET['show'] : 0;
$detail = isset($_GET['detail']) ? (int)$_GET['detail'] : 0;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// range for a month
$date_start = "$year-$month-1";
$date_end = "$year-$month-".DaysCount($month,$year);

($month > 1) ? ($prevmonth = $month - 1) :  ($prevmonth = 12);
($month < 12) ? ($nextmonth = $month + 1) :  ($nextmonth = 1);
($month == 1) ? ($prevyear = $year - 1) : ($prevyear = $year);
($month == 12) ? ($nextyear = $year + 1) : ($nextyear = $year);

$actions  = fillActionArray($date_start, $date_end, $section_id);
$action_types = fillActionTypes($section_id);

if($detail == 1) {
  if($id == 0) {
    ShowActionDetails($actions, $section_id, $day, $month, $year, $show, $dayview);
  } else {
    ShowActionDetailsFromId($actions, $id, $section_id, $day);
  }
} else {
  ShowCalendar($month,$year,$actions,$section_id,false);
  ShowActionList($day,$month,$year,$actions,$section_id);
}