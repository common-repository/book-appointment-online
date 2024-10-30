jQuery.noConflict();

/**
var oz_alang - array of translate strings
**/
Array.prototype.objIndexOf = function(val) {
    var cnt =-1;
    for (var i=0, n=this.length;i<n;i++) {
      cnt++;
      for(var o in this[i]) {
        if (this[i].hasOwnProperty(o) && this[i][o]==val) return cnt;
      }
    }
    return -1;
}

Array.prototype.next = function() {
    return this[++this.current];
};

Array.prototype.prev = function() {
    return this[--this.current];
};

Array.prototype.current = 0;

/*select исключаем услуги*/

function oz_book_changeSelect($this) {
	switch($this) {
		case 'all' :
		jQuery('#book_oz_worktime').hide();
		jQuery('#book_oz_worktime .at-repater-block').remove();
		break;
		case 'exclude' :
		jQuery('#book_oz_worktime').show();
		break;
		case 'include' :
		jQuery('#book_oz_worktime').show();
		break;
		}
	}
	
oz_book_changeSelect(jQuery('#oz_book_provides_services').val());
 
 /*******************************сотрудники**************************************/
 
 
 /*генерируем рабочие дни для календаря*/
 
 function oz_bus_days(nrasp) {
	 var bus_days = [];
jQuery.each(nrasp, function(i,v) {
	 var now = moment();
	 for (i=0; i < 60; i++) {
	var dayName = now.locale('en').format('ddd').toLowerCase();
 		 if (jQuery.inArray('oz_'+dayName,v.days) > -1) {
			 now.format('YYYY-MM-DD');
 		 bus_days.push({
			 day: now.format('YYYY-MM-DD'),
			 start: v.start,
			 end: v.end
		 }) 
		 } 
		 now.add(1, 'days');
	 }
	})	
	return bus_days;	
 }
 
 function showDefault() {
		if (jQuery('.oz_worktime tr').length > 2) {
	jQuery('.oz_worktime tr.remove').hide();
	}
	else {
	jQuery('.oz_worktime tr.remove').show();
	}
}
function oz_days(id) {
		var days = [];
		jQuery('#'+id+' .oz_day input:checked').each(function() {
			days.push(jQuery(this).attr('name'));
		});
		return days;
	}
	
function checkChecked() {
			if (jQuery('.times-line .oz_day input:checked').length < 7) {
		jQuery('.add-date').removeClass('hide');
		}
}
	
function removeDate() {
	jQuery('.remove-date').click(function() {
	if (removeClick) {
		return false;
	}
		var rod = jQuery(this).parent().parent();
		var rodId = rod.attr('id');
		rod.remove();
		showDefault();
		if (jQuery('.times-line').length <= 2) {
		jQuery('.add-date').removeClass('hide');
		}
		rasp = rasp.filter(function(e, i) {
			return rasp[i]['id'] !== rodId;
		});	
		checkChecked();
		jQuery('#oz_raspis').val(JSON.stringify(rasp));
	});
}

/* выводим постоянный график*/

function postGraph(rasp) {
if (!rasp.hasOwnProperty('day') && typeof rasp[0] == 'undefined') {
	rasp = [];
	}
	

 	rasp.sort(function(a, b) {
    return a.id.valueOf() > b.id.valueOf();
}) 
	nrasp = [];
	tr = '';
	days1 = [];
	jQuery.each(rasp, function(i,v) {
		days1.push(v.day);
		tr =  rasp.next();
		if (typeof tr !== 'undefined' && tr['id'] != v.id) {
		nrasp.push({
			id: v.id,
			days: days1 ,
			start:v.start,
			end:v.end,
		})
		days1 = [];
		}
		if (typeof tr == 'undefined') {
		nrasp.push({
			id: v.id,
			days: days1 ,
			start:v.start,
			end:v.end,
		})
		}
	})
jQuery.each(nrasp, function(i,v) {
		var week = ['mon','tue','wed','thu','fri','sat','sun'];
		td = '';
		jQuery.each(week, function(g,z) {
		checked = (jQuery.inArray('oz_'+z,v.days) > -1) ? 'checked="checked"' : '';
		td += '<td class="oz_day oz_'+z+'"><input name="oz_'+z+'" disabled id="oz_'+z+'" '+checked+' type="checkbox"></td>';
		});
		jQuery('.oz_worktime tbody').append(
		'<tr id="'+v.id+'" class="times-line">'+
		'<td class="time-show">'+v.start+' - '+v.end+'</td>'+td+
		'<td><span class="remove-date dashicons dashicons-no-alt"></span></td>'+
		'</tr>');
		td = '';
		});
}

