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


if (defined('WB_URL')) {
  
  $database->query("DROP TABLE IF EXISTS ".TABLE_PREFIX."mod_procalendar_settings");
  $database->query("DROP TABLE IF EXISTS ".TABLE_PREFIX."mod_procalendar_actions");
	$database->query("DROP TABLE IF EXISTS ".TABLE_PREFIX."mod_procalendar_eventgroups");

  $database->query("CREATE TABLE ".TABLE_PREFIX."mod_procalendar_settings (
      section_id INT NOT NULL,
      page_id INT NOT NULL,
      settings TEXT,
      startday INT default '0',
      onedate INT default '0',
      usetime INT default '0',
      useformat VARCHAR(15) NOT NULL,
      useifformat VARCHAR(15) NOT NULL,
      usecustom1 INT default '0',
      custom1 TEXT NOT NULL,
      customtemplate1 TEXT,
      usecustom2 INT default '0',
      custom2 TEXT NOT NULL,
      customtemplate2 TEXT,
      usecustom3 INT default '0',
      custom3 TEXT NOT NULL,
      customtemplate3 TEXT,
      usecustom4 INT default '0',
      custom4 TEXT NOT NULL,
      customtemplate4 TEXT,
      usecustom5 INT default '0',
      custom5 TEXT NOT NULL,
      customtemplate5 TEXT,
      usecustom6 INT default '0',
      custom6 TEXT NOT NULL,
      customtemplate6 TEXT,
      usecustom7 INT default '0',
      custom7 TEXT NOT NULL,
      customtemplate7 TEXT,
      usecustom8 INT default '0',
      custom8 TEXT NOT NULL,
      customtemplate8 TEXT,
      usecustom9 INT default '0',
      custom9 TEXT NOT NULL,
      customtemplate9 TEXT,
      resize INT default '0',
      header TEXT NOT NULL,
      footer TEXT NOT NULL,
      posttempl TEXT NOT NULL,
      PRIMARY KEY (section_id))");
  
  $database->query("CREATE TABLE ".TABLE_PREFIX."mod_procalendar_actions (
      id INT NOT NULL AUTO_INCREMENT,
      section_id INT NOT NULL,
      page_id INT NOT NULL,
      owner INT NOT NULL,
      date_start DATE NOT NULL,
      time_start TIME default NULL,
      date_end DATE default NULL,
      time_end TIME default NULL,
      acttype TINYINT(4) NOT NULL,
      name VARCHAR(255) NOT NULL,
      description TEXT default NULL,
      custom1 TEXT default NULL,
      custom2 TEXT default NULL,
      custom3 TEXT default NULL,
      custom4 TEXT default NULL,
      custom5 TEXT default NULL,
      custom6 TEXT default NULL,
      custom7 TEXT default NULL,
      custom8 TEXT default NULL,
      custom9 TEXT default NULL,
      public_stat TINYINT(4) NOT NULL default '0',
      rec_id INT NOT NULL default '0',
      rec_day VARCHAR(255) NOT NULL,
      rec_week VARCHAR(255) NOT NULL,
      rec_month VARCHAR(255) NOT NULL,
      rec_year VARCHAR(255) NOT NULL,
      rec_count SMALLINT NOT NULL default '0',
      rec_exclude VARCHAR(255) NOT NULL,
      PRIMARY KEY (id))");

	$database->query("CREATE TABLE ".TABLE_PREFIX."mod_procalendar_eventgroups (
      id INT NOT NULL AUTO_INCREMENT,
      section_id INT NOT NULL,
      name VARCHAR(255) NOT NULL default '',
      format VARCHAR(255) NOT NULL default '',
      format_days INT NOT NULL default '0',
      PRIMARY KEY (id))");
			 
        
	// Insert info into the search table
  // Module query info
  $field_info = array();
  $field_info['page_id'] = 'page_id';
  $field_info['title'] = 'page_title';
  $field_info['link'] = 'link';
  $field_info['description'] = 'description';
  $field_info['modified_when'] = 'modified_when';
  $field_info['modified_by'] = 'modified_by';
  $field_info = serialize($field_info);
  $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'procalendar', '$field_info')");
  // Query start
  $query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,  [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_procalendar_actions, [TP]pages WHERE ";
  $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'procalendar')");
  // Query body
  $query_body_code = "
  [TP]pages.page_id    = [TP]mod_procalendar_actions.page_id AND [TP]mod_procalendar_actions.name LIKE \'%[STRING]%\'
  OR [TP]pages.page_id = [TP]mod_procalendar_actions.page_id AND [TP]mod_procalendar_actions.description LIKE \'%[STRING]%\'";
  $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'procalendar')");
  // Query end
  $query_end_code = ""; 
  $database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'procalendar')");
  
  // Insert blank row (there needs to be at least on row for the search to work)
  $database->query("INSERT INTO ".TABLE_PREFIX."mod_procalendar_actions (page_id,section_id) VALUES ('0','0')");
  
  // Make calendar images directory
  make_dir(WB_PATH.MEDIA_DIRECTORY.'/calendar/');  
}



?>