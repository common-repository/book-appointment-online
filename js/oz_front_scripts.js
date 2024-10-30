(function( $ ) {
var zagArray = new Array();
var stepAnN = 0;
var stopAnimationSet;
stopAnimation = false;
document.addEventListener('animateNextEv',function(e){
	if (e.detail.reverse == false) {
	var tZ = $('.oz_container .stepname').text();
	if (zagArray.indexOf(tZ) < 0) {
	zagArray.push(tZ);
	}
	stepAnN++;
	}

	else {
	stepAnN--;
	$('.oz_container .stepname').text(zagArray[stepAnN]);
	}	
	var zAT = zagArray.length - 1;
	if (Math.round(parseFloat(e.detail.left)) >= 0) {
		$('.oz_back_btn').addClass('fadeOutTop');
		zagArray.splice(0,zagArray.length);
	}
	
	else {
		$('.oz_back_btn').removeClass('fadeOutTop');
	}
	
	if (stepAnN <= 0) {
		$('.oz-form input.chist[type="hidden"]').val('');
	}
	if (stepAnN == 1) {
	$('.oz_zapis_info .oz_spec_info').hide();
	}
	
},false);
	
	
$('.oz_back_btn').click(function() {
	animateNext('.oz_hid_carousel',false,false,true);
});



/* выпадающий список sms оповещений*/
$('#rememSms').change(function() {
		$('.remList').toggleClass('hide');
		if ($(this).prop('checked') == true) {
			//oz_remList
			$('input[name="oz_remList"]').val($('.remDef').attr('data-rem'));
		}
		
		else {
			$('input[name="oz_remList"]').val('');
		}
});
$('.remDef').text($('.spisokRem ul li').eq(0).text()).attr('data-rem',$('.spisokRem ul li').eq(0).attr('data-time'))
			.click(function() {
			var par = $(this).parent();
			if (par.hasClass('oz_open')) {
			par.removeClass('oz_open');		
			}
			
			else {
			par.addClass('oz_open');				
			}
			

			});
$('.spisokRem li').click(function() {
	$('.remDef').text($(this).text()).attr('data-rem',$(this).attr('data-time'));	
	$('.remList').removeClass('oz_open');	
	$('input[name="oz_remList"]').val($('.remDef').attr('data-rem'));
})
/* выпадающий список sms оповещений*/

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
 
function getAllIndexes(arr, val) {
    var indexes = [], i;
    for(i = 0; i < arr.length; i++)
        if (arr[i].day === val)
            indexes.push(i);
    return indexes;
}

function plav_bus_days(rasp) {
	
	var start = moment();
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
		
		var plav_bus_days = postV_bus_days;
			

		var end = moment() - start;
		console.log('Время генерации рабочих дней: '+end+'мс');
	 
	 return plav_bus_days;
  }	
	
function animateNext(selector, shag = 16.666667, callback = false, reverse = false) {
	if (stopAnimation) {
		return false;
	}
	clearTimeout(stopAnimationSet);
	stopAnimation = true;
	if (shag == false) {
		var shag = 16.66667;
	}
	if (reverse == true) {
	var left = shag+'%';
	}
	else {
	var left = '-'+shag+'%';
	}
	$(selector).off('click');
	if (typeof $(selector).attr('style') != 'undefined') {
	var left = CSSJSON.toJSON($(selector).attr('style')).attributes.transform.split('(')[1].split('%')[0];
	if (reverse == false) {	
	var left = left-shag+'%';
	}
	else {
		var left = Number(left) + Number(shag);
		var left = left+'%';
	}
	}
	
	if (!left || Math.round(parseFloat(left)) > 0) {
		return false;
	}
	
	$(selector).css({
		transform: 'translateX('+left+')',
	});
	
	if (typeof callback === "function") {
		setTimeout(callback(),700);
	}
	
	var event= new CustomEvent('animateNextEv',{detail:{selector:selector,left:left,reverse:reverse}});
	document.addEventListener('animateNextEv',function(){},false);
	document.dispatchEvent(event);
	/* задержка чтобы не было двойного клика */
	stopAnimationSet = setTimeout(function() {stopAnimation = false;
		var event= new CustomEvent('animateNextEvAfter',{detail:{selector:selector,left:left,reverse:reverse}});
		document.addEventListener('animateNextEvAfter',function(){},false);
		document.dispatchEvent(event);
	},700);
}	
	
function objectifyForm(formArray) {//serialize data function

  var returnArray = {};
  for (var i = 0; i < formArray.length; i++){
    returnArray[formArray[i]['name']] = formArray[i]['value'];
  }
  return returnArray;
}

function CheckDuplicateInArray(arr){
	var result = [];
	for(i=0; i< arr.length; i++){
		var duplicate = 0;
		var val = arr[i];
		arr.map(function(x){
			if(val == x) duplicate++;
		})
		result.push({'true': duplicate>= 2, 'value' : val});
	}
	return result;
}

function countInArray(arr, what) {
    var count = 0;
    for(i=0; i< arr.length; i++){
        if (arr[i] === what) {
            count++;
        }
    }
    return count;
}
	
	
/* это была функция ozFormSend*/
	$('#timeForm .oz-form').submit(function(e) {
		e.preventDefault();
		var req = $(this).find('input[aria-required="true"]');
		var form = $(this);
		fl = true; 
		if (req.length) {
			req.each(function(i,v) {
				if ($(v).val() == '') {
					$(v).addClass('req');
					setTimeout(function() {$(v).removeClass('req')},2000);
					fl = false; 
				}
			});
		}
			if (fl) {
 		$('body').addClass('oz_load');
		var data = objectifyForm($( this ).serializeArray());
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.oz_ajax_url,
		success: function(response,status) {
		form.trigger("reset");
			$('body').removeClass('oz_load');
					$('.oz_zapis_info').html('<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg> <p class="oz_check">'+oz_lang.str1+'</p>');
					$('.form_fields').slideUp();
			$('.oz_back_btn').addClass('fadeOutTop'); 
			var event= new CustomEvent('ozBookSend',{detail:{response:response}});
			document.addEventListener('ozBookSend',function(){},false);
			document.dispatchEvent(event);
		}
		}); 
			}
	});

	
