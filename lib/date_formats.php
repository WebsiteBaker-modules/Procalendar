<?php
/**
 *
 * @category        admin
 * @package         interface
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: date_formats.php 2 2017-07-02 15:14:29Z Manuela $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb/2.10.x/branches/main/admin/interface/date_formats.php $
 * @lastmodified    $Date: 2017-07-02 17:14:29 +0200 (So, 02. Jul 2017) $
 *
 * Date format list file
 * This file is used to generate a list of date formats for the user to select
 *
 */


// Create array
$DATE_FORMATS = [];

// Get the current time (in the users timezone if required)
$actual_time = time()+ ((isset($user_time) && $user_time == true) ? TIMEZONE : DEFAULT_TIMEZONE);

// Add values to list

$DATE_FORMATS['dd.mm.yyyy'] = gmdate('d.m.Y', $actual_time);
$DATE_FORMATS['dd-mm-yyyy'] = gmdate('d-m-Y', $actual_time);
$DATE_FORMATS['dd/mm/yyyy'] = gmdate('d/m/Y', $actual_time);
$DATE_FORMATS['dd mm yyyy'] = gmdate('d m Y', $actual_time);
$DATE_FORMATS['mm.dd.yyyy'] = gmdate('m.d.Y', $actual_time);
$DATE_FORMATS['mm. dd. yyyy'] = gmdate('m. d. Y', $actual_time);
$DATE_FORMATS['mm-dd-yyyy'] = gmdate('m-d-Y', $actual_time);
$DATE_FORMATS['mm/dd/yyyy'] = gmdate('m/d/Y', $actual_time);
$DATE_FORMATS['mm dd yyyy'] = gmdate('m d Y', $actual_time);
$DATE_FORMATS['yyyy.mm.dd'] = gmdate('Y.m.d', $actual_time);
$DATE_FORMATS['yyyy-mm-dd'] = gmdate('Y-m-d', $actual_time);
$DATE_FORMATS['yyyy/mm/dd'] = gmdate('Y/m/d', $actual_time);
$DATE_FORMATS['yyyy mm dd'] = gmdate('Y m d', $actual_time);


// Reverse array so "System Default" is at the top
$DATE_FORMATS = array_reverse($DATE_FORMATS, true);
