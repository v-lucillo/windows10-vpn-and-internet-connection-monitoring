@extends('layout.layout')
@section('style')
  <style type="text/css">
    tr{
      cursor: pointer;
    }
  </style>
@endsection
@section('page_title')
  FTP Configuration
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">FTP Configuration</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.update_ftp')}}" method="POST">
          @csrf
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">FQDN/IP/URL</label>
            <div class="col">
              <input value="{{$data->url}}" name = "url" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter FQDN/IP/URL">
                @error('url')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">URL (ex: ftp.google.com)</small>
            </div>
          </div>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Port</label>
            <div class="col">
              <input value="{{$data->port}}" name = "port" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter port">
                @error('port')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Port (ex: 20)</small>
            </div>
          </div>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Username</label>
            <div class="col">
              <input value="{{$data->username}}" name = "username" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter username">
                @error('port')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Username (ex: badjong)</small>
            </div>
          </div>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Password</label>
            <div class="col">
              <input value="{{$data->password}}" name = "password" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter password">
                @error('password')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Password (ex: asd123PA23)</small>
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
  </script>
@endsection
