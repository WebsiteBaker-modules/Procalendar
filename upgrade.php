<?php
/*
 * CMS module: MPForm
 * For more information see info.php
 *
 * upgrade.php provides the functions for an upgrade from an older version of the module.
 * This file is (c) 2009 Website Baker Project <http://www.websitebaker.org/>
 * Improvements are copyright (c) 2009-2011 Frank Heyne
*/

if (defined('WB_URL')) {

    // create tables from sql dump file
    if (is_readable(__DIR__.'/install-struct.sql')) {
        $database->SqlImport(__DIR__.'/install-struct.sql', TABLE_PREFIX, __FILE__ );
    }

    // Make calendar images directory
    if (!is_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar/')){ make_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar/');}
}

/*
// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

echo '<div class="info"><B>Updating database for module: procalendar</B></div>';

// adding fields new in version 1.2:
//get settings table to see what needs to be created
$table = $database->query("DESCRIBE `".TABLE_PREFIX."mod_procalendar_eventgroups` 'format'");
// If not already there, add new fields to the existing settings table
echo'<div class="info"><b>Adding new fields to the settings table</b></div>';

if (!$table->numRows()){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_eventgroups` ADD `format` VARCHAR(255) NOT NULL default '' AFTER `name`";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `format` successfully</div>';
    }
}

$table = $database->query("DESCRIBE `".TABLE_PREFIX."mod_procalendar_eventgroups` 'format_days'");
if (!$table->numRows()){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_eventgroups` ADD `format_days` INT NOT NULL default '0' AFTER `format`";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `format_days` successfully</div>';
    }
}

$table=$database->query("SELECT * FROM `".TABLE_PREFIX."mod_procalendar_actions`");
$fields = $table->fetchRow();

if (!isset($fields['rec_id'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_id` INT NOT NULL default '0'";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_id` successfully</div>';
    }
}

if (!isset($fields['rec_day'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_day` VARCHAR(255) NOT NULL";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_day` successfully</div>';
    }
}

if (!isset($fields['rec_week'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_week` VARCHAR(255) NOT NULL";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_week` successfully</div>';
    }
}

if (!isset($fields['rec_month'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_month` VARCHAR(255) NOT NULL";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_month` successfully</div>';
    }
}

if (!isset($fields['rec_year'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_year` VARCHAR(255) NOT NULL";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_year` successfully</div>';
    }
}

if (!isset($fields['rec_count'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_count` SMALLINT NOT NULL default '0'";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_count` successfully</div>';
    }
}

if (!isset($fields['rec_exclude'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_procalendar_actions` ADD `rec_exclude` VARCHAR(255) NOT NULL";
    $database->query($qs);
    if($database->is_error()) {
        echo '<div class="warning">'.($database->get_error()).'</div><br />';
    } else {
        echo '<div class="info">Added new field `rec_exclude` successfully</div>';
    }
}
$sql = '
    UPDATE
        `wb_efgmod_procalendar_actions`
    SET
        `public_stat` = `public_stat`-3
    WHERE
        `public_stat` > 3
';
    $database->query($sql);

echo '<div class="info"><B>Module proclendar updated to version: 1.3.8-dev</B></div>';
*/