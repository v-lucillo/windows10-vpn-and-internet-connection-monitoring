@extends('layout.layout')
@section('style')

@endsection
@section('page_title')
  Modify Station
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Modify station form</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_modify_station')}}" method="POST" id = "edit_form">
          @csrf
          <input type="" name="id" value="{{$data->id}}" hidden>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Master Station</label>
            <div class="col">
                <select name = "master_station" class="form-select">
                  <option value="">--Select master station--</option>
                  @foreach($master_station as $row)
                    <option value="{{$row->id}}">{{$row->master_station}}</option>
                  @endforeach
                </select>
                @error('master_station')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Station name (ex: Love Radio)</small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Station Name</label>
            <div class="col">
              <input name = "station_name" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('station_name')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Station name (ex: Love Radio Laog)</small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Call Sign</label>
            <div class="col">
              <input name = "call_sign" type="text" class="form-control" placeholder="Enter call sign">
              @error('call_sign')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Call sign (ex: DWWS)
              </small>
            </div>
          </div>

           <!-- <hr> -->

           <div class="form-group mb-3 row" hidden>
            <label class="form-label col-3 col-form-label">Longtitude</label>
            <div class="col">
              <input name = "longt" type="text" class="form-control" placeholder="Enter Longtitude">
                @error('longt')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Longtitude (ex: 16.691969081187874)
              </small>
            </div>
          </div>
          <div class="form-group mb-3 row" hidden>
            <label class="form-label col-3 col-form-label">Latitude</label>
            <div class="col">
              <input name = "latt" type="text" class="form-control" placeholder="Enter Latitude">
                @error('latt')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Latitude (ex: 121.5535125539633)
              </small>
            </div>
          </div>

          <div class="form-group mb-3 row" hidden>
            <label class="form-label col-3 col-form-label">Address</label>
            <div class="col">
              <input name = "address" type="text" class="form-control" placeholder="Enter Address ">
                @error('address')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Address (ex: #123 Loveradio Kalyeng langit, Philippines)
              </small>
            </div>
          </div>

          <div class="form-group mb-3 row" hidden>
            <label class="form-label col-3 col-form-label">Additional information</label>
            <div class="col">
              <textarea name = "other_info" class="form-control" name="example-textarea-input" rows="6" placeholder="Additional information..."></textarea>
                @error('ohter_info')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
              </small>
            </div>
          </div>

          <div class="form-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<br>
<br>


<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Teamviewer Access ID</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_modify_station_tv')}}" method="POST" id = "edit_form">
          @csrf
          <input type="" name="id" value="{{$data->id}}" hidden>
          <input type="" name="application" value="logger_station_tbl" hidden>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Teamviewer ID</label>
            <div class="col">
              <input name = "team_viewer" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('team_viewer')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Station name (ex: 224374798)</small>
            </div>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<br><br>

<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">File Transfer Protocol</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_ftp_access_account')}}" method="POST">
          @csrf
          <input type="" name="call_sign" value="{{$data->call_sign}}" hidden>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">URL</label>
            <div class="col">
              <input name = "ftp_url" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('ftp_url')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">URL (ex: ftp.google.net)</small>
            </div>
          </div>


          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Port</label>
            <div class="col">
              <input name = "ftp_port" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('ftp_port')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Username (ex: 21)</small>
            </div>
          </div>



          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Username</label>
            <div class="col">
              <input name = "ftp_username" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('ftp_username')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Username (ex: ftpusrname)</small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Password</label>
            <div class="col">
              <input name = "ftp_password" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('ftp_password')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">password (ex: 1234asdf123)</small>
            </div>
          </div>

          <div class="form-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection


@section('script')
  <script type="text/javascript">
    var  data = {!! json_encode($data) !!};
    $('input[name="team_viewer"]').val(data.team_viewer);
    $('input[name="shout_cast"]').val(data.shout_cast);
    $('input[name="station_name"]').val(data.station_name);
    $('input[name="call_sign"]').val(data.call_sign);
    $('input[name="longt"]').val(data.longt);
    $('input[name="latt"]').val(data.latt);
    $('input[name="address"]').val(data.address);
    $('textarea[name="other_info"]').val(data.other_info);
    $('input[name="ftp_url"]').val(data.ftp_url);
    $('input[name="ftp_port"]').val(data.ftp_port);
    $('input[name="ftp_username"]').val(data.ftp_username);
    $('input[name="ftp_password"]').val(data.ftp_password);
    $("select[name='master_station']").find('option[value="'+data.master_station+'"]').attr('selected','selected');
    var message = "{{session('message')}}";
    if(message){
      Swal.fire(
        'Success!',
        message,
        'success'
      )
    }
  </script>
@endsection
