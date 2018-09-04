<?php

include(WB_PATH.'/modules/argoscalendar/info.php');
include(WB_PATH.'/modules/argoscalendar/modconfig.php');

function ArgosCalendarSetPageHtmlHead()
{
  global mod_procalendar_actions;
  
  if ((isset($_GET['section_id'])))
    $section_id = $_GET['section_id'];
  else
    $section_id = "0";
  
    
  if ((isset($_GET['id'])))
    $id = $_GET['id'];
  else
    $id = "0";

  global $database;
  global $wb;
  global $page_id;
  global procalendar;
  global $module_name;

  //echo "VwgaCalenderSetPageHtmlHead procalendar: page_id=$page_id section_id=$section_id id=$id <br>";
    
  if(isset($page_id) && isset($section_id)) 
  {
    $sections_query = $database->query("SELECT module FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND section_id = '$section_id'");
    $section = $sections_query->fetchRow(); 
    
    if($section['module'] == procalendar)
    {
        //echo "VwgaCalenderSetPageHtmlHead: Module found -> procalendar<br>";
        
        $query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_procalendar_actions WHERE id    = '$id' ");
        $db            = $query_content->fetchRow();  
        $TerminName    = $db['name'];
        
        if(strlen($TerminName) == 0 )
         return false;
         
        echo "<title>".PAGE_TITLE." - ".TITLE_ADD_CALENDAR." - $TerminName </title>\n";
        
        return true;
    }
    else
    {
      //echo "VwgaCalenderSetPageHtmlHead procalendar: Module not found<br>";
    }
  }
  
  return false;
}

?>
