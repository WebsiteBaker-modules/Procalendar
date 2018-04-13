<?php
/**
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Description of Translate
 *
 * @category     modules
 * @package      page
 * @subpackage   procalendar
 * @copyright    Manuela v.d.Decken <manuela@isteam.de>
 * @author       Manuela v.d.Decken <manuela@isteam.de>
 * @author       Dietmar Wöllbrink <dietmar.woellbrink@websitebaker@org>
 * @license      GNU General Public License 3.0
 * @version      0.0.1
 * @revision     $Revision: $
 * @lastmodified $Date: $
 * @since        File available since 26.04.2017
 * @description  xxx
 */

if (!defined('SYSTEM_RUN')) {header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); echo '404 File not found'; flush(); exit;}

global $wb, $day, $month, $year, $action_types, $monthnames, $weekdays, $CALTEXT;
if (!function_exists('isProcalcFuncLoaded')){require(__DIR__.'/functions.php');}

$day     = isset($_GET['day']) ? (int)$_GET['day'] : 0;
$dayview = isset($_GET['dayview']) ? (int)$_GET['dayview'] : 0;
$month   = isset($_GET['month']) ? (int)$_GET['month'] : date("n");
$year    = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");
$show    = isset($_GET['show']) ? (int)$_GET['show'] : 0;
$detail  = isset($_GET['detail']) ? (int)$_GET['detail'] : 0;
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$daysInMonth = DaysCount($month,$year);
// range for a month

$day = ((($day == 0)&&($month == date("n"))&&($year == date("Y"))) ? date("j") : $day);

$date_start = "$year-$month-1";
$date_end   = "$year-$month-".$daysInMonth;
/*
$dateStart = strtotime("$year-$month-1");
$dateEnd   = strtotime("$year-$month-".$daysInMonth);
$date_start = date('d.m.y',$dateStart);
$date_end   = date('d.m.y',$dateEnd);
*/

($month   >  1) ? ($prevmonth = $month - 1) : ($prevmonth = 12);
($month   < 12) ? ($nextmonth = $month + 1) : ($nextmonth =  1);
($month  ==  1) ? ($prevyear  = $year  - 1) : ($prevyear  = $year);
($month  == 12) ? ($nextyear  = $year  + 1) : ($nextyear  = $year);

$IsMonthOverview = $dayview;

$actions      = fillActionArray($date_start, $date_end, $section_id);
$action_types = fillActionTypes($section_id);

//$aInputRequest = compact('section_id','actions','action_types','day','dayview','month','year','show','detail','id','date_start','date_end');

$IsBackend = (preg_match('/view\.php$/i', basename(__FILE__)) ? false : true);


$localVariables = compact(array_keys(get_defined_vars()));

if ($detail == 1) {
    if ($id == 0) {
        ShowActionDetails($localVariables);
//        ShowActionDetails($actions, $section_id, $day, $month, $year, $show, $dayview);
    } else {
        ShowActionDetailsFromId($localVariables);
//        ShowActionDetailsFromId($actions, $id, $section_id, $day);
    }
} else {
//    ShowCalendar($month,$year,$actions,$section_id,false);
//    ShowActionList($day,$month,$year,$actions,$section_id);
    ShowCalendar($localVariables);
    ShowActionList($localVariables);
}