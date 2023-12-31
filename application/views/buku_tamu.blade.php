<!doctype html>
<html lang="en" class="pxp-root">

<!-- Mirrored from pixelprime.co/themes/jobster/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Sep 2022 01:46:50 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="{{ asset_url('images/logo-1.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&amp;display=swap"
        rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ base_url('assets/pengunjung/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ base_url('assets/pengunjung/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ base_url('assets/pengunjung/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ base_url('assets/pengunjung/css/animate.css') }}">
    <link rel="stylesheet" href="{{ base_url('assets/pengunjung/css/style.css') }}">
    <link rel="stylesheet" href="{{ base_url('assets/libs/jquery-ui/jquery-ui.min.css') }}">
    <style>
        body {
            display: grid;
            grid-template-rows: 1fr auto;
            /* 1 fr untuk konten utama, auto untuk footer */
            min-height: 100vh;
        }

        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }

        /* IE 6 doesn't support max-height
 * we use height instead, but this forces the menu to always be this tall
 */
        * html .ui-autocomplete {
            height: 100px;
        }
    </style>
    <title>BM400 - Buku Tamu</title>
</head>

<body>
    <div class="pxp-preloader"><span>Loading...</span></div>



    <section class="pt-5 pb-100">
        <div class="pxp-container" style="padding-left: 0.5rem; padding-right: 0.5rem;">
            <h2 class="pxp-section-h2 text-center">Selamat Datang di {{ $library_detail->library }}</h2>
            <div class="row mt-5">
                <div class="col-6" style="padding-right: 5rem;">
                    <div class="row nav pxp-animate-in pxp-animate-in-top pxp-in" role="tablist">
                        <a class="col-6 pxp-library-card active" type="button" id="qrcode" data-bs-toggle="tab"
                            data-bs-target="#qrCode" role="tab" aria-controls="qrCode" aria-selected="true">
                            <div class="pxp-library-card-1 text-center">
                                <div class="pxp-cities-card-1-top">
                                    <div class="pxp-library-card-1-image pxp-cover bg-white">
                                        <div class="pxp-categories-card-2-icon">
                                            <span class="fa fa-qrcode"></span>
                                        </div>
                                    </div>
                                    <div class="pxp-library-card-1-name">Scan QR-Code</div>
                                </div>
                                <div class="pxp-library-card-1-bottom">
                                    <div class="pxp-library-card-1-jobs font-italic">Scan jika memiliki qrcode</div>
                                </div>
                            </div>
                        </a>
                        <a class="col-6 pxp-library-card" type="button" id="internal" data-bs-toggle="tab"
                            data-bs-target="#anggotaInternal" role="tab" aria-controls="anggotaInternal"
                            aria-selected="true">
                            <div class="pxp-library-card-1 text-center">
                                <div class="pxp-cities-card-1-top">
                                    <div class="pxp-library-card-1-image pxp-cover bg-white">
                                        <div class="pxp-categories-card-2-icon">
                                            <span class="fa fa-user-circle-o"></span>
                                        </div>
                                    </div>
                                    <div class="pxp-library-card-1-name">Internal</div>
                                </div>
                                <div class="pxp-library-card-1-bottom">
                                    <div class="pxp-library-card-1-jobs font-italic">Anggota BM400</div>
                                </div>
                            </div>
                        </a>
                        <a class="col-6 pxp-library-card" type="button" id="eksternal" data-bs-toggle="tab"
                            data-bs-target="#anggotaEksternal" role="tab" aria-controls="anggotaEksternal"
                            aria-selected="true">
                            <div class="pxp-library-card-1 text-center">
                                <div class="pxp-cities-card-1-top">
                                    <div class="pxp-library-card-1-image pxp-cover bg-white">
                                        <div class="pxp-categories-card-2-icon">
                                            <span class="fa fa-building-o"></span>
                                        </div>
                                    </div>
                                    <div class="pxp-library-card-1-name">Eksternal</div>
                                </div>
                                <div class="pxp-library-card-1-bottom">
                                    <div class="pxp-library-card-1-jobs font-italic">Tamu Luar BM400</div>
                                </div>
                            </div>
                        </a>
                        <div class="col-6 pxp-library-card">
                            <a href="katalog.html" class="pxp-library-card-1 text-center">
                                <div class="pxp-cities-card-1-top">
                                    <div class="pxp-library-card-1-image pxp-cover bg-white">
                                        <div class="pxp-categories-card-2-icon">
                                            <span class="fa fa-book"></span>
                                        </div>
                                    </div>
                                    <div class="pxp-library-card-1-name">Katalog Buku</div>
                                </div>
                                <div class="pxp-library-card-1-bottom">
                                    <div class="pxp-library-card-1-jobs font-italic">Lihat!</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="tab-content pxp-jobs-tab-content">
                        <div class="tab-pane mt-4 active" id="qrCode" role="tabpanel" aria-labelledby="qrcode">
                            <div class="pxp-jobs-card-1 pxp-has-shadow">
                                <div class="pxp-sign-hero-form">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 col-xl-8 col-xxl-8">
                                            <div class="pxp-sign-hero-form-content">
                                                <div class="row">
                                                    <div class="col-12" style="text-align: -webkit-center;">
                                                        <div class="pxp-company-dashboard-messages-item-avatar pxp-cover"
                                                            style="background-image: url(images/logo-1.png);"></div>
                                                    </div>
                                                </div>
                                                <h5 class="text-center mt-4">Silakan Scan QRCode E-Card</h5>
                                                <form class="mt-4">
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control"
                                                            id="pxp-signup-page-email" placeholder="Email address"
                                                            disabled>
                                                        <label for="pxp-signup-page-email">Autofill NIS / NIP</label>
                                                        <span class="fa fa-user"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control" id="pxp-company-about" placeholder="Keperluan" style="height: 100px;"></textarea>
                                                    </div>
                                                    <a href="#"
                                                        class="btn rounded-pill pxp-sign-hero-form-cta">Submit</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane mt-4" id="anggotaInternal" role="tabpanel" aria-labelledby="internal">
                            <div class="pxp-jobs-card-1 pxp-has-shadow">
                                <div class="pxp-sign-hero-form">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 col-xl-8 col-xxl-8">
                                            <div class="pxp-sign-hero-form-content">
                                                <div class="row">
                                                    <div class="col-12" style="text-align: -webkit-center;">
                                                        <div class="pxp-company-dashboard-messages-item-avatar pxp-cover"
                                                            style="background-image: url(images/logo-1.png);"></div>
                                                    </div>
                                                </div>
                                                <h5 class="text-center mt-4">Silakan Masukkan Data Diri</h5>
                                                {{ show_status() }}
                                                <form class="mt-4" method="POST"
                                                    action="{{ site_url('post-buku-tamu') }}">
                                                    {{ csrf_token() }}
                                                    <div class="form-floating mb-3">
                                                        <input name="library" required type="hidden"
                                                            class="form-control" value="{{ $lib_id }}">
                                                        <input name="lib_enc" required type="hidden"
                                                            class="form-control" value="{{ $lib_enc }}">
                                                        <input name="nis" id="nis" required type="hidden"
                                                            class="form-control">
                                                        <input type="text" class="form-control textInputFilter"
                                                            name="nama" id="pxp-signup-page-nama"
                                                            placeholder="Nama">
                                                        <label for="pxp-signup-page-nama">Cari NIS / NIP / Nama
                                                            ...</label>
                                                        <span class="fa fa-user"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control" id="pxp-company-about" name="description" placeholder="Keperluan"
                                                            style="height: 100px;"></textarea>
                                                    </div>
                                                    <button type="submit" style="width: 100%"
                                                        class="btn-block btn rounded-pill pxp-sign-hero-form-cta mb-2">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="anggotaEksternal" role="tabpanel"
                            aria-labelledby="anggotaEksternal">
                            <div class="pxp-jobs-card-1 pxp-has-shadow">
                                <div class="pxp-sign-hero-form">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 col-xl-8 col-xxl-8">
                                            <div class="pxp-sign-hero-form-content">
                                                <div class="row">
                                                    <div class="col-12" style="text-align: -webkit-center;">
                                                        <div class="pxp-company-dashboard-messages-item-avatar pxp-cover"
                                                            style="background-image: url(images/logo-1.png);"></div>
                                                    </div>
                                                </div>
                                                <h5 class="text-center mt-4">Silakan Masukkan Data Diri</h5>
                                                <form class="mt-4">
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control"
                                                            id="pxp-signup-page-email" placeholder="Email address">
                                                        <label for="pxp-signup-page-email">Nama</label>
                                                        <span class="fa fa-user"></span>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="password" class="form-control"
                                                            id="pxp-signin-password" placeholder="Password">
                                                        <label for="pxp-signin-password">Institusi</label>
                                                        <span class="fa fa-university"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control" id="pxp-company-about" placeholder="Keperluan" style="height: 100px;"></textarea>
                                                    </div>
                                                    <a href="#"
                                                        class="btn rounded-pill pxp-sign-hero-form-cta">Submit</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="{{ base_url('assets/pengunjung/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ base_url('assets/pengunjung/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ base_url('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ base_url('assets/pengunjung/js/owl.carousel.min.js') }}"></script>
    <script src="{{ base_url('assets/pengunjung/js/nav.js') }}"></script>
    <script src="{{ base_url('assets/pengunjung/js/main.js') }}"></script>
    <script src="{{ base_url('assets/js/vendor/vendor.js') }}"></script>
    <script src="{{ base_url('assets/js/vendor/catalog.js') }}"></script>
    <script src="{{ base_url('assets/js/vendor/guest.js') }}"></script>
    <script>
        $(function() {
            $(".textInputFilter").autocomplete({
                source: function(request, response) {
                    var url = "{{ admin_url('member/ajax/data/(:any)') }}";
                    $.ajax({
                        type: "POST",
                        url: url.replace("(:any)", $('.textInputFilter').val()),
                        dataType: "json",
                        success: function(res) {
                            response(res);
                            //     for (var i = 0; i < res.length; ++i) {
                            //         console.log(res[i].user_nama);
                            //     };
                            //     // var data = JSON.stringify(res);
                            //     // // console.log(data);
                            //     // $.each(data, function(a, b) {
                            //     //     response(b.user_nama);
                            //     //     // response(b.nama);
                            //     //     console.log(b.user_nama);
                            //     // });
                        }
                    });
                },
                // source: "{{ admin_url('member/ajax/data') }}",
                select: function(event, ui) {
                    $('#pxp-signup-page-nama').val(ui.item.user_nama); // save selected id to input
                    $('#nis').val(ui.item.user_no); // save selected id to input
                    return false;
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.user_nama + "</div>")
                    .appendTo(ul);
            };
        });
    </script>
</body>

<!-- Mirrored from pixelprime.co/themes/jobster/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Sep 2022 01:46:51 GMT -->

</html>
