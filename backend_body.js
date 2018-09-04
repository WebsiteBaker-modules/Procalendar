// Test to see if it needs to do something 
// MODULE_URL is set in modify (at the end of functions.php)

if (typeof MODULE_URL != 'undefined') {

  // Variables are set in functions.php from line 1292-ish
  // Include datepicker files:
  $.insert(MODULE_URL + '/js/date.js');        
  $.insert(MODULE_URL + '/js/jquery.datePicker.js'); 
  
  // This is only needed for ie 6 and smaller
  if ($.browser.msie && $.browser.version.substr(0,1)<7) { 
    $.insert(MODULE_URL + '/js/jquery.bgiframe.js');
  }
  // Insert language file if it is not none!
  if (datelang !== "none") {
	$.insert(MODULE_URL + '/js/lang/'+ datelang);
  }
  // firstday 0=sunday, 1=monday
  // Fomat = datefomat yyyy/mm/dd or dd mm yyy or ...
  Date.firstDayOfWeek = firstDay; 
  Date.format 		= format;

  // set up the calendars and make them work together, date from first will be minimum date for second
  $(function()
  {
	$('.date-pick').datePicker({
		clickInput:false, 
		autoFocusNextInput: true, 
		startDate:datefrom}   // datefrom!
		);
		
	$('#date1').bind(
		// Update date2 when date changes, set starting date to date1
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#date2').dpSetStartDate(d.addDays(0).asString());
			}
		}
	).datestart;   // datestart!!
	
	$('#date2').datePicker().dpSetStartDate(datestart).dateend;   // dateend!!
  });

// End of MODULE_URL test
};

// load the ColorPicker for editing categories only when needed
if (location.href.search(/modify_settings/) > -1)
  $.insert('js/mColorPicker/javascripts/mColorPicker.js');
  

