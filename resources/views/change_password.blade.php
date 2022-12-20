@extends('layout.layout')
@section('style')
  <style media="screen">
  .select2-container {
    width: 100% !important;
  }

  .select2-container--default .select2-selection--single {
    height: 40px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    margin-top: 7px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    margin-top: 4px !important;
  }

  </style>
@endsection
@section('page_title')
  Change password
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Change password</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_change_password')}}" method="POST">
          @csrf
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">New Password</label>
            <div class="col"><input  name = "password" type="text" class="form-control" aria-describedby="emailHelp" placeholder="New Password">
                @error('password')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
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
