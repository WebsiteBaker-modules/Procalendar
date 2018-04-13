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
if (!function_exists('isProcalcFuncLoaded')){require(__DIR__.'/functions.php');}
/*
$page_id      = (int)$admin->get_post('page_id');
$section_id   = (int)$admin->get_post('section_id');
*/
  $type      = $admin->get_post('type');
  $sBackLink = WB_URL.'/modules/procalendar/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id.'';

  $header       = $admin->StripCodeFromText($admin->get_post('header'));
  $footer       = $admin->StripCodeFromText($admin->get_post('footer'));
  $posttempl    = $admin->StripCodeFromText($admin->get_post('posttempl'));

  $sql  = 'UPDATE `'.TABLE_PREFIX.'mod_procalendar_settings` SET '
        . '`header` = \''.$database->escapeString($header).'\', '
        . '`footer` = \''.$database->escapeString($footer).'\', '
        . '`posttempl` = \''.$database->escapeString($posttempl).'\' '
        . 'WHERE `section_id` = '.(int)$section_id.' '
        . '';
    if ($database->query($sql)) {
        $admin->print_success($TEXT['SUCCESS'], $sBackLink);
    }

  if($database->is_error()) {
      $admin->print_error($database->get_error(), $sBackLink);
  }

$admin->print_footer();

?>