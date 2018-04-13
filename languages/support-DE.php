
<h3>Optionen</h3>

<p>
<img alt="Button" height="38" src="{SYSVAR:AddonUrl}/themes/default/img/Optionen.jpg" style="margin-right: 0.525em; vertical-align: middle" width="105" />
Die Grundeinstellung für den ProCalendar erfolgt im BackEnd (BE) über diesen Button.</p>

<p>Folgende grundlegende Einstellungen können vorgenommen werden:</p>

<h3>unter Optionen:</h3>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Erster_Wochentag.jpg" style="margin-right: 0.525em; vertical-align: middle" width="400" />
mit welchem Wochentag startet der Kalender</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Datumsformat.jpg" style="margin-right: 0.525em; vertical-align: middle" width="400" />
in welchem Format soll das Datum angezeigt werden</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Start_Ende_datum_14.png" style="float: left;margin-right: 0.525em;" width="411" />
Hier kann festgelegt werden, ob zu den Events nur ein Startdatum oder ein Start- und ein Enddatum angegeben werden soll. Wenn nur Ein-Tages-Events eingetragen werden soll, ist logischerweise kein Enddatum erforderlich, wenn Events hingegen über mehrere Tage stattfinden, ist die Angabe eines Enddatums sinnvoll. Auch wenn ausgewählt wurde, Start- und Enddatum zu verwenden, kann trotzdem auch nur ein Startdatum angegeben werden, das dann leere Feld für das Enddatum wird auf der Website nicht angezeigt.</p>

<p>Tipp: Am besten für die Datumsangabe den eingebauten Date Picker verwenden, um zu verhindern, dass versehentlich ungültige Enddatumsangabn (Enddatum vor Startdatum) gemacht werden.</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Uhrzeit.jpg" style="float: left; margin-right: 0.525em;" width="411" />
Es kann ausgewählt werden, ob zum Start- und Endzeitpunkt auch die Uhrzeit angegeben werden soll. Ist diese option ausgewählt, werden zusätzlich Eingabefelder für die Uhrzeit bei der Termineingabe angezeigt. Diese Felder können aber auch leer bleiben. Wenn ein Uhrzeitfeld leer ist oder 00:00 Uhr angegeben wird, wird es auf der Website nicht angezeigt.</p>

<p>Tipp: Am besten für die Datumsangabe den eingebauten Date Picker verwenden, um zu verhindern, dass versehentlich ungültige Enddatumsangabn (Enddatum vor Startdatum) gemacht werden.</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Speichern.jpg" style="margin-right: 0.525em; vertical-align: middle" width="80" />
diese Grundeinstellung speichern</p>

<p>Im Abschnitt <strong>Kategorien verwalten</strong> werden diese angelegt und wenn gewünscht mit einer Farbe versehen.</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Kategorie_anlegen.jpg" style="margin-right: 0.525em; vertical-align: middle" width="400" />
Kategoriename in das leere Textfeld eintragen</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/ColorPicker_20.png" style="float:left; margin-right: 0.525em; " width="411" />
<span style=" vertical-align: middle;"><br />über den Farbenpicker eine Farbe auswählen und speichern, diese Farbe wird in der Auflistung für die jeweilige Kaegorie angezeigt.</span></p>
<p style="clear:both">&nbsp;</p>

<p>
<img src="{SYSVAR:AddonUrl}/themes/default/img/Farbe_in_Kalender.jpg" style="float: left; margin-right: 0.525em;white-space: nowrap;" width="400" />
Wenn diese Farbe auch in den Kalenderansichten erscheinen soll, den Hacken setzen und speichern.</p>

<p style="clear:both">&nbsp;</p>
<h3>Vorgegebene Felder</h3>

<p>
<img alt="" class="img-responsive" src="{SYSVAR:AddonUrl}/themes/default/img/Title.png" style="float: left; margin-right: 0.525em;" width="411" />
Hier wird der Name bzw. Titel des Events eingetragen. Dieser Eintarg erscheint in der Übersicht aller termine im Frontend und im Backend.</p>

