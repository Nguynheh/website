<?php
 
  $setting =\App\Models\SettingDetail::find(1);
  $user = auth()->user();

?>
@extends('frontend.layouts.master')


@section('content')
  @include('frontend.layouts.dashboard') {{-- Include đúng chỗ --}}
    

@endsection