function showFormOrd(dateText) {
	jQuery('input.checkb').on('change', function() {
		jQuery('input#oz_time_rot').val(jQuery(this).val());
		jQuery('input.checkb').not(this).prop('checked', false); 
		var timerot = jQuery(this).val();
		var pers = $('.personal.active .pname').attr('data-id');
		$('#timeForm .oz-form input[name="oz_start_date_field_id"]').val(dateText);
		$('#timeForm .oz-form input[name="oz_time_rot"]').val(timerot);
		$('#timeForm .oz-form input[name="oz_personal_field_id"]').val(pers);
		//ozFormSend(dateText, timerot);
		
	});
}
	
function generTimeList(timestart,timefinish,range = 30, zapisi = [], dateText = null) {
	var hours, minutes;
    time = [];
	timeB = [];
	b = Number(range);
	var thAj = true;
	if (zapisi == '') {
		$('body').addClass('oz_load');
		var thAj = false;
		var ids = ($('.oz_service.active').attr('data-ids')) ? $('.oz_service.active').attr('data-ids') : '';
		var data = {
		action: 'dayRaspis',
		dateText: dateText,
		ids: ids
		
		};
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.oz_ajax_url,
		success: function(response,status) {
		$('body').removeClass('oz_load');
		var zapisi = response;
		animateNext('.oz_hid_carousel');
		if (Array.isArray(timestart)) {
		generTimeListInFn(zapisi,dateText,thAj, timestart.length,timestart,timefinish);
		}
		}
		}); 
	}
	if (thAj) {
/* 	dHStart = Number(timestart.split(':')[0]);
	dMStart = Number(timestart.split(':')[1]);
 	dHFinish = Number(timefinish.split(':')[0]);
	dMFinish = Number(timefinish.split(':')[1]) ; */
	animateNext('.oz_hid_carousel');
	generTimeListInFn(zapisi,dateText,thAj, timestart.length,timestart,timefinish);
	//generTimeListInFn(zapisi,dateText);
	}
	
}

