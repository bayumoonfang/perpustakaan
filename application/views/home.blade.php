<!doctype html>
<html lang="en">

    
<!-- Mirrored from themesbrand.com/minible/layouts/vertical/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Oct 2020 07:17:41 GMT -->
<head>
        
        <meta charset="utf-8" />
        <title>E-Library</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{base_url('assets/images/logo-1.png')}}">

        <!-- Bootstrap Css -->
        <link href="{{base_url('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{base_url('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{base_url('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <style>
            .header-judul{
                font-family: NunitoSansBold,sans-serif;
                font-weight: 700;
                color: white;
                letter-spacing: -.02rem;
                line-height: clamp(2.25rem,3.5vw,3.5rem);
                
            }
            .des-judul{
                font-family: NunitoSansRegular,sans-serif;
                font-size: clamp(.875rem,3vw,1rem);
                letter-spacing: .02em;
                line-height: 1.5rem;
                color: rgba(255,255,255,.8);
            }
            .btn-judul{
                border-radius: 2rem;
                font-family: NunitoSansBold,sans-serif;
                background: #ff9800;
                box-shadow: 0 8px 10px rgb(255 176 77 / 32%);
                color: #fff;
                padding: 0.625rem 4rem;
            }
            .image-judul{
                -webkit-animation: action 1s infinite  alternate;
                animation: action 1s infinite  alternate;
            }

            @-webkit-keyframes action {
                0% { transform: translateY(0); }
                100% { transform: translateY(-20px); }
            }

            @keyframes action {
                0% { transform: translateY(0); }
                100% { transform: translateY(-20px); }
            }
            .img-judul{
                background-image: url('assets/images/oogmrv.png');
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            #page-topbar{
                
                height:100vh;
            }
        </style>
    </head>

    <body data-layout="horizontal" data-topbar="colored">

        <!-- Begin page -->
        <div id="layout-wrapper">
            <header id="page-topbar" style="position: absolute;">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{site_url('/')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{base_url('assets/images/logo.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{base_url('assets/images/logo.png')}}" alt="" height="40">
                                </span>
                            </a>

                            <a href="{{site_url('/')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{base_url('assets/images/logo.png')}}" alt="" height="32">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{base_url('assets/images/logo3.png')}}" alt="" height="50">
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-5 pb-5" >
                    <div class="row flex align-items-center">
                        <div class="col-xl-5">
                            <div class="align-items-center ml-xl-5">
                                <h1 class="header-judul">Rumah Web Bakti <br> Muliya 400: Web Master</h1>
                                <p class="des-judul">Kami berikan penawaran terbaik unlimited web hosting. Fitur terlengkap, harga terjangkau, dan dukungan teknis 24/7 telah tersedia untuk Anda. Promo spesial segera berakhir. Order sekarang!</p>
								<div class="mt-4">
									@if (is_login())
										<a href="{{admin_url()}}" class="btn waves-effect waves-light btn-judul"><b>Dashboard</b></a>
									@else
										
										{{show_status()}}
										<form action="{{site_url('login')}}" method="post" enctype='multipart/form-data' >
											{{csrf_token()}}
											<div class="form-group">
												<label for="username" class="text-white">Username</label>
												<input autocomplete="off" type="text" required class="form-control {{get_error('username') ? 'is-invalid':''}}" name="username" id="username" placeholder="Enter username" value="{{old('username')}}">
												@if (get_error('username'))
													<div class="invalid-feedback">{{get_error('username')}}</div>
												@endif
											</div>
											<div class="form-group">
												<label for="password" class="text-white">Password</label>
												<input type="password" required name="password" class="form-control {{get_error('password') ? 'is-invalid':''}}"  id="password" placeholder="Enter password">
												@if (get_error('password'))
													<div class="invalid-feedback">{{get_error('password')}}</div>
												@endif
											</div>
											<div class="mt-4 text-center">
												<button class="btn btn-block waves-effect waves-light btn-judul" type="submit"><b>Login Sekarang !</b></button>
											</div>
										</form>
									@endif
								</div>
                            </div>
                        </div>
                        <div class="col-xl-7 pt-3 pl-3 img-judul">
                            <img class="image-judul" src="assets/images/cwok_casual_17-1024x844.png" width="80%">
                        </div>
                    </div>
                </div>
            </header>
    


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                
                        <!-- sample modal content -->
                        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center">
                                        <h5 class="modal-title mt-0" id="myModalLabel" style="font-size: 24px;">Silahkan Login!</h5>
                                    </div>
                                    <div class="modal-body">
                                        
                                <div class="p-2 mt-4">
                                    <form action="dashboard.html">
        
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" placeholder="Enter username">
                                        </div>
                
                                        <div class="form-group">
                                            <div class="float-right">
                                                <a href="auth-recoverpw.html" class="text-muted">Forgot password?</a>
                                            </div>
                                            <label for="userpassword">Password</label>
                                            <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                                        </div>
                
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="auth-remember-check">
                                            <label class="custom-control-label" for="auth-remember-check">Remember me</label>
                                        </div>
                                        
                                        <div class="mt-3 text-right">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Log In</button>
                                        </div>
            
                                        

                                        <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="font-size-14 mb-3 title">Sign in with</h5>
                                            </div>
                                            
            
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void()" class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Don't have an account ? <a href="auth-register.html" class="font-weight-medium text-primary"> Signup now </a> </p>
                                        </div>
                                    </form>
                                </div>
                                            
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Bakti Muliya 400.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-right d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://themesbrand.com/" target="_blank" class="text-reset">Bukakarya</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


        <!-- JAVASCRIPT -->
        <script src="{{base_url('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{base_url('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{base_url('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{base_url('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{base_url('assets/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{base_url('assets/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>

        <!-- apexcharts -->
        {{-- <script src="{{base_url('assets/libs/apexcharts/apexcharts.min.js')}}"></script> --}}

        {{-- <script src="{{base_url('assets/js/pages/dashboard.init.js')}}"></script> --}}

        <script src="{{base_url('assets/js/app.js')}}"></script>

    </body>

<!-- Mirrored from themesbrand.com/minible/layouts/vertical/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Oct 2020 07:17:41 GMT -->
</html>