/****************************начало скриптов*****************************/

(function( $ ) {
$(document).ready(function() {

if (typeof rasp == 'string' || typeof rasp == 'undefined') {
	rasp = new Array();
}
removeClick = false;
postGraph(rasp);
var bus_hour = oz_bus_days(nrasp);

	$('.plav_time_text span').click(function() {
		$('.plav_grafh_block').removeClass('hide');
	if (rasp[0].hasOwnProperty('day')) {
			rasp = [];
		}
	});

if ($('#calendar').length) {
	$('#calendar').fullCalendar({
	header: {
		left: 'prev,next today',
		center: 'title',
		right: 'month,agendaWeek,agendaDay,listDay'
	},
	locale: oz_lang,
	defaultView: 'listDay',
	navLinks: true, // can click day/week names to navigate views
	selectable: false,
	selectHelper: true,
	select: function(start, end) {
		var title = prompt('Event Title:');
		var eventData;
		if (title) {
			eventData = {
				title: title,
				start: start,
				end: end
			};
			$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
		}
		$('#calendar').fullCalendar('unselect');
	},
	slotDuration: '00:15:00',
	editable: true,
	eventLimit: true, // allow "more" link when too many events
	minTime: dayStart,
	maxTime: dayFinish,
	events: clients,
	eventRender: function(event, element) {
		console.log(event, element);
			$('<div class="fc-ther_info"> '+event.tel+', '+event.usl+'</div>').insertAfter($(element).find('.fc-title, .fc-list-item-title a'));
		},
	eventDurationEditable: false,
	dayRender: function( date, cell ) {
		if (bus_hour.objIndexOf(moment(date._d).format('YYYY-MM-DD')) < 0) {
		$(cell).addClass('fc-nonbusiness');
		}
	},
	eventDrop: function(event, delta, revertFunc) {
		if (bus_hour.objIndexOf(moment(event.start._d).format('YYYY-MM-DD')) < 0) {
		revertFunc();
		}
		else {
	var data = {
			action: 'saveDropChange',
			id: event.id,
			date: moment(event.start).format('DD.MM.YYYY HH:mm')
			
		};
		jQuery.ajax( {
		url:oz_vars.adminAjax,
		data:data,
		method:'POST',
		beforeSend: function() {
			$('body').addClass('oz_calen_load');
		},
		success: function(response,status) {
		$('body').removeClass('oz_calen_load');
		},
		error: function(err,st) {
			$('body').removeClass('oz_calen_load');
			alert('Error! '+st+' '+oz_alang.str2);
		}
		}); 
		}
	},
	eventResizeStop:  function( event, jsEvent, ui, view ) { console.log(event.id,moment(event.start).format('H:mm')); }
});
}

	jQuery('.add-date').click(function() {
		removeClick = true;
		jQuery('.add-block').removeClass('hide');
		d = Math.round(Math.random() * 1000000); //jQuery('.times-line').length;
		if (!$('.oz_worktime tbody tr.times-line').hasClass('empty')) {
		jQuery('.oz_worktime tbody').append(
		'<tr id="line-'+d+'" class="times-line empty">'+
		'<td class="time-show">'+oz_alang.str3+'</td>'+
		'<td class="oz_day oz_mon"><input name="oz_mon" id="oz_mon" type="checkbox"></td>'+
		'<td class="oz_day oz_tue"><input name="oz_tue" id="oz_tue" type="checkbox"></td>'+
		'<td class="oz_day oz_wed"><input name="oz_wed" id="oz_wed" type="checkbox"></td>'+
		'<td class="oz_day oz_thu"><input name="oz_thu" id="oz_thu" type="checkbox"></td>'+
		'<td class="oz_day oz_fri"><input name="oz_fri" id="oz_fri" type="checkbox"></td>'+
		'<td class="oz_day oz_sat"><input name="oz_sat" id="oz_sat" type="checkbox"></td>'+
		'<td class="oz_day oz_sun"><input name="oz_sun" id="oz_sun" type="checkbox"></td>'+
		'<td><span class="remove-date dashicons dashicons-no-alt"></span></td>'+
		'</tr>');
		}
		showDefault();
		jQuery.each(rasp,function(i,v) {
			jQuery(".oz_worktime tr#line-"+d+" .oz_day input#"+rasp[i].day).attr("disabled", true);
		});
		
		jQuery('.add-block').show();
	
	jQuery(this).addClass('hide');
	
	removeDate();

	});
	
jQuery('.add-date-time').click(function(e) {
		i = 0;
		time = '';
		jQuery('.add-block .at-time').each(function() {
			if (jQuery(this).val().length === 0) {
				jQuery(this).addClass('warning');
			}
			
			else {
			jQuery(this).removeClass('warning');
			i++;
			}
		});
		
		jQuery(".oz_worktime tr#line-"+d+" .oz_day input").each(function() {
			if (!jQuery(this).is(':checked') ) {
				jQuery(".oz_worktime tr#line-"+d).addClass('warning');
				setTimeout(function() {jQuery(".oz_worktime tr#line-"+d).removeClass('warning')},1000);
			}
			
			else {
				i++;
			}
		});
		
		
		if (i > 2) {
		jQuery(".oz_worktime tr#line-"+d).removeClass('warning');
		jQuery('.times-line .oz_day input:checked').each(function(){
			if (rasp.length) {
			if (rasp[0].hasOwnProperty('dayF')) {
				rasp = [];
			}
			}
	if (rasp.objIndexOf(jQuery(this).attr('name')) < 0) {
		rasp.push({
			day:jQuery(this).attr('name'),
			start:jQuery('#oz_ras_start').val(),
			end:jQuery('#oz_ras_end').val(),
			id: jQuery(this).parents('.times-line').attr('id'),
			pId: postId
			})
	}
		});
		checkChecked();
		
		jQuery('.oz_worktime tr#line-'+d+' .time-show').text();
		jQuery('.add-block').hide();
		$('.times-line').removeClass('empty');
		jQuery('.times-line td input').attr("disabled", true);
		jQuery(".oz_worktime tr#line-"+d+" .time-show").text(jQuery('#oz_ras_start').val()+' - '+jQuery('#oz_ras_end').val());
		jQuery('#oz_raspis').val(JSON.stringify(rasp));
		removeClick = false;
		}
		
	})
	
	removeDate();
	
	if (jQuery('.at-time').length) {
	jQuery('.at-time').timepicker({
		controlType: 'select',
		stepMinute: 5,
		oneLine: true,
		onSelect: function (datetimeText, datepickerInstance) {
			
		}
		});
	}
			checkChecked();
});

 })(jQuery);

