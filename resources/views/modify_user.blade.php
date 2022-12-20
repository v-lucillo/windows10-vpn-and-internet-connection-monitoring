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
  Modify User
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Modify user form</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_modify_user')}}" method="POST">
          @csrf
          <input type="text" name="id" value="{{$id}}" hidden>
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">First Name</label>
            <div class="col">
              <input value="{{old('first_name')}}" name = "first_name" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter first name">
                @error('first_name')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">First name (ex: Victorino I)</small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Last name</label>
            <div class="col">
              <input value="{{old('last_name')}}" name = "last_name" type="text" class="form-control" placeholder="Enter last name">
              @error('last_name')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Last Name (ex: Lucillo)
              </small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Email</label>
            <div class="col">
              <input value="{{old('email')}}" name = "email" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
                @error('email')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">Email (ex: email@mbcradio.net)</small>
            </div>
          </div>

          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Password</label>
            <div class="col">
              <input value="{{old('password')}}" name = "password" type="text" class="form-control" placeholder="Enter password">
              @error('password')
                    <div class="text-muted font-italic">
                      <small class="text-warning font-weight-700">{{$message}} </small>
                    </div>
                @enderror
              <small class="form-hint">
                Password (ex. ABcdEF123)
              </small>
            </div>
          </div>


           <hr>


           <div class="form-group mb-3 row">
             <label class="form-label col-3 col-form-label">User Level</label>
             <div class="col">
               <div class="form-selectgroup-boxes row mb-3">
                  <div class="col-lg-6">
                    <label class="form-selectgroup-item">
                      <input type="radio" name="user_lvl" value="1" class="form-selectgroup-input" id ="admin">
                      <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                          <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                          <span class="form-selectgroup-title strong mb-1">Admin</span>
                          <span class="d-block text-muted"></span>
                        </span>
                      </span>
                    </label>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-selectgroup-item">
                      <input type="radio" name="user_lvl" value="0" class="form-selectgroup-input" id ="user">
                      <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                          <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                          <span class="form-selectgroup-title strong mb-1">User</span>
                          <span class="d-block text-muted"></span>
                        </span>
                      </span>
                    </label>
                  </div>
                </div>
             </div>
           </div>


           <hr>


           <div class="user_lvl_priv">
             <div class="form-group mb-3 row">
               <label class="form-label col-3 col-form-label">Station</label>
               <div class="col">
                 <select name = "station[]" class="form-select" multiple>
                   @foreach($station as $row)
                      <option value="{{$row->call_sign}}" selected>{{$row->station_name}}</option>
                   @endforeach
                 </select>
                 @error('call_sign')
                       <div class="text-muted font-italic">
                         <small class="text-warning font-weight-700">{{$message}} </small>
                       </div>
                   @enderror
               </div>
             </div>

             <div class="form-group mb-3 row">
               <label class="form-label col-3 col-form-label">Access</label>
               <div class="col">
                  <div class="mb-3">
                      <div>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="audio_logger" value="1">
                          <span class="form-check-label">Audio Logs</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox"  name="shout_cast" value="1">
                          <span class="form-check-label">Live streaming monitoring</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox"  name="winmedia" value="1">
                          <span class="form-check-label">Winmedia</span>
                        </label>
                      </div>
                    </div>
               </div>
             </div>

             <div class="form-group mb-3 row">
               <label class="form-label col-3 col-form-label">Priviledge</label>
               <div class="col">
                  <div class="mb-3">
                      <div>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="ftp" value="1">
                          <span class="form-check-label">FTP</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox"  name="audio_player_st" value="1">
                          <span class="form-check-label">Shoutcast</span>
                        </label>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox"  name="team_viewer" value="1">
                          <span class="form-check-label">Teamviewer</span>
                        </label>
                      </div>
                    </div>
               </div>
             </div>

             <div class="form-group mb-3 row">
               <label class="form-label col-3 col-form-label">Credential</label>
               <div class="col">
                  <div class="mb-3">
                      <div>
                        <label class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="shout_cast_cred" value="1">
                          <span class="form-check-label">Shoutcast server credential</span>
                        </label>
                      </div>
                    </div>
                    @error('error_message')
                          <div class="text-muted font-italic">
                            <small class="text-warning font-weight-700">{{$message}} </small>
                          </div>
                    @enderror
               </div>
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
      $('input[name="first_name"]').val(data.first_name);
      $('input[name="last_name"]').val(data.last_name);
      $('input[name="email"]').val(data.email);
      $('input[name="password"]').val(data.password);
      var user_lvl_priv = $('div.user_lvl_priv');
      if(data.user_lvl == 0){
        $('input#user').attr('checked','checked');
          user_lvl_priv.show();
      }else{
        $('input#admin').attr('checked','checked');
        user_lvl_priv.hide();
      }
      if(data.shout_cast == 1){
          $('input[name="shout_cast"]').attr('checked','checked');
      }
      if(data.team_viewer == 1){
          $('input[name="team_viewer"]').attr('checked','checked');
      }
      if(data.audio_logger == 1){
          $('input[name="audio_logger"]').attr('checked','checked');
      }

      if(data.ftp == 1){
          $('input[name="ftp"]').attr('checked','checked');
      }
      if(data.winmedia == 1){
          $('input[name="winmedia"]').attr('checked','checked');
      }
      if(data.audio_player_st == 1){
          $('input[name="audio_player_st"]').attr('checked','checked');
      }

      if(data.shout_cast_cred == 1){
          $('input[name="shout_cast_cred"]').attr('checked','checked');
      }


      $(document).on('click','input[name="user_lvl"]',function(){
        var data =  $(this).val();
        if(data == 0){
          user_lvl_priv.show();
        }else{
          user_lvl_priv.hide();
        }
      });


      var message = "{{session('message')}}";
      if(message){
        Swal.fire(
          'Success!',
          message,
          'success'
        )
      }


      $("select[name='master_station[]']").select2({
        ajax: {
          url: "{{route('admin.get_master_station_lov')}}",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
              results: data.results,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        placeholder: 'Select master station',
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        multiple: true,
      });



      $("select[name='station[]']").select2({
        ajax: {
          url: "{{route('admin.get_station_lov')}}",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              page: params.page
            };
          },
          processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
              results: data.results,
              pagination: {
                more: (params.page * 30) < data.total_count
              }
            };
          },
          cache: true
        },
        placeholder: 'Select station',
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        multiple: true,
      });



      function formatRepo (repo) {
        if (repo.loading) {
          return repo.text;
        }

        return repo.text;
      }

      function formatRepoSelection (repo) {
        return repo.text;
      }



  </script>
@endsection
