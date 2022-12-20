<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\UserModel;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //
    public function home(){
        $master_station = $this->get_master_station_filter();
        $data = $this->user_restriction();
        return view('home',compact('master_station','data'));
    }

    public function create_station(){
        $data =  DB::select("SELECT * FROM master_station_tbl");
        return view('create_station',compact('data'));
    }

    public function view_station(){
        return view('view_station');
    }

    public function submit_station(Request $request){
        $data =  $request->all();
        unset($data['_token']);
        $request->validate([
            "station_name" => "required",
            "call_sign" => "required|unique:station_tbl,call_sign",
            "longt" => "required",
            "latt" => "required",
            "address" => "required",
            "other_info" => "required",
            "master_station" => "required"
        ]);
        DB::table("station_tbl")->insert($data);
        DB::table("listener_tbl")->insert(["call_sign" => $data['call_sign']]);

        DB::table("ftp_tbl")->insert([
          "url" => null,
          "port" => null,
          "username" => null,
          "password" => null,
          "call_sign" => $data['call_sign']
        ]);

        $call_sign =  $data['call_sign'];
        $station_name =  $data['station_name'];
        return back()->with([
            "message" => "$call_sign | $station_name created succesfully"
        ]);
    }


    public function connection(Request $request){
        $data =  $request->all();
        $timestamp =  $data['timestamp'];
        $call_sign =  $data['call_sign'];
        $adapters =  explode('--',$data['adapters']);
        foreach($adapters as $adapter){
            $adapter_name =  explode('-',$adapter);
            if($adapter_name[0] == "SophosTAPAdapter"){
                $data['is_vpn_connected'] = 1;
                break;
            }
            $data['is_vpn_connected'] = 0;
        }
        unset($data['adapters']);

        DB::table('listener_tbl')->where('call_sign', $data['call_sign'])->update($data);
        return response()->json([
            "timestamp" => $timestamp,
            "call_sign" => $call_sign,
            "adapters" => $adapters
        ]);
    }

    public function connection_logger(Request $request){
        $data =  $request->all();
        $timestamp =  $data['timestamp'];
        $call_sign =  $data['call_sign'];
        $adapters =  explode('--',$data['adapters']);
        foreach($adapters as $adapter){
            $adapter_name =  explode('-',$adapter);
            if($adapter_name[0] == "SophosTAPAdapter"){
                $data['is_vpn_connected'] = 1;
                break;
            }
            $data['is_vpn_connected'] = 0;
        }
        unset($data['adapters']);

        DB::table('logger_listener_tbl')->where('call_sign', $data['call_sign'])->update($data);
        return response()->json([
            "timestamp" => $timestamp,
            "call_sign" => $call_sign,
            "adapters" => $adapters
        ]);
    }


    public function get_connection(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data =  $request->all();
        $status =  [];
        $master_station = "(";
        foreach ($data as $key => $value) {
          if(is_int($key)){
            $master_station .= $key.",";
          }
        }
        foreach ($data as $key => $value) {
          if(is_string($key)){
            array_push($status,$key);
          }
        }

        if(strlen($master_station) > 1){
          $master_station = substr($master_station,0,(strlen($master_station) -1 )).")";
          $data = DB::select("SELECT CONCAT('ftp://',c.username,':',c.password,'@',c.url) as ftp, a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast FROM listener_tbl a
            LEFT JOIN ftp_tbl c on c.call_sign = a.call_sign
            LEFT JOIN station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM station_tbl) AND b.master_station IN $master_station ORDER BY b.station_name ASC");
        }else{
          $data = DB::select("SELECT CONCAT('ftp://',c.username,':',c.password,'@',c.url) as ftp, a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast FROM listener_tbl a
            LEFT JOIN ftp_tbl c on c.call_sign = a.call_sign
            LEFT JOIN station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM station_tbl) ORDER BY b.station_name ASC");
        }
        // WHERE b.station_name LIKE '%LOVE%'
        $stat = [];
        $date = date("Y.m.d.H.i.s");
        $less_time = strtotime('-35 sec');
        $date = date("Y.m.d.H.i.s", $less_time);
        foreach($data as $row){
            if($date > $row->timestamp){ //
                $stat[$row->call_sign] = [
                    "network" => "disconnected",
                    "vpn" => "disconnected",
                    "status" => "off",
                    "coordinate" => [$row->longt,$row->latt],
                    "call_sign" => $row->call_sign,
                    "station_name" => $row->station_name,
                    "logo" => $row->logo,
                    "team_viewer" => $row->team_viewer,
                    "shout_cast" => $row->shout_cast,
                    "ftp" => $row->ftp
                ];
            }else{
                if($row->is_vpn_connected == 0){
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "disconnected",
                        "status" => "on",
                        "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "ftp" => $row->ftp
                    ];
                }else{
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "connected",
                        "status" => "onn",
                        "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "ftp" => $row->ftp
                    ];
                }
            }
        }
        $data = [];
        if($status){
          foreach($stat as $row){
            if(in_array($row['status'],$status)){
              array_push($data, $row);
            }
          }
          return response()->json($data);
        }

        return response()->json($stat);
    }


    public function get_station(){
        return DataTables::of(
            DB::select("SELECT * FROM station_tbl")
        )->make(true);
    }

    public function get_auth_log(){
        return DataTables::of(
            DB::select("SELECT * FROM auth_log")
        )->make(true);
    }

    public function create_master_station(){
        return view("create_master_station");
    }

    public function submit_master_station(Request $request){
        $data = $request->all();
        $request->validate([
            "master_station" => "required"
        ]);
        unset($data["_token"]);
        DB::table("master_station_tbl")->insert($data);

        return back()->with([
            "message" => "Master station created"
        ]);
    }

    public function get_master_station(){
        return DataTables::of(
            DB::select("SELECT * FROM master_station_tbl")
        )->make(true);
    }

    public function modify_master_station(Request $request){
        $id =  $request->akajhdad12hasdhkjsa123ah129083asdhk;
        $data =  DB::select("SELECT * FROM master_station_tbl WHERE id = $id")[0];
        $master_station = $data->master_station;
        return view("modify_master_station",compact('id','master_station'));
    }

    public function submit_modify_master_station(Request $request){
        $data = $request->all();
        $id = $data['id'];
        $master_station = $data['master_station'];
        DB::table("master_station_tbl")->where("id",$id)->update([
            "master_station" => $master_station
        ]);

        return redirect()->route("admin.create_master_station")->with([
            "message" => "Master station updated"
        ]);
    }

    public function modify_station(Request $request){
        $id =  $request->ajsdasdalkf89098aslaf9812asdfjlaf;
        $data =  DB::select("SELECT * FROM station_tbl WHERE id = $id")[0];
        $call_sign = $data->call_sign;
        $master_station = DB::select("SELECT * FROM master_station_tbl");
        $ftp = [];
        if(DB::select("SELECT * FROM ftp_tbl WHERE call_sign = '$call_sign'")){
          $ftp =  DB::select("SELECT * FROM ftp_tbl WHERE call_sign = '$call_sign'")[0];
        }

        // dd($ftp);
        return view("modify_station",compact('data','master_station','ftp'));
    }

    public function submit_modify_station(Request $request){
        $data = $request->all();
        $id = $data['id'];
        $request->validate([
            "station_name" => "required",
            "call_sign" => "required",
            "longt" => "required",
            "latt" => "required",
            "address" => "required",
            "other_info" => "required",
            "master_station" => "required"
        ]);
        unset($data['_token']);
        unset($data['id']);
        DB::table('station_tbl')->where("id",$id)->update($data);

        return back()->with([
            "message" => "Radio Station record updated"
        ]);
    }

    public function submit_modify_station_tv(Request $request){
        $data = $request->all();
        $application = "station_tbl";
        if(isset($data['application'])){
            $application = $data['application'];
        }
        $id = $data['id'];
        $request->validate([
            "team_viewer" => "required",
        ]);
        unset($data['_token']);
        unset($data['id']);
        unset($data['application']);
        DB::table($application)->where("id",$id)->update($data);

        return back()->with([
            "message" => "Radio Station Teamviewer access updated"
        ]);
    }

    public function submit_modify_station_shout_cast(Request $request){
        $data = $request->all();
        $id = $data['id'];
        $application = "station_tbl";
        if(isset($data['application'])){
            $application = $data['application'];
        }
        $request->validate([
            "shout_cast" => "required",
        ]);
        unset($data['_token']);
        unset($data['id']);
        unset($data['application']);
        DB::table($application)->where("id",$id)->update($data);

        return back()->with([
            "message" => "Radio Station ShoutCast Monitoring Player updated"
        ]);
    }

    public function create_user(){
        return view("create_user");
    }


    public function get_station_lov(Request $request){
      $q = $request->q;
      if($q){
        return response()->json([
          "results" => DB::select("SELECT call_sign as id, CONCAT(call_sign, ' | ', station_name) as text FROM station_tbl WHERE call_sign LIKE '%$q%' OR station_name LIKE '%$q%'")
        ]);
      }

      return response()->json([
        "results" => DB::select("SELECT call_sign as id, CONCAT(call_sign, ' | ', station_name) as text FROM station_tbl")
      ]);
    }

    public function filter_station(Request $request){
      date_default_timezone_set('Asia/Manila');
      $q = $request->q;
      if($q){



      // 2022.04.20.21.58.37
      $data = DB::select("SELECT b.id, CONCAT(b.call_sign, ' | ', b.station_name) as text, a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast FROM listener_tbl a LEFT JOIN station_tbl b on a.call_sign = b.call_sign WHERE (b.call_sign LIKE '%$q%' OR b.station_name LIKE '%$q%') AND b.call_sign IN (SELECT call_sign FROM station_tbl)");
      // WHERE b.station_name LIKE '%LOVE%'
      $stat = [];
      $date = date("Y.m.d.H.i.s");
      $less_time = strtotime('-60 sec');
      $date = date("Y.m.d.H.i.s", $less_time);
      $content = [];
      // $counter = 0;
      foreach($data as $row){
          // $counter++;
          // if($counter < 35){
          //     continue;
          // }


          $stat['id'] = $row->id;
          $stat['text'] = $row->text;
          if($date > $row->timestamp){
            $stat['content'][$row->call_sign] = [
                "network" => "disconnected",
                "vpn" => "disconnected",
                "status" => "off",
                "coordinate" => [$row->longt,$row->latt],
                "call_sign" => $row->call_sign,
                "station_name" => $row->station_name,
                "logo" => $row->logo,
                "team_viewer" => $row->team_viewer,
                "shout_cast" => $row->shout_cast
            ];
          }else{
              if($row->is_vpn_connected == 0){
                  $stat['content'][$row->call_sign] = [
                      "network" => "connected",
                      "vpn" => "disconnected",
                      "status" => "on",
                      "coordinate" => [$row->longt,$row->latt],
                      "call_sign" => $row->call_sign,
                      "station_name" => $row->station_name,
                      "logo" => $row->logo,
                      "team_viewer" => $row->team_viewer,
                      "shout_cast" => $row->shout_cast
                  ];
              }else{
                  $stat['content'][$row->call_sign] = [
                      "network" => "connected",
                      "vpn" => "connected",
                      "status" => "onn",
                      "coordinate" => [$row->longt,$row->latt],
                      "call_sign" => $row->call_sign,
                      "station_name" => $row->station_name,
                      "logo" => $row->logo,
                      "team_viewer" => $row->team_viewer,
                      "shout_cast" => $row->shout_cast
                  ];
              }
          }

          array_push($content, $stat);
      }

      return response()->json([
        "results" => $content
      ]);

    }



      return response()->json([
        "results" => DB::select("SELECT id, CONCAT(call_sign, ' | ', station_name) as text FROM station_tbl")
      ]);
    }

    public function get_master_station_lov(Request $request){
      $q = $request->q;
      if($q){
        return response()->json([
          "results" => DB::select("SELECT id, master_station as text FROM master_station_tbl WHERE master_station LIKE '%$q%'")
        ]);
      }
      return response()->json([
        "results" => DB::select("SELECT id, master_station as text FROM master_station_tbl")
      ]);
    }

    public function submit_user(Request $request){
      $data =  $request->all();
      $request->validate([
        "first_name" => "required",
        "last_name" => "required",
        "email" => "required|email",
        "password" => "required",
        "user_lvl" => "required",
      ]);

      $user_lvl =  $data["user_lvl"];

      if($user_lvl == 0){
        if(!isset($data['master_station']) && !isset($data['station']) && !isset($data['audio_logger']) && !isset($data['shout_cast']) && !isset($data['team_viewer'])){
          throw ValidationException::withMessages(['error_message' => 'Please add atleast one priviledge']);
        }
        $add_user =  new UserModel;
        $add_user->first_name = $data['first_name'];
        $add_user->last_name = $data['last_name'];
        $add_user->email = $data['email'];
        $add_user->password = $data['password'];
        $add_user->user_lvl = $data['user_lvl'];
        $add_user->save();
        $id = $add_user->id;
        unset($data['first_name']);
        unset($data['last_name']);
        unset($data['email']);
        unset($data['password']);
        unset($data['user_lvl']);
        unset($data['_token']);
        $data['user_id'] = $id;
        if(isset($data['master_station'])){
          $data['master_station'] = implode(",", $data['master_station']);
        }
        if(isset($data['station'])){
          $stations = implode("','", $data['station']);
          $data['station'] = "'".$stations."'";
        }
        // dd($data);
        DB::table('user_access_tbl')->insert($data);

        return back()->with([
          "message" => "User created"
        ]);
      }

      unset($data['_token']);
      DB::table('user_tbl')->insert($data);

      return back()->with([
        "message" => "User created"
      ]);
    }

    public function view_user(){
      return view("view_user");
    }

    public function get_user(){
      return DataTables::of(
          DB::select("SELECT * FROM user_tbl")
        )->make(true);
    }

    public function modify_user(Request $request){
      $id = $request->ajsdasdalkf89098aslaf9812asdfjlaf;
      $master_station = [];
      $station = [];
      $data =  DB::select("SELECT a.*, b.* FROM user_tbl a LEFT JOIN user_access_tbl b on a.id =  b.user_id WHERE a.id = $id")[0];
      // dd($data);
      if($data->master_station){
        $master_station =  DB::select("SELECT * FROM master_station_tbl WHERE id IN ($data->master_station)");
      }
      if($data->station){
        $station =  DB::select("SELECT * FROM station_tbl WHERE call_sign IN ($data->station)");
      }

      return view("modify_user", compact('data','master_station','station','id'));
    }

    public function submit_modify_user(Request $request){
      $data = $request->all();
      // dd($data);
      $request->validate([
        "first_name" => "required",
        "last_name" => "required",
        "email" => "required|email",
        "password" => "required",
        "user_lvl" => "required",
      ]);
      $user_id = $data['id'];
      $user_lvl =  $data["user_lvl"];
      unset($data['_token']);
      DB::table('user_tbl')->where('id',$user_id)->update([
        "first_name" => $data['first_name'],
        "last_name" => $data['last_name'] ,
        "email" => $data['email'],
        "password" => $data['password'],
        "user_lvl" => $data['user_lvl'],
      ]);
      if($user_lvl == 0){
        if(!isset($data['master_station']) && !isset($data['station']) && !isset($data['audio_logger']) && !isset($data['shout_cast']) && !isset($data['team_viewer'])){
          throw ValidationException::withMessages(['error_message' => 'Please add atleast one priviledge']);
        }
        unset($data['first_name']);
        unset($data['last_name']);
        unset($data['email']);
        unset($data['password']);
        unset($data['user_lvl']);
        unset($data['_token']);
        $data['user_id'] = $user_id;
        if(isset($data['master_station'])){
          $data['master_station'] = implode(",", $data['master_station']);
        }else{
          $data['master_station'] = null;
        }
        if(isset($data['station'])){
          $data['station'] = implode("','", $data['station']);
          $data['station'] = "'".$data['station']."'";
        }else{
          $data['station'] = null;
        }
        if(!isset($data['team_viewer'])){
          $data['team_viewer'] = null;
        }else{
          $data['team_viewer'] = 1;
        }

        if(!isset($data['audio_logger'])){
          $data['audio_logger'] = null;
        }else{
          $data['audio_logger'] = 1;
        }

        if(!isset($data['shout_cast'])){
          $data['shout_cast'] = null;
        }else{
          $data['shout_cast'] = 1;
        }

        if(!isset($data['ftp'])){
          $data['ftp'] = null;
        }else{
          $data['ftp'] = 1;
        }

        if(!isset($data['winmedia'])){
          $data['winmedia'] = null;
        }else{
          $data['winmedia'] = 1;
        }

        if(!isset($data['audio_player_st'])){
          $data['audio_player_st'] = null;
        }else{
          $data['audio_player_st'] = 1;
        }

        if(!isset($data['shout_cast_cred'])){
          $data['shout_cast_cred'] = null;
        }else{
          $data['shout_cast_cred'] = 1;
        }

        if(DB::select("SELECT * FROM user_access_tbl WHERE user_id = $user_id")){
          DB::table('user_access_tbl')->where('user_id',$user_id)->update($data);
        }else{
          DB::table('user_access_tbl')->insert($data);
        }

      }else{
        DB::table("user_access_tbl")->where('user_id',$data['id'])->delete();
      }

      return back()->with([
        "message" => "User updated"
      ]);

    }

    public function ftp(){
      $data = DB::select("SELECT * FROM ftp_tbl")[0];
      return view('ftp',compact('data'));
    }

    public function update_ftp(Request $request){
      $data =  $request->all();
      unset($data['_token']);
      DB::table("ftp_tbl")->where('id',1)->update($data);

      return back()->with([
        "message" => "FTP Updated!"
      ]);
    }

    public function submit_ftp_station(Request $request){
      $data = $request->all();
      $call_sign = $data['call_sign'];
      unset($data['_token']);
      if(DB::select("SELECT * FROM ftp_tbl WHERE call_sign = '$call_sign'")){
        DB::table("ftp_tbl")->where('call_sign',$call_sign)->update($data);
      }else{
          DB::table("ftp_tbl")->insert($data);
      }

      return back()->with([
        "message" => "FTP Updated!"
      ]);
    }

    public function logger(){
      $data = $this->get_logger_connection()->original;
      $user_restriction = $this->user_restriction();
      // dd($user_restriction);
      return view("logger",compact('data','user_restriction'));
    }

    public function streaming(){
      $data = $this->get_streaming_connection()->original;
      $user_restriction = $this->user_restriction();
      return view("streaming",compact('data','user_restriction'));
    }



    public function get_streaming_connection(){
        date_default_timezone_set('Asia/Manila');
        $user = session("user");
        $user_id =  $user->id;
        $station =  DB::select("SELECT * FROM user_access_tbl WHERE user_id = $user_id");
        if($user->user_lvl == 0 && $station != null){
          $station = $station[0]->station;
          $data = DB::select("SELECT  a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast,
            b.centova_url,b.centova_password,b.centova_username
            FROM streaming_listener_tbl a
            LEFT JOIN streaming_station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM streaming_station_tbl  WHERE call_sign IN ($station)) ORDER BY b.station_name ASC");
        }else{
          $data = DB::select("SELECT  a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast,
            b.centova_url,b.centova_password,b.centova_username
            FROM streaming_listener_tbl a
            LEFT JOIN streaming_station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM streaming_station_tbl) ORDER BY b.station_name ASC");
        }

        // WHERE b.station_name LIKE '%LOVE%'
        $stat = [];
        $date = date("Y.m.d.H.i.s");
        $less_time = strtotime('-35 sec');
        $date = date("Y.m.d.H.i.s", $less_time);
        // dd($data);
        // $counter = 0;
        foreach($data as $row){
            // $counter++;
            // if($counter < 35){
            //     continue;
            // }
            //
            // if($row->call_sign != "DWIL"){
            //     continue;
            // }
            if($date > $row->timestamp){
                $stat[$row->call_sign] = [
                    "network" => "disconnected",
                    "vpn" => "disconnected",
                    "status" => "off",
                    // "coordinate" => [$row->longt,$row->latt],
                    "call_sign" => $row->call_sign,
                    "station_name" => $row->station_name,
                    "logo" => $row->logo,
                    "team_viewer" => $row->team_viewer,
                    "shout_cast" => $row->shout_cast,
                    "centova_url" => $row->centova_url,
                    "centova_username" => $row->centova_username,
                    "centova_password"=> $row->centova_password
                    // "ftp" => $row->ftp
                ];
            }else{
                if($row->is_vpn_connected == 0){
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "disconnected",
                        "status" => "on",
                        // "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "centova_url" => $row->centova_url,
                        "centova_username" => $row->centova_username,
                        "centova_password"=> $row->centova_password
                        // "ftp" => $row->ftp
                    ];
                }else{
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "connected",
                        "status" => "onn",
                        // "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "centova_url" => $row->centova_url,
                        "centova_username" => $row->centova_username,
                        "centova_password"=> $row->centova_password
                        // "ftp" => $row->ftp
                    ];
                }
            }
        }

        return response()->json($stat);
    }


    public function streaming_view_station(){
        return view("streaming_view_station");
    }

    public function get_streaming_station(){
        return DataTables::of(
            DB::select("SELECT * FROM streaming_station_tbl")
        )->make(true);
    }


    public function modify_streaming_station(Request $request){
        $id =  $request->ajsdasdalkf89098aslaf9812asdfjlaf;
        $data =  DB::select("SELECT * FROM streaming_station_tbl WHERE id = $id")[0];
        $call_sign = $data->call_sign;
        $master_station = DB::select("SELECT * FROM master_station_tbl");
        return view("modify_streaming_station",compact('data','master_station'));
    }

    public function submit_centova_access_account(Request $request){
        $data = $request->all();
        $call_sign = $data['call_sign'];
        unset($data['_token']);
        unset($data['call_sign']);
        DB::table("streaming_station_tbl")->where("call_sign",$call_sign)->update($data);

        return back()->with([
          "message" => "Centova/Shoutcast crendetial added!"
        ]);
    }

    public function streaming_create_station(){
      $data =  DB::select("SELECT * FROM master_station_tbl");
      return view('streaming_create_station',compact('data'));
    }

    public function submit_create_streaming_station(Request $request){
      $data =  $request->all();
      unset($data['_token']);
      $request->validate([
          "station_name" => "required",
          "call_sign" => "required|unique:station_tbl,call_sign",
          "master_station" => "required"
      ]);
      DB::table("streaming_station_tbl")->insert($data);
      DB::table("streaming_listener_tbl")->insert(["call_sign" => $data['call_sign']]);

      $call_sign =  $data['call_sign'];
      $station_name =  $data['station_name'];
      return back()->with([
          "message" => "$call_sign | $station_name created succesfully"
      ]);
    }



    public function get_logger_connection(){
        date_default_timezone_set('Asia/Manila');
        $user = session("user");
        $user_id =  $user->id;
        $station =  DB::select("SELECT * FROM user_access_tbl WHERE user_id = $user_id");
        if($user->user_lvl == 0 && $station != null){
          $station = $station[0]->station;
          $data = DB::select("SELECT  a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast,
            CONCAT('ftp://',b.ftp_username,':',b.ftp_password,'@',b.ftp_url) as ftp
            FROM logger_listener_tbl a
            LEFT JOIN logger_station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM logger_station_tbl WHERE call_sign IN ($station))  ORDER BY b.station_name ASC");
        }else{
          $data = DB::select("SELECT  a.*, b.longt,b.latt, b.call_sign,b.station_name,b.logo,b.team_viewer,b.shout_cast,
            CONCAT('ftp://',b.ftp_username,':',b.ftp_password,'@',b.ftp_url) as ftp
            FROM logger_listener_tbl a
            LEFT JOIN logger_station_tbl b on a.call_sign = b.call_sign WHERE b.call_sign IN (SELECT call_sign FROM logger_station_tbl) ORDER BY b.station_name ASC");
        }
        $stat = [];
        $date = date("Y.m.d.H.i.s");
        $less_time = strtotime('-35 sec');
        $date = date("Y.m.d.H.i.s", $less_time);
        // dd($data);
        // $counter = 0;
        foreach($data as $row){
            // $counter++;
            // if($counter < 35){
            //     continue;
            // }
            //
            // if($row->call_sign != "DWIL"){
            //     continue;
            // }
            if($date > $row->timestamp){
                $stat[$row->call_sign] = [
                    "network" => "disconnected",
                    "vpn" => "disconnected",
                    "status" => "off",
                    // "coordinate" => [$row->longt,$row->latt],
                    "call_sign" => $row->call_sign,
                    "station_name" => $row->station_name,
                    "logo" => $row->logo,
                    "team_viewer" => $row->team_viewer,
                    "shout_cast" => $row->shout_cast,
                    "ftp" => $row->ftp,
                    // "ftp" => $row->ftp
                ];
            }else{
                if($row->is_vpn_connected == 0){
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "disconnected",
                        "status" => "on",
                        // "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "ftp" => $row->ftp,
                    ];
                }else{
                    $stat[$row->call_sign] = [
                        "network" => "connected",
                        "vpn" => "connected",
                        "status" => "onn",
                        // "coordinate" => [$row->longt,$row->latt],
                        "call_sign" => $row->call_sign,
                        "station_name" => $row->station_name,
                        "logo" => $row->logo,
                        "team_viewer" => $row->team_viewer,
                        "shout_cast" => $row->shout_cast,
                        "ftp" => $row->ftp,
                    ];
                }
            }
        }

        return response()->json($stat);
    }

    public function logger_view_station(){
      $data = $this->user_restriction();
      return view('logger_view_station');
    }

    public function get_logger_station(){
      return DataTables::of(
          DB::select("SELECT * FROM logger_station_tbl")
      )->make(true);
    }

    public function modify_logger_station(Request $request){
      $id =  $request->ajsdasdalkf89098aslaf9812asdfjlaf;
      $data =  DB::select("SELECT * FROM logger_station_tbl WHERE id = $id")[0];
      $call_sign = $data->call_sign;
      $master_station = DB::select("SELECT * FROM master_station_tbl");
      return view("modify_logger_station",compact('data','master_station'));
    }

    public function submit_ftp_access_account(Request $request){
        $data =  $request->all();
        $call_sign = $data['call_sign'];
        unset($data['_token']);
        unset($data['call_sign']);
        DB::table("logger_station_tbl")->where("call_sign",$call_sign)->update($data);

        return back()->with([
          "message" => "FTP updated!"
        ]);
    }

    public function logger_create_station(){
      $data =  DB::select("SELECT * FROM master_station_tbl");
      return view('logger_create_station',compact('data'));
    }

    public function submit_create_logger_station(Request $request){
      $data =  $request->all();
      unset($data['_token']);
      $request->validate([
          "station_name" => "required",
          "call_sign" => "required|unique:station_tbl,call_sign",
          "master_station" => "required"
      ]);
      DB::table("logger_station_tbl")->insert($data);
      DB::table("logger_listener_tbl")->insert(["call_sign" => $data['call_sign']]);

      $call_sign =  $data['call_sign'];
      $station_name =  $data['station_name'];
      return back()->with([
          "message" => "$call_sign | $station_name created succesfully"
      ]);

    }

    public function signin(Request $request){
      $data = $request->all();
      $email = $data['email'];
      $password = $data['password'];
      $user =  DB::select("SELECT a.* ,b.* FROM user_tbl a LEFT JOIN user_access_tbl b on a.id = b.user_id WHERE a.email = '$email'");
      if($user){
        if($password == $user[0]->password){
          session([
            "user" => $user[0]
          ]);
        }
      }
      throw ValidationException::withMessages(['message' => 'Unauthorize access is prohibited, please login']);
    }

    private function get_master_station_filter(){
        return   DB::select("SELECT * FROM master_station_tbl");
    }

    public function filter(Request $request){
        $data = $request->all();
        dd($data);
    }

    public function logout(){
      session()->flush();
      return redirect()->route('login')->with([
        "logout" => "Logout Successfully!"
      ]);
    }


    private function user_restriction(){
      $user_session =  session("user");
      // dd($user_session);
      $ftp = "";
      $team_viewer = "";
      $audio_player_st = "";
      if($user_session->user_lvl == 0){
        $user_id = $user_session->user_id;
        $data = DB::select("SELECT * FROM user_access_tbl WHERE user_id = $user_id");
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

      return [
        "ftp" => $ftp,
        "team_viewer" => $team_viewer,
        "audio_player_st" => $audio_player_st
      ];
    }

    public function change_password(){
      return view("change_password");
    }

    public function submit_change_password(Request $request){
        $user = session('user');
        $user_id = $user->id;
        $request->validate([
          "password" => 'required'
        ]);
        DB::table("user_tbl")->where("id",$user_id)->update([
          "password" => $request->password
        ]);
        return back()->with([
          "message" => "Password updated!"
        ]);
    }

    public function auth_log(){
      return view('auth_log');
    }

}
