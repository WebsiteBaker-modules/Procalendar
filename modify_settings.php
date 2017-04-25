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
include_once(WB_PATH .'/framework/module.functions.php');

if (LANGUAGE_LOADED) { // load languagepack
  if(file_exists(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php")) {    // if exist proper language mutation
    require_once(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php");    // load it
  } else {
    require_once(WB_PATH."/modules/procalendar/languages/EN.php");        // else use english
  }
}
$fillvalue = "";

$group_id = 0;
if (isset($_GET['group_id']) && is_numeric($_GET['group_id']))  $group_id = $_GET['group_id'];


// Added PCWacht
// moved to one place
// Fetch all settings from db
$sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_settings WHERE section_id=$section_id ";
$db = $database->query($sql);
$Sday=0;
$Utime =0;
$Uformat = '';
$Uifformat = '';

if ($db->numRows() > 0) {
   while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {
      $startday    = $rec["startday"];
      $usetime     = $rec["usetime"];
      $onedate     = $rec["onedate"];
      $useformat   = $rec["useformat"];
      $useifformat = $rec["useifformat"];
   }
}
?>
<table style="width: 99%; border-collapse: collapse; ">
    <tr>
      <td width="70%" valign="top">
        <form name="modify_startday" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_settings.php">
          <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
          <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
          <input type="hidden" name="type" value="startd">

          <h2><?php echo $CALTEXT['CAL-OPTIONS']; ?></h2>
          <table>
            <tr>
              <td width="160"><?php echo $CALTEXT['CAL-OPTIONS-STARTDAY'];?></td>
              <td valign="top"><select class="edit_select_short" name="startday" >
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
            <tr>
              <td><?php echo $CALTEXT['CAL-OPTIONS-FORMAT'];?></td>
              <td valign="top"><select class="edit_select_short" name="useformat" >
                <option value="dd.mm.yyyy" <?php if($useformat == 'dd.mm.yyyy') { echo ' selected="selected"'; } ?>>dd.mm.yyyy</option>
                <option value="dd-mm-yyyy" <?php if($useformat == 'dd-mm-yyyy') { echo ' selected="selected"'; } ?>>dd-mm-yyyy</option>
                <option value="dd/mm/yyyy" <?php if($useformat == 'dd/mm/yyyy') { echo ' selected="selected"'; } ?>>dd/mm/yyyy</option>
                <option value="dd mm yyyy" <?php if($useformat == 'dd mm yyyy') { echo ' selected="selected"'; } ?>>dd mm yyyy</option>
                <option value="mm.dd.yyyy" <?php if($useformat == 'mm.dd.yyyy') { echo ' selected="selected"'; } ?>>mm.dd.yyyy</option>
                <option value="mm. dd. yyyy" <?php if($useformat == 'mm. dd. yyyy') { echo ' selected="selected"'; } ?>>mm. dd. yyyy</option>
                <option value="mm-dd-yyyy" <?php if($useformat == 'mm-dd-yyyy') { echo ' selected="selected"'; } ?>>mm-dd-yyyy</option>
                <option value="mm/dd/yyyy" <?php if($useformat == 'mm/dd/yyyy') { echo ' selected="selected"'; } ?>>mm/dd/yyyy</option>
                <option value="mm dd yyyy" <?php if($useformat == 'mm dd yyyy') { echo ' selected="selected"'; } ?>>mm dd yyyy</option>
                <option value="yyyy.mm.dd" <?php if($useformat == 'yyyy.mm.dd') { echo ' selected="selected"'; } ?>>yyyy.mm.dd</option>
                <option value="yyyy-mm-dd" <?php if($useformat == 'yyyy-mm-dd') { echo ' selected="selected"'; } ?>>yyyy-mm-dd</option>
                <option value="yyyy/mm/dd" <?php if($useformat == 'yyyy/mm/dd') { echo ' selected="selected"'; } ?>>yyyy/mm/dd</option>
                <option value="yyyy mm dd" <?php if($useformat == 'yyyy mm dd') { echo ' selected="selected"'; } ?>>yyyy mm dd</option>
              </select></td>
            </tr>
            <tr>
              <td><?php echo $CALTEXT['CAL-OPTIONS-USETIME'];?></td>
              <td valign="top"><select class="edit_select_short" name="usetime" >
              <?php
              echo '<option value="0" ';
              if ($usetime == 0)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-USETIME-1'].'</option>';
              echo '<option value="1" ';
              if ($usetime == 1)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-USETIME-2'].'</option>';
              ?>
              </select></td>
            </tr>
            <tr>
              <td><?php echo $CALTEXT['CAL-OPTIONS-ONEDATE'];?></td>
              <td valign="top"><select class="edit_select_short" name="onedate" >
              <?php
              echo '<option value="0" ';
              if ($onedate == 0)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-ONEDATE-1'].'</option>';
              echo '<option value="1" ';
              if ($onedate == 1)
                echo " selected='selected'";
              echo ">".$CALTEXT['CAL-OPTIONS-ONEDATE-2'].'</option>';
              ?>
              </select></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input class="edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>"></td>
            </tr>
          </table>
        </form>
    </td>
    <td width="30%" valign="top" align="right">
        <h2><?php echo $CALTEXT['ADVANCED-SETTINGS']; ?></h2>
        <table >
            <tr>
                <td valign="top">
                    <input type="button" value="<?php echo $CALTEXT['CUSTOMS']; ?>" class="edit_button"
                    onclick="window.location='<?php echo WB_URL; ?>/modules/procalendar/modify_customs.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>'"/>
                </td>
                <td valign="top">
                    <input type="button" value="<?php echo $TEXT['TEMPLATE']; ?>" class="edit_button"
                    onclick="window.location='<?php echo WB_URL; ?>/modules/procalendar/modify_layout.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>'"/>
                </td>
                <td valign="top">
                  <?php
                  if(function_exists('edit_module_css'))
                  {
                    edit_module_css('procalendar');
                  }
                  ?>
                </td>
            </tr>
            <tr>
                <td valign="top" align="left" colspan="3">
                    <br />
                    <h2><?php echo $CALTEXT['SUPPORT_INFO']; ?></h2>
                    <?php echo $CALTEXT['SUPPORT_INFO_INTRO']; ?>
                    <a href="<?php if (LANGUAGE_LOADED) {
                                if(file_exists(WB_PATH."/modules/procalendar/languages/support-".LANGUAGE.".php")) {
                                echo(WB_URL."/modules/procalendar/languages/support-".LANGUAGE.".php?page_id=$page_id&amp;section_id=$section_id");
                            } else {
                                echo(WB_URL."/modules/procalendar/languages/support-EN.php?page_id=$page_id&amp;section_id=$section_id");
                            }
                        }
                        ?>">
                    <?php echo $CALTEXT['SUPPORT_INFO']; ?></a>.
                </td>
            </tr>
        </table>
    </td>
    </tr>
</table>

<br /><br />

<form name="modify_eventgroup" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_settings.php">
  <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
  <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
  <input type="hidden" name="type" value="change_eventgroup">
    <table style="width: 450px;">
    <tr>
        <td colspan="2"><h2><?php echo $CALTEXT['CATEGORY-MANAGEMENT']; ?></h2></td>
    </tr>
    <tr>
      <td>
          <select class="edit_select_short" name="group_id" onchange="document.location.href='<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id&amp;group_id="?>'+this.value">
              <option value="0"><?php echo $CALTEXT['CHOOSE-CATEGORY']; ?></option>
              <?php
          $bgColor = '';
          $bghex   = '';
          $sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_eventgroups WHERE section_id=$section_id ORDER BY name ASC ";
          $db = $database->query($sql);
          $Sday=0;
          $dayChecked = "";
         if ($db->numRows() > 0) {
            while ($rec = $db->fetchRow(MYSQLI_ASSOC)) {
                echo "<option value='".$rec["id"]."'";
                  if (isset($group_id) AND ($group_id == $rec['id'])) {
                    echo " selected='selected'";
                    $fillvalue = $rec['name'];
                    $bghex = $rec['format'];
                    $bgColor = $rec['format'] == "" ? "background:#ffffff" : "background:".$rec['format'];
                    $dayChecked  = $rec['format_days'] == 1 ? 'checked="checked"' : "";
                  }
                echo ">".$rec["name"]."  (id=".$rec['id'].")</option>";
              }
          }
          ?> </select>
        </td>
      <td valign="top" align="right"><input class="edit_button" type="submit" name="delete" value="<?php echo $CALTEXT['DELETE']; ?>"></td>
    </tr>
    <tr>
      <td><input class="edit_field_short color" style="<?php echo $bgColor; ?>;" data-hex="true" type="text" title="<?php echo $CALTEXT['FORMAT_ACTION']; ?>" value="<?php echo $fillvalue; ?>" name="group_name"></td>
      <td valign="top" align="right"><input class="edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>"></td>
    </tr>
    <tr>
        <td><input id="dayformat" type="checkbox" name="dayformat" value="1" <?php echo $dayChecked; ?>><label for="dayformat"><?php echo $CALTEXT['FORMAT_DAY']; ?></label>
        </td>
         <td>
        </td>
    </tr>
  </table>
  <input type="hidden" name="action_background" value="<?php echo $bghex; ?>">
</form>
<br />
<br />
<br />
<input type="button" class="edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="window.location='<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" />
<?php
$admin->print_footer();

