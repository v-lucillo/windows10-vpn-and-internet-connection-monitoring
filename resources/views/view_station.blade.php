@extends('layout.layout')
@section('style')
@endsection
@section('page_title')
  Station List
@endsection
@section('container')

<table class="table table-vcenter card-table" id="station_table" style="width: 100%;">
  <thead>
    <tr>
      <th>Call Sign</th>
      <th>Station Name</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>


@endsection

@section('script')
<script>
    var station_table =  $('table#station_table').DataTable({
     		serverSide: true,
     		processing: true,
     		ajax: "{{route('api.get_station')}}",
     		columns: [
     			{data: "call_sign"},
     			{data: "station_name"},
     		]
   	});

    $(document).on('click','table#station_table tbody tr', function(){
      var data =  station_table.row( this ).data();
      window.location.href = "{{route('admin.modify_station')}}?ajsdasdalkf89098aslaf9812asdfjlaf="+data.id;
    });
</script>
@endsection
