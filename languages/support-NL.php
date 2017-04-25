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
    <h3>&nbsp;</h3>
    <h3>Opties</h3>
  <p>De basisopties die u kunt kiezen voor een gebeurtenis zijn:
    <ul>
        <li><strong>Startdatum:</strong> de dag dat een  gebeurtenis start. In geval van een &eacute;&eacute;ndaagse gebeurtenis is dit de enige datum die ingevuld wordt.</li>
        <li><strong>Naam:</strong> de naam of titel van de  gebeurtenis.</li>
        <li><strong>Categorie:</strong> de categorie of  type   gebeurtenis, bijvoorbeeld workshop, training, vergadering, comgres, of vakantie. U kunt ongelimiteerd categorie&euml;n aanmaken in de Opties. Hierna  kunt u een categorie kiezen bij het aanmaken van een  gebeurtenis.</li>
        <li><strong>Zichtbaarheid:</strong> u kunt de  gebeurtenis een openbare of een priv&eacute;status geven. Een openbare  gebeurtenis is voor iedere site-bezoeker zichtbaar, terwijl een priv&eacute;gebeurtenis alleen voor ingelogde bezoekers te zien is.</li>
        <li><strong>Beschrijving:</strong> uw beschrijving van de  gebeurtenis, waarbij u gebruik kunt maken van de mogelijkheden van de  WYSIWYG-editor.</li>
    </ul>
<p><strong> Startdatum  &amp; einddatum</strong><br />
  De opties geven de mogelijkheid om ofwel een startdatum, ofwel een start- en een einddatum te gebruiken voor de gebeurtenissen. Als u alleen &eacute;&eacute;ndaagse gebeurtenissen heeft, hoeft u uiteraard geen einddatums te gebruiken. Als u gebeurtenisssen heeft die meerdere dagen bestrijken, kunt u  kiezen voor gebruik van start- en eindatums. </p>
<p>Indien u kiest voor deze optie, dan nog hoeft u bij het aanmaken van een gebeurtenis de einddatum niet in te vullen. Als u de einddatum leeglaat, zal het einddatumveld gewoon niet getoond worden op de website. </p>
<p>Het is overigens aan te raden om de datumkiezer te gebruiken als u een datum invoert, om fouten te voorkomen. De datumkiezer verhindert bijvoorbeeld het gebruik van een einddatum die voor de startdatum ligt.</p>
<p><strong>Tijden</strong><br />
  U kunt ervoor kiezen om tijden toe te voegen aan uw start- en einddatums. Als u tijden inschakelt zullen er invoervelden voor de tijden beschikbaar zijn bij het aanmaken van een een gebeurtenis. </p>
<p>Het is overigens niet verplicht deze invoervelden daadwerkelijk en u kunt deze dus leeg laten als u dat wilt. Leeglaten van een tijdveld in de admin, of de tijd op  00:00 zetten, zal tot gevolg hebben dat het complete tijdveld niet getoond zal worden op de website. Dus u kunt de optie inschakelen om tijden te gebruiken, maar per gebeurtenis bepalen of u het tijdveld daadwerkelijk gebruikt.</p>
    <hr />
<h3>Extra velden</h3>
    <p>U kunt maximaal 9 extra invoervelden toevoegen aan de kalender.  Deze extra velden zijn beschikbaar bij het aanmaken van een nieuwe gebeurtenis, indien u ze heeft ingeschakeld. De invoer in de admin wordt getoond op de website, in de context die u aangeeft in de specifieke veld-template. Er zijn diverse soorten extra veld:</p>
    <ul>
      <li><strong>Tekstveld:</strong> een enkele regel informatie, meestal gebruikt voor een paar woorden of enkele zin.</li>
      <li><strong>Tekstvak:</strong> meerdere regels informatie, meestal gebruikt voor kleine teksten bestaande uit meerdere zinnen.</li>
      <li><strong>WB-link:</strong> een link naar een andere WB-pagina op dezelfde website, een interne link dus.</li>
      <li><strong>Afbeelding:</strong> een afbeelding die u kunt  uploaden of naar verwijzen vanuit de Media-sectie. De afbeelding kan automatisch verkleind worden naar de maximale hoogte of breedte die u instelt in de eerste optie op de pagina voor de Extra Velden.</li>
    </ul>
    <p>Het staat u vrij om een of meerdere extra in- en uitvoervelden te gebruiken als aanvulling op de standaardvelden. U kunt daarbij de template en de naam van ieder veld naar wens aanpassen. Alleen de extra velden die zijn  &quot;ingeschakeld&quot; door het veldtype te kiezen, zullen beschikbaar zijn bij het aanmaken van een nieuwe gebeurtenis. </p>
  <p>De standaard-templates voor de 4 verschillende veldtypes zijn:</p>
  <p><strong>Tekstveld</strong> / <strong>Tekstvak</strong><br />
    <code>&lt;div class=&quot;field_line&quot;&gt;   <br />
    &nbsp;&nbsp;&nbsp;&lt;div class=&quot;field_title&quot;&gt;[CUSTOM_NAME]&lt;/div&gt;   <br />
