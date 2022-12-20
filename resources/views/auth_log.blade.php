@extends('layout.layout')
@section('style')

@endsection
@section('page_title')
  Authentication Logs
@endsection
@section('container')
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="station_table" style="width: 100%;">
          <thead>
            <tr>
              <th>Email</th>
              <th>Time</th>
              <th>IP</th>
              <th>User Agent</th>
            </tr>
          </thead>
         <tbody></tbody>
        </table>
      </div>
@endsection

@section('script')
<script>
    var station_table =  $('table#station_table').DataTable({
     		serverSide: true,
     		processing: true,
     		ajax: "{{route('admin.get_auth_log')}}",
     		columns: [
     			{data: "email"},
     			{data: "login"},
          {data: "ip"},
          {data: "user_agent"},
     		]
   	});

</script>
@endsection
