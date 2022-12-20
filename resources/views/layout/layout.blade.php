<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta9
* @link https://tabler.io
* Copyright 2018-2022 The Tabler Authors
* Copyright 2018-2022 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    @include('layout.head')
    @yield('style')
  </head>
  <body class = "theme-dark">
    <div class="page">
      @include('layout.header')
      @include('layout.navbar')
      <div class="page-wrapper">
        <div class="container-xl">
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
                <h2 class="page-title">
                  @yield('page_title')
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div class="page-body">
          <div class="container-xl">
              @yield('container')
          </div>
        </div>
        @include('layout.footer')
      </div>
    </div>

    @include('layout.script')
    @yield('script')
  </body>
</html>
