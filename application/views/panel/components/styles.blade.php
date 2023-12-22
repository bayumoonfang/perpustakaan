<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset_url('images/logo-1.png') }}">

<!-- Bootstrap Css -->
<link href="{{ asset_url('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset_url('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset_url('css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<script>
    var baseUrl = "<?= base_url() ?>";
    var siteUrl = "<?= site_url() ?>";
</script>
@yield('styles')
