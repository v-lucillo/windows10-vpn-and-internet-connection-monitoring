@extends('layout.layout')
@section('style')
  <style type="text/css">
    tr{
      cursor: pointer;
    }
  </style>
@endsection
@section('page_title')
  Create Master Station
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create master station form</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_master_station')}}" method="POST">
          @csrf
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Radyo Station Name</label>
            <div class="col">
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

<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Master station list</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table card-table table-vcenter text-nowrap datatable" id="master_station_tbl" style="width: 100%;">
            <thead>
              <tr>
                <th>Master station</th>
              </tr>
            </thead>
           <tbody></tbody>
          </table>
        </div>
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

    var master_station_tbl =  $('table#master_station_tbl').DataTable({
        serverSide: true,
        processing: true,
        ajax: "{{route('admin.get_master_station')}}",
        columns: [
          {data: "master_station"},
        ]
      });


    $(document).on('click','table#master_station_tbl tbody tr', function(){
      var data =  master_station_tbl.row( this ).data();
      window.location.href = "{{route('admin.modify_master_station')}}?akajhdad12hasdhkjsa123ah129083asdhk="+data.id;
    });
    
  </script>
@endsection
