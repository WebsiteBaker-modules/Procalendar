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

if (LANGUAGE_LOADED) {
    if (file_exists(WB_PATH . "/modules/" . basename(__dir__) . "/languages/" . LANGUAGE . ".php")) {
        require_once (WB_PATH . "/modules/" . basename(__dir__) . "/languages/" . LANGUAGE . ".php");
    } else {
        require_once (WB_PATH . "/modules/" . basename(__dir__) . "/languages/EN.php");
    }
}
 */
/*
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

*/
if (!defined('SYSTEM_RUN')) {header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); echo '404 File not found'; flush(); exit;}

if (is_readable(__DIR__.'/languages/EN.php')) {require(__DIR__.'/languages/EN.php');}
if (is_readable(__DIR__.'/languages/'.DEFAULT_LANGUAGE.'.php')) {require(__DIR__.'/languages/'.DEFAULT_LANGUAGE.'.php');}
if (is_readable(__DIR__.'/languages/'.LANGUAGE.'.php')) {require(__DIR__.'/languages/'.LANGUAGE.'.php');}

//global $action_types, $public_stat, $weekdays, $monthnames,$year, $month, $day;

    function isProcalcFuncLoaded(){;}
/* returns count of days in given month */
    function DaysCount($month, $year) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    function ReplaceAbsoluteMediaUrl($sContent)
    {
      if (ini_get( 'magic_quotes_gpc') == true) {
        $sContent = $this->strip_slashes( $sContent);
      }
      if (is_string( $sContent)) {
        $sRelUrl = preg_replace('/^https?:\/\/[^\/]+(.*)/is', '\1', WB_URL);
        $sDocumentRootUrl = str_replace($sRelUrl, '', WB_URL);
        $sMediaUrl = WB_URL.MEDIA_DIRECTORY.'/';
        $aSearchfor = [
            '@(<[^>]*=\s*")('.preg_quote($sMediaUrl).
            ')([^">]*".*>)@siU', '@(<[^>]*=\s*")('.preg_quote( WB_URL.'/').')([^">]*".*>)@siU',
            '/(<[^>]*?=\s*\")(\/+)([^\"]*?\"[^>]*?)/is',
            '/(<[^>]*=\s*")('.preg_quote($sMediaUrl, '/').')([^">]*".*>)/siU',
#            '/^https?:\/\/('.preg_quote($sMediaUrl, '/').')(.*)/siU'
            ];
        $aReplacements = [ '$1{SYSVAR:AppUrl.MediaDir}$3', '$1{SYSVAR:AppUrl}$3','\1'.$sDocumentRootUrl.'/\3','$1{SYSVAR:MEDIA_REL}$3'];
        $sContent = preg_replace( $aSearchfor, $aReplacements, $sContent);
      }
      return $sContent;
    }

/* returns number (in week) of first day in month,  this was made for countries, where week starts on Monday  */
    function FirstDay($month, $year) {
        $english_order = date("w", mktime(0, 0, 0, $month, 1, $year));
        //echo("FirstDay: " . $english_order);
        return ($english_order == 0) ? 7 : $english_order;
    }

/* returns number of columns for calendar table */

    function ColsCount($month, $year) {
        return date("W", mktime(0, 0, 0, $month, DaysCount($month, $year) - 7, $year)) - date("W", mktime(0, 0, 0, $month, 1 + 7, $year)) + 4;
    }

/* This function returns value of table-cell identified by row and column number.  */

function Cell($row, $column, $firstday, $dayscount, $SecId) {
    global $weekdays;
    $IsStartMon = IsStartDayMonday($SecId);
    if ($row == 1) {
        if ($IsStartMon == false) {
            if ($column - 1 <= 0)
                $column = 7;
            else
                $column = $column - 1;
        }
        return $weekdays[$column];
    }
    if ($IsStartMon == false) {
        $retval = ($row - 2) * 7 + $column;
        if ($firstday < 7)
            $retval -= $firstday;
    } else {
        $retval = ($row - 2) * 7 + $column - $firstday + 1;
    }
    if ($retval < 1 || $retval > $dayscount) {
        return "&nbsp;";
    }
    return $retval;
}

//#######################################################################
function GetCalRowCount($dayscount, // how many days have this month
        $firstday, // 1=Monday 7=Sunday
        $section_id) { //#######################################################################
    $IsMondayFirstDay = IsStartDayMonday($section_id);
    $Extra = $IsMondayFirstDay ? 1 : 0;
    // calc how many rows are needed
    $rowcount = ceil($dayscount / 7);
    // calc if all days fit to table..
    if ($rowcount * 7 - $firstday + $Extra < $dayscount) {
        //..no, add row to show left days
        $rowcount = $rowcount + 1;
    }
    // special case to avoid empty row
    if (!$IsMondayFirstDay && $firstday == 7)
        $rowcount -= 1;
    // extra row for displaying weekdays
    $rowcount += 1;
    // return the right value
    return $rowcount;
}

//#######################################################################
function ShowMiniCalendar($LinkName, $PageIdCal, $SectionIdCal) {
    global $page_id, $monthnames, $weekdays, $section_id,$day;
    $timestamp = time();
    $datum = date("m.Y", $timestamp);
    $month = substr($datum, 0, 2);
    $year = substr($datum, 3, 4);

    $date_start = "$year-$month-1"; // range for all month
    $date_end = "$year-$month-" . DaysCount($month, $year);

    $actions = fillActionArray($date_start, $date_end, $section_id);
    ($month > 1) ? ($prevmonth = $month - 1) : ($prevmonth = 12);
    ($month < 12) ? ($nextmonth = $month + 1) : ($nextmonth = 1);
    ($month == 1) ? ($prevyear = $year - 1) : ($prevyear = $year);
    ($month == 12) ? ($nextyear = $year + 1) : ($nextyear = $year);
    $colcount = ColsCount($month, $year);
    $dayscount = DaysCount($month, $year);
    $firstday = FirstDay($month, $year);
    ?>
    <table border="1" class="calendarmod_mini">
        <tr>
            <td colspan="7" style="width:<?php echo ($colcount - 2) * 30;?>px;" class="calendarmod_header_mini">
            <a href="<?php echo $LinkName?>?page_id=<?php echo $PageIdCal;?>&amp;month=<?php echo $month;?>&amp;year=<?php echo $year;?>"><?php echo $monthnames[intval($month)]."&nbsp;".$year;?></a>
        </td>
        </tr>
        <?php
        $rowcount = GetCalRowCount($dayscount, $firstday, $section_id);
        for ($row = 1; $row <= $colcount; $row++) {
            echo "<tr>";
            // Spalte
            for ($col = 1; $col <= 7; $col++) {
                //echo "<td style='width: 30px;' style='text-align: center;'>";
                $day = Cell($row, $col, $firstday, $dayscount, $SectionIdCal);
                if (is_numeric($day)) {
                    $FlagDayWr = 1;
                    // alle Termine durchsuchen
                    for ($i = 0; $i < sizeof($actions); $i++) {
                        $tmp = $actions[$i];
                        $dayend = substr($tmp['date_end'], -2);
                        $monthend = substr($tmp['date_end'], 5, 2);
                        $daystart = substr($tmp['date_start'], 8, 2);
                        $monthstart = substr($tmp['date_start'], 5, 2);
                        //echo "day: ".$day." daystart:".$daystart." dayend:".$dayend." monthstart:".$monthstart." monthend:".$monthend."<br>";
                        if (MarkDayOk($day, $month, $year, $actions, $i)) {
                            $FlagDayWr = 0;
                            echo "<td class='calendar_markday_mini'>";
                            echo "<a href='$LinkName?page_id=$PageIdCal&amp;day=$day&amp;month=$month&amp;year=$year&amp;dayview=1'>$day</a>";
                            break;
                        }
                    }
                    // Was Day already written?
                    if ($FlagDayWr == 1) {
                        echo "<td style='width: '30px; tex-align: center;'>";
                        echo $day;
                    }
                } else {
                    if ($day != "&nbsp;")
                        echo "<td class='calendar_weekday_mini'>";
                    else
                        echo "<td class='calendar_noday_mini'>";
                    // write Mo-Su
                    echo "<b>$day</b>";
                }
                // end of column
                echo "</td>";
            }
            // end of row
            echo "</tr>\n";
        }
        ?>
    </table>
<?php
}

