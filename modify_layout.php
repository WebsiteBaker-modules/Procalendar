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
// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');


if (LANGUAGE_LOADED) {        // load languagepack
  if(file_exists(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php")) {    // if exist proper language mutation
    require_once(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php");    // load it
  } else {
    require_once(WB_PATH."/modules/procalendar/languages/EN.php");        // else use english
  }
}

$sql = "SELECT * FROM ".TABLE_PREFIX."mod_procalendar_settings WHERE section_id=$section_id ";
$db = $database->query($sql);

if ($db->numRows() > 0) {
   while ($rec = $db->fetchRow()) {
      $header			= $rec["header"];
	  $footer		 	= $rec["footer"];
      $posttempl		= $rec["posttempl"];
   }
}

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');
?>
<h2><?php echo $TEXT['TEMPLATE']; ?></h2>
<form name="modify_startday" method="post" action="<?php echo WB_URL; ?>/modules/procalendar/save_layout.php">
  <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
  <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
  <input type="hidden" name="type" value="layout">
  <table cellpadding="2" cellspacing="1" border="0" width="98%" class="customfields">
    <tr>
      <td valign="top"><?php echo $TEXT['HEADER']; ?>:</td>
      <td colspan="2">
        <textarea name="header"><?php echo str_replace($raw, $friendly, $header); ?></textarea>
      </td> 
    </tr>
     <tr>
      <td valign="top"><?php echo $TEXT['FOOTER']; ?>:</td>
      <td colspan="2">
        <textarea name="footer"><?php echo str_replace($raw, $friendly, $footer); ?></textarea>
      </td>
    </tr>    
	<tr>
    <tr>
      <td valign="top"><?php echo $TEXT['POST']; ?>:</td>
      <td colspan="2">
        <textarea name="posttempl" style="height:350px;"><?php echo str_replace($raw, $friendly, $posttempl); ?></textarea>
      </td>
    </tr>

      <td align="right" colspan="3"><input class="edit_button" type="submit" value="<?php echo $CALTEXT['SAVE']; ?>"></td>
    </tr>
  </table>
</form>

<br>
<input type="button" class="edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id"; ?>';" />
<?php

$admin->print_footer();

?>
