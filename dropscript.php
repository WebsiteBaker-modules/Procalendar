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

function years(){
  $i=date('Y');
  $end=$i+10;
  while ($i <= $end) { 
    echo"<option value=\"$i\">$i</option>";
    $i++; 
  }
}

function years_preselect(){
  $i=date('Y');
  echo"<option selected value=\"$i\">$i</option>";
  $i++;
  $end=$i+10;
  while ($i <= $end) { 
    echo"<option value=\"$i\">$i</option>";
    $i++; 
  }
}

function days(){
  $i=1;
  while ($i <= 31) { 
    echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

function days_preselect(){
  $i=1;
  $m=date('d');
  while ($i <= 31) { 
    if($i == $m)
      echo'<option selected value="';
    else
      echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

function months(){
  $i=1;
  while ($i <= 12) { 
    echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

function months_preselect(){
  $i=1;
  $m=date('m');
  while ($i <= 12) { 
    if($i == $m)
      echo'<option selected value="';
    else
      echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

function hours(){
  $i=0;
  while ($i <= 23) { 
    echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

function minutes(){
  $i=0;
  while ($i <= 59) { 
    echo'<option value="';
    printf("%002s",  $i);
    echo"\">$i</option>";
    $i++; 
  }
}

?>