@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();

/* Display elements */
$customizerHidden = ($customizerHidden ?? '');

@endphp

@extends('layouts.layoutFront' )

@section('layoutContent')

<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
