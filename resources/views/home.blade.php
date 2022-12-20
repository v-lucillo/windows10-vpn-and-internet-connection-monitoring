@extends('layout.layout')
@section('style')
<link href="https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.css" rel="stylesheet"/>
<style>
	body {
		margin: 0;
		padding: 0;
	}
	#map {
		position: absolute;
		top: 0;
		bottom: 0;
		width: 100%;
	}
	.onn {
		background-image: url('{{asset("onn.png")}}');
		background-size: cover;
		width: 50px;
		height: 50px;
		border-radius: 50%;
		cursor: pointer;
	}

	.off {
		background-image: url('{{asset("off.png")}}');
		background-size: cover;
		width: 50px;
		height: 50px;
		border-radius: 50%;
		cursor: pointer;
	}

	.on {
		background-image: url('{{asset("on.png")}}');
		background-size: cover;
		width: 50px;
		height: 50px;
		border-radius: 50%;
		cursor: pointer;
	}


	.mapboxgl-popup {
		max-width: 200px;
	}
	.mapboxgl-popup-content {
		text-align: center;
		font-family: 'Open Sans', sans-serif;
	}

	div.pop_up_tag{
		color: black;
		/*width: 40%;*/
	}

	span.station_name{
		font-size: 18px;
		font-weight: bold;
	}

	span.disconnected{
		color: red;
	}

	span.connected{
		color: green;
	}

	hr {
	  margin: 0.5rem 0 !important;
	}

	.h-100 {
	  height: 87% !important;
	}

	#map {
	  width: 98% !important;
	}

	.filter {
    position: absolute;
    top: 127px;
    width: 25%;
    height: 87%;
    background: #1e293b;
    right: 0;
		padding-left: 20px;
		padding-top: 1px;
		padding-right: 73px;
}

</style>
@endsection
@section('page_title')
	Home Page
@endsection
@section('container')
<div style="justify-content: center;align-content: center;display: flex;">
	<div id="map" class="w-200 h-100 rounded" style="margin-top: 127px;"></div>
</div>
<div class="filter">
	<h2>Filter</h2>
	<hr>
	<div class="mb-3">
    	<form id = "filter" action="{{route('admin.filter')}}" method="POST">
				<div class="form-label">Master Station</div>
				<div>
						@foreach($master_station as $row)
						<label class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" value="{{$row->id}}" name="{{$row->id}}">
							<span class="form-check-label">{{$row->master_station}}</span>
						</label>
						@endforeach
				</div>
				<br>
				<div class="form-label">Status</div>
				<div>
					<label class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="on"value="1">
						<span class="form-check-label">Online</span>
					</label>
					<label class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="off"value="1">
						<span class="form-check-label">Offline</span>
					</label>
					<label class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="onn"value="1">
						<span class="form-check-label">VPN Connected</span>
					</label>
				</div>
    	</form>
  </div>

	<div class="row">
		<div class="col-6 col-sm-4 col-md-2 col-xl mb-3">
			<a href="#filter" id = "filter" class="btn btn-dark btn-square w-100">
				Apply
			</a>
		</div>
		<div class="col-6 col-sm-4 col-md-2 col-xl mb-3">
			<a href="#reset_filter" id = "reset_filter" class="btn btn-dark btn-square w-100">
				Reset
			</a>
		</div>
	</div>
	<hr>
	<div class="test" style="background: #253249;padding: 20px;text-align: center;margin-right: -51px;height: 65%;">

	</div>
</div>
@endsection


