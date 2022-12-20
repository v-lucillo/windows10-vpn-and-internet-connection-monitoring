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

    .rotate {
      animation: rotation 0.4s infinite linear;
    }

    @keyframes rotation {
      from {
        transform: rotate(0deg);
        transform: scale(1.1);
      }
      to {
        transform: rotate(359deg);
      }
    }

  </style>
@endsection
@section('page_title')
  Audio streaming monitoring board
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div style="display: flex;justify-content: space-between;align-content: center;">
          <h3 class="card-title">Audio streaming monitoring board</h3>
          <img src="{{asset('st.png')}}" style="width:10%">
        </div>
      </div>
      <div class="card-body">
        <div class="row"id = "tile_container">
          @foreach($data as $row)
          <div class="col-4">
            <div class="text-white tile_station" color = "{{$row['status']}}" id = "{{$row['call_sign']}}">
              <span class="call_sign rotate">{{$row['call_sign']}}</span><br>
              <img class = "rotate" src="{{$row['logo']}}" style="width:60%"><br>
              <span>{{$row['station_name']}}</span><br>
              <audio controls style="width: 70%;">
               <source src="{{$row['shout_cast']}}">
              </audio><br>
              <br>
              <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-1">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$row['call_sign']}}" aria-expanded="false" style="color: white">
                        Management/Credentials
                      </button>
                    </h2>
                    <div id="collapse-{{$row['call_sign']}}" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
                      <div class="accordion-body pt-0">
                        <a {{$user_restriction['team_viewer']}} href="https://start.teamviewer.com/{{$row['team_viewer']}}" target="_blank"> <img src="{{asset('tv.png')}}" style="width:50%;float:left"> </a><br><br>
                        <hr style="margin:0.5rem 0">
                        <div style="text-align:left;line-height: 1;" {{$user_restriction['audio_player_st']}}>
                          <h4 style="font-weight:bold">Shoutcast/Centova Server</h4><br><br>
                          <p >URL : <a href="{{$row['centova_url']}}" target = "_blank">{{$row['centova_url']}}</a></p>
                          <p >Username : {{$row['centova_username']}}</p>
                          <p >Password : {{$row['centova_password']}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
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
        url: "{{route('admin.get_streaming_connection')}}",
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