function findIndex(key,arr) {
    for(var i=0, j=arr.length; i<j; i++) {
        if(arr[i].hasOwnProperty(key)) {
            return i;
        }
    }
    return -1;
}

function generTimeListInFn(zapisi,dateText, thAj = true, iter = 0, timestart = 0, timefinish = 0) { // iter - сколько сотрудников столько и прогонов времени
	var tmptime;
	var startTimelist = moment();
if (typeof zapisi === 'string' ) {
	var bTime = JSON.parse(zapisi);
	$.each(bTime,function(i,v) {
		if (dateText == v.dayStart) {
			var mtime = v.w_time;
			timeB.push({start:v.timeStart,w_time:mtime,id:v.pers_id});
		}
	})
	}
	
	else {
	var Vremya = zapisi.pop();
/* 	for (key in Vremya) {
		timeB.push(Vremya[key].start.split(' ')[1]);
	} */
	}
	
	if (iter > 0)  {
	for (var j = 0; j < iter; j++) { // прогон сотрудников по времени
	dHStart = Number(timestart[j].min.split(':')[0]);
	dMStart = Number(timestart[j].min.split(':')[1]);
	dHFinish = Number(timefinish[j].split(':')[0]);
	dMFinish = Number(timefinish[j].split(':')[1]) ;
	pId = timestart[j].pId;
	forTime(dateText,iter,pId);
	}
	time = time.filter(function(elem, index, self) {
	var onlytime = [];
	for (i = 0; i < self.length; i++) {
		onlytime.push(self[i].time);
	}
		//return index == findIndex(elem.time,self);
		return index == onlytime.indexOf(elem.time);
	});
	time = time.sort(function(a,b){
		var a = (a.time) ? a.time : a;
		var b = (b.time) ? b.time : b;		
		return parseInt(a.replace(':','')) - parseInt(b.replace(':',''));
	});
	}
	
	else {
		forTime(dateText);
	}
	if (!$('.oz_service.active').length) time.pop();
	$('.timeRange').remove();
	if ($('.inlinedatepicker').length) {
		$('<ul class="timeRange" />').insertAfter('.inlinedatepicker');
		timerU = true;
		timerD = true;
		timerE = true;
		jQuery.each(time,function(i,v) {
		var pId = (v.pId) ? v.pId : '';
		var v = (v.time) ? v.time : v;
	if (timerU && v.split(':')[0] < 12) {
	jQuery('.timeRange').append('<li class="zagday squaredThree">'+oz_lang.str2+'</li>');
	timerU = false;	
	}
	if (v.split(':')[0] > 11 && v.split(':')[0] < 18 && timerD) {
		jQuery('.timeRange').append('<li class="zagday squaredThree">'+oz_lang.str3+'</li>');
		timerD = false;
	}
	if (v.split(':')[0] > 17 && timerE) {
	jQuery('.timeRange').append('<li class="zagday squaredThree">'+oz_lang.str4+'</li>');
	timerE= false;	
	}
	var vStr = moment(v, 'HH:mm').locale(oz_vars.lang.split('_')[0]).format(oz_vars.timeFormat)
			jQuery('.timeRange').append(' <li class="squaredThree">'+
			  '<input id="time-'+i+'" data-pId="'+pId+'" class="checkb" type="checkbox" value="'+v+'" name="oz_time_rot" />'+
				'<label for="time-'+i+'">'+vStr+'</label>'+
			  '</li>');
		});
	}
//document.getElementById("genertimelist").innerText = time ;
jQuery('#genertimelist').html('');

jQuery.each(time,function(i,v) {
	var setCh = jQuery('input#oz_time_rot').val();
	var checked = (setCh == v) ? 'checked' : '';
	var v = (v.time) ? v.time : v;
	var pId = (v.pId) ? v.pId : '';
	jQuery('#genertimelist').append(' <li class="squaredThree">'+
      '<input class="checkb" data-pId="'+pId+'" type="checkbox" '+checked+' value="'+v+'" name="oz_time_rot" />'+
		'<label for="squaredThree">'+v+'</label>'+
	  '</li>');
});

var timestart, timefinish;
showFormOrd(dateText);
checkTime(thAj, dateText, timeB);
var end = moment() - startTimelist;
}