&nbsp;&nbsp;&nbsp;[CUSTOM_CONTENT] <br />
&lt;/div&gt;</code></p>
  <p><strong>WB-link</strong><br />
    <code>&lt;div class=&quot;field_line&quot;&gt;     <br />
    &nbsp;&nbsp;&nbsp;&lt;a href=&quot;[wblink[CUSTOM_CONTENT]]&quot;&gt;[CUSTOM_NAME]&lt;/a&gt; <br />
  &lt;/div&gt; </code></p>
  <p><strong>Afbeelding</strong><br />
    <code>&lt;div class=&quot;field_line&quot;&gt;<br />
    &nbsp;&nbsp;&nbsp;&lt;img src=&quot;[CUSTOM_CONTENT]&quot; border =&quot;0&quot; alt=&quot;[CUSTOM_NAME]&quot; /&gt; <br />
&lt;/div&gt;</code> </p>
<hr />
<h3>Template</h3>
<p>In de &quot;hoofd-template&quot; kunt u de layout vastleggen voor de output op de website, zowel voor  de header en footer van de overzichtspagina van gebeurtenissen, als voor de complete detailpagina van een gebeurtenis. Daarbij kunt u gebruik maken van tekst, HTML en droplets.</p>
<p><strong>Header en footer</strong><br />
  Standaard zijn de  header en footer van de overzichtspagina leeg, afgezien van de [CALENDAR]-tag. U kunt tekst,  HTML en droplets toevoegen om de indexpagina aan te vullen. Een speciale ProCalendar-tag die u al dan niet kunt plaatsen in het  header-veld is [CALENDAR]. Deze tag toont een grafische kalender over de volle breedte van het content-veld, met klikbare datums en maanden die gekoppeld zijn aan die in het overzicht eronder.</p>
<p><strong>Bericht  (= gebeurtenisdetailpagina)</strong> <br />
  Het bericht-templateveld kan tekst, HTML en droplets bevatten en tevens de speciale ProCalendar tags: [NAME], [DATE_SIMPLE], [DATE_FULL], [CATEGORY], [CUSTOM1], [CUSTOM2], [CUSTOM3], [CUSTOM4], [CUSTOM5], [CUSTOM6], [CATEGORY], [CONTENT] en [BACK]. Het staat u vrij om deze tags te verplaatsen of te verwijderen. </p>
<p>Het verschil tussen  [DATE_SIMPLE] en [DATE_FULL] is dat de &quot;simple&quot;-versie slechts de kale datum toont, zonder opmaak met HTML/CSS. De &quot;full&quot;-versie toont de datum met volledige HTML/CSS opmaak:<br />
  <code>&lt;div class=&quot;field_line&quot;&gt; <br />
&nbsp;&nbsp;&nbsp;&lt;div class=&quot;field_title&quot;&gt;(de datumnaam):&lt;/div&gt; <br />
&nbsp;&nbsp;&nbsp;(de datum zelf) <br />
&lt;/div&gt;</code></p>
<p>De standaard bericht-template is:</p>
<p><code>&lt;div class=&quot;event_details&quot;&gt;   <br />
  &nbsp;&nbsp;&nbsp;&lt;h2&gt;[NAME]&lt;/h2&gt;   <br />
  &nbsp;&nbsp;&nbsp;&lt;div class=&quot;info_block&quot;&gt;     <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[DATE_FULL] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM1] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM2] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM3] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM4] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM5] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CUSTOM6] <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CATEGORY] <br />
  &nbsp;&nbsp;&nbsp;&lt;/div&gt; <br />
  &nbsp;&nbsp;&nbsp;[CONTENT] <br />
  &lt;/div&gt; <br />
  [BACK] </code></p>
<p> <strong>Deze combinatie van extra velden en de vrij aan te passen hoofd-template bieden een zeer flexibel en krachtig systeem om de agenda/kalenderinstellingen en website-output precies aan uw wensen aan te passen!</strong></p>
<hr />
<h3>Wijzig CSS</h3>
<p>Zoals veel andere  WB-modules, biedt ProCalendar de gelegenheid om de  stylesheets voor het beheer en de website (&quot;backend&quot; en &quot;frontend&quot;) aan te passen. Zorg er wel voor dat de  CSS-bestanden de juiste schrijfrechten hebben, anders worden de veranderingen niet opgeslagen. U krijgt overigens wel een foutmelding als dit het geval is.</p>
</div>
<br />
<input type="button" class="edit_button" value="<?php echo $CALTEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo WB_URL."/modules/procalendar/modify_settings.php?page_id=$page_id&amp;section_id=$section_id"; ?>';" />
<?php
$admin->print_footer();
?>