//#######################################################################
//function ShowCalendar($month, $year, $actions, $section_id, $IsBackend) {
function ShowCalendar(array $localVariables) {
//    global $page_id, $monthnames, $weekdays,$database, $admin, $wb;

if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}

    $prevmonth  = (($month >   1) ? ($month - 1) : 12);
    $nextmonth  = (($month <  12) ? ($month + 1) : 1);
    $prevyear   = (($month ==  1) ? ($year - 1) : $year);
    $nextyear   = (($month == 12) ? ($year + 1) : $year);

    $dayscount  = DaysCount($month, $year);
    $firstday   = FirstDay($month, $year);
    $addBracket = function ()
    {
        $aList = func_get_args();
    //    return preg_replace('/^(.*)$/', '/\[$1\]/s', $aList);
        return preg_replace('/^(.*)$/', '[$1]', $aList);
    };
    /*
      //$previmg   = WB_URL."/modules/".basename(__DIR__)."/prev.png";
      //$nextimg   = WB_URL."/modules/".basename(__DIR__)."/next.png";
     */
    $sAddonPath = str_replace(DIRECTORY_SEPARATOR,'/', __DIR__);

    $PagesModifyUrl = (@$IsBackend ? ADMIN_URL.'/pages/modify.php' : $wb->link);
    $output = '';
    $output .= '<div class="w3-row">'.PHP_EOL;
    if (isset($IsBackend)&&$IsBackend){
        if (is_readable($sAddonPath.'/info.php')){require $sAddonPath.'/info.php';}
        $output .= '<div class="w3-threequarter" style="margin-bottom: 0.925em;text-align: left;">'.PHP_EOL;
        $output .= '<h2 style="margin-left:0.825em;">'.$module_name.'</h2>'.PHP_EOL;
        if (is_readable($sAddonPath.'/languages/support-'.LANGUAGE.'.php')){
            $output  .= '<div class="w3-container" style="overflow:auto;height: 11.725em;">'.PHP_EOL;
            $output  .= '<h2>'.$CALTEXT['SUPPORT_INFO'].'</h2>'.PHP_EOL;
            $sContent = file_get_contents($sAddonPath.'/languages/support-'.LANGUAGE.'.php');
            $aSearches[] = '{SYSVAR:AddonUrl}';
            $aReplacements[] = WB_URL.'/modules/'.basename(__DIR__);
            $output  .= str_replace($aSearches, $aReplacements, $sContent);
            $output  .= '</div>'.PHP_EOL;
        }
        $output .= '</div>'.PHP_EOL;
    }
    // change Luisehahne WB_URL.'/modules/'.basename(__DIR__).'/view.php'
    $output .= '<div class="show_calendar">'.PHP_EOL;
    $output .= '<table class="calendarmod" >'.PHP_EOL;
    $output .= '  <tr class="calendarmod-header">'.PHP_EOL;
    $output .= '    <td>'.PHP_EOL.'<span class="arrows">'.PHP_EOL.'<a href="' . $PagesModifyUrl . '?page_id=' . $page_id . '&amp;month=' . $month .'&amp;year=' . ($year - 1) . '" title="' . ($year - 1) . '">&laquo;</a>'.PHP_EOL.'</span>';
    $output .= '    <span><a href="' . $PagesModifyUrl . '?page_id=' . $page_id . '&amp;month=' . $prevmonth . '&amp;year=' .$prevyear . '" title="' . $monthnames[$prevmonth] . '">&lsaquo;</a></span></td>';
    $output .= '    <td colspan="5" style="width:150px;">' . $monthnames[$month] . '&nbsp;' . $year . '</td>';
    $output .= '    <td>'.PHP_EOL.'<span class="arrows"><a href="' . $PagesModifyUrl . '?page_id=' . $page_id . '&amp;month=' . $nextmonth .'&amp;year=' . $nextyear . '" title="' . $monthnames[$nextmonth] . '">&rsaquo;</a></span>';
    $output .= '    <span>'.PHP_EOL.'<a href="' . $PagesModifyUrl . '?page_id=' . $page_id . '&amp;month=' . $month . '&amp;year=' . ($year+1) . '" title="' . ($year + 1) . '">&raquo;</a></span></td>';
    $output .= ' </tr>'.PHP_EOL.'';
    // ShowTermineDebug($month, $year, $actions);
    if (glob(WB_PATH . "/modules/" . basename(__dir__) . "/images/*.png") !== false)
        foreach (glob(WB_PATH . "/modules/" . basename(__dir__) . "/images/*.png") as $filename) {
            unlink($filename);
        }
    $this_day = (intval($month) == date('n') && intval($year) == date('Y')) ? date('j') : 0;
    $rowcount = GetCalRowCount($dayscount, $firstday, $section_id);
    for ($row = 1; $row <= $rowcount; $row++) {
        $output .= '<tr>'.PHP_EOL;
        for ($col = 1; $col <= 7; $col++) {
            $day = Cell($row, $col, $firstday, $dayscount, $section_id);
            $procal_today = (is_numeric($day) && $day == $this_day) ? " procal_today" : "";
            if (is_numeric($day)) {
                $colors = [];
                $FlagDayWr = 1;
                for ($i = 0; $i < sizeof($actions); $i++) {

                    $tmp = $actions[$i];
                    $dayend = substr($tmp['date_end'], -2);
                    $monthend = substr($tmp['date_end'], 5, 2);
                    $daystart = substr($tmp['date_start'], 8, 2);
                    $monthstart = substr($tmp['date_start'], 5, 2);
                    $dayformat = $tmp['act_dayformat'];
                    $bgName = $day . $month . $year;

                    if (MarkDayOk($day, $month, $year, $actions, $i)) {
                        if ($actions[$i]['act_format'] != "" & $dayformat)
                            $colors[] = $actions[$i]['act_format'];
                        $FlagDayWr = 0;
/*
                        $yearstart  = substr($tmp['date_start'],0,4);
                          $link_pre = "".($tmp['name']);
                          if(IstStartTerminVergangeheit("$year-$month-$day","$yearstart-$monthstart-$daystart") == 1 ) {
                          $link = "?$link_pre&amp;month=$monthstart&amp;year=$yearstart&amp;day=$daystart&amp;show=-1";
                          } else {
                          $link = "?$link_pre&amp;month=$month&amp;year=$year&amp;day=$day&amp;show=$i";
                          }
                          if (isset($pageid)) {
                          $link .= "&amp;page_id=$pageid";
                          }
                          $link .= "&amp;id=".$tmp['id']."&amp;section_id=$section_id&amp;detail=1";
                          $link = str_replace("\"","'",$link);
*/
                    }
                }
                // Was Day already written?
                if ($FlagDayWr) {
                    // change Luisehahne WB_URL.'/modules/'.basename(__DIR__).'/view.php'
                    $PagesModifyUrl = (@$IsBackend ? ADMIN_URL.'/pages/modify.php' : $wb->link);
                    $output .= "<td style='width: 30px;'  class='calendar_emptyday" . $procal_today . "'>".PHP_EOL;
                    if ($IsBackend == false){
                        $output .= $day;
                    }else{
                        $output .= '<a href="' . $PagesModifyUrl.'?page_id=' . $page_id . '&amp;day=' . $day . '&amp;month=' . $month . '&amp;year=' . $year . '&amp;edit=new">' . $day . '</a>';
                    }
                } else { //day must be marked
                    $style = "";
                    if (count($colors)) {
                        createBackground($colors, $bgName);
                        $style = 'style="background-image: url(' . WB_URL . '/modules/' . basename(__DIR__) . '/images/' . $bgName . '.png); background-position: bottom;background-repeat:repeat-x"';
                    }
                    $output .= "<td class='calendar_markday" . $procal_today . "' id='acttype" . $tmp["acttype"].'_'.$col . "' " . $style . ">".PHP_EOL;
                    $output .= '<a href="' . $PagesModifyUrl . '?page_id=' . $page_id . '&amp;day=' . $day . '&amp;month=' . $month . '&amp;year=' . $year . '&amp;dayview=1">' . $day . '</a>'.PHP_EOL;
                    //$output .="<a href='".$link."'>$day</a>";
                }
            } else {
                if ($day != "&nbsp;")
                    $output .= "<td class='calendar_weekday" . $procal_today . "'>";
                else
                    $output .= "<td class='calendar_noday" . $procal_today . "'>";
                // write Mo-Su
                $output .= "<b>$day</b>";
            }
            // end of column
            $output .= "</td>".PHP_EOL;
        }
        // end of row
        $output .= "</tr>\n";
    }
    $output .= '</table>'.PHP_EOL.'</div>'.PHP_EOL;
    $output .= '</div>'.PHP_EOL;

    if (!$IsBackend) {
        // Fetch needed settings from db
        $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_settings` WHERE `section_id`=$section_id ";
        $oRes = $database->query($sql);
        if ($oRes->numRows() > 0) {
#            while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {}
                $rec = $oRes->fetchRow(MYSQLI_ASSOC);
                $header = $rec["header"];

        }
        $aPlaceHolders = $addBracket(
            'NEW_ENTRY',
            'CALENDAR'
        );
        $display_new_entry = '<br />';
        if ($admin->is_authenticated())
        {
        $display_new_entry = '<a target="_blank" rel="noopener nofollow" href="'.ADMIN_URL.'/pages/modify.php/pages/modify.php?page_id='.$page_id.'&edit=new">neuer Eintrag</a>';
        }
        $aReplacements = array(
            $display_new_entry,
            $output
        );
        $output2 = str_replace($aPlaceHolders, $aReplacements, $header);
        print $output2;
    } else {
        echo $output;
    }
}

//########################################################################
function ShowActionList(array $localVariables) {
/*
function ShowActionList($day, $month, $year, $actions, $section_id) {
    global $page_id, $monthnames, $action_types, $IsBackend;
    global $CALTEXT;
    global $database, $admin, $wb;
    global $aInputRequest;

print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $monthnames ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();

    $localVariables = compact(array_keys(get_defined_vars()));
*/
    if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}

    $aErrorMsg = [];
    $prevmonth = (($month > 1) ? ($month - 1) : 12);
    $nextmonth = (($month < 12) ? ($month + 1) : 1);
    $prevyear  = (($month == 1) ? ($year - 1) : $year);
    $nextyear  = (($month == 12) ? ($year + 1) : $year);
    $colcount  = ColsCount($month, $year);
    $dayscount = DaysCount($month, $year);
    $firstday  = FirstDay($month, $year);
    //$previmg   = WB_URL."/modules/".basename(__DIR__)."/prev.gif";
    //$nextimg   = WB_URL."/modules/".basename(__DIR__)."/next.gif";
    $today = date("Y-m-d H:m:s");
    // change Luisehahne WB_URL.'/modules/'.basename(__DIR__).'/view.php'
    $PagesModifyUrl = (@$IsBackend ? ADMIN_URL . '/pages/modify.php' : $wb->link);
    $BackToMonthLink = '';
    $BackToMonthLink = '<a class="go_back" href="'.$PagesModifyUrl.'?page_id='.$page_id.'&amp;month='.$month.'&amp;year=' . $year . '">' . $CALTEXT['OF_MONATS'].'-'.$CALTEXT['DATES'].'</a>';

    $IsMonthOverview = ($month != date("n"));
    $IsMonthOverview =  ($dayview && ($day != date("d")) ? $IsMonthOverview : !$dayview);

    $complementary = (function ($color){
        $leadingHash = false;
        //clear whitespaces just to be shure
        $color = trim($color);
        //cut leading #
        if (strpos($color, "#") !== false) {
            $color = substr($color, 1);
            $leadingHash = true;
        }
        //check if valid color string
        if  (preg_match('/^[A-Fa-f0-9]+$/', $color)== 'false') {
            return $leadingHash ? '#' . $color : $color;
        }
        $r1 = dechex((15 - (hexdec($color[0]))));
        $r2 = dechex((15 - (hexdec($color[1]))));
        $g1 = dechex((15 - (hexdec($color[2]))));
        $g2 = dechex((15 - (hexdec($color[3]))));
        $b1 = dechex((15 - (hexdec($color[4]))));
        $b2 = dechex((15 - (hexdec($color[5]))));
        $complementary = $r1 . $r2 . $g1 . $g2 . $b1 . $b2;
        return $leadingHash ? '#' . $complementary : $complementary;
    });

    // no backlink in actuell month, because only events today will be shown
#    $BackToMonthLink = ((($IsMonthOverview != 1) && ($month != date('n'))) ? $BackToMonthLink : '');
    $HeaderText = '<td class="arrow_left">'.PHP_EOL.'<a href="' . $PagesModifyUrl . '?page_id=' . $page_id .
            '&amp;month=' . $prevmonth . '&amp;year=' . $prevyear . '" title="' . $monthnames[$prevmonth] . '">&laquo;&nbsp;' . $monthnames[$prevmonth] .
            '</a>'.PHP_EOL.'</td>';
    $HeaderText .= '<td style="width:100%;">'.PHP_EOL.'<h2>'.$monthnames[$month].'&nbsp;'.$year.
            '</h2>'.PHP_EOL.'</td>';
    $HeaderText .= '<td class="arrow_right">'.PHP_EOL.'<a href="' . $PagesModifyUrl . '?page_id=' . $page_id .
            '&amp;month=' . $nextmonth . '&amp;year=' . $nextyear . '" title="' . $monthnames[$nextmonth] . '">' . $monthnames[$nextmonth] .
            '&nbsp;&raquo;</a>'.PHP_EOL.'</td>';

    if (isset($IsBackend) && !$IsBackend) {
        // Fetch header settings from db
        $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_settings` WHERE `section_id`=$section_id ";
        if ($oRes = $database->query($sql)){
            if ($oRes->numRows() > 0) {
#                while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {}
                $rec = $oRes->fetchRow(MYSQLI_ASSOC);
                    $header  = $rec["header"];
                    $usetime = $rec["usetime"];

                if (is_int(strpos($header, '[CALENDAR]'))){
                    $HeaderText = '';
                }
            }
        } else {
          $aErrorMsg[] = sprintf('%s',$database->get_error());
        }
    }
/*
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.$IsBackend.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( htmlspecialchars($header) ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
//    $jscal_use_time = $usetime; // whether to use a clock, too
//    require_once(WB_PATH."/include/jscalendar/wb-setup.php");
*/
    if ($HeaderText <> '') {
?>
        <div class="actionlist_headernav">
            <table class="action_table w3-table">
                <tr>
        <?php echo $HeaderText;?>
                </tr>
            </table>
        </div>
    <?php }
      $display = (sizeof($actions) ? 'block' : 'none');
    ?>

    <div class="actionlist" style="display:<?php echo $display;?>;">
        <table class="w3-table scrollable actionlist_table">
          <thead>
            <tr class="actionlist_header">
                <th style="vertical-align: top;" class="actionlist_date"><?php echo $CALTEXT['DATE'];?></th>