$(document).ready(function () {
	// functions for colors in actionlist
  $('.edit_field_short').bind('colorpicked', function (e,color) {
    $('input[name="action_background"]').attr('value', color);
  });
  $('.rec_select input').bind("change myinit", function(event){
  	if ($(this).attr('id') == 'rec_exceptions'){
  		if (event.type != "myinit")
  			$('.rec_exceptions input').attr('value',"");
  		$('.rec_exceptions').toggle(400);
  	}else {
  		  	
  		var myId = $(this).attr('id');
  		if (!$(this).prop("checked")){
  			$('.'+myId).hide(300);
  		} else {
	  		$('.rec_select input:checked').not('#rec_exceptions').prop("checked", false);
	  		$(this).prop("checked", true);
	  		$('.procal_hidden:not(.rec_exceptions, .rec_enddate, .rec_rep_select)').hide(300);
	  		$('.'+myId).show(300);
	  	};
	  	if (!$('.rec_select input:checked').not('#rec_exceptions').length) {
	  		$('#rec_exceptions').prop("checked", false);
	  		$('#rec_exceptions').prop("disabled", true);
	  		$('.rec_rep_select').hide();
	  		$('.rec_enddate.procal_hidden').hide();
	  		$('.rec_exceptions').hide();
	  	} else {
	  		$('#rec_exceptions').prop("disabled", false);
	  		$('.rec_enddate').show();
	  		$('.rec_rep_select').show();
	  	}
  	};	
  });
  // functions for recurring events
	$('.rec_rep_select input').bind("click change",function(){
		$('.rec_rep_select input').not($(this)).attr('value',"");
		$('.rec_rep_select input').not($(this)).prop("checked", false);
		$('#date2').attr('value',"");
		$('#end_time').attr('value',"");
	});
	$('#date2').bind("click change",function(){
		$('.rec_rep_select input').attr('value',"");
		$('.rec_rep_select input').prop("checked", false);
		if ($('#end_time').val() == '') {
				$('#end_time').attr('value',"00:00");
		}
	});
	$('.rec_day input').bind("click change",function(){
		if (isNaN($(this).attr('value'))) $(this).attr('value',"");
	});
	$('.rec_week input:eq(0)').bind("click change",function(){
		if (isNaN($(this).attr('value')) || $(this).attr('value') < 1) $(this).attr('value',"");
	});
	$('.rec_month input:lt(2)').bind("click change",function(){
		$('.rec_month input:gt(1)').attr('value',"");
		$('.rec_month input:gt(1)').prop("checked", false);
		if (isNaN($('.rec_month input:eq(0)').attr('value'))) $('.rec_month input:eq(0)').attr('value',"");
		if (isNaN($('.rec_month input:eq(1)').attr('value')) || $('.rec_month input:eq(1)').attr('value') < 1) $('.rec_month input:eq(1)').attr('value',"");
		if ($('.rec_month input:eq(0)').attr('value') > 31 || $('.rec_month input:eq(0)').attr('value') < 1) $('.rec_month input:eq(0)').attr('value',"");
	});
	$('.rec_month input:gt(1), select').bind("click change",function(){
		$('.rec_month input:lt(2)').attr('value',"");
		if (isNaN($('.rec_month input:eq(9)').attr('value')) || $('.rec_month input:eq(9)').attr('value') < 1) $('.rec_month input:eq(9)').attr('value',"");
	});
	$('.rec_year input:eq(0), select:eq(1)').bind("click change",function(){
		$('.rec_year input:gt(0)').prop("checked", false);
		var sel = $('.rec_year select:eq(0)').attr('value');
		var day = $('.rec_year input:eq(0)').attr('value');
		if (isNaN(day) || day > (32 - new Date( 2012, sel-1, 32 ).getDate()))
			$('.rec_year input:eq(0)').attr('value',"");
	});
	$('.rec_year input:gt(0), .rec_year select:gt(1)').bind("click change",function(){
		$('.rec_year input:eq(0)').attr('value',"");
	});
	$('.rec_rep_count').bind("click change",function(){
		if (isNaN($(this).attr('value')) || $(this).attr('value') < 1) $(this).attr('value',"");
	});
	
  $('.rec_select input:checked').each(function(){
  	//alert($(this).length);
    $(this).trigger('myinit');
	});
	$('#rec_never:checked').each(function(){
    $(this).trigger('change');
	});
	$('#rec_rep_count[value!=""]').each(function(){
    $(this).trigger('change');
	});
	
	$('.edit_button[type=submit]').click(function(){
		if (!$('.date_title').val().length) {
			alert(NoTitleFault);
			return false;
		}
		if ($('.rec_select input:checked').not('#rec_exceptions').length) {
			ok = true;
			var testId = $('.rec_select input:checked').not('#rec_exceptions').attr('id');
			if (testId == "rec_day" && $('.rec_day input').attr('value') == "") ok = false;
			if (testId == "rec_week" 
					&& ($('.rec_week input:eq(0)').attr('value') == "" 
					||  !$('.rec_week input:checked').length)) ok = false;
			if (testId == "rec_month" 
					&& !(($('.rec_month input:eq(0)').attr('value') != "" 
					&& $('.rec_month input:eq(1)').attr('value') != "")
					|| ($('.rec_month input:checked').length
					&& $('.rec_month input:eq(9)').attr('value') != ""))) ok = false;
			if (testId == "rec_year" 
					&& ($('.rec_year input:eq(0)').attr('value') == "" 
					&&  !$('.rec_year input:checked').length)) ok = false;	
			if ($('#date2').attr('value') == ""
					&& $('.rec_rep_count').attr('value') == ""
					&& !$('#rec_never').prop('checked')) ok = false;
      if (!ok) alert(InputFault);
			return ok;
		};
	});
	if ($('#rec_id').attr('value') && $('#rec_id').attr('value') > 0){
		if (!$('.rec_select input:checked').length) {
			alert($('#rec_overwrite_message').attr('value'));
		} else {
			if (confirm($('#rec_message').attr('value'))){
				$('#rec_id').attr('value',"");
				$('.edit_button:eq(2)').attr('name','edit_overwrite');
			} else {
				$('.edit_button:eq(2)').attr('value',$('#rec_overwrite').attr('value'));
				$('.edit_button:eq(2)').attr('name','overwrite');
				$('.edit_button').click(function(){
					$('#date1, #date2').prop("disabled", false);
				});
				$('.rec_select input').prop("checked", false);
				$('.rec_select input').prop("disabled", true);
				$('.procal_hidden').hide();
				$('#date1, #date2').attr('value',$('#rec_day_called').attr('value'));
				$('#date1, #date2').prop("disabled", true);
			};
		};
	};
});
  