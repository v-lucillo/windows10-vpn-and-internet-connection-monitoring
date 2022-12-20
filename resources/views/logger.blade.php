@extends('layout.layout')
@section('style')
  <style type="text/css">
    tr{
      cursor: pointer;
    }
    div.tile_station{
      padding: 12px;
      margin: 10px;
      border-radius: 6px;
      text-align: center;
    }

    div.tile_station:hover{
      background-color: black;
      transform: scale(1.2);
    }


    span.call_sign{
      font-size: 20px;
      font-weight: bold;
    }

    div[color="off"]{
      background-color: #d63939;
    }
    div[color="on"]{
      background-color: #4263eb;
    }
    div[color="onn"]{
      background-color: #2fb344 ;
    }

  </style>
@endsection
@section('page_title')
  Audio logger monitoring board
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div style="display: flex;justify-content: space-between;align-content: center;">
          <h3 class="card-title">Audio logger monitoring board</h3>
          <img src="{{asset('ftp.png')}}" style="width:4%">
        </div>
      </div>


      <div class="card-body">
        <div class="row" id = "tile_container">
          @foreach($data as $row)
          <div class="col-3">
            <div class="text-white tile_station" color = "{{$row['status']}}" id = "{{$row['call_sign']}}">
              <span class="call_sign">{{$row['call_sign']}}</span> <img src="{{$row['logo']}}" alt=""> <br>
              <span>{{$row['station_name']}}</span><br>
              <span {{$user_restriction['ftp']}}> <a href="{{$row['ftp']}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 4h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2"></path><path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2"></path></svg>
              </a></span><br>
              <span {{$user_restriction['team_viewer']}}><a href="https://start.teamviewer.com/{{$row['team_viewer']}}" target="_blank"> <img src="{{asset('tv.png')}}" style="width:60%"> </a></span><br>
            </div>
          </div>
          @endforeach
        </div>
      </div>


    </div>
  </div>
</div>
 <br>

@endsection
@section('script')
  <script type="text/javascript">
  var team_viewer = "{{$user_restriction['team_viewer']}}";
	var ftp = "{{$user_restriction['ftp']}}";

    var message = "{{session('message')}}";
    if(message){
      Swal.fire(
        'Success!',
        message,
        'success'
      )
    }

    setInterval(function(){
      $.ajax({
        url: "{{route('admin.get_logger_connection')}}",
        success: function(e){
          for(let key in e){
            $("div#"+key).attr('color',e[key].status);
          }
        },
        error: function(e){
          console.log(e);
        }
      });
    },2000);
  </script>
@endsection