<?php
                if ($usetime) {
                    echo '<th class="actionlist_time">' . $CALTEXT['FROM'] . '</th>';
                    echo '<th class="actionlist_time">' . $CALTEXT['DEADLINE'] . '</th>';
                }
 ?>
                <th class="actionlist_name"><?php echo $CALTEXT['NAME'];
 ?></th>
                <th class="actionlist_actiontype"><?php echo $CALTEXT['CATEGORY'];
 ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $firstday = 1;
            $lastday = DaysCount($month, $year);
            $FlagEntryWritten = 0;
            if (!isset($day)) {
                $ReplaceDay = 1;
            } else{
                $ReplaceDay = 0;
            }

            if (sizeof($actions)) {; }
                foreach ($actions as $i => $aValue){
                    $sStartTime = $aValue['date_start'].' '.$aValue['time_start'];
                    $sEndTime   = $aValue['date_end'].' '.$aValue['time_end'];
/*
                if (!$listIt){continue;}
                    $listIt = (strtotime($aValue['date_start']) >= strtotime('now') ? 1 : 0);
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $actions ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $sStartTime.'<br />'.$today ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
*/
//            }
//                for ($i = 0; $i < sizeof($actions); $i++) {
                    $FlagEntryWritten = 1;
                    $tmp = $aValue;
                    extract($tmp);
                    $timestart = substr($tmp['time_start'], 0, 5);
                    $timeend = substr($tmp['time_end'], 0, 5);
                    $dayend = substr($date_end, -2);
                    $monthend = substr($tmp['date_end'], 5, 2);
                    $yearend = substr($tmp['date_end'], 0, 4);
                    $daystart = substr($tmp['date_start'], 8, 2);
                    $monthstart = substr($tmp['date_start'], 5, 2);
                    $yearstart = substr($tmp['date_start'], 0, 4);

                    $fontcol = $tmp['act_format'] == '' ? '' : (hexdec(substr($tmp['act_format'], 0, 3)) + hexdec(substr($tmp['act_format'], 3, 2)) + hexdec(substr($tmp['act_format'], 5, 2)) < 400) ? '; color:#FFFFFF' : '';
                    $style = $tmp['act_format'] == '' ? '' : 'style="background:' . $tmp['act_format'] . $fontcol . ';"';
                    //if (!isset($_GET['dayview']) && intval($daystart) !== intval(date('j'))) { continue; }
                    if ($ReplaceDay == 1) {
                        $day = $daystart;
                    }
                    $listIt = ((strtotime($sStartTime) >= strtotime('now')) && !isset($_GET['[dayview]']) ? 1 : 0);
                    if (MarkDayOk($day, $month, $year, $actions, $i) || ($IsMonthOverview) ) {  //  && $listIt
                        $link_pre = "" . ($tmp['name']);
                        if (IstStartTerminVergangeheit("$year-$month-$day", "$yearstart-$monthstart-$daystart") == 1) {
                            $link = "?$link_pre&amp;month=$monthstart&amp;year=$yearstart&amp;day=$daystart&amp;show=-1";
                        } else {
                            $link = "?$link_pre&amp;month=$month&amp;year=$year&amp;day=$daystart&amp;show=$i";
                        }
                        if (isset($pageid)) {
                            $link .= "&amp;page_id=$pageid";
                        }
                        $link .= "&amp;id=" . $tmp['id'] . "&amp;section_id=$section_id&amp;detail=1";
?>
                        <tr class="list<?php echo $ReplaceDay;?>" id=<?php echo '"acttype' . $tmp["acttype"] . '" ' . $style; ?>>
                            <td class="actionlist_date"><?php
                                echo $tmp['fdate_start'];
                                if ($tmp['date_end']) {
                                    if ($tmp['date_end'] != $tmp['date_start']) { //only show end date if event has multiple days
                                        echo "&nbsp;/&nbsp;";
                                        echo $tmp['fdate_end'];
                                    }
                                }
?>
                            </td>
<?php
                                if ($usetime) {
                                    echo '<td class="actionlist_time">' . $timestart . '</td>';
                                    echo '<td class="actionlist_time">' . $timeend . '</td>';
                                }
?>
                            <td class="actionlist_name"><?php
                                $link = str_replace("\"", "'", $link);
                                echo "<a href=\"$link\" >" . $tmp["name"] . "</a>";
?>
                            </td>
                            <td class="actionlist_actiontype "><?php
                                if (isset($action_types)&&sizeof($action_types)&&($tmp['acttype'] > 0)) {
                                    $action_name = explode("#", $action_types[$tmp['acttype']]['name']);
                                    print_r( $action_name[0]);
                                } ?>
                            </td>
                        </tr>
<?php
                            } // MarkDayOk
                        }  //  for $actions

                    if ($FlagEntryWritten == 0) {
?>
                <tr>
                    <td class="actionlist_name" colspan="3">&nbsp;<?php echo $CALTEXT['NODATES'];
         ?></td>

                </tr>
    <?php }?>
          </tbody>
          <tfoot>
              <tr class="actionlist_header">
<?php
                if ($usetime) {
?>
              <th colspan="5">
                  <div style="margin: 0.525em auto;text-align: center;"><?php echo (!$IsMonthOverview ? $BackToMonthLink : $monthnames[$month].' '.$year);?></div>
              </th>
<?php
  } else {
?>
              <th colspan="3">
                  <div style="margin: 0.525em auto;text-align: center;"><?php echo (!$IsMonthOverview ? $BackToMonthLink : $monthnames[$month]);?></div>
              </th>
<?php
  }
?>
              </tr>
          </tfoot>
        </table>
    </div>