/*********************end сотрудники*****************************/
/*********************клиенты*****************************/

(function( $ ) {
time = '';
/* чтоб не исполнялось в других местах. только в клиентах */
if (oz_vars.post_type !== 'clients') { 
	return;
}
function book_oz_plav_bus_days(rasp) {
	
	 var start = moment();
	
		var postG = [];
		var postV = [];
		var postG_plav_bus_days = [];
		var postV_bus_days = [];
		var plav_bus_days = [];
		 $.each(rasp, function(index) {
			if (typeof rasp[index].day != 'undefined') {
				postV.push(rasp[index]);
			}
		});
		
		if (postV.length) {
			jQuery.each(postV, function(i,v) {
			var now = moment();
		 for (i=0; i < 60; i++) {
		var dayName = now.locale('en').format('ddd').toLowerCase();
			 if ('oz_'+dayName == v.day) {
				 now.format('YYYY-MM-DD');
				 var pId = (v.pId) ? v.pId : '';
			 postV_bus_days.push({
				 day: now.format('YYYY-MM-DD'),
				 start: v.start,
				 end: v.end,
				 pId
			 }) 
			 } 
			 now.add(1, 'days');
		 }
			})
		}
		plav_bus_days = postV_bus_days;
			

		var end = moment() - start;
		console.log('Время генерации рабочих дней: '+end+'мс');
	 
	 return plav_bus_days;
  }
 
/* рандомное значение из массива */ 
function book_oz_randFromArray(arr) {
	return arr[Math.floor(Math.random()*arr.length)];
}

function book_oz_checkCurrentZapisi(dateText,emp, currentClient = false) {
	/*
	currentClient - вычитаем этого клиента из списка записанных к специалисту. 
	Используется для вывода времени у уже записанных
	*/
	var CurrentZapisi = [];
	var currentClient = (currentClient) ? currentClient : '';
	var data = {
		action: 'checkCurrentZapisi',
		dateText: dateText,
		currentClient,
		};
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.adminAjax,
		success: function(zapisi,status) {
		book_oz_onCheckCurrentZapisi(dateText,emp,zapisi);
		}
		})
}

