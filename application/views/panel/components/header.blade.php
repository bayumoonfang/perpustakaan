@php
	$notif_count=notification_count();
	$list_expired=notification_expired_list();
@endphp
<header id="page-topbar">
	<div class="navbar-header">
		<div class="d-flex">
			<!-- LOGO -->
			<div class="navbar-brand-box">
				<a href="dashboard.html" class="logo logo-dark">
					<span class="logo-sm">
						<img src="{{asset_url('images/logo.png')}}" alt="" height="30">
					</span>
					<span class="logo-lg">
						<img src="{{asset_url('images/logo.png')}}" alt="" height="30">
					</span>
				</a>

				<a href="dashboard.html" class="logo logo-light">
					<span class="logo-sm">
						<img src="{{asset_url('images/logo.png')}}" alt="" height="30">
					</span>
					<span class="logo-lg">
						<img src="{{asset_url('images/logo.png')}}" alt="" height="30">
					</span>
				</a>
			</div>

			<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
				<i class="fa fa-fw fa-bars"></i>
			</button>
		</div>

		<div class="d-flex">
			<div class="dropdown d-inline-block">
				<a href="{{site_url('logout')}}">
					<button type="button" class="btn header-item noti-icon waves-effect" aria-haspopup="true" aria-expanded="false">
						<i class="uil-apps"></i>
					</button>
				</a>
			</div>

			<div class="dropdown d-inline-block">
				<button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
					data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="uil-bell"></i>
					@if ($notif_count>0)
						<span class="badge badge-danger badge-pill">{{notification_count()}}</span>
					@endif
				</button>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
					aria-labelledby="page-header-notifications-dropdown">
					<div class="p-3">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="m-0 font-size-16"> Notifications </h5>
							</div>
							<div class="col-auto">
							</div>
						</div>
					</div>
					<div data-simplebar style="max-height: 230px;">
						@foreach ($list_expired as $item)
						@php
							$exp_date=date_format(date_create($item->expired_date), 'Y-m-d')
						@endphp
							<a href="{{admin_url('issue?due_date='.$exp_date)}}" class="text-reset notification-item">
								<div class="media">
									<div class="avatar-xs mr-3">
										<span class="avatar-title bg-danger rounded-circle font-size-16">
											<i class="mdi mdi-clock-outline"></i>
										</span>
									</div>
									<div class="media-body">
										<h6 class="mt-0 mb-1">{{number_format($item->total)}} Peminjaman belum kembali</h6>
										<div class="font-size-12 text-muted">
											<p class="mb-1">expired pada tanggal {{date_format(date_create($item->expired_date), 'd M Y')}}</p>
										</div>
									</div>
								</div>
							</a>
						@endforeach
					</div>
					
				</div>
			</div>

			<div class="dropdown d-inline-block">
				<button type="button" class="btn header-item waves-effect">
					<img class="rounded-circle header-profile-user" src="{{asset_url('images/users/avatar-4.jpg')}}"
						alt="Header Avatar">
					<span class="d-none d-xl-inline-block ml-1 font-weight-medium font-size-15">{{current_user('user_nama')}}</span>
				</button>
			</div>

		</div>
	</div>
</header>
