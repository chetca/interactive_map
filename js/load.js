function getClientWidth(){
  return document.documentElement.clientWidth;
}

function getClientHeight(){
  return document.documentElement.clientHeight;
}

$(document).ready(function() {

	var viewport = $('meta[name=viewport]');
	/*if ( navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i)) {
		viewport.setAttribute("content", "initial-scale=0.3");
	} else*/
	if (navigator.userAgent.match(/iPad/i)) {
		viewport.attr("content", "initial-scale=1.5, maximum-scale=1.5");
    }

    $(".datepicker-here").datepicker({
    	language: 'my-lang',
		toggleSelected: false
    });
	
	$("#dateIntervalData").on('click', function () {
    	$("#dateIntervalMonth").val('');
    });
	
	$("#dateIntervalMonth").on('click', function () {
    	$("#dateIntervalData").val('');
    });
	
	$('body').find('select').each( function () {
		var $select = $(this).selectize({
			//sortField: 'text'
			render: {
				option: function(data, escape) {		
					switch (data.value) {
						case "524":
							var color = "#1e98ff"; break;
						case "525":
							var color = "#ed4543"; break;
						case "526":
							var color = "#e6761b"; break;
						case "527":
							var color = "#0e4779"; break;
						case "528":
							var color = "#177bc9"; break;
						case "529":
							var color = "#f371d1"; break;
						case "530":
							var color = "#b3b3b3"; break;
						case "558":
							var color = "#793d0e"; break;
						case "559":
							var color = "#1bad03"; break;
						case "560":
							var color = "#b51eff"; break;
						case "561":
							var color = "#595959"; break;
						case "562":
							var color = "#ffd21e"; break;
						case "563":
							var color = "#56db40"; break;
						case "564":
							var color = "#ff931e"; break;
						case "565":
							var color = "#82cdff"; break;
						case "566":
							var color = "#97a100"; break;
					}
					
					if(color)
						return '<div class="option">' 
						 + escape(data.text)
						 + '<p style="background-color: '
						 + color
						 + ';" class="industry-color"></p>'
						 + '</div>';
					else
						return '<div class="option">' 
						 + escape(data.text)
						 + '</div>';
				},
					item: function(data, escape) {						
						switch (data.value) {
						case "524":
							var color = "#1e98ff"; break;
						case "525":
							var color = "#ed4543"; break;
						case "526":
							var color = "#e6761b"; break;
						case "527":
							var color = "#0e4779"; break;
						case "528":
							var color = "#177bc9"; break;
						case "529":
							var color = "#f371d1"; break;
						case "530":
							var color = "#b3b3b3"; break;
						case "558":
							var color = "#793d0e"; break;
						case "559":
							var color = "#1bad03"; break;
						case "560":
							var color = "#b51eff"; break;
						case "561":
							var color = "#595959"; break;
						case "562":
							var color = "#ffd21e"; break;
						case "563":
							var color = "#56db40"; break;
						case "564":
							var color = "#ff931e"; break;
						case "565":
							var color = "#82cdff"; break;
						case "566":
							var color = "#97a100"; break;
					}
					
					if(color)
						return '<div class="item">' 
						 + escape(data.text)
						 + '<p style="background-color: '
						 + color
						 + ';" class="industry-color-item"></p>'
						 + '</div>';
					else
						return '<div class="item">' 
						 + escape(data.text)
						 + '</div>';
              }    
			}
		});

		// Clear selected items from selectize control on dropdown click
		$('.inp-reset-content').on( 'click', function () {
			var selectize = $select[0].selectize;
			selectize.clear(true);
			$("#builder").val('');
			$("#project_finance").val('');
			$(".datepicker-here").val('');
			ChangeData(0, 0, 0, '', '', '');
		});
		
		$(window).bind('beforeunload',function(){
			var selectize = $select[0].selectize;
			selectize.clear(true);
			$("#builder").val('');
			$("#project_finance").val('');
			$(".datepicker-here").val('');
			ChangeData(0, 0, 0, '', '', '');
		});		
	});
	
	var selectizeDestroy = setInterval(function() {
        $('#category').each( function () {
            if ($(this)[0].selectize) {
                $(this)[0].selectize({
					render: {
						option: function(data, escape) {		
							switch (data.value) {
								case "524":
									var color = "#1e98ff"; break;
								case "525":
									var color = "#ed4543"; break;
								case "526":
									var color = "#e6761b"; break;
								case "527":
									var color = "#0e4779"; break;
								case "528":
									var color = "#177bc9"; break;
								case "529":
									var color = "#f371d1"; break;
								case "530":
									var color = "#b3b3b3"; break;
								case "558":
									var color = "#793d0e"; break;
								case "559":
									var color = "#1bad03"; break;
								case "560":
									var color = "#b51eff"; break;
								case "561":
									var color = "#595959"; break;
								case "562":
									var color = "#ffd21e"; break;
								case "563":
									var color = "#56db40"; break;
								case "564":
									var color = "#ff931e"; break;
								case "565":
									var color = "#82cdff"; break;
								case "566":
									var color = "#97a100"; break;
							}
							 
						return '<div class="option">' 
							 + escape(data.text)
							 + '<p style="background-color: '
							 + color
							 + ';" class="industry-color"></p>'
							 + '</div>';
						}   
					}
				});
            }
        });
    }, 10);

	
	
	if (getClientWidth() >= 992){
		$(".container, .row-flex").css({
			'height': getClientHeight()
		});
	} else {
		$('#myMapId').css({
			'height': (getClientHeight()*0.6)+'px'
		});
	}
	$(".main_aside_button button").click(function(){
		$(".main_aside_search").toggle();
		if ($(this).text() == "Показать фильтр")
			$(this).text("Скрыть фильтр");
		else 
			$(this).text("Показать фильтр");
	});
	
	//Фильтр по застройщикам
	$('#builder').bind("click", function() {
		$.ajax({
			dataType: "json",
			url: "/bitrix/templates/maps-build-plan/ajax_builder.php",
			data: {'builder':this.value}
	   }).done(function(data) {
			$('.search_result').html(data).fadeIn();
		});
	})
		
	$(".search_result").hover(function(){
		$("#builder").blur(); //Убираем фокус с input
	})
	
	$(document).click( function(event){
		if( $(event.target).closest("#builder").length ) 
			return;
		$(".search_result").fadeOut();
		event.stopPropagation();
    });
		
	//При выборе результата поиска, прячем список и заносим выбранный результат в input
	$(".search_result").on("click", "li", function(){
		s_user = $(this).text();
		$("#builder").val(s_user);
		$(".search_result").fadeOut();
	})
	
	//Фильтр по проектам
	$('#project_finance').bind("click", function() {
		$.ajax({
			dataType: "json",
			url: "/bitrix/templates/maps-build-plan/ajax_project_finance.php",
			data: {'project_finance':this.value}
	   }).done(function(data) {
			$('.search_result_project_finance').html(data).fadeIn();
		});
	})
		
	$(".search_result_project_finance").hover(function(){
		$("#project_finance").blur(); //Убираем фокус с input
	})
	
	$(document).click( function(event){
		if( $(event.target).closest("#project_finance").length ) 
			return;
		$(".search_result_project_finance").fadeOut();
		event.stopPropagation();
    });
		
	//При выборе результата поиска, прячем список и заносим выбранный результат в input
	$(".search_result_project_finance").on("click", "li", function(){
		s_user = $(this).text();
		$("#project_finance").val(s_user);
		$(".search_result_project_finance").fadeOut();
	})


	var myMap;
	var objectManager;

	ymaps.ready(init);
	function init(){
		//console.log("map");
		myMap = new ymaps.Map('myMapId', {
			center: [51.83500727, 107.60193161],
			type: 'yandex#map',
			zoom: 13,
			controls: ['fullscreenControl', 'geolocationControl', 'zoomControl']
		}, {
			minZoom: 10
		});
		var searchControl = new ymaps.control.SearchControl({
			options: {
				provider: 'yandex#search'
			}
		});
		myMap.controls.add(searchControl);

		var pane = new ymaps.pane.StaticPane(myMap, {
			zIndex: 100, css: {
				width: '100%',
				height: '100%',
				backgroundColor: '#f7f7f7',
				opacity: 0.5
			}
		});
		myMap.panes.append('white', pane);

		objectManager = new ymaps.ObjectManager({
			hasBalloon: true
		});
		objectManagerPoints = new ymaps.ObjectManager({
			clusterize: true,
			gridSize: 64,
			clusterDisableClickZoom: true,
			clusterIconLayout: "default#pieChart",
			hasBalloon: true
		});
		myMap.geoObjects.events.add('click', function (e) {
		    var objectId = e.get('objectId');
		    var geometry = e.get('target').getById(objectId)._data.geometry;
		    if (geometry.type == "Point")
		    {				
		    	var lon = geometry.coordinates[0];
		    	var lat = geometry.coordinates[1];
		    	ShowElement(objectId, lon, lat);
		    } else if (geometry.type == "LineString"){
		    	var lon = geometry.coordinates[0][0];
		    	var lat = geometry.coordinates[0][1];
		    	ShowElement(objectId, lon, lat);
		    } else if (geometry.type == "Polygon") {
		    	var lon = geometry.coordinates[0][0][0];
		    	var lat = geometry.coordinates[0][0][1];
				console.log(geometry.type);
				console.log(geometry.coordinates);
		    	ShowElement(objectId, lon, lat);
		    }
			//console.log(geometry);
		});
		myMap.geoObjects.add(objectManager);
		myMap.geoObjects.add(objectManagerPoints);
		ChangeData(0, 0, 0, '', '', '');
		$('#findBtn').on('click', (function(){
			var finance = $("#finance").val();
			var category = $("#category").val();
			var type_work = $("#type_work").val();
			var builder = $("#builder").val();
			var project_finance = $("#project_finance").val();
			if($("#dateIntervalData").val() == "") {
				var dateInterval = $("#dateIntervalMonth").val();
			}
			else {
				var dateInterval = $("#dateIntervalData").val();
			}
			ChangeData(finance, category, type_work, builder, project_finance, dateInterval);
		}));
		$('#excelBtn').click(function(){
			var finance = $("#finance").val();
			var category = $("#category").val();
			var type_work = $("#type_work").val();
			var builder = $("#builder").val();
			var project_finance = $("#project_finance").val();
			if($("#dateIntervalData").val() == "") {
				var dateInterval = $("#dateIntervalMonth").val();
			}
			else {
				var dateInterval = $("#dateIntervalData").val();
			}
			getExcel(finance, category, type_work, builder, project_finance, dateInterval);
		});
	}
	function ChangeData(finance, category, type_work, builder, project_finance, dateInterval){
		myMap.setCenter([51.83500727, 107.60193161]);
		myMap.setZoom(13);
		
		if (dateInterval.length) {
			if (dateInterval.indexOf('-') > -1) {
				var dates = dateInterval.split(' - ');
			}
			else {
				var dates = [dateInterval, dateInterval];
			}
		}
		else
			var dates = ['', ''];
		$.ajax({
			dataType: "json",
			url: "/bitrix/templates/maps-build-plan/ajax.php",
			data: {
				'finance': finance,
				'category': category,
				'type_work': type_work,
				'builder': builder,
				'project_finance': project_finance,
				'datefrom': dates[0],
				'dateto': dates[1]
			}
		}).done(function(data) {
			objectManager.removeAll();
			objectManagerPoints.removeAll();
			objectManager.add(data.others);
			objectManagerPoints.add(data.points);
			$('.main_aside_top-content').html(data.html);
			$('.goForward').click(function(){
				var id = $(this).attr("data-id");
				var lon = $(this).attr("lon");
				var lat = $(this).attr("lat");
				ShowElement(id, lon, lat);
			});
		});		
	}
	function getExcel(finance, category, type_work, builder, project_finance, dateInterval){
		if (dateInterval.length) {
			if (dateInterval.indexOf('-') > -1) {
				var dates = dateInterval.split(' - ');
			}
			else {
				var dates = [dateInterval, dateInterval];
			}
		}
		else
			var dates = ['', ''];
		
		window.open('/bitrix/templates/maps-build-plan/ajax_excel.php?finance='+finance+'&category='+category+'&type_work='+type_work+'&builder='+builder+'&project_finance='+project_finance+'&datefrom='+dates[0]+'&dateto='+dates[1],'_blank' );
		/*
		$.ajax({
			url: "/bitrix/templates/maps-build-plan/ajax_excel.php",
			data: {
				'finance': finance,
				'category': category,
				'type_work': type_work,
				'builder': builder,
				'datefrom': dates[0],
				'dateto': dates[1]
			},
			response:'xml'
		});
		*/
	}
	function ShowElement(id, lon, lat){
		myMap.setCenter([lon, lat]);
		myMap.setZoom(19);

		$.ajax({
			dataType: "json",
			url: "/bitrix/templates/maps-build-plan/ajax_element.php?id="+id
		}).done(function(data) {
			if (data.length){
				$('.main_aside_top-content').html(data);
				$('.gallery').lightGallery({
					subHtmlSelectorRelative: true
				});
				$('.slick-slider').slick({
					slidesToShow: 1, 
					slidesToScroll: 1,
					centerMode: true,
					variableWidth: true,
					draggable: true,
					easing: true,
					infinite: false
				});
				$('.goBack').click(function(){
					var finance = $("#finance").val();
					var category = $("#category").val();
					var type_work = $("#type_work").val();
					var builder = $("#builder").val();
					var project_finance = $("#project_finance").val();
					if($("#dateIntervalData").val() == "") {
						var dateInterval = $("#dateIntervalMonth").val();
					}
					else {
						var dateInterval = $("#dateIntervalData").val();
					}
					ChangeData(finance, category, type_work, builder, project_finance, dateInterval);
				});
			}
		});
	}
	
	
});