/*убираем из текущего дня уже прошедшее время*/
function remove_prosh_vr(time) {
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

/* генерация массива времени */
function forTime(dateText = 0,iter = 0, pId = 0) {
		/* проверка текущего времени */
		b = (typeof oz_vars.timeslot !== 'undefined') ? parseInt(oz_vars.timeslot) : b; // временной интервал
		var today = moment().format('DD.MM.YYYY');
    for(var i = (dHStart*60)+dMStart; i <= 1440; i += b){
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
	hours < dHStart || 
	(hours == dHStart && minutes < dMStart ) ||
	hours > dHFinish ||
	(hours == dHFinish && minutes > dMFinish)
	) {
		continue;	
		}
		
		/* не помню зачем это*/
/* 		if ($('.oz_service').length) {
			var serL = $('.oz_service').length+1;
			var timeC = [];
			$.each(timeB,function(i,v) {
			if (countInArray(timeB, v) >= serL ) {
			timeC.push(v);
			}
		});
		timeB = timeC;
		} */
		var index = timeB.map(function (img) { return img.start; }).indexOf(hours + ':' + minutes);
		if (index > -1) {
		var tmpmin = (timeB[index].w_time != '') ? parseInt(minutes) + parseInt(timeB[index].w_time) : false;
		if (tmpmin) {
        if (tmpmin < 10){
            tmpmin = '0' + tmpmin;
        }
		if (tmpmin >= 60 ) {
			/* прибавляем часы и остаток это минуты*/
			var plushour = Math.floor(tmpmin/60);
			var plusminutes = tmpmin%60;
			var tmpmin = plusminutes;
			var hours = parseInt(hours) +plushour;
			if (hours < 10) {
				hours= '0' + hours;
			}
			if (tmpmin < 10) {
				tmpmin= '0' + tmpmin;
			}		}
		var tmptime = hours + ':' + tmpmin;
		}
		
					if (timeB[index].id == pId) {
					timeB.splice(index, 1);
					}
		
		}
		
		else {
			if ($('.oz_service.active').length) {
				var mtime = $('.oz_service.active').attr('data-mintime'); // возможно распространится на специалистов [не выводить время если следующая запись начнется раньше]
				var z = 0;
				var tectime = hours+':'+minutes;
				/* не выводить время если конец рабочего дня*/
				var forEnd = Math.abs(parseInt(moment(dHFinish+':'+dMFinish, "h:mm").format('X')) - parseInt(moment(tectime, "h:mm").format('X')) )/60;
				if (forEnd < mtime) {
					continue;
				}
				for (k=0; k < timeB.length; k++ ) {
					var fornow =  Math.abs(parseInt(moment(timeB[k].start, "h:mm").format('X')) - parseInt(moment(tectime, "h:mm").format('X')) )/60;
					if (fornow < mtime || forEnd < mtime ) {
						z++;		
					}
				}
			}
			if (z) {
				continue;
			}
			if (tmpmin) {
				// todo сравнить время а не строку
				if (parseInt(moment(tmptime, "h:mm").format('X')) <= parseInt(moment(hours + ':' + minutes, "h:mm").format('X'))) { 
				if (pId) {
					time.push({'time':hours + ':' + minutes,pId});
				}
				else {
				time.push(hours + ':' + minutes); 
				}
				var tmpmin = false;
				}
			}
			else {
				if (pId) {
					time.push({'time':hours + ':' + minutes,pId});
				}
				else {
					time.push(hours + ':' + minutes);
				}			
			}
		}
		
    }
	/*убираем из массива уже прошедшее за сегодня время*/
	if (today == dateText) {
	time = remove_prosh_vr(time);
	}
		}