function book_oz_onCheckCurrentZapisi (dateText,emp,zapisi) {
	var timeList = book_oz_forTime(dateText,emp,zapisi);
		book_oz_printTime(timeList);
}

function book_oz_forTime(dateText = 0, emp = 0, zapisi = 0) {
		/*
		@ генерация массива времени
		dateText  	- выбранная дата
		emp 		- массив с персоналом
		*/
				console.log('Найдено сотрудников: '+emp.length);
				var timeList = []; // лист доступного времени на выбранную дату
				timeB		= JSON.parse(zapisi);
		for (s=0; s < emp.length; s++) {
			var dHstart 	= emp[s].start.split(':')[0],
				dMStart 	= emp[s].start.split(':')[1],
				dHFinish 	= emp[s].end.split(':')[0],
				dMFinish 	= emp[s].end.split(':')[1],
				pId			= emp[s].pId,
				b			= 15; // посколько минут прибавлять
			
		
		/* проверка текущего времени */
		var today = moment().format('DD.MM.YYYY');
    for(var i = dHstart*60; i <= 1440; i += b){
        hours = Math.floor(i / 60);
        minutes = i % 60;
        if (minutes < 10){
            minutes = '0' + minutes;
        }
		
		if (hours < 10) {
			hours= '0' + hours;
		}
		
if (
	hours === 24 || 
	hours < dHstart || 
	(hours == dHstart && minutes < dMStart ) ||
	hours > dHFinish ||
	(hours == dHFinish && minutes > dMFinish)
	) {
		continue;	
		}
		var index = timeB.map(function (img) { return img.start; }).indexOf(hours + ':' + minutes);
		if (index > -1 && timeB[index].pId == pId) {
		var tmpmin = timeB[index].w_time != '';
		if (tmpmin) {
		var tmptime = moment(timeB[index].start, 'HH:mm')	.add(timeB[index].w_time, 'minutes').format('HH:mm');
		}
		
		}
		
		else {
			if ($('select[name="oz_uslug_set"]').length) {
				var mtime = $('select[name="oz_uslug_set"] option:selected').attr('data-servtime');
				var z = 0;
				var tectime = hours+':'+minutes;
				/* не выводить время если конец рабочего дня*/
				var forEnd = Math.abs(parseInt(moment(dHFinish+':'+dMFinish, "h:mm").format('X')) - parseInt(moment(tectime, "h:mm").format('X')) )/60;
				if (forEnd < mtime) {
					continue;
				}
				/* не выводить время если выбранная услуга длится дольше ближайшей записи */
				for (k=0; k < timeB.length; k++ ) {
					var fornow =  (parseInt(moment(timeB[k].start, "h:mm").format('X')) - parseInt(moment(tectime, "h:mm").format('X')) )/60;
					if (fornow > 0 && fornow < mtime && timeB[k].pId == pId) {
						z++;		
					}
				}
				
				if (z) {
					continue;
				}
			}
			if (tmpmin) {
				if (moment(tmptime, 'HH:mm').isSameOrBefore(moment(hours + ':' + minutes, 'HH:mm'))) { 
				if (pId) {
					timeList.push({'time':hours + ':' + minutes,pId});
				}
				else {
				timeList.push(hours + ':' + minutes); 
				}
				var tmpmin = false;
				}
			}
			else {
				if (pId) {
					timeList.push({'time':hours + ':' + minutes,pId});
				}
				else {
					timeList.push(hours + ':' + minutes);
				}	
			}
		}
		
    }
		}
	/*убираем из массива уже прошедшее за сегодня время*/
	if (today == dateText) {
	timeList = book_oz_remove_prosh_vr(timeList);
	}
	console.log('собрал массив времени');
	return timeList;
		}
		