@section('script')
<script src="https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script>
	var team_viewer = "{{$data['team_viewer']}}";
	var audio_player_st = "{{$data['audio_player_st']}}";
	var ftp = "{{$data['ftp']}}";

	$.LoadingOverlay("show");
	setTimeout(function(){
		$.LoadingOverlay("hide");
	},5000);

	setInterval(function(){
		if(!markers){
			$.LoadingOverlay("show");
			$.ajax({
				url: "{{route('api.get_connection')}}",
				success: function(e){
					process_pin_render(e);
					init_hover();
					$.LoadingOverlay("hide");
				},
				error: function(e){console.log(e);}
			});
		}
	},3000);

	setTimeout(function(){$('div.offcanvas-backdrop').remove();},1000);
	mapboxgl.accessToken = 'pk.eyJ1IjoidmljdG9yaW5vbHVjaWxsbyIsImEiOiJjbDFxamRtanMwZGRmM2VvM21vajluM2swIn0.NWuqMYIGlqjmu1nyWbV-CQ';
	const map = new mapboxgl.Map({
		container: 'map',
  		style: 'mapbox://styles/mapbox/satellite-streets-v11',
  		center: [121.7740, 12.8797], // starting position [lng, lat]
			zoom: 5.6 // starting zoom
	});
	var markers = [];

	setInterval(function(){ // recurring render of pins
		get_station_status(markers);
	},2000);

	map.addControl(new mapboxgl.FullscreenControl());
	var play_button =  "{{asset('play_button.png')}}";
	$.ajax({
		url: "{{route('api.get_connection')}}",
		success: function(e){
				process_pin_render(e);
		},
		error: function(){
			// markers.remove();
			// mark = [];
		}
	});

	var process_pin_render = (e) =>{


		for(let key in e){
		const el = document.createElement('div');
		var pin = { color: "#206bc4" };
		if(e[key].status == 'onn'){
			pin =  { color: "#00ff2a"};
		}else if(e[key].status == 'off'){
			pin =  { color: "#f50707"};
		}

		var stat_class_network;
		if(e[key].network == "disconnected"){
			stat_class_network = "disconnected";
		}else{
			stat_class_network = "connected"
		}

		var stat_class_vpn;
		if(e[key].vpn == "disconnected"){
			stat_class_vpn = "disconnected";
		}else{
			stat_class_vpn = "connected"
		}
		// status
		// long, lat,
		// internet, vpn
		// make a marker for each feature and add it to the map

		var marker = new mapboxgl.Marker(pin)
			.setLngLat(e[key].coordinate)
			.setPopup(
				new mapboxgl.Popup({ offset: 25,draggable: true}) // add popups
				.setHTML("<div class = 'pop_up_tag' id = "+e[key].call_sign+">\
					<img width = '50%' src='"+e[key].logo+"'><br><br>\
					<span class='station_name'>"+e[key].station_name+" (<span id = 'call_sign' value = '"+e[key].call_sign+"'>"+e[key].call_sign+"</span>)</span><br>\
						<span>STATION ISP (Internet) </span>\
						<span class = '"+stat_class_network+"'>"+e[key].network+"</span><br>\
						<span> MBC- H.O. VPN</span>\
						<span style = 'margin-left: 38px' class = '"+stat_class_vpn+"'>"+e[key].vpn+"</span><br><br>\
						<div id = 'team_viewer_access' "+team_viewer+">\
						<span><img style = 'margin-top: -5px;' width = '50%' src='{{asset('tv.png')}}'></span>\
						<span style = 'font-size:13px;font-weight:bold;'> : <a style = 'margin-top:-10px' target='_blank' href = ' https://start.teamviewer.com/"+e[key].team_viewer+"'>"+e[key].team_viewer+"</a></span><hr>\
						</div>\
						<div id='shoutcast' "+audio_player_st+">\
						<span id = 'play_streaming' url ='"+e[key].shout_cast+"' style='cursor:pointer'>\
						<img width = '50%' src='{{asset('st.png')}}'><br>\
							<span>Play streaming</span>\
						</span><hr>\
						</div>\
						<div id='ftp' "+ftp+">\
						<span id = 'play_streaming' url ='"+e[key].ftp+"' style='cursor:pointer'>\
						<img width = '30%' src='{{asset('ftp.png')}}'><br>\
							<span>Open audio logs</span>\
						</span>\
						</div>\
						</div>"))
			.addTo(map);

			marker._element.id = e[key].call_sign;
			markers.push(marker);
	}
}
	var init_hover = ()=>{
		setTimeout(function(){ // recurring render of pins
			invoke_show_detail_on_hover(markers);
		},5000);
	}
	init_hover();

	var invoke_show_detail_on_hover = function(pins){
		for(let key in pins){
			const markerDiv = pins[key].getElement();
			markerDiv.addEventListener('mouseenter', () => {
				$('div.mapboxgl-popup').hide();
				$('div.test').empty().append(pins[key]._popup._content.innerHTML);
				pins[key].togglePopup();
			});
			// markerDiv.addEventListener('mouseleave', () => pins[key].togglePopup());
		}
	}
	var get_station_status = function(pins){
		$.ajax({
			url: "{{route('api.get_connection')}}",
				success: function(e){
					for(let key in e){
						var pin_color = $($('div#'+key).find('g')[2]);
						if(e[key].status == 'onn'){
							pin_color.attr('fill', '#00ff2a');
						}else if(e[key].status == 'off'){
							pin_color.attr('fill', '#f50707');
						}else{
							pin_color.attr('fill', '#206bc4');
						}

						if(e[key].network == "disconnected"){
							stat_class_network = "disconnected";
						}else{
							stat_class_network = "connected"
						}
						if(e[key].vpn == "disconnected"){
							stat_class_vpn = "disconnected";
						}else{
							stat_class_vpn = "connected"
						}
						// console.log(pins[0].getElement());
						// console.log("-------");
						// var call_sign = $($(pins[0]._popup._content.innerHTML).find("span[value='DYRC']#call_sign")).text();
					}

				},
				error: function(){}
			});

	};

	var filter = () => {
		$.LoadingOverlay("show");
		$.ajax({
			url: "{{route('api.get_connection')}}",
			data: $('form#filter').serialize(),
			success: function(e){
				for(let key in markers){
					markers[key].remove();
				}
				process_pin_render(e);
				init_hover();
				$.LoadingOverlay("hide");
			},
			error: function(e){console.log(e);$.LoadingOverlay("hide");}
		});
	}

	$('a#filter').on('click', function(){filter();});
	$('a#reset_filter').on('click', function(){$('form#filter').trigger("reset");filter();});

    $(document).on('click','span#play_streaming',function(){
    	var url =  $(this).attr('url');
    	window.open(url, '_blank', 'location=yes,height=300,width=420,scrollbars=yes,status=yes');
    });

</script>
@endsection
