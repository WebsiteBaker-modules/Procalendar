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
//print_r($weekdays);

function date_to_jscal($day, $month, $year, $jscalFormat) {
    $aDate = array(strlen($day)== 1 ? "0".$day : $day,strlen($month)== 1 ? "0".$month : $month,$year);
    $aFormat = array("d","m","Y");
  return str_replace($aFormat, $aDate, $jscalFormat);
};

$rec_day_checked = $rec_week_checked = $rec_month_checked = $rec_year_checked = "";
$rec_exceptions_checked = 'disabled="disabled"';
$wd = array(1=>"mon","tue","wed","thu","fri","sat","sun");
$counter = array(1=>"first","second","third","fourth","last");
for ($i = 1; $i < 8; $i++) {
 ${"rec_week_weekdays_checked".$i} = "";
 ${"rec_month_weekdays_checked_".$wd[$i]} = "";
 ${"rec_year_weekdays_checked_".$wd[$i]} = "";
};
for ($i = 1; $i < 13; $i++) {
 ${"rec_year_option_day_month_selected_".$i} = "";
 ${"rec_year_option_weekday_month_selected_".$i} = "";
}
for ($i = 1; $i < 6; $i++) {
 ${"rec_month_option_selected_".$counter[$i]} = "";
 ${"rec_year_option_selected_".$counter[$i]} = "";
}

$rec_day_days = "";
$rec_week_weeks = "";
$rec_month_days = $rec_month_month = $rec_month_weekday_month ="";
$rec_year_days = "";
$rec_rep_count = $rec_rep_count_checked = "";
$rec_date_exclude1 = $rec_date_exclude2 = $rec_date_exclude3 = date($jscal_ifformat,$datetime_start);
if ($ret['rec_id'] > 0){
    if ($ret['rec_day'] != ""){
        $rec_day_checked='checked="checked"';
        $rec_day_days='value="'.$ret['rec_day'].'"';
    } elseif ($ret['rec_week'] != ""){
      $rec_week_checked='checked="checked"';
      $rec_week = explode("+",$ret['rec_week']);
    $rec_week_weeks = 'value="'.$rec_week[0].'"';
    $rec_weekdays = explode(";",$rec_week[1]);
    foreach($rec_weekdays as $key => $val) {
        ${"rec_week_weekdays_checked".$val} = 'checked="checked"';
        };
    } elseif ($ret['rec_month'] != ""){
        $rec_month_checked='checked="checked"';
        $rec_month = explode("+",$ret['rec_month']);
    if (count($rec_month) == 2){  // day - month
        $rec_month_days = 'value="'.$rec_month[0].'"';
        $rec_month_month = 'value="'.$rec_month[1].'"';
        } else { // week - weekday
            ${"rec_month_option_selected_".$rec_month[0]} = "selected";
            $rec_weekdays = explode(";",$rec_month[1]);
        foreach($rec_weekdays as $key => $val) {
            ${"rec_month_weekdays_checked_".$val} = 'checked="checked"';
            };
            $rec_month_weekday_month = 'value="'.$rec_month[2].'"';
        };
    } elseif ($ret['rec_year'] != "") {
        $rec_year_checked='checked="checked"';
        $rec_year = explode("+",$ret['rec_year']);
    if (count($rec_year) == 2){  // day - month
        $rec_year_days = 'value="'.$rec_year[0].'"';
            ${"rec_year_option_day_month_selected_".$rec_year[1]} = "selected";
        } else { // weekday - month
            ${"rec_year_option_selected_".$rec_year[0]} = "selected";
            $rec_weekdays = explode(";",$rec_year[1]);
        foreach($rec_weekdays as $key => $val) {
            ${"rec_year_weekdays_checked_".$val} = 'checked="checked"';
            };
            ${"rec_year_option_weekday_month_selected_".$rec_year[2]} = "selected";
        };
    };

    if ($ret['rec_count'] == -1)
      $rec_rep_count_checked='checked="checked"';
    elseif ($ret['rec_count'] > 0)
        $rec_rep_count='value="'.$ret['rec_count'].'"';

    if ($ret['rec_exclude'] != ""){
        $rec_exceptions_checked='checked="checked"';
        $excludes = explode(";",$ret['rec_exclude']);
        for ($i = 0; $i < 4; $i++){
          $excludes[$i] == "" ? $excludes[$i] = "" : $excludes[$i] = date($rec['useifformat'],strtotime($excludes[$i]));
        }
/* */
        $rec_date_exclude1=$excludes[0];
        $rec_date_exclude2=$excludes[1];
        $rec_date_exclude3=$excludes[2];

    };

}
?>
<input id="rec_id" type="hidden" name="rec_id" value="<?php echo $ret['rec_id']; ?>"/>
<input id="rec_message" type="hidden" name="rec_message" value="<?php echo $CALTEXT['ISREC_MESSAGE']; ?>"/>
<input id="rec_overwrite_message" type="hidden" name="rec_overwrite_message" value="<?php echo $CALTEXT['REC_OVERWRITE_MESSAGE']; ?>"/>
<input id="rec_overwrite" type="hidden" name="rec_overwrite" value="<?php echo $CALTEXT['REC_OVERWRITE']; ?>"/>
<input id="rec_day_called" type="hidden" name="rec_day_called" value="<?php echo date_to_jscal($day,$month,$year,$jscal_ifformat); ?>"/>
<div class="field_line rec_select clearfix">
    <label class="field_title"><?php echo $CALTEXT['MAKE_REC']; ?></label>
    <input id="rec_day" type="checkbox" name="rec_by[]" <?php echo $rec_day_checked; ?> value="1"/><label for="rec_day"><?php echo $CALTEXT['DAILY']; ?></label>
    <input id="rec_week" type="checkbox" name="rec_by[]" <?php echo $rec_week_checked; ?> value="2"/><label for="rec_week"><?php echo $CALTEXT['WEEKLY']; ?></label>
    <input id="rec_month" type="checkbox" name="rec_by[]" <?php echo $rec_month_checked; ?> value="3"/><label for="rec_month"><?php echo $CALTEXT['MONTHLY']; ?></label>
    <input id="rec_year" type="checkbox" name="rec_by[]" <?php echo $rec_year_checked; ?> value="4"/><label for="rec_year"><?php echo $CALTEXT['YEARLY']; ?></label>
    <input id="rec_exceptions" type="checkbox" name="rec_by[]" <?php echo $rec_exceptions_checked; ?> value="5" ><label for="rec_exceptions"><?php echo $CALTEXT['USE_EXCEPTION']; ?></label>