/*убираем из текущего дня уже прошедшее время*/
function book_oz_remove_prosh_vr(time) {
	var ttime = new Array();
	var tnow = moment().format('X');
	for (i=0;i<time.length;i++) {
	var t = (typeof time[i].time == 'undefined') ? time[i] : time[i].time;
	var tech = moment(t, "h:mm").format('X');
		if (tech > tnow) {
		ttime.push(time[i]);
		}
	}
	return ttime;
}

function book_oz_printTime(timeList) {
	var checked = '';
	timeList = timeList.sort(function(a,b){
		var a = (a.time) ? a.time : a;
		var b = (b.time) ? b.time : b;		
		return parseInt(a.replace(':','')) - parseInt(b.replace(':',''));
	});
	jQuery('.timeRange').remove();
	$('#oz_time_rot').parents('.at-field').append('<ul class="timeRange" />');
	for (var i=0; i<timeList.length; i++) {
		if (typeof timeList[i].time !== 'undefined' && $('input.checkb[value="'+timeList[i].time+'"]').length) {
			$('input.checkb[value="'+timeList[i].time+'"]').attr('data-pId',$('input.checkb[value="'+timeList[i].time+'"]').attr('data-Pid')+','+timeList[i].pId);
		}
		else {
			var tStr = moment(timeList[i].time, 'HH:mm').locale(oz_vars.lang.split('_')[0]).format(oz_vars.timeFormat);
	jQuery('.timeRange').append(' <li class="squaredThree">'+
      '<input class="checkb" data-pId="'+timeList[i].pId+'" type="checkbox" '+checked+' value="'+timeList[i].time+'" name="oz_time_rot_block" />'+
		'<label for="squaredThree">'+tStr+'</label>'+
	  '</li>');
		}
	}
	
	book_oz_viborVremeni();
	var event= new CustomEvent('onTimeListRender');
	document.addEventListener('onTimeListRender',function(){},false);
	document.dispatchEvent(event);
}

function book_oz_checkFreeTimeSpec(spec,t) {
		var post_ID = ($('input[name="post_ID"]')) ? $('input[name="post_ID"]').val() : '';
 		var data = {
					action: 'checkSvobTime',
					nonce : oz_vars.nonce,
					spec:spec,
					time:t,
					date: $('input[name="oz_start_date_field_id"]').val(),
					servtime: $('select[name="oz_uslug_set"] option:selected').attr('data-servtime'),
					post_ID
				};
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.adminAjax,
		success: function(response,status) {
		if (response && response !== 'ok' ) {
			alert(response);
			$('.squaredThree').removeClass('active');
			$('input[name="oz_time_rot"]').val('');
		}
		}
		}); 
}

