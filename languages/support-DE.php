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
 
 Translation of the help page by Florian Meerwinck (instantflorian), meerwinck.com

*/

require('../../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

if (LANGUAGE_LOADED) {        // load languagepack
  if(file_exists(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php")) {    // if exist proper language mutation
    require_once(WB_PATH."/modules/procalendar/languages/".LANGUAGE.".php");    // load it
  } else {
    require_once(WB_PATH."/modules/procalendar/languages/EN.php");        // else use english
  }
}

?>
<div style="width:60%;">
    <h2><?php echo $CALTEXT['SUPPORT_INFO']; ?></h2>
<h3>Optionen</h3>
<p>Folgende grundlegende Einstellungen k&ouml;nnen f&uuml;r einen Eintrag vorgenommen werden:</p>
<ul>
	<li>
		<b>Startdatum:</b> Anfang des Events. Das kann auch die einzige Angabe sein, wenn es sich um einen Event handelt, der nur einen Tag dauert.</li>
	<li>
		<b>Name:</b> Der Name bzw. titel des Events.</li>
	<li>
		<b>Kategorie:</b> Die Kategorie bzw. der Typ des Events, z.B. Workshop, Training, Meeting, Konferenz... Bei den Optionen k&ouml;nnen beliebig viele Kategorien angelegt werden. Nachdem dies geschehen ist, stehen sie hier dann zur Auswahl.</li>
	<li>
		<b>Sichtbarkeit:</b> Events k&ouml;nnen entweder &ouml;ffentlich oder privat sein. &Ouml;ffentliche Events sehen alle Besucher der Seite. Private Events werden nur angemeldeten Besuchern angezeigt.</li>
</ul>
<p><b>Datum / Start- und Enddatum verwenden</b><br />Hier kann festgelegt werden, ob zu den Events nur ein Startdatum oder ein Start- und ein Enddatum angegeben werden soll. Wenn nur Ein-Tages-Events eingetragen werden soll, ist logischerweise kein Enddatum erforderlich, wenn Events hingegen &uuml;ber mehrere Tage stattfinden, ist die Angabe eines Enddatums sinnvoll. Auch wenn ausgew&auml;hlt wurde, Start- und Enddatum zu verwenden, kann trotzdem auch nur ein Startdatum angegeben werden, das dann leere Feld f&uuml;r das Enddatum wird auf der Website nicht angezeigt.</p>
<p><i>Tipp:</i> Am besten f&uuml;r die Datumsangabe den eingebauten Date Picker verwenden, um zu verhindern, dass versehentlich ung&uuml;ltige Enddatumsangabn (Enddatum vor Startdatum) gemacht werden.</p>
<p><b>Uhrzeit verwenden</b><br />Es kann ausgew&auml;hlt werden, ob zum Start- und Endzeitpunkt auch die Uhrzeit angegeben werden soll. Ist diese option ausgew&auml;hlt, werden zus&auml;tzlich Eingabefelder f&uuml;r die Uhrzeit bei der Termineingabe angezeigt. Diese Felder k&ouml;nnen aber auch leer bleiben. Wenn ein Uhrzeitfeld leer ist oder 00:00 Uhr angegeben wird, wird es auf der Website nicht angezeigt.<br />&nbsp;</p>
<hr />
<h3>Eigene Felder</h3>
<p>Esk&ouml;nnen bis zu 9 zus&auml;tzliche Eingabefelder f&uuml;r Eventdetails definiert werden. Diese &quot;Eigenen Felder&quot; werden dann angezeigt, wenn ein neuer Event angelegt wird. Die jeweiligen Eingaben werden in der im Backend festgelegten Form im Frontend angezeigt. Es stehen die folgenden feldtypen zur Verf&uuml;gun:</p>
<ul>
	<li>
		<b>Textfeld: </b>einzeiliges Eingabefeld (kurze Texte oder einzelne S&auml;tze)</li>
	<li>
		<b>Textarea: </b>Langtext (mehrere S&auml;tze)</li>
	<li>
		<b>WB Link:</b> Link zu einer anderen Seite auf derselben Website.</li>
	<li>
		<b>Bild:</b> Bild, das entweder hier hochgeladen oder in der Medienverwaltung ausgew&auml;hlt wird. Das Bild kann automatisch auf eine bestimmte Gr&ouml;&szlig;e verkleinert werden; diese Gr&ouml;&szlig;e wird ganz oben auf der Eigene-Felder-Seite festgelegt.</li>
</ul>
<p>Es k&ouml;nnen beliebig viele der 9 Felder verwendet werden, indem der Feldtyp ausgew&auml;hlt und die Ausgabe im Feld-Template festgelegt wird. Dabei werden nur die Eingabefelder angezeigt, die auch aktiviert werden (also nicht auf &quot;Nicht benutzt&quot; stehen). Feldbezeichnung und Feld-Template k&ouml;nnen beliebig festgelegt werden.</p>
<p>Die Standard-Feldtemplate sind:</p>
<p><strong>Textfeld</strong> / <strong>Textarea</strong><br /><code>&lt;div class=&quot;field_line&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&lt;div class=&quot;field_title&quot;&gt;[CUSTOM_NAME]&lt;/div&gt;<br />&nbsp;&nbsp;&nbsp;[CUSTOM_CONTENT]<br />&lt;/div&gt;</code></p>
<p><strong>WB-Link</strong><br /><code>&lt;div class=&quot;field_line&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&lt;a href=&quot;[wblink[CUSTOM_CONTENT]]&quot;&gt;[CUSTOM_NAME]&lt;/a&gt;<br />&lt;/div&gt; </code></p>
<p><strong>Bild</strong><br /><code>&lt;div class=&quot;field_line&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&lt;img src=&quot;[CUSTOM_CONTENT]&quot; border =&quot;0&quot; alt=&quot;[CUSTOM_NAME]&quot; /&gt;<br />&lt;/div&gt;</code></p>
<hr />
<h3>Template</h3>
<p>Im &quot;Master-Template&quot; wird das Layout f&uuml;r Kopf- und fu&szlig;zeile der Event-&Uuml;bersichtsseite und die Detailseiten festgelegt. Zul&auml;ssig sind Text, HTML und Droplets.</p>
<p><b>Kopf- und Fu&szlig;zeile</b><br />Standardm&auml;&szlig;ig sind Kopf- und Fu&szlig;zeile der Event-&Uuml;bersichtsseite leer, hier k&ouml;nnen Text und HTML-Code eingegeben werden, und nat&uuml;rlich auch Droplets. Zudem kann der ProCalendar-Tag [CALENDAR] hinterlegt werden, der &uuml;ber die gesamte zur Verf&uuml;gung stehende Breite des Abschnitts einen Monatskalender mit Links zu den hinterlegten Eventdetails anzeigt.</p>
<p><b>Beitrag (Event-Detailseite)</b><br />Das Detailseiten-Template kann ebenfalls Text, HTML, Droplets enthalten; sowie die folgenden ProCalendar-Tags: [NAME], [DATE_SIMPLE], [DATE_FULL], [CATEGORY], [CUSTOM1], [CUSTOM2], [CUSTOM3], [CUSTOM4], [CUSTOM5], [CUSTOM6], [CATEGORY], [CONTENT] und [BACK]. All diese Tags k&ouml;nnen, m&uuml;ssen aber nicht verwendet werden; auch die Reihenfolge ist beliebig.</p>
<p>Der Unterschied zwischen [DATE_SIMPLE] und [DATE_FULL] besteht darin, dass [DATE_SIMPLE] nur die reine Datumsangabe ohne HTML/CSS ausgibt. [DATE_FULL] generiert die Datumsangabe mit Formatierung:</p>
<p><code>&lt;div class=&quot;field_line&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&lt;div class=&quot;field_title&quot;&gt;Start:&lt;/div&gt;<br />&nbsp;&nbsp; 01.10.2011<br />&lt;/div&gt;</code></p>
<p>Das Standard-Template f&uuml;r die Detailseiten sieht so aus:</p>
<p><code>&lt;div class=&quot;event_details&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&lt;h2&gt;[NAME]&lt;/h2&gt;<br />&nbsp;&nbsp;&nbsp;&lt;div class=&quot;info_block&quot;&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[DATE_FULL]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM1]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM2]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM3]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM4]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM5]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM6]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CATEGORY]<br />&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br />&nbsp;&nbsp;&nbsp;[CONTENT]<br />&lt;/div&gt;<br />[BACK] </code></p>
<p>Durch die <b>Kombination zwischen dem &quot;Master-Template&quot; und den &quot;Feld-Templates&quot;</b> kann die Darstellung von Eventdetails flexibel an den jeweiligen Bedarf angepasst werden.</p>
<hr />
<h3>CSS bearbeiten</h3>
<p>Wie viele andere WebsiteBaker-Module k&ouml;nnen auch beim ProCalendar die Stylesheets f&uuml;r Frontend und Backend angepasst werden. Das setzt allerdings voraus, dass die CSS-Dateien beschreibbar sind, sonst k&ouml;nnen die &Auml;nderungen nicht gespeichert werden.</p>

<br />
<input type="button" class="edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id"; ?>';" />
<?php
$admin->print_footer();
?>
