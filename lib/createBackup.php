<?php

if (!defined('SYSTEM_RUN')) {header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); echo '404 File not found'; flush(); exit;}

    if (!\function_exists( 'make_dir' ) ) { require($sAppPath.'/framework/functions.php');  }
    if (!defined('\backup\text\SETNAMES') && is_readable($sAddonPath.'/lib/upgradePlates.php')){require $sAddonPath.'/lib/upgradePlates.php';}

    function Export_Database(){;}

    $createBackup = (function($sTableFlags = \backup\text\FULL, $aTables=[],$sTablePrefix='', $sFileName='') use ($database,$sAddonPath){
//
        $sRetval = false;
        $aValues = [];
        $vars = ['`','`'];
        $sDate = date("Y-m-d");
        $sBackupName = (isset($sBackupName) ? $sBackupName.'_'.$sDate : $sFileName).'.php';

        if (!(\file_put_contents($sAddonPath.'/sql/'.$sBackupName, '-- <?php die(\'Access denied\');?>'.PHP_EOL.\backup\text\SETNAMES))){
            throw new \Exception (sprintf('%s '.PHP_EOL.'Couldn\'t create %s',\backup\text\SETNAMES, '/sql/'.$sBackupName));
        }
        $DbHandle = $database->DbHandle;
        $mysqli = new mysql($DbHandle);

        $queryTables = $mysqli->query('SHOW TABLES');
        while($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }

        $bCreateSelectedTables = (\is_array($aTables) && sizeof($aTables) ? true : false);
        if($bCreateSelectedTables) {
            $target_tables = array_intersect( $target_tables, $aTables);
        }

        foreach($target_tables as $table)
        {
            $iFlag = 15;
            $sTableTitle  = '';
            $sInsertDrop  = '';
            $sInsertTitle = '';

            $sData        = (!isset($sData) ?  '' : $sData);
// INSERT INTO
            if (($sTableFlags & \backup\text\DATA)==\backup\text\DATA){
                $sInsertTitle = sprintf(\backup\text\INSERTTITLE, $table);
            }
            if (($sTableFlags & \backup\text\FULL)==\backup\text\FULL){
                $sInsertTitle = sprintf(\backup\text\INSERTTITLE, $table);
            }
            $sGroup           = '';
            $result           = $mysqli->query('SELECT * FROM `'.$table.'`');
            $rows_num         = mysqli_affected_rows($DbHandle);
            $fields_amount    = mysqli_field_count($DbHandle);

            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter=0)
            {
                while($row = $mysqli->fetchRow(MYSQLI_BOTH))
                {
                    $sCaption = '';
                    if ($sGroup != $table){
// change to an associative array
                        $sCaption = PHP_EOL.$sInsertTitle;
                        $aNumeric = [];
                        for ($i=0; $i<=$fields_amount; $i++) {$aNumeric[]=$i;}
                        $aRows = \array_diff_key($row,$aNumeric);
                        $aFields = \array_keys($aRows);
// add backticks to column
                        $add_backticks =
                        \array_walk(
                            $aFields,
                            function (&$val, &$key) use ($vars) {
                              $val = $vars[1].$val.$vars[1];
                            });
// prepare column list to add to INSERT INTO
                        $sInserts = ''.\implode(', ', $aFields).') ';
                        $sGroup = $table;
                    }
                    if ($st_counter == 0 ) {
                        $sTableName = str_replace($sTablePrefix,'{TABLE_PREFIX}',$table);
#                        if ($st_counter%100 == 0 || $st_counter == 0 ) {
                        $insert = 'INSERT INTO `'.$sTableName.'` ('.$sInserts.'';
                    }
// VALUE data row
                    $sData .= $sCaption;
                    $sData .= $insert.'VALUES (';
                    for($j=0; $j<$fields_amount; $j++) {
                        $aFieldDetails = $result->fetch_field_direct($j);
                        $isInteger = \array_key_exists($aFieldDetails->type, \backup\text\NUMBERTYPES);
                        $row[$j] = \str_replace(["\n","\r"],["\\n","\\r"], \addslashes($row[$j]) );
                        if (!$isInteger) {
                            if (isset($row[$j])) {
                                $sData .= '\''.$row[$j].'\'';
                            } else {
                                $sData .= '\'\'';
                            }
                        } else {
                          if (isset($row[$j])){
                            $sData .= $row[$j];
                          } else {
                              $sData .= 0;
                          }
                        }
                        if ($j<($fields_amount-1)) {
                            $sData.= ', ';
                        }
                    }  // for($j=0; $j<$fields_amount; $j++)
                    $sData .=')';
#                        if ($st_counter+1==$rows_num){
                        if ((($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num){
                            $sData .= ';'.PHP_EOL;
                        } else {
                            $sData .= ';'.PHP_EOL;
                        }
                        $st_counter++;
                } // while row
            }
        } // foreach $table

    $content = $sData.PHP_EOL.'-- created '.date("Y-m-d H:i:s");
    if (!($sRetval = \file_put_contents($sAddonPath.'/sql/'.$sBackupName, $content, FILE_APPEND))){
        throw new \Exception (sprintf('Couldn\'t write %s ', '/sql/'.$sBackupName));
    }

        return $sRetval;
    });
