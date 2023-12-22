<!-- JAVASCRIPT -->
<script src="{{ asset_url('libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset_url('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset_url('libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset_url('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset_url('libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset_url('libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset_url('libs/jquery.counterup/jquery.counterup.min.js') }}"></script>

<!-- apexcharts -->
{{-- <script src="{{asset_url('libs/apexcharts/apexcharts.min.js')}}"></script> --}}

{{-- <script src="{{asset_url('js/pages/dashboard.init.js')}}"></script> --}}
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ asset_url('js/app.js') }}"></script>
@yield('scripts')
