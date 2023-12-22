@extends('panel.app')
@section('title', 'Dashboard')
@section('content')

		<div id="root" data-url={{admin_url()}} data-is_admin={{is_admin()? '1':'0'}} data-year={{date('Y')}}></div>

<!-- End Page-content -->
@endsection
@section('scripts')
<script src="{{base_url('assets/js/vendor/vendor.js')}}"></script>
<script src="{{base_url('assets/js/vendor/dashboard.js')}}"></script>
@endsection