<?php
            // Fetch needed settings from db
            $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_settings` WHERE `section_id`=$section_id ";
            $oRes = $database->query($sql);
            if ($oRes->numRows() > 0) {
#                while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {}
                $rec = $oRes->fetchRow(MYSQLI_ASSOC);
                    $footer = $admin->strip_slashes($rec["footer"]);

            }
            print $footer;
        }  // end ShowActionList

        /* this function returns array filled action-types grabbed from database */

        function fillActionTypes($sec_id) {
            global $database;
            $retarray = [];
            $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'mod_procalendar_eventgroups` ' . 'WHERE `section_id`=' . $sec_id . ' ' .
                    'ORDER by `name` ' . '';
            if ($db = $database->query($sql)) {
                while ($record = $db->fetchRow(MYSQLI_ASSOC)) {
                    $retarray[$record['id']] = $record;
                }
            }
            return ($retarray);
        }

        /* this function returns array filled with action-datas      */

        function fillActionArray($datestart, $dateend, $section_id) {
            global $database, $admin, $oReg;
            // Create new frontend object
//            if (!class_exists('admin')){ include(WB_PATH.'/framework/class.admin.php'); }
            if (!isset($admin) || !($admin instanceof admin)) { $admin = new admin('##skip##',false); }
            $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'mod_procalendar_settings` WHERE `section_id`='.$section_id.' ';
            if (!$db = $database->query($sql)){
              echo sprintf('%s',$database->get_error());
            }
            if ($db->numRows() > 0) {
                $rec = $db->fetchRow(MYSQLI_ASSOC);
                $useifformat = preg_replace('#[^.]?Y.?#s', '', $rec["useifformat"]);
            }

            // complete extrawhere part for joining to sql-query
            if ($admin->is_authenticated() && $admin->ami_group_member('1')) {
                // if user is admin, no extrawhere needed - show all actions
                $extrawhere = '';
            } else {
                $extrawhere = ''
                        .'AND ((`a`.`public_stat` = 0) '; // public actions
// if user is authenticated decide which actions to show
                if ($admin->is_authenticated())
                {
                    $extrawhere  .= ''
                       .'OR (`a`.`public_stat` > 0 AND `a`.`owner` IN (3,'.$admin->get_user_id().')) '
                       .'OR (`a`.`public_stat` IN ('.$_SESSION['GROUPS_ID'].')) '
                      .') ';
                } else {
                   $extrawhere .= ') '.PHP_EOL;
                }
            }
            $sql  = ''
                .  'SELECT '.PHP_EOL
                .    '`a`.*, '.PHP_EOL
                .    '`e`.`name` AS `act_name`, '.PHP_EOL
                .    '`e`.`format` AS `act_format`, '.PHP_EOL
                .    '`e`.`format_days` AS `act_dayformat` '.PHP_EOL
                .  'FROM '.PHP_EOL
                  .  '`'.TABLE_PREFIX . 'mod_procalendar_actions` AS `a` '.PHP_EOL
                .  'LEFT JOIN '.PHP_EOL
                 .     '`'. TABLE_PREFIX.'mod_procalendar_eventgroups` AS `e` '.PHP_EOL
                .  'ON '.PHP_EOL
                .    '`a`.`acttype` = `e`.`id` '.PHP_EOL
                .  'WHERE (`a`.`section_id`='.$section_id.' )'.PHP_EOL
                .    'AND (`a`.`date_start` <=\''.$dateend.'\')'.PHP_EOL
               .    ' AND (`a`.`date_end`   >=\''.$datestart.'\' OR `a`.`rec_count` != 0) '.PHP_EOL
               .    $extrawhere.''
               .   'ORDER BY '.PHP_EOL
                .    '`a`.`date_start`,`a`.`time_start`'.PHP_EOL;
            if (!$db = $database->query($sql))
            {
                file_put_contents(WB_PATH.'/var/logs/procalc_'.$datestart.'.sql', $sql.PHP_EOL);
            }
            if ($admin->is_authenticated() && $admin->ami_group_member('1')) {
            }
            $actions = [];
            $aRetval = [];
            $overwrites = [];
            if ($db->numRows() > 0) {
                while ($ret = $db->fetchRow(MYSQLI_ASSOC)) {
                    $maxCount = $ret['rec_count'];
                    $dateCount = 0;
                    $excludeDates = explode(";", $ret['rec_exclude']);
                    $dayDateStart = new DateTime($ret['date_start']);
                    $dayDateEnd = new DateTime($ret['date_end']);
                    $firstCalendarDay = new DateTime($datestart);
                    $lastCalendarDay = new DateTime($dateend);
                    if ($ret['rec_day'] != "") {
                        $days = $ret['rec_day'];
                        while (($dayDateStart <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $dayDateStart <=
                        $lastCalendarDay) {
                            if ($dayDateStart >= $firstCalendarDay && !in_array($dayDateStart->format('Y-m-d'), $excludeDates)) {
                                $strday = $dayDateStart->format('Y-m-d');
                                $ret['date_start'] = $strday;
                                $ret['date_end'] = $strday;
                                $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                $actions[] = $ret;
                            }
                            //$dayDateStart->add(new DateInterval('P'.$days.'D'));
                            $dayDateStart->modify('+' . $days . ' day');
                            $dateCount++;
                        }
                    } elseif ($ret['rec_week'] != "") {
                        $iStart = microtime(true); // change Luisehahne
                        $ret_week = explode("+", $ret['rec_week']);
                        $weeks = (int)$ret_week[0] - 1;
                        $weekdays = explode(";", $ret_week[1]);
                        while (($dayDateStart <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $dayDateStart <=
                        $lastCalendarDay) {
                            for ($i = 1; $i < 8 && ($dayDateStart <= $dayDateEnd || !$maxCount == 0) && $dayDateStart <= $lastCalendarDay; $i++) {
                                $strday = $dayDateStart->format('Y-m-d');
                                $wday = date("N", strtotime($strday));
                                if (in_array($wday, $weekdays) && $dayDateStart >= $firstCalendarDay && !in_array($dayDateStart->format('Y-m-d'), $excludeDates)) {
                                    $ret['date_start'] = $strday;
                                    $ret['date_end'] = $strday;
                                    $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                    $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                    $actions[] = $ret;
                                }
                                //$dayDateStart->add(new DateInterval('P1D'));
                                $dayDateStart->modify('+1 day');
                            }
                            //$dayDateStart->add(new DateInterval('P'.$weeks.'W'));
                            $dayDateStart->modify('+' . $weeks . ' weeks');
                            $dateCount++;
                            // change Luisehahne
                            $iExecutionTime = microtime(true) - $iStart;
                            if ($iExecutionTime > 1) {
                                $msg  = sprintf('created: [' . date('c') . ']' . ' Corrupted record -> %4d date_start -> %s', $ret['id'], $ret['date_start']) . PHP_EOL;
                                $msg  = sprintf('created: [' . date('c') . ']' . ' Corrupted record -> %4d date_start -> %s', $ret['id'], $ret['date_start']) . PHP_EOL;
                                $msg .= serialize($ret).PHP_EOL;
                                file_put_contents(WB_PATH . '/var/logs/procalc_error.log', $msg);
                                break;
                            }
                        }
                    } elseif ($ret['rec_month'] != "") {
                        $ret_month = explode("+", $ret['rec_month']);
                        if (count($ret_month) == 2) { // day - month
                            $days = $ret_month[0];
                            $months = $ret_month[1];
                            $strday = $dayDateStart->format('Y-m-d');
                            $strdays = substr($strday, 0, 8) . $days;
                            $firstDate = new DateTime($strdays);
                            if ($firstDate->format('j') != $days) {
                                $strdays = $firstDate->format('Y-m-d');
                                $strdays = substr($strdays, 0, 8) . $days;
                                $firstDate = new DateTime($strdays);
                            }
                            ;
                            if ($firstDate < $dayDateStart) //$firstDate->add(new DateInterval('P1M'));
                                $firstDate->modify('+1 month');
                            if ($firstDate->format('j') != $days) {
                                $firstDate = new DateTime($strdays);
                                //$firstDate->add(new DateInterval('P2M'));
                                $firstDate->modify('+2 months');
                            }
                            ;
                            $dayDateStart = clone $firstDate;
                            while (($dayDateStart <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $dayDateStart <=
                            $lastCalendarDay) {
                                if ($dayDateStart >= $firstCalendarDay && !in_array($dayDateStart->format('Y-m-d'), $excludeDates)) {
                                    $strday = $dayDateStart->format('Y-m-d');
                                    $ret['date_start'] = $strday;
                                    $ret['date_end'] = $strday;
                                    $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                    $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                    $actions[] = $ret;
                                }
                                ;
                                $oldDay = clone $dayDateStart;
                                $i = 1;
                                do {
                                    $dayDateStart = clone $oldDay;
                                    //$dayDateStart->add(new DateInterval('P'.($months*$i).'M'));
                                    $dayDateStart->modify('+' . ($months * $i) . ' month');
                                    $i++;
                                } while ($i < 20 && $dayDateStart->format('j') != $days);
                                $dateCount++;
                            }
                            ;
                        } else { // weekday - month
                            $weeks = $ret_month[0];
                            $weekdays = explode(";", $ret_month[1]);
                            $months = $ret_month[2];
                            $strday = $dayDateStart->format('Y-m-d');
                            $strmonth = $dayDateStart->format('F Y');
                            $startThisMonth = false;
                            foreach ($weekdays as $key => $val) {
                                $strweekday = strtotime($weeks . " " . $val . " of " . $strmonth);
                                if ($dayDateStart <= new DateTime(date('Y-m-d', $strweekday)))
                                    $startThisMonth = true;
                            }
                            ;
                            $strFirstDay = substr($strday, 0, 8) . "01";
                            $firstDay = new DateTime($strFirstDay);
                            if (!$startThisMonth) //$firstDay->add(new DateInterval('P1M'));
                                $firstDay->modify('+1 month');
                            while (($firstDay <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $firstDay <= $lastCalendarDay) {
                                $strMonth = $firstDay->format('F Y');
                                foreach ($weekdays as $key => $val) {
                                    $strWeekday = strtotime($weeks . " " . $val . " of " . $strMonth);
                                    $dayDateStart = new DateTime(date('Y-m-d', $strWeekday));
                                    if ($dayDateStart >= $firstCalendarDay &&
                                          ($dayDateStart <= $dayDateEnd || !$maxCount == 0) &&
                                            $dayDateStart <= $lastCalendarDay &&
                                              !in_array($dayDateStart->format('Y-m-d'), $excludeDates))
                                    {
                                        $strday = $dayDateStart->format('Y-m-d');
                                        $ret['date_start'] = $strday;
                                        $ret['date_end'] = $strday;
                                        $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                        $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                        $actions[] = $ret;
                                    }
                                }
                                //$firstDay->add(new DateInterval('P'.$months.'M'));
                                $firstDay->modify('+' . $months . ' month');
                                $dateCount++;
                            }
                            ;
                        }
                        ;
                    } elseif ($ret['rec_year'] != "") {
                        $ret_year = explode("+", $ret['rec_year']);
                        if (count($ret_year) == 2) { // day - month
                            $days = $ret_year[0];
                            $months = $ret_year[1];
                            $strday = $dayDateStart->format('Y-m-d');
                            $strFirstDay = substr($strday, 0, 5) . $months . "-" . $days;
                            $firstDay = new DateTime($strFirstDay);
                            $firstMonth = $firstDay->format('m');
                            $i = 1;
                            while ($firstDay < $dayDateStart || ($firstMonth != $firstDay->format('m'))) {
                                $firstDay = new DateTime($strFirstDay);
                                //$firstDay->add(new DateInterval('P'.$i.'Y'));
                                $firstDay->modify('+' . $i . ' year');
                                $i++;
                            }
                            ;
                            $dayDateStart = $firstDay;
                            while (($dayDateStart <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $dayDateStart <=
                            $lastCalendarDay) {
                                if ($dayDateStart >= $firstCalendarDay && !in_array($dayDateStart->format('Y-m-d'), $excludeDates)) {
                                    $strday = $dayDateStart->format('Y-m-d');
                                    $ret['date_start'] = $strday;
                                    $ret['date_end'] = $strday;
                                    $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                    $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                    $actions[] = $ret;
                                }
                                ;
                                $dateCount++;
                                $dayDateStart = clone $firstDay;
                                if ($firstDay->format('m-d') == "02-29") {
                                    //$dayDateStart->add(new DateInterval('P'.($dateCount*4).'Y'));
                                    $dayDateStart->modify('+' . ($dateCount * 4) . ' years');
                                } else //$dayDateStart->add(new DateInterval('P'.$dateCount.'Y'));
                                    $dayDateStart->modify('+' . $dateCount . ' year');
                            }
                            ;
                        } else { //weekday - month
                            $weeks = $ret_year[0];
                            $weekdays = explode(";", $ret_year[1]);
                            $months = $ret_year[2];
                            $strday = $dayDateStart->format('Y-m-d');
                            $strmonth = $months . " " . $dayDateStart->format('o');
                            $startThisMonth = false;
                            foreach ($weekdays as $key => $val) {
                                $strweekday = strtotime($weeks . " " . $val . " of " . $strmonth);
                                if ($dayDateStart < new DateTime(date('Y-m-d', $strweekday)))
                                    $startThisMonth = true;
                            }
                            ;
                            $strFirstDay = substr($strday, 0, 5) . $months . "-01";
                            $firstDay = new DateTime($strFirstDay);
                            if (!$startThisMonth)
                                $firstDay->modify('+1 year');
                            //$firstDay->add(new DateInterval('P1Y'));
                            while (($firstDay <= $dayDateEnd || !$maxCount == 0) && ($dateCount < $maxCount || $maxCount < 1) && $firstDay <= $lastCalendarDay) {
                                $strMonth = $firstDay->format('F o');
                                foreach ($weekdays as $key => $val) {
                                    $strWeekday = strtotime($weeks . " " . $val . " of " . $strMonth);
                                    $dayDateStart = new DateTime(date('Y-m-d', $strWeekday));
                                    if ($dayDateStart >= $firstCalendarDay && ($dayDateStart <= $dayDateEnd || !$maxCount == 0) && $dayDateStart <= $lastCalendarDay &&
                                            !in_array($dayDateStart->format('Y-m-d'), $excludeDates)) {
                                        $strday = $dayDateStart->format('Y-m-d');
                                        $ret['date_start'] = $strday;
                                        $ret['date_end'] = $strday;
                                        $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                                        $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                                        $actions[] = $ret;
                                    }
                                    ;
                                }
                                ;
                                //$firstDay->add(new DateInterval('P1Y'));
                                $firstDay->modify('+1 year');
                                $dateCount++;
                            }
                            ;
                        }
                        ;
                    } elseif ($ret['rec_id'] > 0) {
                        $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                        $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                        $overwrites[] = $ret;
                    } else {
                        $ret['fdate_start'] = date($useifformat, strtotime($ret['date_start']));
                        $ret['fdate_end'] = date($useifformat, strtotime($ret['date_end']));
                        $actions[] = $ret;
                    }
                    unset($dayDateStart);
                    unset($dayDateEnd);
                    unset($firstCalendarDay);
                    unset($lastCalendarDay);
                }
                foreach ($overwrites as $over) {
                    for ($i = 0; $i < count($actions); $i++) {
                        if ($over['rec_id'] == $actions[$i]['rec_id'] && $over['date_start'] == $actions[$i]['date_start'])
                            $actions[$i] = $over;
                    }
                }
                if (!function_exists('cmp')) {
                    function cmp($a, $b) {
                        if ($a['date_start'] == $b['date_start'] && $a['time_start'] == $b['time_start']) {
                            return 0;
                        }
                        return ($a['date_start'] < $b['date_start'] || ($a['date_start'] == $b['date_start'] && $a['time_start'] < $b['time_start'])) ?
                                -1 : 1;
                    }
                }
                usort($actions, "cmp");
#                return ($actions);
            } else {
/*
                print '<pre  class="mod-pre rounded">function <span>' . __function__ . '( ' . '' . ' );</span>  filename: <span>' .
                        basename(__file__) . '</span>  line: ' . __line__ . ' -> <br />';
                print_r($sql . '<br />' . $database->get_error());
                print '</pre>';
                flush(); //  ob_flush();;sleep(10); die();
*/
            }
            return $actions;
        }

//#############################################################################
        function MarkDayOk(
                $day, //
                $month, //
                $year, //
                $actions, // Array with dates
                $ActionIndex // Index of date to check
        ) { //
        // Return: 0: No Date active
//         1: Yes there is date aczive
            $bRetVal = false;
//
//#############################################################################
            $Termin = $actions[$ActionIndex];

            $dayend = substr($Termin['date_end'], -2);
            $monthend = substr($Termin['date_end'], 5, 2);
            $yearend = substr($Termin['date_end'], 0, 4);

            $daystart = substr($Termin['date_start'], 8, 2);
            $monthstart = substr($Termin['date_start'], 5, 2);
            $yearstart = substr($Termin['date_start'], 0, 4);

            $DateRefString   = date('Y-m-d',\strtotime("$year-$month-$day"));
            $DateStartString = date('Y-m-d',\strtotime($Termin['date_start']));
            $DateEndString   = date('Y-m-d',\strtotime($Termin['date_end']));

            $sDebugMsg = sprintf('%s %s',$DateStartString, $DateEndString);

            // Liegt der Starttermin in der Vergangenheit?
            if (IstStartTerminVergangeheit("$year-$month-$day", "$yearstart-$monthstart-$daystart") == 1) {
                if (($monthend == $month && $day <= $dayend && $year == $year)
                 || (($monthend > $month || $yearend > $year) && $day > $daystart)
                 ||  ($monthend > $month || $yearend > $year)) {
                    $bRetVal = true;
                }
            } else
            // Termin startet und endet in diesem Monat
            if (($day >= $daystart && $monthstart == $month)) {
                $bRetVal = true;
            }
            return $bRetVal;
        }

//#############################################################################
        function IstStartTerminVergangeheit(  /* function IstStartTerminVergangeheit */
                $DateRefString, // Todays date
                $DateStartString // date to check
        ) { //
        //  Return: 0 - Date is not in the past
//          1 - Yes, the date starts in the past
            $bRetVal = false;
//#############################################################################
            // echo "DateStartString  $DateRefString <br>";date("Y-m-d", strtotime("$DateStartString")
            // echo "dateref $DateStartString <br>";  date("Y-m-d", strtotime("$DateRefString"))
            if (strtotime("$DateStartString") < strtotime("$DateRefString")) {
//            if (date("Y-m-d", strtotime("$DateStartString")) < date("Y-m-d", strtotime("$DateRefString"))) {
                $bRetVal = true;
            }
            return $bRetVal;
        }

        function ShowTermineDebug($month, $year, $actions) {
            $AnzTage = sizeof($actions);
            // Loop ?ber die Anzahl Tage im Monat
            for ($day = 0; $day < $AnzTage; $day++) {
                if ($AnzTage) {
                    $Termin = $actions[$day];
                    $dayend = substr($Termin['date_end'], -2);
                    $monthend = substr($Termin['date_end'], 5, 2);
                    $yearend = substr($Termin['date_end'], 0, 4);
                    $daystart = substr($Termin['date_start'], 8, 2);
                    $monthstart = substr($Termin['date_start'], 5, 2);
                    $yearstart = substr($Termin['date_start'], 0, 4);
                    echo "Termin am $daystart.$monthstart.$yearstart - $dayend.$monthend.$yearend ";
                    if (IstStartTerminVergangeheit("$year-$month-$day", "$yearstart-$monthstart-$daystart") == 1){
                        echo "--> alter Termin";
                    }
                    echo "<br/>";
                }
            }
        }

        function PrintArray($array) {
            foreach ($array as $key => $value){
                echo "$key: $value ";
            }
        }

        /* writes ordered list of actions */

        function ShowActionListEditor(array $localVariables) {
/*
        function ShowActionListEditor($actions, $day = null, $pageid = null, $dayview) {
            global $action_types, $monthnames;
            global $month, $year;
            global $CALTEXT;
            $today = date("Y-m-d");
            $IsMonthOverview = $dayview;
*/
            if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}
            $pageid = $page_id;
            $BackToMonthLink = "<a href=?page_id=$pageid&amp;month=$month&amp;year=$year>[".$CALTEXT["CALENDAR-BACK-MONTH"]."]</a>";
            if (!$IsMonthOverview) {
                $HeaderText = $monthnames[(int)$month].' '.$year;
            } else {
                $HeaderText = $day.'-'.$month.'-'.$year.'&nbsp;&nbsp;'.$BackToMonthLink;
            }
?>
    <div class="actionlist">
        <h2><?php echo $HeaderText; ?></h2>
        <table class="actionlist_table scrollable w3-table-all">
            <thead class="w3-header-blue-wb">
            <tr class="actionlist_header">
                <th class="actionlist_date"><?php echo $CALTEXT['DATE'];?></th>
                <th class="actionlist_description"><?php echo $CALTEXT['NAME'];?></th>
                <th class="actionlist_type"><?php echo $CALTEXT['CATEGORY'];?></th>
            </tr>
            </thead>
            <tbody>
    <?php
                    $firstday = 1;
                    $lastday = DaysCount($month, $year);
                    if (!isset($day)) {
                        $ReplaceDay = 1;
                    } else {
                        $ReplaceDay = 0;
                    }

                    for ($i = 0; $i < sizeof($actions); $i++) {
                        $tmp = $actions[$i];
                        $dayend = substr($tmp['date_end'], -2);
                        $monthend = substr($tmp['date_end'], 5, 2);
                        $yearend = substr($tmp['date_end'], 0, 4);
                        $daystart = substr($tmp['date_start'], 8, 2);
                        $monthstart = substr($tmp['date_start'], 5, 2);
                        $yearstart = substr($tmp['date_start'], 0, 4);

                        if (MarkDayOk($day, $month, $year, $actions, $i) || !$IsMonthOverview) {
                            if (IstStartTerminVergangeheit("$year-$month-$day", "$yearstart-$monthstart-$daystart") == 1) {
                                $link = "?month=$monthstart&amp;year=$yearstart&amp;day=$daystart&amp;show=-1&amp;edit=edit";
                            } else {
                                $link = "?month=$monthstart&amp;year=$yearstart&amp;day=$daystart&amp;show=$i&amp;edit=edit";
                            }
                            if (isset($pageid)) {
                                $link .= "&amp;page_id=$pageid";
                            }
/*  */
                        $sBGColor = (( isset($tmp['acttype']) &&isset($action_types[$tmp['acttype']])) ? $action_types[$tmp['acttype']]['format'] : '#B8B8B8');
                        $sBorderBottom = ' border-bottom: 3px solid '.$sBGColor.';';
?>
                    <tr>
                        <td class="actionlist_date"><?php
                    echo '<span style="'.$sBorderBottom.'">'.$tmp['fdate_start'];
                    if ($tmp['date_end'] != $tmp['date_start']) { //only show end date if event has multiple days
                        echo '&nbsp;/&nbsp;'.$tmp['fdate_end'];
                    }
                    echo '</span>';
?>
                        </td>
                        <td class="actionlist_description">
                            <a href="<?php echo $link . '&amp;id=' . $tmp["id"]; ?>"><?php echo $tmp["name"];?></a>
                        </td>
                        <td class="actionlist_type">
<?php
                if ($tmp['acttype'] != 0) {
                    if (array_key_exists($tmp['acttype'], $action_types)) {
                        if ($action_types[$tmp['acttype']] != null)
                            $string = str_replace(["\r\n", "\r", "\n"], "<br>", $action_types[$tmp['acttype']]['name']);
                            echo $string;
                    } else {
                        //echo "Action Type not valid";
                    }
                }
?>
                        </td>
                    </tr>
<?php                   }  // end MarkDayOk
                    } // end for $actions
?>
            </tbody>
        </table>
    </div>
        <?php
        }

//######################################################################
        function ShowActionDetailsFromId(array $localVariables) { //
//        function ShowActionDetailsFromId($actions, $id, $section_id, $day) { //
        //  Return: nothing
//            global $CALTEXT, $database, $admin;
            if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}
            foreach ($actions as $a) {
                $sDayFormat = ($day['0']=='0' ? 'd' : 'j');
                if ($a["id"] == $id && date($sDayFormat, strtotime($a['date_start'])) == $day) {
                    $tmp = $a;
                    break;
                }
            }
            if (!isset($tmp)){
                $json = \json_encode($actions, JSON_PRETTY_PRINT);
                if (\file_put_contents(WB_PATH . '/var/logs/procalc_tmp_error.log',$json)) {
                    \trigger_error(sprintf('[%d] Can\'t call ShowActionEntry section_id = %d count(action) = %d id =  %d day = %d show = %d dayview = %d', __LINE__,$section_id, sizeof($actions),$id,$day, $show, $dayview),E_USER_WARNING);
                }
            } else {
                echo ShowActionEntry($tmp, $section_id);
            }
        }

//######################################################################
        function ShowActionEntry($tmp, $section_id) { //
        //  Return: nothing
            global $CALTEXT, $action_types;
            global $page_id, $weekdays;
            global $database, $admin, $wb;
$description = '
            <div class="field_line">
              <div class="field_value">
'.PHP_EOL;
            if (!isset($tmp)){
#                \trigger_error(sprintf('[%d] Missing action/tmp ', __LINE__),E_USER_WARNING);
                echo '<div>'.sprintf('[%d] '.$CALTEXT['NO_DESCRIPTION'], __LINE__).'</div>'.PHP_EOL;
                echo '<div>'.'<a class="go_back" href="?page_id='.$page_id.'&amp;month='.date('m').'&amp;year='.date('Y').'">'.$CALTEXT['BACK'].'</a>'.'</div>'.PHP_EOL;

$description .= '
                </div>
            </div>
'.PHP_EOL;
                return;
            }
            // Fetch all settings from db
            $sql = "SELECT * FROM `" . TABLE_PREFIX . "mod_procalendar_settings` WHERE `section_id`=$section_id ";
            $oRes = $database->query($sql);
            $Sday = 0;
            $Utime = 0;
            $Uformat = '';
            $Uifformat = '';
            if ($oRes->numRows() > 0) {
#                while ($rec = $oRes->fetchRow(MYSQLI_ASSOC)) {
                $rec = $oRes->fetchRow(MYSQLI_ASSOC);
                    $startday = $rec["startday"];
                    $usetime = $rec["usetime"];
                    $onedate = $rec["onedate"];
                    $useformat = $rec["useformat"];
                    $useifformat = $rec["useifformat"];
                    $usecustom1 = $rec["usecustom1"];
                    $custom1 = $rec["custom1"];
                    $customtemplate1 = $rec["customtemplate1"];
                    $usecustom2 = $rec["usecustom2"];
                    $custom2 = $rec["custom2"];
                    $customtemplate2 = $rec["customtemplate2"];
                    $usecustom3 = $rec["usecustom3"];
                    $custom3 = $rec["custom3"];
                    $customtemplate3 = $rec["customtemplate3"];
                    $usecustom4 = $rec["usecustom4"];
                    $custom4 = $rec["custom4"];
                    $customtemplate4 = $rec["customtemplate4"];
                    $usecustom5 = $rec["usecustom5"];
                    $custom5 = $rec["custom5"];
                    $customtemplate5 = $rec["customtemplate5"];
                    $usecustom6 = $rec["usecustom6"];
                    $custom6 = $rec["custom6"];
                    $customtemplate6 = $rec["customtemplate6"];
                    $usecustom7 = $rec["usecustom7"];
                    $custom7 = $rec["custom7"];
                    $customtemplate7 = $rec["customtemplate7"];
                    $usecustom8 = $rec["usecustom8"];
                    $custom8 = $rec["custom8"];
                    $customtemplate8 = $rec["customtemplate8"];
                    $usecustom9 = $rec["usecustom9"];
                    $custom9 = $rec["custom9"];
                    $customtemplate9 = $rec["customtemplate9"];
                    $posttempl = $rec["posttempl"];
#                }
            }
            //$previmg   = WB_URL."/modules/".basename(__DIR__)."/prev.png";
            // echo "<a class=\"go_back\" href=\"javascript:history.back()\" >&laquo; " . $CALTEXT['BACK'] . "</a>";
            $ds = $tmp['date_start'] . " " . substr($tmp['time_start'], 0, 5);
            $de = $tmp['date_end'] . " " . substr($tmp['time_end'], 0, 5);
            $datetime_start = mktime(substr($ds, 11, 2), substr($ds, 14, 2), 0, substr($ds, 5, 2), substr($ds, 8, 2), substr($ds, 0, 4));
            $datetime_end = mktime(substr($de, 11, 2), substr($de, 14, 2), 0, substr($de, 5, 2), substr($de, 8, 2), substr($de, 0, 4));
            $datetime_start = strtotime($tmp['date_start'].'  '.$tmp['time_start']);
            $datetime_end   = strtotime($tmp['date_end'] . '  '.$tmp['time_end']);

            $name = $tmp['name'];

            $date_start = date($useifformat, $datetime_start);
            $date_end   = date($useifformat, $datetime_end);

            $time_start = substr($tmp['time_start'], 0, 5);
            $time_end   = substr($tmp['time_end'], 0, 5);
            $action_name = "";

            if ($tmp['acttype'] > 0){
                $action_name = $action_types[$tmp['acttype']];
            }
            // 2011-oct-01 PCWacht
            // Added date_simple , just shows date (start (and end when given)
            // First set date_simple to startdate
            $date_simple = $date_start;

//            $date_full = PHP_EOL . '<div class="field_line">'.PHP_EOL;
//            $date_full .= '<label class="field_title">'.PHP_EOL;
/*
*/
$date_full = '
            <div class="field_line">
                <div class="field_title">
'.PHP_EOL;
            if ($tmp['date_start'] == $tmp['date_end']) {
                if ($tmp['time_start'] <> '00:00:00') {
                    $date_full .= $CALTEXT['DATE-AND-TIME'];
                } else
                    $date_full .= $CALTEXT['CAL-OPTIONS-ONEDATE'];
            } else{
                $date_full .= $CALTEXT['FROM'];
            }
$date_full .= '
                </div>
'.PHP_EOL;

            $date_full .= date($useifformat, $datetime_start);

            if ($usetime) {
                $start = substr($tmp['time_start'], 0, -3);
                if ($start != "00:00") {
                    $date_full .= " (" . $start . "&nbsp;" . $CALTEXT['TIMESTR'] . ")";
                }
            }

            if (count($action_name) > 1) {
                $day_index = [
                    '0' =>  "Sun,Mon,Tue,Wed,Thu,Fri,Sat",
                    '1' =>  "Mon,Sun,Tue,Wed,Thu,Fri,Sat",
                    ];
//                $date_full .= '</div>' . PHP_EOL . '<div class="field_line">';

$date_full .= '
            </div>
            <div class="field_line">
'.PHP_EOL;
/*
$date_full .= '
';
                for ($i = 1; $i < count($action_name); $i++) {
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $action_name ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
*/

                foreach ($action_name as $aValue) {
//                    $date_full .= $weekdays[array_search($aValue['format_days'], $day_index)].PHP_EOL;
                    $date_full .= ''.PHP_EOL;
                }
            }
$date_full .= '
            </div>
'.PHP_EOL;

            if (($tmp['date_start'] != $tmp['date_end']) || (($tmp['date_start'] == $tmp['date_end']) && (($tmp['time_start'] != $tmp['time_end']) &&
                    ((substr($tmp['time_end'], 0, -3)) != "00:00")))) {
//                $date_full .= '<div class="field_line">';
$date_full .= '
            <div class="field_line">
'.PHP_EOL;
                if ($tmp['date_end'] or $tmp['time_end']) {
$date_full .= '
                <div class="field_title">
'.PHP_EOL;
                $date_full .= '' . $CALTEXT['DEADLINE'].PHP_EOL;
$date_full .= '
                </div>
'.PHP_EOL;
                    if ($tmp['date_end']) {
                        $date_full .= date($useifformat, $datetime_end);
                        // 2011-oct-01 PCWacht
                        // and add dateend to date_simple
                        $date_simple .= ' - ' . $date_end;
                    }

                    if ($usetime) {
                        $ende = substr($tmp['time_end'], 0, -3);
                        if ($ende != "00:00") {
                            $date_full .= " (" . $ende . "&nbsp;" . $CALTEXT['TIMESTR'] . ")".PHP_EOL;
                        }
                    }
                }
//                $date_full .= '</div>' . PHP_EOL;
$date_full .= '
            </div>
'.PHP_EOL;
            }
            $category = '';
            if ($tmp['acttype'] > 0) {
//                $category .= '<div class="field_line">' . PHP_EOL;
//                $category .= '<label class="field_title">' . $CALTEXT['CATEGORY'] . '</label>' . $newline;
$category .= '
            <div class="field_line">
                <div class="field_title">
'.PHP_EOL;
$category .= $CALTEXT['CATEGORY'].'
                </div>
'.PHP_EOL;

                if ($tmp['acttype'] > 0){
                    $category .= $action_name['name'].PHP_EOL;
                }
//                $category .= '</div>' . PHP_EOL;
$category .= '
            </div>
'.PHP_EOL;

            }
            $custom_output1 = '';
            if (($usecustom1 <> 0 && $tmp['custom1'] <> '')){
                $custom_output1 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom1, $tmp['custom1']), $customtemplate1).PHP_EOL;
            }
            $custom_output2 = '';
            if (($usecustom2 <> 0 && $tmp['custom2'] <> '')){
                $custom_output2 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom2, $tmp['custom2']), $customtemplate2).PHP_EOL;
            }
            $custom_output3 = '';
            if (($usecustom3 <> 0 && $tmp['custom3'] <> '')){
                $custom_output3 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom3, $tmp['custom3']), $customtemplate3).PHP_EOL;
            }
            $custom_output4 = '';
            if (($usecustom4 <> 0 && $tmp['custom4'] <> '')){
                $custom_output4 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom4, $tmp['custom4']), $customtemplate4).PHP_EOL;
            }
            $custom_output5 = '';
            if (($usecustom5 <> 0 && $tmp['custom5'] <> '')){
                $custom_output5 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom5, $tmp['custom5']), $customtemplate5).PHP_EOL;
            }
            $custom_output6 = '';
            if (($usecustom6 <> 0 && $tmp['custom6'] <> '')){
                $custom_output6 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom6, $tmp['custom6']), $customtemplate6).PHP_EOL;
            }
            $custom_output7 = '';
            if (($usecustom7 <> 0 && $tmp['custom7'] <> '')){
                $custom_output7 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom7, $tmp['custom7']), $customtemplate7).PHP_EOL;
            }
            $custom_output8 = '';
            if (($usecustom8 <> 0 && $tmp['custom8'] <> '')){
                $custom_output8 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom8, $tmp['custom8']), $customtemplate8).PHP_EOL;
            }
            $custom_output9 = '';
            if (($usecustom9 <> 0 && $tmp['custom9'] <> '')){
                $custom_output9 = str_replace(array('[CUSTOM_NAME]', '[CUSTOM_CONTENT]'), array($custom9, $tmp['custom9']), $customtemplate9).PHP_EOL;
            }