</div>
<div class="field_line rec_day procal_hidden clearfix">
    <label class="field_title"><?php echo $CALTEXT['EVERY']; ?></label>
    <input type="text" id="rec_day_days" name="rec_day_days" <?php echo $rec_day_days; ?> size="3" maxlength="3"/><label for="rec_day_days">.<?php echo $CALTEXT['DAY']; ?></label>
</div>
<div class="field_line rec_week procal_hidden clearfix">
    <label class="field_title"><?php echo $CALTEXT['EVERY_SINGLE']; ?></label>
    <input type="text" id="rec_week_weeks" name="rec_week_weeks" size="2" maxlength="2" <?php echo $rec_week_weeks; ?> /><label for="rec_week_weeks">.<?php echo $CALTEXT['WEEK_ON']; ?></label>
    <input type="checkbox" id="rec_week_weekday1" name="rec_week_weekday[]" value="1" <?php echo $rec_week_weekdays_checked1; ?> /><label for="rec_week_weekday1"><?php echo $weekdays[1]; ?></label>
    <input type="checkbox" id="rec_week_weekday2" name="rec_week_weekday[]" value="2" <?php echo $rec_week_weekdays_checked2; ?> /><label for="rec_week_weekday2"><?php echo $weekdays[2]; ?></label>
    <input type="checkbox" id="rec_week_weekday3" name="rec_week_weekday[]" value="3" <?php echo $rec_week_weekdays_checked3; ?> /><label for="rec_week_weekday3"><?php echo $weekdays[3]; ?></label>
    <input type="checkbox" id="rec_week_weekday4" name="rec_week_weekday[]" value="4" <?php echo $rec_week_weekdays_checked4; ?> /><label for="rec_week_weekday4"><?php echo $weekdays[4]; ?></label>
    <input type="checkbox" id="rec_week_weekday5" name="rec_week_weekday[]" value="5" <?php echo $rec_week_weekdays_checked5; ?> /><label for="rec_week_weekday5"><?php echo $weekdays[5]; ?></label>
    <input type="checkbox" id="rec_week_weekday6" name="rec_week_weekday[]" value="6" <?php echo $rec_week_weekdays_checked6; ?> /><label for="rec_week_weekday6"><?php echo $weekdays[6]; ?></label>
    <input type="checkbox" id="rec_week_weekday7" name="rec_week_weekday[]" value="7" <?php echo $rec_week_weekdays_checked7; ?> /><label for="rec_week_weekday7"><?php echo $weekdays[7]; ?></label>