<p>
<img alt="Kategorie" class="img-responsive"  src="{SYSVAR:AddonUrl}/themes/default/img/Categorie_53.png" style="float: left; margin-right: 0.525em;" width="411" />
Die Kategorie bzw. der Typ des Events, z.B. Workshop, Training, Meeting, Konferenz, Raumbelegung.... Bei den Optionen können beliebig viele Kategorien angelegt werden (siehe oben). Nachdem dies geschehen ist, stehen sie hier dann zur Auswahl.</p>

<p>
<img alt="Sichtbarkeit" class="img-responsive" src="{SYSVAR:AddonUrl}/themes/default/img/Sichtbarkeit_54.png" style="float: left; margin-right: 0.525em;" width="411" />
Sichtbarkeit: Events können entweder öffentlich oder "privat" sein. Öffentliche Events sehen alle Besucher der Seite. "Private Events" werden nur angemeldeten Besuchern der jeweiligen Gruppe angezeigt. Hier werden die angelegten Gruppen von WebsiteBaker als Grundlage genommen. Als Beispiel: Wird ein Termin mit der "Sichtbarkeit" tester angelegt, ist dieser nur sichtbar&nbsp; wenn ein USER mit der Gruppe "tester" sind angemeldet hat. Eine angelegte Gruppe "Autoren" kann diesen Termin nicht sehen. Ausnahme, die Gruppe "Administrators" sieht alle Termine.</p>

<p>
<img alt="" class="img-responsive" src="{SYSVAR:AddonUrl}/themes/default/img/Eigene_Felder.jpg" style="float: left; margin-right: 0.525em;" width="112" />
Neben den vorgegebenen Felder können bis zu 9 eigene Felder mit 4 verschiedenen Feldtypen definiert werden. Diese "Eigenen Felder" werden dann angezeigt, wenn ein neuer Event angelegt wird. Die jeweiligen Eingaben werden in der im Backend festgelegten Form im Frontend angezeigt. Es können beliebig viele der 9 Felder verwendet werden, indem der Feldtyp ausgewählt und die Ausgabe im Feld-Template festgelegt wird. Dabei werden nur die Eingabefelder angezeigt, die auch aktiviert werden (also nicht auf "<strong>Nicht benutzt</strong>" stehen). Feldbezeichnung und Feld-Template können beliebig festgelegt werden. Es stehen die folgenden Feldtypen zur Verfügung:</p>

<p>
<img alt="" class="img-responsive" src="{SYSVAR:AddonUrl}/themes/default/img/Eigenes_Feld.jpg" style="float: left; margin-right: 0.525em;" width="411" />
<strong>Textfeld:</strong>einzeiliges Eingabefeld (kurze Texte oder einzelne Sätze)<br />
<strong>Textarea:</strong> Langtext (mehrere Sätze)<br />
<strong>WB Link:</strong> Link zu einer anderen Seite auf derselben Website.<br />
<strong>E-Mail Link:</strong> Link zu einer anderen Seite auf derselben Website.<br />
<strong>Bild:</strong> Bild, das entweder hier hochgeladen oder in der Medienverwaltung ausgewählt wird. Das Bild kann automatisch auf eine bestimmte Größe verkleinert werden; diese Größe wird ganz oben auf der Eigene-Felder-Seite festgelegt.</p>

<h3>Die Standard-Feldtemplate sind:</h3>

<p><strong>Textfeld / Textarea</strong></p>

<pre class="brush:xml;">&lt;div class="field_line"&gt;
   &lt;div class="field_title"&gt;[CUSTOM_NAME]&lt;/div&gt;
   [CUSTOM_CONTENT]
&lt;/div&gt;</pre>

<p><strong>WB-Link</strong></p>