function book_oz_viborVremeni() {
	$('.squaredThree').click(function() {
		$('.squaredThree').removeClass('active');
		$(this).addClass('active');
		var t = $(this).find('input').val();
		var speci = $(this).find('input').attr('data-pid').split(',');
		$('input[name="oz_time_rot"]').val(t);
		jQuery('.at-posts-select[name="oz_personal_field_id"] option').not(':first').prop('disabled',true);
		for (var i = 0; i< speci.length; i++) {
			jQuery('.at-posts-select[name="oz_personal_field_id"] option[value="'+speci[i]+'"]').prop('disabled',false);
			book_oz_checkFreeTimeSpec(speci[i],t);
		}
	});
}

function book_oz_disabledSpec() {
	if ( jQuery('.at-posts-select[name="oz_uslug_set"]').val() == -1 ) {
		jQuery('.at-posts-select[name="oz_personal_field_id"]').prop('disabled',true);
	}
	
	else {
		jQuery('.at-posts-select[name="oz_personal_field_id"]').prop('disabled',false);
	}
}

book_oz_disabledSpec();

function book_oz_checkIfClientExist() {
	/*
	проверяем если клиент создан, то выдаем выбранное дату и время
	*/
	var emp = [];
	var dateText;
	var flagTime = 1;
	if ($('.hidenextTr').length) {
	$('.hidenextTr').next().hide();
	$('.hidenextTr span').click(function() {
	$('.hidenextTr').hide().next().show(); 
	});
	}
	var select =  $('.at-posts-select[name="oz_personal_field_id"]').val(); 
	/*добавляем id спеца по умолчанию*/
	if ($('*[name="oz_personal_field_id"]').val() == '') {$('*[name="oz_personal_field_id"]').val(select)};
	book_oz_reDatePersonal(select);
	var dateText = $('input[name="oz_start_date_field_id"]').val();
	var currentClient = $('input[name="post_ID"]').val();
	$.each($('.ui-datepicker-current-day').attr('class').split(' '), function(i,v) {
		if (v.indexOf('time-') > -1) {
			var pId = v.split('-')[3];
			emp.push({'start':v.split('-')[1],'end':v.split('-')[2], pId});
		}
	});
	book_oz_checkCurrentZapisi(dateText,emp,currentClient);
	var time = $('input[name="oz_time_rot"').val();
	/* когда вывелось время на страницу */
	document.addEventListener('onTimeListRender',function(e){
			if (flagTime > 0) {
		$('.checkb[value="'+time+'"]').parents('.squaredThree').addClass('active');	
		flagTime--;
			}
	},false);
}