function parseTime(s) {
   var c = s.split(':');
   return parseInt(c[0]) * 60 + parseInt(c[1]);
}

function checkTime( thAj = true, dateText = '', timeB = '') {
jQuery('input.checkb').on('change', function() {
	if ($(this).attr('data-pId')) {
$('input[name="oz_personal_field_id"]').val($(this).attr('data-pId'));
	}	
	var th = jQuery(this).val();
	var thStr = moment(th, 'HH:mm').locale(oz_vars.lang.split('_')[0]).format(oz_vars.timeFormat);
	$('.oz_zapis_info .oz_time_info').text(thStr);
	if (!thAj) {
	animateNext('.oz_hid_carousel');
	$('.stepname').html(oz_lang.str5);
	}
	jQuery('input#oz_time_rot').val(jQuery(this).val());
    jQuery('input.checkb').not(this).prop('checked', false);  
	if (thAj) {
		/* находим промежуток времени до следующей записи */
		var minRange = 9999;
		$.each(timeB, function(i,v) {
			var tec = parseTime(v.start) - parseTime(th);
			if (tec > 0 && tec < minRange) {
				 minRange = tec;
			}
		});
		if (typeof dHFinish != 'undefined' && typeof dMFinish != 'undefined' ) {
			var tecEnd = parseTime(dHFinish+':'+dMFinish) - parseTime(th);
			if (tecEnd < minRange) {
				minRange = tecEnd; 
			}
		}
	listUslug(dateText,minRange);
	}
});	
}

function listUslug(dateText,minRange) {
	$('body').addClass('oz_load');
	$('.listUslug, .uslugas').remove();
	var ids = $('.personal.active .pname').attr('data-usl');
	var id = $('.personal.active .pname').attr('data-id');
		var data = {
		action: 'listUslug',
		ids:ids,
		id:id,
		minRange:minRange
		};
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.oz_ajax_url,
		success: function(response,status) {
			//animateNext('.oz_hid_carousel');
			$('body').removeClass('oz_load');
			$('.listUslug').remove();
			$('<ul class="listUslug" />').insertAfter('.timeRange');
			$('.listUslug').html(response);
			animateNext('.oz_hid_carousel');
			$('.stepname').html(oz_lang.str6);
			oz_uslug_set();
		}
		}); 
}

function addTen() {
	$('#timeForm').addClass('tf-ten');
}

function oz_uslug_set() {
	$('.usluga').click(function() {
		var ids = $(this).attr('id').split('-')[1];
		$('#timeForm input[name="oz_uslug_set"]').val(ids);
		$('.oz_zapis_info .oz_usluga_info span').text($(this).find('p').text());
		animateNext('.oz_hid_carousel',addTen());
		$('.stepname').html(oz_lang.str7);
	});
}
	
function getTimerasp(dateText) {
	var aTime = JSON.parse($('.personal .pname').attr('data-days'));
	
}

