@extends('layout.layout')
@section('style')
  <style type="text/css">
    tr{
      cursor: pointer;
    }
  </style>
@endsection
@section('page_title')
  Modify Master Station
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Modify master station form</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_modify_master_station')}}" method="POST">
          @csrf
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Radyo Station Name</label>
            <div class="col">
              <input type="" name="id" value="{{$id}}" hidden>
              <input name = "master_station" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter station name">
                @error('master_station')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Station name (ex: Love Radio)</small>
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

    var master_station = "{{$master_station}}";
    $('input[name="master_station"]').val(master_station);
    
  </script>
@endsection