function book_oz_reDatePersonal(select,def = 0) {
	/*
	select - список id сотрудников
	*/
console.log('select is '+select);
$.datepicker.setDefaults(book_oz_setLang);
$('#datePickerInput').datepicker( "destroy" );
if (def <=-1 || select <=-1) return;
var TimeWeek = [];	
if (typeof select == 'string' || typeof select == 'number') {
	var arr = typeof select == 'string' ? select.split(',') : [select];
 	for (i=0;i<arr.length;i++) {
		var sotrudnik = jQuery('select[name="oz_personal_field_id"] option[value="'+arr[i]+'"]');
		var timeweek = JSON.parse(sotrudnik.attr('data-days'));
		sotrudnik.attr('disabled',false);
		for (t=0;t<timeweek.length;t++) {
			TimeWeek.push(timeweek[t]);
		}
	}
		//$('.at-posts-select[name="oz_personal_field_id"]').select2('destroy').select2();
}
//TimeWeek = JSON.parse(jQuery('select[name="oz_personal_field_id"] option:selected').attr('data-days'));
time = [];
time =  book_oz_plav_bus_days(TimeWeek);
jQuery('#datePickerInput').datepicker({
	minDate: 0,
	maxDate: '2M',
	dateFormat: "dd.mm.yy",
	defaultDate: jQuery('#oz_start_date_field_id').val(),
	beforeShowDay: function(date){
				var popup;
				if (typeof time == 'undefined') return [ 1, ];
				//console.log(date);
				var AllIndexes = [];
				var day = jQuery.datepicker.formatDate('yy-mm-dd', date);
				var today = moment().format('YYYY-MM-DD');
				//console.log('формируем календарь');
				//console.log(time);
				var ind = time.map(function(obj,ind) { if (obj.day == day) {AllIndexes.push(time[ind])} return obj.day }).indexOf(day);
				var fal = 0;
				if (ind > -1) {
					if (AllIndexes.length) {
						var popup = '';
						for (var key in AllIndexes) {
/* 							console.log(moment().format('YYYY-MM-DD')+' - '+day+' - '+moment()+' - '+moment(AllIndexes[key].end,'HH:mm')); */
							popup += (typeof AllIndexes[key].start !== 'undefined') ? 'time-'+AllIndexes[key].start+'-'+AllIndexes[key].end+'-'+AllIndexes[key].pId+' ' : ''; // поставил условие, т.к. не понимаю почему появляется undefined
							if (day == today && moment() >= moment(AllIndexes[key].end,'HH:mm') ) {
								var fal = 0;
							} 
							else {
								var fal = 1;
							}
						}
					}
				}
				
						return [ fal, popup, ];
			},
	onSelect: function(dateText,inst) {
			var day  = inst.selectedDay,
                    mon  = inst.selectedMonth,
                    year = inst.selectedYear;

                var td = jQuery(inst.dpDiv).find('[data-year="'+year+'"][data-month="'+mon+'"]').filter(function() {
                    return jQuery(this).find('a').text().trim() == day;
                });
			var date = jQuery(this).datepicker('getDate');
			var dayOfWeek = date.getUTCDay();
			var minT, maxT;
			min = 9999;
			max = 0;
			var arrMin = [];
			var arrMax = [];
			var emp = [];
			jQuery.each(jQuery(td).attr('class').split(' '), function(i,v) {
				if (v.indexOf('time-') > -1) {
				var t = parseInt(v.split('-')[1].replace(':',''));
				var n = parseInt(v.split('-')[2].replace(':',''));
				var pId = (v.split('-')[3]) ? v.split('-')[3] : '';
				emp.push({'start':v.split('-')[1],'end':v.split('-')[2], pId});
				arrMin.push({'min':v.split('-')[1], pId});
				arrMax.push(v.split('-')[2]);
				var minR = 15;
				var min = (arrMin) ? arrMin : TimeWeek[0].start;
				var max = (arrMax) ? arrMax : TimeWeek[0].end;
				var clientRas = '';
				//generTimeList(min,max,minR,'',i,dateText); // общий массив сотрудников работающих в этот день
			var min,max,minT,maxT;
			}
			});
			var currentClient = $('input[name="post_ID"]').val(); /* id клиента */
			book_oz_checkCurrentZapisi(dateText,emp,currentClient);
			$('input[name="oz_start_date_field_id"]').val(dateText);
			$('.if_not_set_date').addClass('hide');
}
});
}

