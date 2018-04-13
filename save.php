<?php /*

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
if (!isset($_POST['cal_id'])) {exit("Cannot access this file directly");}

if (!defined('SYSTEM_RUN')) {require( (dirname(dirname((__DIR__)))).'/config.php');}
    $update_when_modified = true;
    require (WB_PATH.'/modules/admin.php');
    // Include WB functions file
    if (!function_exists('make_dir')){require(WB_PATH.'/framework/functions.php');}
    if (!function_exists('isProcalcFuncLoaded')){require(__DIR__.'/functions.php');}

    //formats jscal date string to standard mysql format
    function jscal_to_date($strDate, $jscalFormat) {
        $formatedDate = "";
        if ($strDate != "") {
            $dateParts = preg_split("#\s|/|\.|-#", $strDate);
            $dateCombined = array_combine($jscalFormat, $dateParts);
            $formatedDate = $dateCombined['yyyy']."-".$dateCombined['mm']."-".$dateCombined['dd'];
        }
        return $formatedDate;
    }

    // Check if the user uploaded an image
    function checkimage($checkname, $custom='')
    {
        global $admin, $resize, $MESSAGE;

        if ($custom == '0') {$custom = '';}
        if (isset($_FILES[$checkname]['tmp_name']) && $_FILES[$checkname]['tmp_name'] != '')
        {
            // Get real filename and set new filename
            $filename = $_FILES[$checkname]['name'];
            $new_filename = WB_PATH . MEDIA_DIRECTORY . '/calendar/'.$filename;
            $st_filename = WB_URL . MEDIA_DIRECTORY . '/calendar/'.$filename;
            // Make kinda sure the image is an image - there should be something better then just to test extention
            $file4 = strtolower(substr($filename, -4, 4));
            if (($file4 != '.jpg') and ($file4 != '.png') and ($file4 != 'jpeg')) {
                $admin->print_error($MESSAGE['GENERIC_FILE_TYPE'].' JPG (JPEG) or PNG a');
            }
            // Make sure the target directory exists
            make_dir(WB_PATH . MEDIA_DIRECTORY . '/calendar');
            // Upload image
            move_uploaded_file($_FILES[$checkname]['tmp_name'], $new_filename);
            // Check if we need to create a thumb
            if ($resize != 0)
            {
                // Resize the image
                $thumb_location = WB_PATH . MEDIA_DIRECTORY . '/calendar/thumb'.$filename . '.jpg';
                if (make_thumb($new_filename, $thumb_location, $resize))
                {
                    // Delete the actual image and replace with the resized version
                    unlink($new_filename);
                    rename($thumb_location, $new_filename);
                }
            }
            $custom = $st_filename;
        } //
        $sContent = ReplaceAbsoluteMediaUrl($custom);
        return $sContent;
    }  //  end  function checkimage

#    $aParm = $oRequest->getParamNames();
#    $action = $oRequest->getParam('action', FILTER_SANITIZE_STRING);

    $success = true;
    $out = "";
    $cal_id = (int)$admin->get_post('cal_id');
    $deleteaction = $admin->get_post('delete');
    $SaveAsNew = $admin->get_post('saveasnew');
    $overwrite = $admin->get_post('overwrite');
    $edit_overwrite = $admin->get_post('edit_overwrite');
    $js_start_time = $admin->get_post('date1');
    $js_end_time = $admin->get_post('date2');
    $time_start = $admin->get_post('time_start');
    $time_end = $admin->get_post('time_end');
    $jscal_format = $admin->get_post('jscal_format');
    $rec_by = $admin->get_post('rec_by');
    $rec_id_overwrite = (int)$admin->get_post('rec_id');
    $rec_id = $rec_count = 0;
    $rec_type = $rec_days = $rec_weeks = $rec_months = $rec_years = $rec_exclude = "";
    $jscalFormat = preg_split("#\s|/|\.|-#", $jscal_format);

    if (isset($overwrite)) {
        $SaveAsNew = 1;
        $rec_id = $rec_id_overwrite;
    }

    if (isset($edit_overwrite)) {$rec_id = $rec_id_overwrite;}
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
                $rec_weeks .= implode(";", $admin->get_post('rec_week_weekday'));
                $rec_weeks = (($rec_weeks == '+') ? '' : $rec_weeks);
                break;
            case 3:
                $rec_type = "rec_month";
                if ($admin->get_post('rec_month_days') != "") {
                    $rec_months = $admin->get_post('rec_month_days').'+';
                    $rec_months .= $admin->get_post('rec_month_month');
                } else {
                    $rec_months = $admin->get_post('rec_month_option_count').'+';
                    $rec_months .= implode(";", $admin->get_post('rec_month_weekday')).'+';
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
                    $rec_years .= implode(";", $admin->get_post('rec_year_weekday')).'+';
                    $rec_years .= $admin->get_post('rec_year_option_month_weekday');
                }
                break;
        }
        if (count($rec_by) == 2) {
            for ($i = 1; $i < 4; $i++) {
                $rec_exclude .= jscal_to_date($admin->get_post('date_exclude'.$i), $jscalFormat).';';
            }
        }

        $rec_count = (int)$admin->get_post('rec_rep_count');
        $rec_never = $admin->get_post('rec_never');
        if (isset($rec_never)) {$rec_count = -1;}

    }
// Added PCWacht
// Didn't think of anything nicer, but this works as well
// First get first letter of date format, since we have only 3 choices, d, m, Y and rebuild date as yyyy-mm-dd
    $format = substr($jscal_format, 0, 1);
    if ($format == 'd') {
        $js_end_day     = substr($js_end_time, 0, 2);
        $js_end_month   = substr($js_end_time, 3, 2);
        $js_end_year    = substr($js_end_time, 6, 4);
        $js_start_day   = substr($js_start_time, 0, 2);
        $js_start_month = substr($js_start_time, 3, 2);
        $js_start_year  = substr($js_start_time, 6, 4);
    } elseif ($format == 'm') {
        $js_end_day     = substr($js_end_time, 3, 2);
        $js_end_month   = substr($js_end_time, 0, 2);
        $js_end_year    = substr($js_end_time, 6, 4);
        $js_start_day   = substr($js_start_time, 3, 2);
        $js_start_month = substr($js_start_time, 0, 2);
        $js_start_year  = substr($js_start_time, 6, 4);
    } else {
        $js_end_day     = substr($js_end_time, 8, 2);
        $js_end_month   = substr($js_end_time, 5, 2);
        $js_end_year    = substr($js_end_time, 0, 4);
        $js_start_day   = substr($js_start_time, 8, 2);
        $js_start_month = substr($js_start_time, 5, 2);
        $js_start_year  = substr($js_start_time, 0, 4);
    }
/*
$date_start = $js_start_year-$js_start_month-$js_start_day;
$date_end   = $js_end_year-$js_end_month-$js_end_day;
*/
    $date_start = (jscal_to_date($js_start_time, $jscalFormat));
    $date_end   = (jscal_to_date($js_end_time, $jscalFormat));
    $js_back = ADMIN_URL . '/pages/modify.php?page_id='.$page_id.'&month='.date('m').'&year='.date('Y') . '';

    if (isset($deleteaction)) {
        //if recurring, delete all overwrites too
        $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_actions` WHERE `id`=".(int)$cal_id;
        $db = $database->query($sql);
        $rec = $db->fetchRow(MYSQLI_ASSOC);
        if ($rec['rec_id'] > 0 && ($rec['rec_day'] != "" || $rec['rec_week'] != "" || $rec['rec_month'] != "" || $rec['rec_year'] != "")){
            $sql = "DELETE FROM `" . TABLE_PREFIX . "mod_procalendar_actions` WHERE `rec_id`='".$rec['rec_id']."'";
        }else{
            $sql = "DELETE FROM `" . TABLE_PREFIX . "mod_procalendar_actions` WHERE `id`=".(int)$cal_id;
        }
        $database->query($sql);
        $success &= !$database->is_error();
        if ($database->is_error()) {
              $admin->print_error($sql.'<br />'.$database->get_error(), $js_back);
        } else {
            $admin->print_success($MESSAGE['PAGES_DELETED'], $js_back);
        }
    } else {
        $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_settings` WHERE `section_id`=".(int)$section_id;
        if ( $db = ($database->query($sql))) {
            $rec = $db->fetchRow(MYSQLI_ASSOC);
            // Added PCWacht
            // Need to invers the firstday for calendar
            $use_time   = $rec['usetime'];
            $onedate    = $rec["onedate"];
            $usecustom1 = $rec["usecustom1"];
            $usecustom2 = $rec["usecustom2"];
            $usecustom3 = $rec["usecustom3"];
            $usecustom4 = $rec["usecustom4"];
            $usecustom5 = $rec["usecustom5"];
            $usecustom6 = $rec["usecustom6"];
            $usecustom7 = $rec["usecustom7"];
            $usecustom8 = $rec["usecustom8"];
            $usecustom9 = $rec["usecustom9"];
            $resize = $rec["resize"];
        }

        $short = $admin->StripCodeFromText($admin->get_post('short'),24);
        $short = ReplaceAbsoluteMediaUrl($short);
        if (isset($SaveAsNew)) {
            $cal_id = 0;
        } else {
            $cal_id = (int)$admin->get_post('cal_id');
        }

        $section_id = (int)$admin->get_post('section_id');
        $page_id    = (int)$admin->get_post('page_id');
        $name       = $admin->StripCodeFromText($admin->get_post('name'));

        if ($usecustom1 <> 0) {$custom1 = $admin->StripCodeFromText($admin->get_post('custom1'));}
        if ($usecustom1 == 4) {$custom1 = checkimage('custom_image1', $custom1);}

        if ($usecustom2 <> 0) {$custom2 = $admin->StripCodeFromText($admin->get_post('custom2'));}
        if ($usecustom2 == 4) {$custom2 = checkimage('custom_image2', $custom2);}
        if ($usecustom3 <> 0) {$custom3 = $admin->StripCodeFromText($admin->get_post('custom3'));}
        if ($usecustom3 == 4) {$custom3 = checkimage('custom_image3', $custom3);}
        if ($usecustom4 <> 0) {$custom4 = $admin->StripCodeFromText($admin->get_post('custom4'));}
        if ($usecustom4 == 4) {$custom4 = checkimage('custom_image4', $custom4);}
        if ($usecustom5 <> 0) {$custom5 = $admin->StripCodeFromText($admin->get_post('custom5'));}
        if ($usecustom5 == 4) {$custom5 = checkimage('custom_image5', $custom5);}
        if ($usecustom6 <> 0) {$custom6 = $admin->StripCodeFromText($admin->get_post('custom6'));}
        if ($usecustom6 == 4) {$custom6 = checkimage('custom_image6', $custom6);}
        if ($usecustom7 <> 0) {$custom7 = $admin->StripCodeFromText($admin->get_post('custom7'));}
        if ($usecustom7 == 4) {$custom7 = checkimage('custom_image7', $custom7);}
        if ($usecustom8 <> 0) {$custom8 = $admin->StripCodeFromText($admin->get_post('custom8'));}
        if ($usecustom8 == 4) {$custom8 = checkimage('custom_image8', $custom8);}
        if ($usecustom9 <> 0) {$custom9 = $admin->StripCodeFromText($admin->get_post('custom9'));}
        if ($usecustom9 == 4) {$custom9 = checkimage('custom_image9', $custom9);}

        $acttype = (int)$admin->get_post('acttype');
        $public_stat = $admin->StripCodeFromText($admin->get_post('public_stat'));

        if (strlen($date_start) == 0) {$date_start = date("Y-m-d");}
        if (strlen($time_start) == 0) {$time_start = "00:00";}

        if ((int)$js_end_day == 0 || (int)$js_end_month == 0 || (int)$js_end_year == 0) {
            $date_end = $date_start;
        } else {
            $date_end = $date_end;
        }

        if (strlen($time_end) == 0) {$time_end = "00:00";}
        if ($onedate) {$date_end = $date_start;}
        // Check dates, make end equal to start if start > end
        $begin = $date_start.' '.$time_start;
        $end   = $date_end.' '.$time_end;
        if ($begin > $end) {
            $date_end = $date_start;
            $time_end = $time_start;
        }

#        $description = $admin->StripCodeFromText($admin->get_post('description'),24);

        $owner = (int)$admin->get_post('owner');
        $sql = '`';

        if (trim($name) != "") {
            if ($cal_id == 0) {
                $sqlWhere = '';
                $sql  = 'INSERT INTO `'.TABLE_PREFIX.'mod_procalendar_actions` SET ';
                $sql .= '`section_id`='.$section_id.', ';
                $sql .= '`page_id`='.$page_id.', ';
                $sql .= '`owner`='.$owner.', ';
            } else {
                $sqlWhere = 'WHERE `id`='.$cal_id.'';
                $sql = 'UPDATE `'.TABLE_PREFIX.'mod_procalendar_actions` SET ';
            }

            $sql .= '`name`        =\''.$database->escapeString($name) . '\', '
                  . '`custom1`     =\''.$database->escapeString($usecustom1 <> 0 ? $custom1 : ''). '\', '
                  . '`custom2`     =\''.$database->escapeString($usecustom2 <> 0 ? $custom2 : ''). '\', '
                  . '`custom3`     =\''.$database->escapeString($usecustom3 <> 0 ? $custom3 : ''). '\', '
                  . '`custom4`     =\''.$database->escapeString($usecustom4 <> 0 ? $custom4 : ''). '\', '
                  . '`custom5`     =\''.$database->escapeString($usecustom5 <> 0 ? $custom5 : ''). '\', '
                  . '`custom6`     =\''.$database->escapeString($usecustom6 <> 0 ? $custom6 : ''). '\', '
                  . '`custom7`     =\''.$database->escapeString($usecustom7 <> 0 ? $custom7 : ''). '\', '
                  . '`custom8`     =\''.$database->escapeString($usecustom8 <> 0 ? $custom8 : ''). '\', '
                  . '`custom9`     =\''.$database->escapeString($usecustom9 <> 0 ? $custom9 : ''). '\', '
                  . '`acttype`     ='.(int)$acttype . ', '
                  . '`public_stat` =\''.$database->escapeString($public_stat).'\', '
                  . '`date_start`  =\''.$date_start . '\', '
                  . '`date_end`    =\''.$date_end . '\', '
                  . '`time_start`  =\''.$time_start . '\', '
                  . '`time_end`    =\''.$time_end . '\', '
                  . '`description` =\''.$database->escapeString($short).'\', '
                  . '`rec_id`      ='.(int)$rec_id . ', '
                  . '`rec_day`     =\''.$rec_days . '\', '
                  . '`rec_week`    =\''.$rec_weeks . '\', '
                  . '`rec_month`   =\''.$rec_months . '\', '
                  . '`rec_year`    =\''.$rec_years . '\', '
                  . '`rec_count`   ='.(int)$rec_count . ', '
                  . '`rec_exclude` =\''.$database->escapeString($rec_exclude). '\' '
                  . $sqlWhere;

            if (!$database->query($sql)) {
                if ($database->is_error()) {
                      $admin->print_error($sql.'<br />'.$database->get_error(), $js_back);
                } else {
                    $admin->print_error($MESSAGE['PAGES_NOT_SAVED'], $js_back);
                }
            } else {
                $admin->print_success($MESSAGE['PAGES_SAVED'], $js_back);
            }
        } else {
                    $admin->print_error($MESSAGE['PAGES_NOT_SAVED'], $js_back);
        }
    }

$admin->print_footer();
