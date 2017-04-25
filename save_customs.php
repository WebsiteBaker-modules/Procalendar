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

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
/**

 * $page_id      = $admin->get_post('page_id');
 * $section_id   = $admin->get_post('section_id');
 */
$sBackLink = WB_URL.'/modules/'.basename(__DIR__).'/modify_settings.php?page_id='.(int)$page_id.'&amp;section_id='.(int)$section_id;

$usecustom1           = ($admin->get_post('usecustom1'));
$custom1              = trim($admin->get_post('custom1'));
$customtemplate1      = ($admin->get_post('customtemplate1'));
$usecustom2           = ($admin->get_post('usecustom2'));
$custom2              = trim($admin->get_post('custom2')).'';
$customtemplate2      = $admin->get_post('customtemplate2');
$usecustom3           = $admin->get_post('usecustom3');
$custom3              = trim($admin->get_post('custom3')).'';
$customtemplate3      = $admin->get_post('customtemplate3');
$usecustom4           = ($admin->get_post('usecustom4'));
$custom4              = trim($admin->get_post('custom4')).'';
$customtemplate4      = $admin->get_post('customtemplate4');
$usecustom5           = ($admin->get_post('usecustom5'));
$custom5              = trim($admin->get_post('custom5')).'';
$customtemplate5      = $admin->get_post('customtemplate5');
$usecustom6           = $admin->get_post('usecustom6');
$custom6              = trim($admin->get_post('custom6')).'';
$customtemplate6      = $admin->get_post('customtemplate6');
$usecustom7           = $admin->get_post('usecustom7');
$custom7              = trim($admin->get_post('custom7')).'';
$customtemplate7      = $admin->get_post('customtemplate7');
$usecustom8           = $admin->get_post('usecustom8');
$custom8              = trim($admin->get_post('custom8')).'';
$customtemplate8      = $admin->get_post('customtemplate8');
$usecustom9           = $admin->get_post('usecustom9');
$custom9              = trim($admin->get_post('custom9')).'';
$customtemplate9      = $admin->get_post('customtemplate9');
$resize               = $admin->get_post('resize');

    $sql  = ''
          . 'UPDATE `'.TABLE_PREFIX.'mod_procalendar_settings` SET'
          . '`usecustom1`=\''.$database->escapeString($usecustom1).'\','
          . '`customtemplate1`=\''.$database->escapeString($customtemplate1).'\','
          . '`custom1`=\''.$database->escapeString($custom1).'\','
          . '`usecustom2`=\''.$database->escapeString($usecustom2).'\','
          . '`customtemplate2`=\''.$database->escapeString($customtemplate2).'\','
          . '`custom2`=\''.$database->escapeString($custom2).'\','
          . '`usecustom3`=\''.$database->escapeString($usecustom3).'\','
          . '`customtemplate3`=\''.$database->escapeString($customtemplate3).'\','
          . '`custom3`=\''.$database->escapeString($custom3).'\','
          . '`usecustom4`=\''.$database->escapeString($usecustom4).'\','
          . '`customtemplate4`=\''.$database->escapeString($customtemplate4).'\','
          . '`custom4`=\''.$database->escapeString($custom4).'\','
          . '`usecustom5`=\''.$database->escapeString($usecustom5).'\','
          . '`customtemplate5`=\''.$database->escapeString($customtemplate5).'\','
          . '`custom5`=\''.$database->escapeString($custom5).'\','
          . '`usecustom6`=\''.$database->escapeString($usecustom6).'\','
          . '`customtemplate6`=\''.$database->escapeString($customtemplate6).'\','
          . '`custom6`=\''.$database->escapeString($custom6).'\','
          . '`usecustom7`=\''.$database->escapeString($usecustom7).'\','
          . '`customtemplate7`=\''.$database->escapeString($customtemplate7).'\','
          . '`custom7`=\''.$database->escapeString($custom7).'\','
          . '`usecustom8`=\''.$database->escapeString($usecustom8).'\','
          . '`customtemplate8`=\''.$database->escapeString($customtemplate8).'\','
          . '`custom8`=\''.$database->escapeString($custom8).'\','
          . '`usecustom9`=\''.$database->escapeString($usecustom9).'\','
          . '`customtemplate9`=\''.$database->escapeString($customtemplate9).'\','
          . '`custom9`=\''.$database->escapeString($custom9).'\','
          . '`resize`=\''.$database->escapeString($resize).'\' '
          . 'WHERE `section_id`='.(int)$section_id.'';

if (!$database->query($sql)||$database->is_error())
{
  $admin->print_error($database->get_error(), $sBackLink);
} else {
  $admin->print_success($TEXT['SUCCESS'], $sBackLink);
}

$admin->print_footer();

