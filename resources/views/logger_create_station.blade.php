@extends('layout.layout')
@section('style')

@endsection
@section('page_title')
  Create Station
@endsection
@section('container')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create station form</h3>
      </div>
      <div class="card-body">
        <form action="{{route('admin.submit_create_logger_station')}}" method="POST">
          @csrf
          <div class="form-group mb-3 row">
            <label class="form-label col-3 col-form-label">Master Station</label>
            <div class="col">
                <select name = "master_station" class="form-select">
                  <option value="">--Select master station--</option>
                  @foreach($data as $row)
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

           <hr>

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
