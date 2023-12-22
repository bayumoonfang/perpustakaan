<!doctype html>
<html lang="en" class="pxp-root">

<!-- Mirrored from pixelprime.co/themes/jobster/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Sep 2022 01:46:50 GMT -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="shortcut icon" href="{{asset_url('images/logo-1.png')}}" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{base_url('assets/pengunjung/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{base_url('assets/pengunjung/css/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{base_url('assets/pengunjung/css/owl.theme.default.min.css')}}">
  <link rel="stylesheet" href="{{base_url('assets/pengunjung/css/animate.css')}}">
  <link rel="stylesheet" href="{{base_url('assets/pengunjung/css/style.css')}}">

  <title>BM400 - Buku Tamu</title>
</head>

<body>
  <div class="pxp-preloader"><span>Loading...</span></div>

  <header class="pxp-header fixed-top">
    <div class="pxp-container">
      <div class="pxp-header-container">
        <div class="pxp-logo">
          <a href="#" class="pxp-animate"><span style="color: var(--pxpMainColor)">BM</span>400</a>
        </div>
        <!-- <nav class="pxp-user-nav pxp-on-light d-none d-sm-flex">
                        <a href="company-dashboard-new-job.html" class="btn rounded-pill pxp-nav-btn">BM400</a>
                    </nav> -->
      </div>
    </div>
  </header>

  <section class="pxp-hero vh-100" style="background-color: var(--pxpMainColorLight);">
    <div class="row align-items-center pxp-sign-hero-container">
      <div class="col-xl-6 pxp-column">
        <div class="pxp-sign-hero-fig text-center pb-100 pt-100">
          <img src="{{base_url('assets/read.svg')}}" alt="Sign up">
          <h1 class="mt-4 mt-lg-5">Buku Tamu {{$library_detail->library}}</h1>
        </div>
      </div>
      <div class="col-xl-6 pxp-column pxp-is-light">
        <div class="pxp-sign-hero-form pb-100 pt-100">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-8 col-xxl-8">
              <div class="pxp-sign-hero-form-content">
                <h5 class="text-center">{{$sekolah_detail ? $sekolah_detail->sekolah_nama:''}}</h5>
                {{show_status()}}
                <form method="POST" action="{{site_url('post-buku-tamu')}}" class="mt-4">
                    {{csrf_token()}}
                  <div class="form-floating mb-3">
                    <input name="library" required type="hidden" class="form-control" value="{{$lib_id}}">
                    <input name="lib_enc" required type="hidden" class="form-control" value="{{$lib_enc}}">
                    <input name="nis" type="text" required class="form-control" id="pxp-signup-page-email">
                    <label for="pxp-signup-page-email">NIS / NIP</label>
                    <span class="fa fa-user"></span>
                  </div>
                  <div class="mb-3">
                    <textarea name="description" required class="form-control" id="pxp-company-about" placeholder="Keperluan"
                      style="height: 100px;"></textarea>
                  </div>
                  <button type="submit" style="width:100%" class="btn-block btn rounded-pill pxp-sign-hero-form-cta mb-2">Submit</button>
                  {{-- <a href="" style="width:100%;background-color:#6698FF" class="btn-block btn rounded-pill pxp-sign-hero-form-cta">Guest</a> --}}
                  <div id="button_guest"
                      data-url={{site_url()}}
                      data-library={{$lib_id}}
                      >
                  </div>
                </form>
                <hr/>
                <div id="button_catalog"
                  data-url={{site_url()}}
                  data-library={{$lib_id}}
                  >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <script src="{{base_url('assets/pengunjung/js/jquery-3.4.1.min.js')}}"></script>
  <script src="{{base_url('assets/pengunjung/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{base_url('assets/pengunjung/js/owl.carousel.min.js')}}"></script>
  <script src="{{base_url('assets/pengunjung/js/nav.js')}}"></script>
  <script src="{{base_url('assets/pengunjung/js/main.js')}}"></script>
  <script src="{{base_url('assets/js/vendor/vendor.js')}}"></script>
  <script src="{{base_url('assets/js/vendor/catalog.js')}}"></script>
  <script src="{{base_url('assets/js/vendor/guest.js')}}"></script>
</body>

<!-- Mirrored from pixelprime.co/themes/jobster/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Sep 2022 01:46:51 GMT -->

</html>