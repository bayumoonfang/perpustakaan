@extends('panel.app')
@section('title', $title)

@section('content')
    <div id="root" data-title="{{ $title }}" data-admin_url="{{ admin_url() }}"
        data-user="{{ json_encode($user) }}" data-library="{{ json_encode($library) }}"
        data-history="{{ json_encode($history) }}">
    </div>

@endsection
@section('scripts')
    <script src="{{ base_url('assets/js/vendor/vendor.js') }}"></script>
    <script src="{{ base_url('assets/js/vendor/issue.js') }}"></script>
@endsection
