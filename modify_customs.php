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

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');


if (LANGUAGE_LOADED) {        // load languagepack
  if(file_exists(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php")) {    // if exist proper language mutation
    require_once(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php");    // load it
  } else {
    require_once(WB_PATH."/modules/procalendar/languages/EN.php");        // else use english
  }
}
$fillvalue = "";

// Added PCWacht
// moved to one place
// Fetch needed settings from db
$sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_settings WHERE section_id=$section_id ";
$db = $database->query($sql);

if ($db->numRows() > 0) {
   while ($rec = $db->fetchRow()) {
      $resize			= $rec["resize"];
	  $usecustom1		= $rec["usecustom1"];
      $custom1			= $rec["custom1"];
      $customtemplate1	= $rec["customtemplate1"];
      $usecustom2		= $rec["usecustom2"];
      $custom2			= $rec["custom2"];
      $customtemplate2	= $rec["customtemplate2"];
      $usecustom3		= $rec["usecustom3"];
      $custom3			= $rec["custom3"];
      $customtemplate3	= $rec["customtemplate3"];
      $usecustom4		= $rec["usecustom4"];
      $custom4			= $rec["custom4"];
      $customtemplate4	= $rec["customtemplate4"];
      $usecustom5		= $rec["usecustom5"];
      $custom5			= $rec["custom5"];
      $customtemplate5	= $rec["customtemplate5"];
      $usecustom6		= $rec["usecustom6"];
      $custom6			= $rec["custom6"];
      $customtemplate6	= $rec["customtemplate6"];
      $usecustom7		= $rec["usecustom7"];
      $custom7			= $rec["custom7"];
      $customtemplate7	= $rec["customtemplate7"];
      $usecustom8		= $rec["usecustom8"];
      $custom8			= $rec["custom8"];
      $customtemplate8	= $rec["customtemplate8"];
      $usecustom9		= $rec["usecustom9"];
      $custom9			= $rec["custom9"];
      $customtemplate9	= $rec["customtemplate9"];

   }
}
$CTypes['0'] = $CALTEXT['CUSTOM_OPTIONS-0'];
$CTypes['1'] = $CALTEXT['CUSTOM_OPTIONS-1'];
$CTypes['2'] = $CALTEXT['CUSTOM_OPTIONS-2'];
$CTypes['3'] = $CALTEXT['CUSTOM_OPTIONS-3'];
$CTypes['4'] = $CALTEXT['CUSTOM_OPTIONS-4'];

?>

<form name="modify_startday" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_customs.php">
  <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
  <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
  <input type="hidden" name="type" value="customs">
  <table cellpadding="2" cellspacing="1" border="0" width="98%" class="customfields">
    <tr>
      <td valign="top" colspan="3">
      	<h2><?php echo $CALTEXT['RESIZE_IMAGES']; ?></h2>
      </td>
    </tr>
   
    <?php if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */ ?>
    <tr>
      <td><?php echo $TEXT['RESIZE_IMAGE_TO']; ?>:</td>
      <td colspan="2" class="setting_value">
        <select name="resize" style="width: 25%;">
          <option value=""><?php echo $TEXT['NONE']; ?></option>
            <?php
            $SIZES['50'] = 'Max. 50px';
            $SIZES['75'] = 'Max. 75px';
            $SIZES['100'] = 'Max. 100px';
            $SIZES['125'] = 'Max. 125px';
            $SIZES['150'] = 'Max. 150px';
            $SIZES['175'] = 'Max. 175px';
            $SIZES['200'] = 'Max. 200px';
            $SIZES['225'] = 'Max. 225px';
            $SIZES['250'] = 'Max. 250px';
            foreach($SIZES AS $size => $size_name) {
              if($resize == $size) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$size.'"'.$selected.'>'.$size_name.'</option>';
            }
            ?>
        </select>
      </td>      
    </tr>
    <?php } ?>   
    <tr>
      <td valign="top" colspan="3">
      	<br /><h2><?php echo $CALTEXT['CUSTOMS']; ?></h2>
      </td>
    </tr> 
    <tr>
      <td width="12%">&nbsp;</td>
      <td width="15%"><strong><?php echo $CALTEXT['USE_CUSTOM'];?></strong></td>
      <td><strong><?php echo $CALTEXT['CUSTOM_NAME'];?></strong></td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 1</strong></td>
      <td>
        <select name="usecustom1" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom1 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom1" class="edit_field_short" type="text" value="<?php echo $custom1; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate1" rows="10" cols="1"><?php echo $customtemplate1; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 2</strong></td>
      <td>
        <select name="usecustom2" style="width:98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom2 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom2" class="edit_field_short" type="text" value="<?php echo $custom2; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate2" rows="10" cols="1"><?php echo $customtemplate2; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 3</strong></td>
      <td>
        <select name="usecustom3" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom3 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom3" class="edit_field_short" type="text" value="<?php echo $custom3; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate3" rows="10" cols="1"><?php echo $customtemplate3; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 4</strong></td>
      <td>
        <select name="usecustom4" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom4 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom4" class="edit_field_short" type="text" value="<?php echo $custom4; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate4" rows="10" cols="1"><?php echo $customtemplate4; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 5</strong></td>
      <td>
        <select name="usecustom5" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom5 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom5" class="edit_field_short" type="text" value="<?php echo $custom5; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate5" rows="10" cols="1"><?php echo $customtemplate5; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 6</strong></td>
      <td>
        <select name="usecustom6" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom6 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom6" class="edit_field_short" type="text" value="<?php echo $custom6; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate6" rows="10" cols="1"><?php echo $customtemplate6; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 7</strong></td>
      <td>
        <select name="usecustom7" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom7 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom7" class="edit_field_short" type="text" value="<?php echo $custom7; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate7" rows="10" cols="1"><?php echo $customtemplate7; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 8</strong></td>
      <td>
        <select name="usecustom8" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom8 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom8" class="edit_field_short" type="text" value="<?php echo $custom8; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate8" rows="10" cols="1"><?php echo $customtemplate8; ?></textarea>
      </td>
    </tr>  
    <tr>
      <td><strong><?php echo $CALTEXT['CUSTOM'];?> 9</strong></td>
      <td>
        <select name="usecustom9" style="width: 98%;">
          <?php
            foreach($CTypes AS $type => $type_name) {
              if($usecustom9 == $type) { $selected = ' selected="selected"'; } else { $selected = ''; }
              echo '<option value="'.$type.'"'.$selected.'>'.$type_name.'</option>';
            }
          ?>
        </select>
      </td>
      <td valign="top"><input name="custom9" class="edit_field_short" type="text" value="<?php echo $custom9; ?>"></td>
    </tr>
    <tr>
      <td valign="top"><?php echo $CALTEXT['CUSTOM_TEMPLATE']; ?></td>
      <td colspan="2" class="setting_value">
        <textarea name="customtemplate9" rows="10" cols="1"><?php echo $customtemplate9; ?></textarea>
      </td>
    </tr> 
    <tr>
      <td align="right" colspan="3"><input class="edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>"></td>
    </tr>
  </table>
</form>

<br>
<input type="button" class="edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id"; ?>';" />
<?php
$admin->print_footer();
?>
