<?php
  $user_session =  session("user");
  $winmedia = "";
  $audio_logger = "";
  $streaming = "";
  $ftp = "";
  $team_viewer = "";
  $audio_player_st = "";
  $user = "";
  $master_station = "";
  $authentication_log = "";

  $winmedia_admin_action = "";
  $audio_logger_admin_action = "";
  $audio_streaming_admin_action = "";

  $winmedia_admin_action = "";
  if($user_session->user_lvl == 0){
    $winmedia_admin_action = "hidden";
    $audio_logger_admin_action = "hidden";
    $audio_streaming_admin_action = "hidden";

    $user_id = $user_session->user_id;
    $ftp = "hidden";
    $user = "hidden";
    $master_station = "hidden";
    $authentication_log = "hidden";
    $data = \Illuminate\Support\Facades\DB::select("SELECT * FROM user_access_tbl WHERE user_id = $user_id");
    if($data[0]->audio_logger != 1){
      $audio_logger = "hidden";
    }
    if($data[0]->shout_cast != 1){
      $streaming = "hidden";
    }
    if($data[0]->winmedia != 1){
      $winmedia = "hidden";
    }

    if($data[0]->ftp != 1){
      $ftp = "hidden";
    }
    if($data[0]->team_viewer != 1){
      $team_viewer = "hidden";
    }
    if($data[0]->audio_player_st != 1){
      $audio_player_st = "hidden";
    }
  }

?>
<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar navbar-dark">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li class="nav-item dropdown" {{$winmedia}}>
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="18" y2="6.01"></line><path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"></path><polyline points="10.5 4.75 9 4 3 7 3 20 9 17 15 20 21 17 21 15"></polyline><line x1="9" y1="4" x2="9" y2="17"></line><line x1="15" y1="15" x2="15" y2="20"></line></svg>
              </span>
              <span class="nav-link-title">
                Winmedia
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('admin.home')}}" >
                Monitoring
              </a>
              <a class="dropdown-item" href="{{route('admin.view_station')}}" {{$winmedia_admin_action}}>
                Station List
              </a>
              <a disabled class="dropdown-item" href="{{route('admin.create_station')}}" {{$winmedia_admin_action}}>
                Create Station
              </a>
            </div>
          </li>



          <li class="nav-item dropdown" {{$audio_logger}}>
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z"></path><path d="M19 16h-12a2 2 0 0 0 -2 2"></path><path d="M9 8h6"></path></svg>
              </span>
              <span class="nav-link-title">
                Audio logger
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('admin.logger')}}" >
                Monitoring
              </a>
              <a class="dropdown-item" href="{{route('admin.logger_view_station')}}" {{$audio_logger_admin_action}}>
                Station List
              </a>
              <a disabled class="dropdown-item" href="{{route('admin.logger_create_station')}}" {{$audio_logger_admin_action}}>
                Create Station
              </a>
            </div>
          </li>


          <li class="nav-item dropdown" {{$streaming}}>
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path><polyline points="9 15 12 12 15 15"></polyline><line x1="12" y1="12" x2="12" y2="21"></line></svg>
              </span>
              <span class="nav-link-title">
                Audio Streaming
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('admin.streaming')}}" >
                Monitoring
              </a>
              <a class="dropdown-item" href="{{route('admin.streaming_view_station')}}" {{$audio_streaming_admin_action}}>
                Station List
              </a>
              <a disabled class="dropdown-item" href="{{route('admin.streaming_create_station')}}" {{$audio_streaming_admin_action}}>
                Create Station
              </a>
            </div>
          </li>

          <li class="nav-item dropdown" {{$user}}>
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="9" cy="7" r="4"></circle><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path><path d="M16 11h6m-3 -3v6"></path></svg>
              </span>
              <span class="nav-link-title">
                User
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{route('admin.view_user')}}" >
                View List
              </a>
              <a disabled class="dropdown-item" href="{{route('admin.create_user')}}" >
                Create User
              </a>
            </div>
          </li>

          <li class="nav-item" {{$ftp}}>
            <a class="nav-link" href="{{route('admin.ftp')}}" disabled>
              <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/file-plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3"></path></svg>
              </span>
              <span class="nav-link-title">
                FTP
              </span>
            </a>
          </li>


          <li class="nav-item" {{$master_station}}>
            <a class="nav-link" href="{{route('admin.create_master_station')}}" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z"></path></svg>
              </span>
              <span class="nav-link-title">
                Master Station
              </span>
            </a>
          </li>

          <li class="nav-item" {{$master_station}}>
            <a class="nav-link" href="{{route('admin.auth_log')}}" >
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11"></path><line x1="8" y1="8" x2="12" y2="8"></line><line x1="8" y1="12" x2="12" y2="12"></line><line x1="8" y1="16" x2="12" y2="16"></line></svg>
              </span>
              <span class="nav-link-title">
                Authentication Logs
              </span>
            </a>
          </li>



        </ul>
        <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">

        </div>
      </div>
    </div>
  </div>
</div>