<p>&lt;div class="field_line"&gt;<br />
&nbsp;&nbsp; &lt;a href="[wblink[CUSTOM_CONTENT]]"&gt;[CUSTOM_NAME]&lt;/a&gt;<br />
&lt;/div&gt;</p>

<p><strong>Bild</strong></p>

<pre>&lt;div class="field_line"&gt;
   &lt;img src="[CUSTOM_CONTENT]" border ="0" alt="[CUSTOM_NAME]" /&gt;
&lt;/div&gt;</pre>

<p><strong>E-Mail-Link (Typ ist ein Textfeld!)</strong></p>

<pre class="brush:xml;">&lt;div class="field_line"&gt;
&nbsp; &lt;div class="field_title"&gt;[CUSTOM_NAME]&lt;/div&gt;
&nbsp; &lt;a href="mailto:[CUSTOM_CONTENT]"&gt;[CUSTOM_NAME]&lt;/a&gt;
&lt;/div&gt;</pre>

<h3>Template</h3>

<p>Im "Master-Template" wird das Layout für Kopf- und Fußzeile der Event-Übersichtsseite und die Detailseiten festgelegt. Zulässig sind Text, HTML und Droplets.</p>

<p>Kopf- und Fußzeile<br />
Standardmäßig sind Kopf- und Fußzeile der Event-Übersichtsseite leer, hier können Text und HTML-Code eingegeben werden, und natürlich auch Droplets. Zudem kann der ProCalendar-Tag <strong>[CALENDAR]</strong> hinterlegt werden, der über die gesamte zur Verfügung stehende Breite des Abschnitts einen Monatskalender mit Links zu den hinterlegten Eventdetails anzeigt.</p>

<p>Beitrag (Event-Detailseite)<br />
Das Detailseiten-Template kann ebenfalls Text, HTML, Droplets enthalten; sowie die folgenden ProCalendar-Tags: [NAME], [DATE_SIMPLE], [DATE_FULL], [CATEGORY], [CUSTOM1], [CUSTOM2], [CUSTOM3], [CUSTOM4], [CUSTOM5], [CUSTOM6], [CATEGORY], [CONTENT] und [BACK]. All diese Tags können, müssen aber nicht verwendet werden; auch die Reihenfolge ist beliebig.</p>

<p>Der Unterschied zwischen [DATE_SIMPLE] und [DATE_FULL] besteht darin, dass [DATE_SIMPLE] nur die reine Datumsangabe ohne HTML/CSS ausgibt. [DATE_FULL] generiert die Datumsangabe mit Formatierung:</p>

<p>&lt;div class="field_line"&gt;<br />
&nbsp;&nbsp; &lt;div class="field_title"&gt;Start:&lt;/div&gt;<br />
&nbsp;&nbsp; 01.10.2011<br />
&lt;/div&gt;</p>

<h3>Das Standard-Template für die Detailseiten sieht so aus:</h3>

<p>&lt;div class="event_details"&gt;<br />
&nbsp;&nbsp; &lt;h2&gt;[NAME]&lt;/h2&gt;<br />
&nbsp;&nbsp; &lt;div class="info_block"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [DATE_FULL]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM1]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM2]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM3]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM4]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM5]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CUSTOM6]<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CATEGORY]<br />
&nbsp;&nbsp; &lt;/div&gt;<br />
&nbsp;&nbsp; [CONTENT]<br />
&lt;/div&gt;<br />
[BACK]</p>

<p>Durch die Kombination zwischen dem "Master-Template" und den "Feld-Templates" kann die Darstellung von Eventdetails flexibel an den jeweiligen Bedarf angepasst werden.<br />
CSS bearbeiten</p>

<p>Wie viele andere WebsiteBaker-Module können auch beim ProCalendar die Stylesheets für Frontend und Backend angepasst werden. Das setzt allerdings voraus, dass die CSS-Dateien beschreibbar sind, sonst können die Änderungen nicht gespeichert werden.</p>
