<?php
 
  $setting =\App\Models\SettingDetail::find(1);
  $user = auth()->user();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        @include('frontend.layouts.head')
        <!-- Thêm link CSS cho Bootstrap và Tailwind -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
        @yield('head_css')
    </head>
    <body class="[word-spacing:.05rem!important] font-Manrope text-[0.8rem] !leading-[1.7] font-medium bg-gray-50">

    @include('frontend.layouts.header')
    @include('frontend.layouts.notification')

    <div class="min-vh-100 d-flex flex-column"> <!-- THÊM WRAPPER -->

        <main class="flex-grow-1">
            @yield('content')
        </main>

        @include('frontend.layouts.footer')
    </div>

    @include('frontend.layouts.foot')
    @yield('scripts')

</body>

</html>