//            $description = '<div class="field_line">' . PHP_EOL;
//            $description .= '<div class="field_value">' . PHP_EOL;

            if (mb_strlen($tmp['description']) > 0){
                $sFilterApi = WB_PATH.'/modules/output_filter/OutputFilterApi.php';
                if (is_readable($sFilterApi)) {
                    require_once($sFilterApi);
                    $tmp['description'] = OutputFilterApi('ReplaceSysvar', $tmp['description']);
                }
                $description .= $tmp['description'].PHP_EOL;
            }else{
#                \trigger_error(sprintf('[%d] Can\'t call ShowActionEntry section_id = %d', __LINE__,$section_id),E_USER_WARNING);
                $description .= sprintf(''.$CALTEXT['NO_DESCRIPTION']).PHP_EOL;
            }
//            $description .= '</div>' . PHP_EOL;
//            $description .= '</div>' . PHP_EOL;
$description .= '
                </div>
            </div>
'.PHP_EOL;

            $monthstart = substr($tmp['date_start'], 5, 2);
            $yearstart = substr($tmp['date_start'], 0, 4);
            $back = '<a class="go_back" href="?page_id='.$page_id.'&amp;month='.$monthstart.'&amp;year='.$yearstart.'">'.$CALTEXT['BACK'].'</a>'.PHP_EOL;
            //$back = "<a class=\"go_back\" href=\"javascript:history.back()\" >" . $CALTEXT['BACK'] . "</a>";
            $vars = array(
                '[NAME]',
                '[DATE_FULL]',
                '[DATE_SIMPLE]',
                '[CUSTOM1]',
                '[CUSTOM2]',
                '[CUSTOM3]',
                '[CUSTOM4]',
                '[CUSTOM5]',
                '[CUSTOM6]',
                '[CUSTOM7]',
                '[CUSTOM8]',
                '[CUSTOM9]',
                '[CATEGORY]',
                '[CONTENT]',
                '[BACK]');
            $values = array(
                $name,
                $date_full,
                $date_simple,
                $custom_output1,
                $custom_output2,
                $custom_output3,
                $custom_output4,
                $custom_output5,
                $custom_output6,
                $custom_output7,
                $custom_output8,
                $custom_output9,
                $category,
                $description,
                $back);
            $post_content = str_replace($vars, $values, $posttempl);
            return $post_content;
            /**
             * <script>
             * d = document.getElementsByTagName("div");
             * for (e = 1; e < d.length; e++)
             * if (d[e].className == "info_block")
             * d[e].innerHTML = d[e].innerHTML.replace(/(\/div>)(.*\()([^\)]*)\)/ig,"$1$3");
             *
             * a = document.getElementsByTagName("a");
             * for (e = 1; e < a.length; e++)
             * if (a[e].className == "go_back")
             * a[e].setAttribute('onclick','history.back();return false;');
             * </script>
             * */
        }