$(document).ready(function() {
	$('<span class="if_not_set if_not_set_serv hide">Service not set</span>').insertBefore('#datePickerInput');
	$('<span class="if_not_set if_not_set_date hide">Date not set</span>').insertBefore('#oz_time_rot');
	if ($('.at-posts-select[name="oz_personal_field_id"]').length && $('.at-posts-select[name="oz_personal_field_id"]').val() > -1) {
	book_oz_checkIfClientExist();
	}
	else {
		$('.if_not_set').removeClass('hide');
	}
	
	/* проверяем на клик "обновить" обязательные поля, а также если не выбран спец, то автоматически прикрепляем запись к любому свободному */  
  $('#publish').click(function( event ) {
	  if ($('*[name="oz_time_rot"]').val() == '' ||  $('*[name="oz_uslug_set"]').val() < 0 ||  $('*[name="oz_personal_field_id"]').val() < 0 ) {
		event.preventDefault();
		/* обязательные поля для заполнения */
		var reqArr = [
		'*[name="oz_start_date_field_id"]',
		'*[name="oz_uslug_set"]',
		'*[name="oz_time_rot"]',
		'*[name="oz_personal_field_id"]'
		];
		var reqPolya = true;
		$.each(reqArr, function(i,v) {
		if ($(v).val() == '' || $(v).val() < 0) {
		$(v).parents('td').addClass('red').prepend('<span class="oz_req"> Required field! </span>');
		setTimeout(function() {$(v).removeClass('red'); $('.oz_req').remove();},1500);
		reqPolya = false;
		}
	});
		if (reqPolya) {
        var postID=$('#post_ID').attr('value');
		$('#publish').click();
		}
	  }
	});
});

 jQuery('.at-posts-select[name="oz_personal_field_id"]').change(function() { 
	jQuery('.timeRange').remove();
	$('input[name="oz_start_date_field_id"]').val('');
	$('input[name="oz_time_rot"]').val('');
	var select = jQuery(this).val();
	var uslugi = jQuery('.at-posts-select[name="oz_uslug_set"] option');
	if (select > -1) {
	$('.if_not_set_serv').addClass('hide');
	uslugi.each(function() {
	if (jQuery(this).val() > -1) {
		var speci = jQuery(this).attr('data-pers').split(',');
		if ( speci.indexOf(select) > -1) {
		jQuery(this).prop('disabled',false);
		}
		else {
		jQuery(this).prop('disabled',true);
		}
	}
	});
	}
	else {
		uslugi.prop('disabled',false);
		$('.if_not_set_serv, .if_not_set_date').removeClass('hide');
		$('.at-posts-select[name="oz_uslug_set"]').val(-1);
		$('input[name="oz_time_rot"], input[name="oz_start_date_field_id"]').val('');
	}
	$('.at-posts-select[name="oz_uslug_set"]').select2('destroy').select2();
	book_oz_reDatePersonal(select);
});

$('.at-posts-select[name="oz_uslug_set"], .at-posts-select[name="oz_personal_field_id"]').on('select2:select', function() {
		$(this).select2('destroy').select2();
	});

$('.at-posts-select[name="oz_uslug_set"]').change(function() { 
	$('.timeRange').remove();
	$('input[name="oz_start_date_field_id"]').val('');
	$('input[name="oz_time_rot"]').val('');
	book_oz_disabledSpec();
	var select = $(this).find('option:selected').attr('data-pers');
	var def = $(this).val();
	var sotrudniki = $('.at-posts-select[name="oz_personal_field_id"] option');
	if (def > -1) {
	$('.if_not_set_serv').addClass('hide');
	sotrudniki.each(function() {
	var speci = select.split(',');
		if ( speci.indexOf($(this).val()) > -1 || $(this).val() == -1) {
		$(this).prop('disabled',false);
		}
		else {
			$(this).prop('disabled',true);
			}
		});
	}
	else {
		sotrudniki.prop('disabled',false);
		$('.if_not_set_serv, .if_not_set_date').removeClass('hide');
		$(this).find('option').prop('disabled',false);
		$(this).trigger('change.select2');
		$('.timeRange').remove();
		$('.at-posts-select[name="oz_personal_field_id"]').val(-1);
		$('input[name="oz_time_rot"], input[name="oz_start_date_field_id"]').val('');
	}
	/* если уже выбран сотрудник, то берем только его id */
	if ($('.at-posts-select[name="oz_personal_field_id"]').val() > -1) {
		var select = $('.at-posts-select[name="oz_personal_field_id"]').val();
	}
	//$('.at-posts-select[name="oz_personal_field_id"]').select2('destroy').select2();
	book_oz_reDatePersonal(select,def);
});

 })(jQuery);
 
 /*********************end клиенты*****************************/