</div>
<div class="double_field_line rec_month procal_hidden  clearfix">
    <div class="field_title"><label><?php echo $CALTEXT['EVERY']; ?></label></div>
    <input type="text" name="rec_month_days" <?php echo $rec_month_days; ?> size="2" maxlength="2"/><label for="">.<?php echo $CALTEXT['DAY']; ?></label>
    <label><?php echo $CALTEXT['OF_EVERY']; ?></label>
    <input type="text" name="rec_month_month" <?php echo $rec_month_month; ?> size="2" maxlength="2"/><label for="">.<?php echo $CALTEXT['OF_MONATS']; ?></label><br>
    <div class="field_title">&nbsp;</div>
    <select name="rec_month_option_count" size="1">
    <option <?php echo $rec_month_option_selected_first; ?> value="first"><?php echo $CALTEXT['COUNT'][1]; ?></option>
    <option <?php echo $rec_month_option_selected_second; ?> value="second"><?php echo $CALTEXT['COUNT'][2]; ?></option>
    <option <?php echo $rec_month_option_selected_third; ?> value="third"><?php echo $CALTEXT['COUNT'][3]; ?></option>
    <option <?php echo $rec_month_option_selected_fourth; ?> value="fourth"><?php echo $CALTEXT['COUNT'][4]; ?></option>
    <option <?php echo $rec_month_option_selected_last; ?> value="last"><?php echo $CALTEXT['COUNT'][5]; ?></option>
  </select>&nbsp;
    <input type="checkbox" id="month_weekday_mon" name="rec_month_weekday[]" value="mon" <?php echo $rec_month_weekdays_checked_mon; ?> /><label for="month_weekday_mon"><?php echo $weekdays[1]; ?></label>
    <input type="checkbox" id="month_weekday_tue" name="rec_month_weekday[]" value="tue" <?php echo $rec_month_weekdays_checked_tue; ?> /><label for="month_weekday_tue"><?php echo $weekdays[2]; ?></label>
    <input type="checkbox" id="month_weekday_wed" name="rec_month_weekday[]" value="wed" <?php echo $rec_month_weekdays_checked_wed; ?> /><label for="month_weekday_wed"><?php echo $weekdays[3]; ?></label>
    <input type="checkbox" id="month_weekday_thu" name="rec_month_weekday[]" value="thu" <?php echo $rec_month_weekdays_checked_thu; ?> /><label for="month_weekday_thu"><?php echo $weekdays[4]; ?></label>
    <input type="checkbox" id="month_weekday_fri" name="rec_month_weekday[]" value="fri" <?php echo $rec_month_weekdays_checked_fri; ?> /><label for="month_weekday_fri"><?php echo $weekdays[5]; ?></label>
    <input type="checkbox" id="month_weekday_sat" name="rec_month_weekday[]" value="sat" <?php echo $rec_month_weekdays_checked_sat; ?> /><label for="month_weekday_sat"><?php echo $weekdays[6]; ?></label>
    <input type="checkbox" id="month_weekday_sun" name="rec_month_weekday[]" value="sun" <?php echo $rec_month_weekdays_checked_sun; ?> /><label for="month_weekday_sun"><?php echo $weekdays[7]; ?></label>
    <?php echo $CALTEXT['OF_EVERY']; ?>
    <input type="text" name="rec_month_weekday_month" <?php echo $rec_month_weekday_month; ?> size="2" maxlength="2"/>.<?php echo $CALTEXT['OF_MONATS']; ?>