//######################################################################
        function createBackground($colors, $day) {
            if (is_writable(WB_PATH."/modules/".basename(__DIR__)."/images/")){
                $width = 60;
                if (!function_exists('show_menu')){
                    $width = "30";
                }
                $height = 4;
                $merge = ImageCreate($width, $height);
                $img = ImageCreate($width, $height);
                $count = count($colors);
                for ($i = 0; $i < $count; $i++) {
                    $red = hexdec(substr($colors[$i], 1, 2));
                    $green = hexdec(substr($colors[$i], 3, 2));
                    $blue = hexdec(substr($colors[$i], 5, 2));
                    ${'color' . $i} = ImageColorAllocate($img, $red, $green, $blue);
                }
                for ($i = 0; $i < $count; $i++) {
                    ImageFilledRectangle($img, $i * $width / $count, 0, ($i + 1) * $width / $count, $height, ${'color' . $i});
                }
                ImagePNG($img, WB_PATH."/modules/".basename(__DIR__)."/images/".$day.".png");
                ImageDestroy($img);
            }
        }

//######################################################################
        function ShowActionDetails(array $localVariables)
//        function ShowActionDetails($actions, $section_id, $day, $month, $year, $show = 0, $dayview = 0)
        {
//            global $action_types, $public_stat, $page_id, $CALTEXT;
            if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}
            if (sizeof($actions) == 0) {
                echo "&nbsp;" . $CALTEXT['NODATES'];
                return;
            }
            if ($dayview == 1 || $show == -1) {
                for ($i = 0; $i < sizeof($actions); $i++) {
                    $tmp = $actions[$i];
                    if (MarkDayOk($day, $month, $year, $actions, $i)) {
                        break;
                    }
                }
            } else {
                $tmp = $actions[$show];
            }
            if (!isset($tmp)){
                \trigger_error(sprintf('[%d] Can\'t call ShowActionEntry section_id = %d count(action) = %d show = %d dayview = %d', __LINE__,$section_id, sizeof($actions), $show, $dayview),E_USER_WARNING);
            } else {
                echo ShowActionEntry($tmp, $section_id);
            }
        }

        function IsStartDayMonday($SecId) {
            global $database;
            $sql = "SELECT * FROM " . TABLE_PREFIX . "mod_procalendar_settings WHERE section_id='$SecId' ";
            $db = $database->query($sql);
            if ($db->numRows() > 0) {
                $record = $db->fetchRow(MYSQLI_ASSOC);
                if ($record['startday'] == 0)
                    return true;
                if ($record['startday'] == 1)
                    return false;
            }
            return true;
        }

//
//######################################################################
// Function added by PCWacht
// Fetch all pages current user is allowed to see
//
// Variables,
// $parent = parent_id, start with 0
// $templ, html:->where to put page_id and page_name, uses str_replace
// $current, current from db
//
// returns = $content, html string with all pages and page_id's
//
//######################################################################

    function IteratePageTree($iParent = 0, $sTpl='', $iCurrentId=0)
    {
        global $admin, $database, $sContent;
        // Get page list from database
        $sSqlSet  = 'SELECT ( SELECT COUNT(*) '
              .          'FROM `'.TABLE_PREFIX.'pages` `x` '
              .          'WHERE x.`parent`=`p`.`page_id`'
              .        ') `children`, `p`.`page_id`,`p`.`menu_title`,`p`.`page_title`,`p`.`level` '
              .        ' FROM `'.TABLE_PREFIX.'pages` `p` '
              .        'WHERE `parent`='.$iParent.' '
              . 'ORDER BY `p`.`position` ASC';
        if (($oPages = $database->query($sSqlSet)))
        {
            while($aPage = $oPages->fetchRow(MYSQLI_ASSOC))
            { // iterate through the current branch
                if (PAGE_LEVEL_LIMIT && ($aPage['level'] > PAGE_LEVEL_LIMIT)) {
                    break;
                }
                $menu_title = $aPage['menu_title'];
                $page_title = $aPage['page_title'];
                $title_prefix = str_repeat(' - ', $aPage['level']);
                $select_content = '';
                if ($iCurrentId == $aPage['page_id']) {
                    $select_content = ' selected="selected"';
                }
//                  $sTpl = '  <option value="'.[PAGE_ID].'" [SELECTED]>'.[MENU_TITLE].'</option>'.PHP_EOL;
                $sContent .= str_replace(
                                  array(
                                      '[PAGE_ID]',
                                      '[MENU_TITLE]',
                                      '[SELECTED]'
                                  ),
                                  array(
                                          $aPage['page_id'],
                                          $title_prefix . $menu_title,
                                          $select_content
                                  ),
                                  $sTpl
                        );
                if((int)$aPage['children'] > 0 ) {
                    IteratePageTree($aPage['page_id'], $sTpl, $iCurrentId);
                }
            }
        }
        return $sContent;
    }
