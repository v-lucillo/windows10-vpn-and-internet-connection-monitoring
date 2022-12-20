<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\ValidationException;

class APIController extends Controller
{
  public function signin(Request $request){
    $data = $request->all();
    $email = $data['email'];
    $password = $data['password'];
    $user =  DB::select("SELECT a.*, b.user_id, b.audio_logger,b.shout_cast,b.team_viewer,b.station,b.master_station,b.winmedia,b.ftp,b.audio_player_st,b.shout_cast_cred
       FROM user_tbl a LEFT JOIN user_access_tbl b on a.id = b.user_id WHERE a.email = '$email'");
    if(sizeof($user) > 0){
      if($password == $user[0]->password){
        session([
          "user" => $user[0]
        ]);
        $data = [
          "email" => $user[0]->email,
          "user_agent" => $_SERVER['HTTP_USER_AGENT'],
          "ip" => $_SERVER['REMOTE_ADDR']
        ];
        DB::table("auth_log")->insert($data);
        if($user[0]->user_id == null){
          return redirect()->route('admin.home');
        }
        if($user[0]->audio_logger == 1){
          return redirect()->route('admin.logger');
        }
        if($user[0]->shout_cast == 1){
          return redirect()->route('admin.streaming');
        }
        return "Please contact your system Administrator, you do not have any privilege to access any";
      }
    }
    throw ValidationException::withMessages(['message' => "-"]);
  }
}