</div>
<div class="double_field_line rec_year procal_hidden clearfix">
    <div  class="field_title"><label><?php echo $CALTEXT['EVERY']; ?></label></div>
  <input type="text" name="rec_year_days" <?php echo $rec_year_days; ?> size="2" maxlength="2"/>.
  <select name="rec_year_option_month" size="1">
      <option <?php echo $rec_year_option_day_month_selected_1; ?> value="1"><?php echo $monthnames[1]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_2; ?> value="2"><?php echo $monthnames[2]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_3; ?> value="3"><?php echo $monthnames[3]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_4; ?> value="4"><?php echo $monthnames[4]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_5; ?> value="5"><?php echo $monthnames[5]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_6; ?> value="6"><?php echo $monthnames[6]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_7; ?> value="7"><?php echo $monthnames[7]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_8; ?> value="8"><?php echo $monthnames[8]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_9; ?> value="9"><?php echo $monthnames[9]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_10; ?> value="10"><?php echo $monthnames[10]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_11; ?> value="11"><?php echo $monthnames[11]; ?></option>
      <option <?php echo $rec_year_option_day_month_selected_12; ?> value="12"><?php echo $monthnames[12]; ?></option>
  </select><br>
    <div class="field_title">&nbsp;</div>
    <select name="rec_year_option_count" size="1">
    <option <?php echo $rec_year_option_selected_first; ?> value="first"><?php echo $CALTEXT['COUNT'][1]; ?></option>
    <option <?php echo $rec_year_option_selected_second; ?> value="second"><?php echo $CALTEXT['COUNT'][2]; ?></option>
    <option <?php echo $rec_year_option_selected_third; ?> value="third"><?php echo $CALTEXT['COUNT'][3]; ?></option>
    <option <?php echo $rec_year_option_selected_fourth; ?> value="fourth"><?php echo $CALTEXT['COUNT'][4]; ?></option>
    <option <?php echo $rec_year_option_selected_last; ?> value="last"><?php echo $CALTEXT['COUNT'][5]; ?></option>
  </select>&nbsp;
    <input type="checkbox" id="year_weekday_mon" name="rec_year_weekday[]" value="mon" <?php echo $rec_year_weekdays_checked_mon; ?> /><label for="year_weekday_mon"><?php echo $weekdays[1]; ?></label>
    <input type="checkbox" id="year_weekday_tue" name="rec_year_weekday[]" value="tue" <?php echo $rec_year_weekdays_checked_tue; ?> /><label for="year_weekday_tue"><?php echo $weekdays[2]; ?></label>
    <input type="checkbox" id="year_weekday_wed" name="rec_year_weekday[]" value="wed" <?php echo $rec_year_weekdays_checked_wed; ?> /><label for="year_weekday_wed"><?php echo $weekdays[3]; ?></label>
    <input type="checkbox" id="year_weekday_thu" name="rec_year_weekday[]" value="thu" <?php echo $rec_year_weekdays_checked_thu; ?> /><label for="year_weekday_thu"><?php echo $weekdays[4]; ?></label>
    <input type="checkbox" id="year_weekday_fri" name="rec_year_weekday[]" value="fri" <?php echo $rec_year_weekdays_checked_fri; ?> /><label for="year_weekday_fri"><?php echo $weekdays[5]; ?></label>
    <input type="checkbox" id="year_weekday_sat" name="rec_year_weekday[]" value="sat" <?php echo $rec_year_weekdays_checked_sat; ?> /><label for="year_weekday_sat"><?php echo $weekdays[6]; ?></label>
    <input type="checkbox" id="year_weekday_sun" name="rec_year_weekday[]" value="sun" <?php echo $rec_year_weekdays_checked_sun; ?> /><label for="year_weekday_sun"><?php echo $weekdays[7]; ?></label>
    <?php echo $CALTEXT['IN']; ?>
  <select name="rec_year_option_month_weekday" size="1">
      <option <?php echo $rec_year_option_weekday_month_selected_1; ?> value="january"><?php echo $monthnames[1]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_2; ?> value="february"><?php echo $monthnames[2]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_3; ?> value="march"><?php echo $monthnames[3]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_4; ?> value="april"><?php echo $monthnames[4]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_5; ?> value="may"><?php echo $monthnames[5]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_6; ?> value="june"><?php echo $monthnames[6]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_7; ?> value="july"><?php echo $monthnames[7]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_8; ?> value="august"><?php echo $monthnames[8]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_9; ?> value="september"><?php echo $monthnames[9]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_10; ?> value="october"><?php echo $monthnames[10]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_11; ?> value="november"><?php echo $monthnames[11]; ?></option>
      <option <?php echo $rec_year_option_weekday_month_selected_12; ?> value="december"><?php echo $monthnames[12]; ?></option>
  </select>
</div>
<div class="field_line rec_exceptions procal_hidden clearfix">
    <label class="field_title" ><?php echo $CALTEXT['NOT_AT']; ?></label>
    <span>&nbsp;&nbsp;<input name="date_exclude1" id="date_exclude1" class="date-pick" value="<?php echo $rec_date_exclude1; ?>"/></span>
    <span>&nbsp;&nbsp;<input name="date_exclude2" id="date_exclude2" class="date-pick" value="<?php echo $rec_date_exclude2; ?>"/></span>
    <span>&nbsp;&nbsp;<input name="date_exclude3" id="date_exclude3" class="date-pick" value="<?php echo $rec_date_exclude3; ?>"/></span>
</div>