function createCalendar(time = {},clientRas = '',TimeWeek = {}) {
		time =  plav_bus_days(TimeWeek);
		$('.inlinedatepicker').datepicker({
			minDate: 0,
			maxDate: '1M',
			beforeShowDay: function(date){
				var popup;
				var AllIndexes = [];
				var day = $.datepicker.formatDate('yy-mm-dd', date);
				var today = moment().format('YYYY-MM-DD');
				var ind = time.map(function(obj,ind) { if (obj.day == day) {AllIndexes.push(time[ind])} return obj.day }).indexOf(day);
				var fal = 0;
				if (ind > -1) {
					if (AllIndexes.length) {
						var popup = '';
						for (var key in AllIndexes) {
							popup += 'time-'+AllIndexes[key].start+'-'+AllIndexes[key].end+'-'+AllIndexes[key].pId+' ';
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
			dateFormat: 'dd.mm.yy',
			onSelect: function(dateText,inst) {
			var day  = inst.selectedDay,
                    mon  = inst.selectedMonth,
                    year = inst.selectedYear;

                var td = $(inst.dpDiv).find('[data-year="'+year+'"][data-month="'+mon+'"]').filter(function() {
                    return $(this).find('a').text().trim() == day;
                });
			var dateTextStr = moment(dateText, 'DD.MM.YYYY').locale(oz_vars.lang.split('_')[0]).format(oz_vars.dateFormat);
			$('.oz_zapis_info .oz_date_info').text(dateTextStr);
			var date = $(this).datepicker('getDate');
			var dayOfWeek = date.getUTCDay();
			var list=["oz_mon", "oz_tue", "oz_wed", "oz_thu", "oz_fri", "oz_sat","oz_sun"];
			var atNow = list[dayOfWeek];

			var minT, maxT;
			min = 9999;
			max = 0;
			var arrMin = [];
			var arrMax = [];
			
			$.each($(td).attr('class').split(' '), function(i,v) {
						if (v.indexOf('time-') > -1) {
						var t = parseInt(v.split('-')[1].replace(':',''));
						var n = parseInt(v.split('-')[2].replace(':',''));
						var pId = (v.split('-')[3]) ? v.split('-')[3] : '';
						arrMin.push({'min':v.split('-')[1], pId});
						arrMax.push(v.split('-')[2]);
						}
			});
				var minR = 15;
				var min = (arrMin) ? arrMin : TimeWeek[0].start;
				var max = (arrMax) ? arrMax : TimeWeek[0].end;
				generTimeList(min,max,minR,clientRas,dateText); // это основная генерация времени
			
			var min,max,minT,maxT;
			}
		});
}
	
function personal() {
	$('.personal').click(function() {
		$('.personal, .oz_service').removeClass('active');
		$(this).addClass('active');
		animateNext('.oz_hid_carousel');
			$('.inlinedatepicker, .uslugas').remove();
			var mintimeUslug = null;
				$('<div class="inlinedatepicker" />').insertAfter('.personals');
		var time = $(this).find('.pname').attr('data-days');
		var clientRas = $(this).find('.pname').attr('data-raspis');
		TimeWeek = JSON.parse(time);
		$('.stepname').html(oz_lang.str9);
		var specName =  $(this).find('.pname').text();
		if (specName != '') {
		$('.oz_zapis_info .oz_spec_info').show();
		$('.oz_zapis_info .oz_spec_info span').text(specName);
		}
		else {
		$('.oz_zapis_info .oz_spec_info').hide();	
		}
		createCalendar(time,clientRas,TimeWeek);

	});
}
	$(document).ready(function() {
		$('input[name="clientEmail"]').inputmask();
			$('.oz_vibor .oz_btn').click(function() {
			
			var afterchego;	
			$('body').addClass('oz_load');
			var rod = $(this).parents('.filial');
			var term_id = rod.attr('id').split('-')[1];	
			var atup = $(this).attr('data-atup');
			var afterchego = '.filials';
			var data = {
			action: 'check'+atup,
			id:term_id,
			type:atup,
			};
		$.ajax( {
		data:data,
		type:'POST',
		url:oz_vars.oz_ajax_url,
		success: function(response,status) {
				$('.'+atup+'s').remove();
				$('<ul class="'+atup+'s" />').insertAfter(afterchego);
				$('body').removeClass('oz_load');
				animateNext('.oz_hid_carousel');
				if (atup == 'usluga') {
				$('.stepname').html(oz_lang.str6);
				$('.personals').remove();
				}
				else {
				$('.stepname').html(oz_lang.str10);
				}
				$('.'+atup+'s').html(response);
				personal();
		},
		
		error: function(err) {
			if (err.status) {
				$('.oz_err').remove();
				$('.stepname').append('<span class="oz_err"> Error '+err.status+'! '+oz_lang.str11);
				$('body').removeClass('oz_load');
			}
		}
		});
			});
	});
	
	if (typeof persStart != 'undefined' && persStart) {
		personal();
	}
	
/* адаптивные карточки*/
function oz_cart_width() {
	var width = $('.oz_container').width();
	if (width < 992 && width > 767) {
	$('.oz_container').addClass('oz_width_xl_tablet').removeClass('oz_width_mob oz_width_tablet');
	}
	else if (width < 768 && width > 481) {
		$('.oz_container').addClass('oz_width_tablet').removeClass('oz_width_mob');
	}
	else if ( width < 481) {
		$('.oz_container').addClass('oz_width_mob');
		document.addEventListener('animateNextEv',function(e){
			/* скролл к верхей части плагина*/
			if ($('.oz_popup').length) {
			$("#oz_overlay").stop().animate({scrollTop:$('.oz_container').offset().top});	
			}
			else {
			$("html, body").stop().animate({scrollTop:$('.oz_container').offset().top});		
			}
		},false);
	}
	
	else {
		$('.oz_container').removeClass('oz_width_mob oz_width_tablet oz_width_xl_tablet');
	}
}

oz_cart_width();
$(window).resize(function() {
	oz_cart_width();
});

/* всплывающее окно*/
    $('.oz_pop_btn').click(function() {
        $('#oz_overlay').fadeIn('fast');
        setTimeout(function() {
            $('.oz_popup').show();
			oz_cart_width();
        },50);
        setTimeout(function() {
            $('.oz_popup').addClass('open');
        },200);
    });
    
        $('#oz_overlay .close').click(function() {
        $('.oz_popup').removeClass('open');
        setTimeout(function() {
            $('#oz_overlay, .oz_popup').hide();
        },250);
    });
    
        $('#oz_overlay').click( function(event){
      if( $(event.target).closest('#oz_overlay .oz_popup').length ) 
        return;
        $('.oz_popup').removeClass('open');
        setTimeout(function() {
            $('#oz_overlay, .oz_popup').hide();
        },250);
    });
	
	document.addEventListener('ozBookSend',function(e){
		if ( oz_vars.redirect_url != ''  ) {
		setTimeout(function() {window.location.href= oz_vars.redirect_url;}, 1500);
		}
	},false);	
	
 })(jQuery);
 
 class ozUI {
	 constructor() {
		 this.active = '';
		 this.options = oz_vars;
		 this.stepHeight = null;
		 this.autoheight();
	 }
	 
	 autoheight() {
		 var that = this;
		 if (!this.options.autoheight) return;
		document.addEventListener('animateNextEvAfter',function(){
			jQuery('.oz_hid_carousel').children().each(function() {
				var all = jQuery('.oz_hid')[0].getBoundingClientRect().left;
				var element = jQuery(this)[0];
				if( Math.abs(all - element.getBoundingClientRect().left) <= 8 ){
					that.active = jQuery(this);
					var event= new CustomEvent('onAnimateEnd',{detail:{target:element}});
					document.addEventListener('onAnimateEnd',function(){},false);
					document.dispatchEvent(event);
					jQuery(this).addClass('oz_inview');
				   jQuery('.oz_hid').css({'height': jQuery(this).get(0).scrollHeight});
				  
				}
				else{
				  jQuery(this).removeClass('oz_inview');
				}
			});
		},false);
	 }
 }
 
 const oz = new ozUI();