//
// End function parentlist
//######################################################################
//
//######################################################################
// Function added by PCWacht
// Allow user to select a wbpage
//
// returns = nothing
//
/*
            $aRetval = array(
                  '$tmp'   => $tmp,
                  '$title' => $title,
                  '$name'  => $name,
                  '$wbid'  => $wbid,
                  '$text'  => $text,
            );
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $text ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
*/
//######################################################################
        function select_wblink($title, $name='', $wbid=0, $text='') {
            global $tmp,$sContent;
            $sContent = '';
            $aRetval = '<span class="clearfix"></span>';
            $aRetval .= '<div class="field_line">'.PHP_EOL;
            $aRetval .= '  <label class="field_title">'. trim($title).'</label>'.PHP_EOL;

            $start    = '  <select name="' . $name . '" id="' . $name . '" class="inputbox" size="1" style="width: 40%;">'.PHP_EOL;
            $start   .= '    <option value="">' . $text . '</option>'.PHP_EOL;

            $end      = '  </select>'.PHP_EOL;
            $end     .= '</div>'.PHP_EOL;

            $sTpl     = '  <option value="[PAGE_ID]" [SELECTED]>[MENU_TITLE]</option>';
            $aRetval .= $start.IteratePageTree(0, $sTpl, $wbid).$end;  //

            return $aRetval;
        }

//
// End function parentlist
//######################################################################
//
//######################################################################
// Function added by PCWacht
// Allow user to select an image
//
// returns = nothing
//
//######################################################################
        function select_image($title, $name, $name_img, $image, $img_text, $img_text2) {

            echo '<span class="clearfix"></span>'.PHP_EOL;
            echo '<div class="field_line">'.PHP_EOL;
            echo '  <label class="field_title">'.PHP_EOL . $title . '</label>'.PHP_EOL;
            echo '  <input name="' . $name_img . '" type="file" style="width:410px;" />'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '<span class="clearfix"></span>'.PHP_EOL;
            echo '<div class="field_line">'.PHP_EOL;
            echo '  <label class="field_title">'.PHP_EOL . $img_text . '</label>'.PHP_EOL;
            echo '  <select name="' . $name . '" size="1" style="width:410px;">'.PHP_EOL;
            echo '    <option value="0" >' . $img_text2 . '</option>'.PHP_EOL;

            if ($handle = opendir(WB_PATH.MEDIA_DIRECTORY.'/calendar')) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        echo '<option value="'.'{SYSVAR:MEDIA_REL}/calendar/'.$file.'"';
                        if (strpos($image, $file))
                            echo ' selected="selected"';
                        echo '>' . $file . '</option>'.PHP_EOL;
                    }
                }
                closedir($handle);
            }

            echo '</select>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
        }

