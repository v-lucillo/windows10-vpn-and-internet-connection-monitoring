@extends('layout.layout')
@section('style')
  <style media="screen">
    tr{
      cursor: pointer;
    }
  </style>
@endsection
@section('page_title')
  Station List
@endsection
@section('container')
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="user_table" style="width: 100%;">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
            </tr>
          </thead>
         <tbody></tbody>
        </table>
      </div>
@endsection

@section('script')
<script>
    var user_table =  $('table#user_table').DataTable({
     		serverSide: true,
     		processing: true,
     		ajax: "{{route('admin.get_user')}}",
     		columns: [
     			{data: "first_name"},
     			{data: "last_name"},
          {data: "email"},
     		]
   	});

    $(document).on('click','table#user_table tbody tr', function(){
      var data =  user_table.row( this ).data();
      window.location.href = "{{route('admin.modify_user')}}?ajsdasdalkf89098aslaf9812asdfjlaf="+data.id;
    });
</script>
@endsection
