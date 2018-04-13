<?php
/*
 * CMS module: ProCalendar
 * For more information see info.php
 *
 * upgrade.php provides the functions for an upgrade from an older version of the module.
 * This file is (c) 2009 Website Baker Project <http://www.websitebaker.org/>
 * Improvements are copyright (c) 2009-2011 Frank Heyne
*/

if (defined('SYSTEM_RUN')) {

    $msg = [];
    $sErrorMsg = '';
    $sResult = '';
#    $oDb = ( $GLOBALS['database'] ?: null );
    $sAppPath   = str_replace(DIRECTORY_SEPARATOR,'/', WB_PATH);
    $sAddonPath = str_replace(DIRECTORY_SEPARATOR,'/', __DIR__);

    $globalStarted = preg_match('/upgrade\-script\.php$/', $_SERVER["SCRIPT_NAME"]);

    if (!function_exists('Export_Database')) {require ($sAddonPath.'/lib/createBackup.php');}

    $sInstallStruct = $sAddonPath.'/install-struct.php';

    try {

    if ( !is_readable($sInstallStruct)) {
        $sErrorMsg .= \sprintf('<b>\'missing or not readable file ['.basename($sInstallStruct).']\'</b> ').PHP_EOL;
        $iErr = true;
    }
/*
        // create tables from sql dump file
        if (\is_readable($sAddonPath.'/install-struct.php')) {
            $database->SqlImport($sAddonPath.'/install-struct.php', TABLE_PREFIX, __FILE__ );
        }
*/
        // Make calendar images directory
        if (!\is_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar/')){ make_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar/');}

/* ----------------------------------------------------------------------------------- ----*/
        // try to create table if not exists
#        $database->SqlImport($sInstallStruct, TABLE_PREFIX, true );
        $sBackupFilename = '.'.DB_NAME.\date("_Y-m-d").date('_d'); // _His
        if ($Msg  = $createBackup(
                      \backup\text\DATA,
                      [
                        TABLE_PREFIX.'mod_procalendar_actions',
                        TABLE_PREFIX.'mod_procalendar_eventgroups',
                        TABLE_PREFIX.'mod_procalendar_settings',
                        ],
                      TABLE_PREFIX,
                      $sBackupFilename)
            ) {
                $sResult .= \sprintf('Dump %s %s successfully created', $Msg, $sBackupFilename).PHP_EOL;
        }

// only delete old file if new format extist
        if (\is_readable($sAddonPath.'/install-struct.php') && \is_readable($sAddonPath.'/install-struct.sql')){
          \unlink($sAddonPath.'/install-struct.sql');
        }
        if (\is_readable($sAddonPath.'/install-data.php') && \is_readable($sAddonPath.'/install-data.sql')){
          \unlink($sAddonPath.'/install-data.sql');
        }
// clear image path from color border lines
        if (\is_readable($sAddonPath.'/images') && !rm_full_dir($sAddonPath.'/images', true)){;}

        if (!\is_readable($sAddonPath.'/images')){make_dir($sAddonPath.'/images');}

        if (!empty($sErrorMsg)) {
            throw new \Exception (\sprintf('%s', $sErrorMsg));
        }
// show by upgrade-script.php, if listed in addons whitelist
        if ($globalStarted) {
            $sResult .= \sprintf('Upgrade %s successfully finished', $sBackupFilename).PHP_EOL;
            echo preg_replace('/[\n\r]/u', '',nl2br($sResult, !defined('XHTML'))).PHP_EOL;
        }
    } catch (\Exception $ex) {
        $sErrMsg = xnl2br(\sprintf('[%d] %s', $ex->getLine(), $ex->getMessage()));
        echo $sErrMsg.'<br />';
    }
}