//
// End function select_image
//
//######################################################################
//
        /* this function is used in modify.php for adding new actions and changing details of older actions */
        function ShowActionEditor(array $localVariables) {
//        function ShowActionEditor($actions, $day, $show = 0, $dayview, $editMode, $month, $year, $edit_id) {
/*
            global $action_types, $public_stat, $weekdays, $monthnames,$year, $month, $day;
            global $page_id;
            global $admin;
            global $CALTEXT;
            global $section_id;
            // Added PCWacht
            // Fetch settings
            global $database;
*/
            if (isset($localVariables) && is_array($localVariables)){extract($localVariables);}
            $sql = "SELECT COUNT(*) FROM " . TABLE_PREFIX . "mod_procalendar_settings WHERE section_id='$section_id'";
            if (!($numRow = $database->get_one($sql))){
              echo sprintf('%s',$database->get_error());
              include(__DIR__.'/add.php');
            } else {
            }
            $sql = "SELECT * FROM " . TABLE_PREFIX . "mod_procalendar_settings WHERE section_id='$section_id'";
            if (!$db = $database->query($sql)){
              echo sprintf('%s',$database->get_error());
            }
            if ($db->numRows() > 0) {
                $rec = $db->fetchRow(MYSQLI_ASSOC);
                // Added PCWacht
                // Need to invers the firstday for calendar
                $jscal_firstday = 1 - $rec['startday'];
                $jscal_format = $rec['useformat'];
                $jscal_ifformat = $rec['useifformat'];
                $use_time = $rec['usetime'];
                $onedate = $rec["onedate"];
                $useformat = $rec["useformat"];
                $useifformat = $rec["useifformat"];
                $usecustom1 = $rec["usecustom1"];
                $custom1 = $rec["custom1"];
                $usecustom2 = $rec["usecustom2"];
                $custom2 = $rec["custom2"];
                $usecustom3 = $rec["usecustom3"];
                $custom3 = $rec["custom3"];
                $usecustom4 = $rec["usecustom4"];
                $custom4 = $rec["custom4"];
                $usecustom5 = $rec["usecustom5"];
                $custom5 = $rec["custom5"];
                $usecustom6 = $rec["usecustom6"];
                $custom6 = $rec["custom6"];
                $usecustom7 = $rec["usecustom7"];
                $custom7 = $rec["custom7"];
                $usecustom8 = $rec["usecustom8"];
                $custom8 = $rec["custom8"];
                $usecustom9 = $rec["usecustom9"];
                $custom9 = $rec["custom9"];
            } else {
                echo sprintf('%s settings found',$db->numRows());
            }
            $jscal_today = gmdate('Y/m/d');
            if ($editMode == "edit") {
                if ($dayview == 1) {
                    for ($i = 0; $i < sizeof($actions); $i++) {
                        $tmp = $actions[$i];
                        if ($tmp['id'] == $edit_id) {
                            break;
                        }
                    }
                } else {
                    if ($show == -1) {
                        for ($i = 0; $i < sizeof($actions); $i++) {
                            $tmp = $actions[$i];
                            if ($tmp['id'] == $edit_id) {
                                break;
                            }
                        }
                    } else {
                        $tmp = $actions[$show];
                    }
                }
            }
            if ($editMode == "new" || $editMode == "no") {
                $day = strval($day);
                $month = strval($month);
                if (strlen($month) == 1)
                    $month = "0" . $month;
                if (strlen($day) == 1)
                    $day = "0" . $day;
                $cal_id = 0;
                $tmp['place'] = "";
                $tmp['description'] = "";
                $tmp['date_start'] = date("$year-$month-$day");
                $tmp['date_end'] = date("$year-$month-$day");
                $tmp['time_start'] = "00:00";
                $tmp['time_end'] = "00:00";
                $tmp['acttype'] = 0;
                $tmp['custom1'] = "";
                $tmp['custom2'] = "";
                $tmp['custom3'] = "";
                $tmp['custom4'] = "";
                $tmp['custom5'] = "";
                $tmp['custom6'] = "";
                $tmp['custom7'] = "";
                $tmp['custom8'] = "";
                $tmp['custom9'] = "";
                $tmp['public_stat'] = 0;
                $tmp['name'] = $CALTEXT['CALENDAR-DEFAULT-TEXT'];
                $tmp['owner'] = $admin->get_user_id();
                $tmp['id'] = 0;
                $tmp['phpdate'] = mktime(0, 0, 0, $day, $month, $year);
            }
            $cal_id = $tmp['id'];
            $owner = $tmp['owner'];
            // Added PCWacht
            // Remake date so it suits Calendar
            $sql = "SELECT * FROM " . TABLE_PREFIX . "mod_procalendar_actions WHERE id='" . $tmp['id'] . "'";
            $db = $database->query($sql);
            $ret = $db->fetchRow(MYSQLI_ASSOC);
            if ($ret['rec_id'] > 0) {
                $tmp['date_start'] = $ret['date_start'];
                $tmp['date_end']   = $ret['date_end'];
            }
            $datetime_start = strtotime($tmp['date_start'].'  '.$tmp['time_start']);
            $datetime_end   = strtotime($tmp['date_end'] . '  '.$tmp['time_end']);
/*
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( $datetime_end ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
*/
            $ds = $tmp['date_start']." " . substr($tmp['time_start'], 0, 5);
            $de = $tmp['date_end'] . " " . substr($tmp['time_end'], 0, 5);
            $datetime_start = mktime(substr($ds, 11, 2), substr($ds, 14, 2), 0, substr($ds, 5, 2), substr($ds, 8, 2), substr($ds, 0, 4));
            $datetime_end   = mktime(substr($de, 11, 2), substr($de, 14, 2), 0, substr($de, 5, 2), substr($de, 8, 2), substr($de, 0, 4));
$sActionUrl = WB_URL . '/modules/' . basename(__DIR__) . '/save.php';
?>
    <div class="event_details">
        <form name="editcalendar" action="<?php echo $sActionUrl; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="cal_id" value="<?php echo $cal_id; ?>"/>
            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>"/>
            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
            <input type="hidden" name="owner" value="<?php echo $owner; ?>"/>
            <input type="hidden" name="jscal_format" value="<?php echo $jscal_format; ?>" />

            <div id="buttonrow">
<?php $url = ADMIN_URL . "/pages/modify.php?page_id=$page_id&edit=new";
 ?>
<?php // change Luisehahne; ?>
<?php $sBackUrl = WB_URL . '/modules/' . basename(__DIR__) . '/modify_settings.php?page_id=' . $page_id . '&section_id=' . $section_id . ''; ?>
<?php if ($admin->ami_group_member('1')) { ?>
            <input type="button" value="<?php echo $CALTEXT['SETTINGS']; ?>" class="edit_button float_right w3-blue-wb w3-round w3-hover-green w3-padding-4" onclick="window.location = '<?php echo $sBackUrl; ?>'" />
<?php } ?>
                <input class="edit_button w3-blue-wb w3-round w3-hover-green w3-padding-4" type="button" value="<?php echo $CALTEXT['NEW-EVENT']; ?>" onclick='document.location.href = "<?php echo $url; ?>"' />
<?php if ($editMode == "new" || $editMode == "edit") { ?>
                <input class="edit_button w3-blue-wb w3-round w3-hover-green w3-padding-4" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>" />
    <?php if ($editMode == "edit") { ?>
            <input class="edit_button w3-blue-wb w3-round w3-hover-green w3-padding-4" name="saveasnew" type="submit" value="<?php echo $CALTEXT['SAVE-AS-NEW']; ?>"/>
            <input id="delete" class="edit_button w3-blue-wb w3-round w3-hover-red w3-padding-4" type="submit" name="delete" value="<?php echo $CALTEXT['DELETE']; ?>" />
        <?php } ?>
<?php } ?>
<?php if ($editMode == "new" || $editMode == "edit") { ?>
                <input type="button" class="edit_button w3-blue-wb w3-round w3-hover-red w3-padding-4" value="<?php echo $CALTEXT['BACK']; ?>" onclick="window.location='<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" />
<?php } ?>
            </div>

<?php
    if (($editMode == "new") || ($editMode == "edit")) {
        // Added PCWacht
        // Choose one or two dates (start or start and end)
        include (__DIR__."/modify_recurrent_inc.php");
?>
                <span class="clearfix"></span>
                <div class="field_line" style="display: block;">
                    <label class="field_title"><?php echo $CALTEXT['FROM']; ?></label>
                    <input type="text" name="date1" id="date1" class="date-pick" value="<?php echo date($jscal_ifformat, $datetime_start); ?>"/>
    <?php if ($use_time <> 0) { ?>
                    <input type="text" id="start_time" name="time_start" value="<?php echo substr($tmp['time_start'],0,5); ?>" style="width: 50px;" />
    <?php } ?>
                </div>
<?php
/*
print '<pre  class="mod-pre rounded">function <span>'.__FUNCTION__.'( '.''.' );</span>  filename: <span>'.basename(__FILE__).'</span>  line: '.__LINE__.' -> <br />';
print_r( date($jscal_ifformat, $datetime_end) ); print '</pre>'; flush (); //  ob_flush();;sleep(10); die();
*/
                $hidden = ($onedate ? ' procal_hidden' : '');
?>
                <span class="clearfix"></span>
                <div class="field_line rec_enddate<?php echo $hidden; ?>">
          <?php if (!$onedate) { ?>
                    <label class="field_title"><?php echo $CALTEXT['UNTIL']; ?></label>
                    <input type="text" name="date2" id="date2" class="date-pick" value="<?php echo date($jscal_ifformat, $datetime_end); ?>"/>
              <?php if ($use_time) { ?>
                    <input type="text" id="end_time" name="time_end" value="<?php echo substr($tmp['time_end'], 0, 5); ?>" style="width: 50px;" />
              <?php } ?>
          <?php } ?>
                    <span class="clearfix"></span>
                    <div class="field_line rec_rep_select procal_hidden">
                        <div class="field_title"><label><?php echo $CALTEXT['TO']; ?></label></div>
                        <input id="rec_rep_count" class="rec_rep_count" type="text" name="rec_rep_count" <?php echo $rec_rep_count; ?> size="3" maxlength="3"/>
                        <label><?php echo $CALTEXT['DATES']; ?></label>
                        <input id="rec_never" type="checkbox" <?php echo $rec_rep_count_checked; ?> name="rec_never" value="1"/><label for="rec_never"><?php echo $CALTEXT['NEVER']; ?></label>
                    </div>
                </div>
                <span class="clearfix"></span>
                <div class="field_line">
                    <label class="field_title"><?php echo $CALTEXT['NAME']; ?></label>
                    <input class="edit_field date_title" name="name" type="text" value="<?php
                           if ($tmp) {
                               echo $tmp['name'];
                           } else {
                               echo $CALTEXT['CALENDAR-DEFAULT-TEXT'];
                           }
?>" />
                </div>
<?php // -- Added by PCWacht insertion custom fields
                if ($usecustom1 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom1); ?></label>
                        <input type="text" name="custom1" class="edit_field" value="<?php if ($tmp) {echo $tmp['custom1'];}?>" />
                    </div>
<?php }
                if ($usecustom1 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom1); ?></label>
                        <div class="field_area" >
                            <textarea name="custom1" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom1']; ?></textarea>
                        </div>
                    </div>
                <span class="clearfix"></span>
<?php
        }
        if ($usecustom1 == 3) {
            echo select_wblink($custom1, 'custom1', $tmp['custom1'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
        }
        if ($usecustom1 == 4) {
            select_image($custom1, 'custom_image1', 'custom1', $tmp['custom1'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
        }
        if ($usecustom2 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom2); ?></label>
                        <input type="text" name="custom2" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom2']; } ?>" />
                    </div>
<?php
                }
                if ($usecustom2 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom2); ?></label>
                        <div class="field_area" >
                            <textarea name="custom2" rows="5" cols="1" class="edit_field"><?php echo $tmp['custom2']; ?></textarea>
                        </div>
                    </div>
                <span class="clearfix"></span>
<?php
                }
                if ($usecustom2 == 3) {
                    echo select_wblink($custom2, 'custom2', $tmp['custom2'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                }
                if ($usecustom2 == 4) {
                    select_image($custom2, 'custom2', 'custom_image2', $tmp['custom2'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                }
                if ($usecustom3 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom3); ?></label>
                        <input type="text" name="custom3" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom3']; } ?>" />
                    </div>
<?php }
                        if ($usecustom3 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom3); ?></label>
                        <div class="field_area" >
                            <textarea name="custom3" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom3']; ?></textarea>
                        </div>
                    </div>
                <span class="clearfix"></span>
<?php
        }
        if ($usecustom3 == 3) {
            echo select_wblink($custom3, 'custom3', $tmp['custom3'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
        }
        if ($usecustom3 == 4) {
            select_image($custom3, 'custom3', 'custom_image3', $tmp['custom3'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
        }
        if ($usecustom4 == 1) {
?>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom4); ?></label>
                        <input type="text" name="custom4" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom4']; } ?>"/>
                    </div>
<?php }
                if ($usecustom4 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom4); ?></label>
                        <div class="field_area" >
                            <textarea name="custom4" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom4']; ?></textarea>
                        </div>
                    </div>
<?php
                }
                if ($usecustom4 == 3) {
                    echo select_wblink($custom4, 'custom4', $tmp['custom4'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                }
                if ($usecustom4 == 4) {
                    select_image($custom4, 'custom4', 'custom_image4', $tmp['custom4'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                }
                if ($usecustom5 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom5); ?></label>
                        <input type="text" name="custom5" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom5']; } ?>" />
                    </div>
<?php }
        if ($usecustom5 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom5); ?></label>
                        <div class="field_area" >
                            <textarea name="custom5" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom5']; ?></textarea>
                        </div>
                    </div>
<?php
                }
                if ($usecustom5 == 3) {
                    echo select_wblink($custom5, 'custom5', $tmp['custom5'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                }
                if ($usecustom5 == 4) {
                    select_image($custom5, 'custom5', 'custom_image5', $tmp['custom5'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                }
                if ($usecustom6 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom6); ?></label>
                        <input type="text" name="custom6" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom6']; } ?>" />
                    </div>
                <span class="clearfix"></span>
<?php }
                if ($usecustom6 == 2) {
?>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom6); ?></label>
                        <div class="field_area" >
                            <textarea name="custom6" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom6']; ?></textarea>
                        </div>
                    </div>
                <span class="clearfix"></span>
<?php
                }
                if ($usecustom6 == 3) {
                    echo select_wblink($custom6, 'custom6', $tmp['custom6'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                }
                if ($usecustom6 == 4) {
                    select_image($custom6, 'custom6', 'custom_image6', $tmp['custom6'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                }
                if ($usecustom7 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom7); ?></label>
                        <input type="text" name="custom7" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom7']; } ?>" />
                    </div>
<?php }
                if ($usecustom7 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom7); ?></label>
                        <div class="field_area" >
                            <textarea name="custom7" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom7']; ?></textarea>
                        </div>
                    </div>
<?php
                }
                if ($usecustom7 == 3) {
                    echo select_wblink($custom7, 'custom7', $tmp['custom7'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                }
                if ($usecustom7 == 4) {
                    select_image($custom7, 'custom7', 'custom_image7', $tmp['custom7'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                }
                if ($usecustom8 == 1) {
?>
                 <span class="clearfix"></span>
                   <div class="field_line">
                        <label class="field_title"><?php echo trim($custom8); ?></label>
                        <input type="text" name="custom8" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom8']; } ?>" />
                    </div>
<?php }
                if ($usecustom8 == 2) {
?>
                <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom8); ?></label>
                        <div class="field_area" >
                            <textarea name="custom8" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom8']; ?></textarea>
                        </div>
                    </div>
<?php
                        }
                        if ($usecustom8 == 3) {
                            echo select_wblink($custom8, 'custom8', $tmp['custom8'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                        }
                        if ($usecustom8 == 4) {
                            select_image($custom8, 'custom8', 'custom_image8', $tmp['custom8'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                        }
                        if ($usecustom9 == 1) {
?>
                <span class="clearfix"></span>
                    <div class="field_line">
                        <label class="field_title"><?php echo trim($custom9); ?></label>
                        <input type="text" name="custom9" class="edit_field" value="<?php if ($tmp) { echo $tmp['custom9']; } ?>" />
                    </div>
<?php }
                        if ($usecustom9 == 2) {
  ?>
                    <span class="clearfix"></span>
                    <div class="field_link" >
                        <label class="field_title"><?php echo trim($custom9); ?></label>
                        <div class="field_area" >
                            <textarea name="custom9" rows="4" cols="1" class="edit_field"><?php echo $tmp['custom9']; ?></textarea>
                        </div>
                    </div>
<?php
                        }
                        if ($usecustom9 == 3) {
                            echo select_wblink($custom9, 'custom9', $tmp['custom9'], $CALTEXT['CUSTOM_SELECT_WBLINK']);
                        }
                        if ($usecustom9 == 4) {
                            select_image($custom9, 'custom9', 'custom_image9', $tmp['custom9'], $CALTEXT['CUSTOM_SELECT_IMG'], $CALTEXT['CUSTOM_CHOOSE_IMG']);
                        }
                        // End addition PCWacht for custom fields!
?>
                <span class="clearfix"></span>
                <div class="field_line">
                    <label class="field_title"><?php echo $CALTEXT['CATEGORY']; ?></label>
                    <select name="acttype" class="edit_select">
                        <option value="0"><?php echo $CALTEXT['NON-SPECIFIED']; ?></option>
<?php
                foreach ($action_types as $key => $value){
                    $selected = (($tmp['acttype'] == $key ) ? ' selected="selected"' : '');
?>
                        <option value="<?php echo $key;?>"<?php echo $selected;?>style="border-bottom: 1px solid <?php echo $value['format'];?>;" ><?php echo $value['name'];?></option>
<?php
                }
?>
                    </select>
                </div>
<?php
        if ($admin->is_authenticated())
        {
            $sql = 'SELECT * FROM `'.TABLE_PREFIX.'groups` ';
            $oGroups = $database->query($sql);
            while ($groups = $oGroups->fetchRow(MYSQLI_ASSOC))
            {
                if (
                    $admin->is_group_match($admin->get_groups_id(), $groups["group_id"]) ||
                    $admin->ami_group_member('1')
                ) {
                      $public_stat[$groups["group_id"]] = $groups["name"];
                }
            }
        }
?>
                <span class="clearfix"></span>
                <div class="field_line">
                    <label class="field_title"><?php echo $CALTEXT['VISIBLE']; ?></label>
                    <select name="public_stat" class="edit_select">
<?php
        foreach ($public_stat as $key => $value){
            // Ausblenden von "privat" in der Auswahl Sichtbarkeit
//            if ($key == 1) { continue; }
            $selected = (($tmp['public_stat'] == $key || $tmp['public_stat'] == $key) ? ' selected="selected"' : '');
?>
                        <option value="<?php echo $key;?>"<?php echo $selected;?> ><?php echo $value;?></option>
<?php
        }
?>
                    </select>
                </div>
                <span class="clearfix"></span>
                <div class="field_line" style="float: left; width: 20%; text-align: right;">
                    <label class="title"><?php echo $CALTEXT['DESCRIPTION']; ?></label>
                </div>

                <div style="float: left; width: 80%;">
        <?php
            if (strlen($tmp['description']) > 0){
                $tmp['description'] = OutputFilterApi('ReplaceSysvar', $tmp['description']);
            }
            show_wysiwyg_editor("short", "short", $tmp['description'], "99%", "250px"); ?>
                </div>
    <?php }
?>
        </form>
    </div>
        <script charset="utf-8">
            // Adding variables for datepicker - sent to backend_body.js:
            var MODULE_URL = WB_URL + '/modules/<?php echo basename(__DIR__);?>';
            var firstDay   = '<?php echo $jscal_firstday; ?>';      // Firstday, 0=sunday/1=monday
            var format     = '<?php echo $jscal_format;  ?>';     // format of date, mm.dd.yyy etc
            var datestart  = '<?php echo date($jscal_ifformat, $datetime_start); ?>';    // datestart in input field
            var dateend    = '<?php echo date($jscal_ifformat, $datetime_end);   ?>';    // dateedn in inputfield
            var datefrom   = '<?php echo date($jscal_ifformat, mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1)); ?>';  // How long back?
<?php
            // Set language file, if it exists
            $jscal_lang = defined('LANGUAGE') ? strtolower(LANGUAGE) : 'en';
            $jscal_lang = $jscal_lang != '' ? $jscal_lang : 'en';
            if (file_exists(WB_PATH . "/modules/" . basename(__dir__) . "/js/lang/date_" . $jscal_lang . ".js")) {
                echo 'var datelang     = "date_' . $jscal_lang . '.js"';
            } else {
                echo 'var datelang     = "none"';
            }
?></script>

    <?php
    // End of function.
}
