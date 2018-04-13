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

require (dirname(dirname(__DIR__ )).'/config.php');

$print_info_banner = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
if (!function_exists('edit_module_css')){include(WB_PATH .'/framework/module.functions.php');}

if (is_readable(__DIR__.'/languages/EN.php')) {require(__DIR__.'/languages/EN.php');}
if (is_readable(__DIR__.'/languages/'.DEFAULT_LANGUAGE.'.php')) {require(__DIR__.'/languages/'.DEFAULT_LANGUAGE.'.php');}
if (is_readable(__DIR__.'/languages/'.LANGUAGE.'.php')) {require(__DIR__.'/languages/'.LANGUAGE.'.php');}


    /**
     * Generates the complementary color for a given color
     *
     * @param $color
     * @return string
     */
    $complementary = (function ($color)
    {
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

$fillvalue = "";

$group_id = 0;
$group_id = intval(isset($_GET['group_id']) && is_numeric($_GET['group_id'])?  $_GET['group_id']:0);

// Added PCWacht
// moved to one place
// Fetch all settings from db
$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_procalendar_settings` '
. 'WHERE `section_id`='.$section_id.' ';
if (!$db = $database->query($sql)){}
$Sday=0;
$Utime =0;
$Uformat = '';
$Uifformat = '';

if ($db->numRows() > 0) {
   if ($rec = $db->fetchRow(MYSQLI_ASSOC)) {
      $startday    = $rec["startday"];
      $usetime     = $rec["usetime"];
      $onedate     = $rec["onedate"];
      $useformat   = $rec["useformat"];
      $useifformat = $rec["useifformat"];
   }
}
/*
    $template = new Template(dirname($admin->correct_theme_source('preferences.htt')));
    $template->set_file( 'page', 'preferences.htt' );
    $template->set_block( 'page', 'main_block', 'main' );
*/
?><div class="w3-row">
    <div class="w3-twothird w3-container">
        <form name="modify_startday" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_settings.php">
          <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
          <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
          <input type="hidden" name="type" value="startd">

          <h2><?php echo $CALTEXT['CAL-OPTIONS']; ?></h2>
          <table>
          <tbody>
            <tr>
              <td style="width: 160px;"><?php echo $CALTEXT['CAL-OPTIONS-STARTDAY'];?></td>
              <td>
              <select class="w3-select w3-border edit_select_short" name="startday" >
              <?php
              echo '<option value="0" ';
              if ($startday == 0)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-STARTDAY-1'].'</option>';
              echo '<option value="1" ';
              if ($startday == 1)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-STARTDAY-2'].'</option>';
              ?>
              </select></td>
            </tr>
<?php
              if (is_readable(__DIR__.'/lib/date_formats.php')){
                include( __DIR__.'/lib/date_formats.php' );
?>
            <tr>
              <td><?php echo $CALTEXT['CAL-OPTIONS-FORMAT'];?></td>
              <td>
              <select class="w3-select w3-border edit_select_short" name="useformat" >
<?php
              foreach( $DATE_FORMATS as $format => $title )
              {
                  $selected = '';
                  // Add's white-spaces (not able to be stored in array key)
                  $format = str_replace('|', ' ', $format);
                  $format = ($format != 'system_default' ? $format : 'system_default');
                  $selected = ($useformat == $format ? ' selected="selected"':'');
?>
                  <option value="<?php echo $format;?>" <?php echo $selected;?>><?php echo $title;?></option>
<?php } ?>
              </select>
              </td>
            </tr>
<?php } ?>

            <tr>
                <td><?php echo $CALTEXT['CAL-OPTIONS-USETIME'];?></td>
                <td>
                    <select class="w3-select w3-border edit_select_short" name="usetime" >
<?php
                    $selected = ($usetime == 0)? ' selected="selected"':'';
                    echo '<option value="0" '.$selected;
                    echo '>'.$CALTEXT['CAL-OPTIONS-USETIME-1'].'</option>'."\n";
                    echo '<option value="1" ';
                    if ($usetime == 1)
                      echo " selected='selected'";
                    echo ">".$CALTEXT['CAL-OPTIONS-USETIME-2'].'</option>'."\n";
?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $CALTEXT['CAL-OPTIONS-ONEDATE'];?></td>
                <td>
                    <select class="w3-select w3-border edit_select_short" name="onedate" >
<?php
                      echo '<option value="1" ';
                      if ($onedate == 1) {echo " selected='selected'";}
                      echo ">".$CALTEXT['CAL-OPTIONS-ONEDATE-1'].'</option>'."\n";
                      echo '<option value="0" ';
                      if ($onedate == 0) {echo " selected='selected'";}
                      echo ">".$CALTEXT['CAL-OPTIONS-ONEDATE-2'].'</option>'."\n";
?>
                    </select>
                </td>
            </tr>
            <tr style="line-height: 2.5;">
                <td>&nbsp;</td>
                <td><input style="min-width: 6.5em;" class="btn btn-default w3-blue-wb w3-round w3-hover-green w3-padding-4 edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>"></td>
            </tr>
          </tbody>
          </table>
        </form>
    </div>
    <div class="w3-rest w3-container">
        <h2><?php echo $CALTEXT['ADVANCED-SETTINGS']; ?></h2>
        <table >
          <tbody>
            <tr>
                <td>
                    <input type="button" value="<?php echo $CALTEXT['CUSTOMS']; ?>" class="edit_button w3-blue-wb w3-round w3-hover-green w3-padding-4"
                    onclick="window.location='<?php echo WB_URL; ?>/modules/procalendar/modify_customs.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>'"/>
                </td>
                <td>
                    <input type="button" value="<?php echo $TEXT['TEMPLATE']; ?>" class="edit_button w3-blue-wb w3-round w3-hover-green w3-padding-4"
                    onclick="window.location='<?php echo WB_URL; ?>/modules/procalendar/modify_layout.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>'"/>
                </td>
                <td>
                  <?php
                  if(function_exists('edit_module_css'))
                  {
                    edit_module_css(basename(__DIR__));
                  }
                  ?>
                </td>
            </tr>
          </tbody>
        </table>
    </div>
</div>

<div class="w3-container">
<form name="modify_eventgroup" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_settings.php">
  <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
  <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
  <input type="hidden" name="type" value="change_eventgroup">
    <table>
    <tr>
        <td colspan="2"><h2><?php echo $CALTEXT['CATEGORY-MANAGEMENT']; ?></h2></td>
    </tr>
    <tr>
      <td style="max-width: 30.25em;">
          <select class="w3-select w3-border w3-border edit_select_short" name="group_id" onchange="document.location.href='<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id&amp;group_id="?>'+this.value">
              <option value="0"><?php echo $CALTEXT['CHOOSE-CATEGORY']; ?></option>
<?php
          $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_procalendar_eventgroups` WHERE `section_id`='.$section_id.' ORDER BY `name` ASC ';
          if ($db = $database->query($sql)) {
            $Sday=0;
            $bgColor = '';
            $bghex   = '#ffffff';
            $vghex = $complementary($bghex);
            $bgdec   = '';
            $dayChecked = "";
            while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {
                  $selected = '';
//                echo '<option value="'.$rec['id'].'"';
                  if ((@$group_id == $rec['id'])) {
                    $selected = ' selected="selected"';
                    $fillvalue = $rec['name'];
                    $dayChecked  = $rec['format_days'] == 1 ? 'checked="checked"' : "";
                    $bghex = $rec['format'];
                    $vghex = $complementary($bghex);
                    $bgdec = (($rec['format'][0] === "#")?substr($rec['format'], 1):$rec['format']);
                    $bgColor = $rec['format'] == '' ? 'transparent' : $rec['format'];
                  }
//                echo ' style="border-bottom: 1px solid '.$bghex.';">'.$rec['name'].'  (id='.$rec['id'].')</option>';
?>
                <option style="border-bottom: 1px solid <?php echo $bghex;?>" value="<?php echo $rec['id'];?>" <?php echo $selected;?>><?php echo $rec['name']; ?> [<?php echo $rec['id'];?>]</option>
<?php
              } // end while
          }
?>
          </select>
        </td>
        <td>
            <input class="btn btn-default w3-blue-wb w3-round w3-hover-red w3-padding-4 edit_button" type="submit" name="delete" value=" <?php echo $CALTEXT['DELETE']; ?> " style="min-width: 6.5em; margin-left: 1.25em;">
        </td>
    </tr>
    <tr>
      <td style="white-space: nowrap;" class="w3-navbar">
          <div class="w3-navbar" style="margin-top: 0.55em;">
          <textarea id="js-color" class="w3-input w3-border jscolor" style="width: 100%;background-color:<?php echo $bgColor; ?>;resize: none; overflow: auto; color: <?php echo $vghex;?>;" maxlength="200" title="<?php echo $CALTEXT['FORMAT_ACTION']; ?>" name="group_name" rows="3" cols="1" ><?php echo $fillvalue; ?></textarea>
          </div>
      </td>
      <td>
          <input id="onFineChange" value="<?php echo $bgdec;?>" class="w3-badge w3-border-0 w3-pointer jscolor" style="width: 0.925em; background-image: url('themes/default/img/color.png'); background-repeat: no-repeat; background-position: center;" >
          <input class="btn btn-default w3-blue-wb w3-round w3-hover-green w3-padding-4 edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>" style="min-width: 6.5em;">
      </td>
    </tr>
    <tr>
    <td colspan="2"><output></output></td>
    </tr>
    <tr style="line-height: 3.5;">
        <td>
            <input class="w3-check w3-border" id="dayformat" type="checkbox" name="dayformat" value="1" <?php echo $dayChecked; ?>><label class="w3-label w3-text-blue-wb w3-padding" for="dayformat"><?php echo $CALTEXT['FORMAT_DAY']; ?></label>
        </td>
         <td>
            <input id="action_background" class="jscolor" type="hidden" name="action_background" value="<?php echo $bgdec; ?>">
        </td>
    </tr>
  </table>
</form>
</div>

<?php if ($print_info_banner){echo '</div>';} ?>
<div class="w3-row">
<input type="button" style="min-width: 8.5em;" class="btn btn-default w3-blue-wb w3-round w3-hover-green w3-padding-4 edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="window.location='<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" />
</div>
<script src="themes/default/js/jscolor.js"></script>
<script>

  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('js-color').addEventListener('onkeypress', function(e){
/*
      var text = document.querySelector('textarea#js-color').innerHTML;
      var ergebnis = 'This text has ' + text.length + ' characters.';
      document.querySelector('output').innerHTML += ergebnis + ' \n';
*/
    });

    document.getElementById('onFineChange').addEventListener('change', function(e){
    updateColor(this.jscolor)
})
function updateColor(jscolor) {
      // 'jscolor' instance can be used as a string
console.log(jscolor);
      document.getElementById('js-color').style.backgroundColor = '#' + jscolor
      document.getElementById('action_background').value = jscolor
}
});
</script>
<?php
$admin->print_footer();

// {onFineChange:'update(this)'}