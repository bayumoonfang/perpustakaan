<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ admin_url() }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset_url('images/logo-1.png') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset_url('images/logo.png') }}" alt="" height="45">
            </span>
        </a>

        <a href="{{ admin_url() }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset_url('images/logo.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset_url('images/logo.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ admin_url() }}">
                        <i class="uil-home-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (user_can(['edit issue', 'add issue', 'delete issue', 'view issue']))
                    <li>
                        <a href="{{ admin_url('issue') }}">
                            <i class="uil-exchange"></i>
                            <span>Issue</span>
                        </a>
                    </li>
                @endif
                @if (user_can([
                        'edit transaksi buku masuk',
                        'add transaksi buku masuk',
                        'delete transaksi buku masuk',
                        'view transaksi buku masuk',
                        'edit transaksi buku keluar',
                        'add transaksi buku keluar',
                        'delete transaksi buku keluar',
                        'view transaksi buku keluar',
                    ]))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="uil-books"></i>
                            <span>Transaksi</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @if (user_can([
                                    'edit transaksi buku masuk',
                                    'add transaksi buku masuk',
                                    'delete transaksi buku masuk',
                                    'view transaksi buku masuk',
                                ]))
                                <li><a href="{{ admin_url('transaksi/buku-masuk') }}">Buku Masuk</a></li>
                            @endif
                            @if (user_can([
                                    'edit transaksi buku keluar',
                                    'add transaksi buku keluar',
                                    'delete transaksi buku keluar',
                                    'view transaksi buku keluar',
                                ]))
                                <li><a href="{{ admin_url('transaksi/buku-keluar') }}">Buku Keluar</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (user_can(['laporan library']))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="uil-file-check-alt"></i>
                            <span>Laporan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ admin_url('laporan/peminjaman') }}">Peminjaman</a></li>
                            <li><a href="{{ admin_url('laporan/history-buku') }}">History Buku</a></li>
                            <li><a href="{{ admin_url('laporan/kategori-buku') }}">Kategori Buku</a></li>
                            <li><a href="{{ admin_url('laporan/subjek-buku') }}">Subjek Buku</a></li>
                            <li><a href="{{ admin_url('laporan/transaksi-buku') }}">Transaksi Keluar Masuk</a></li>
                            <li><a href="{{ admin_url('laporan/pengunjung') }}">Pengunjung</a></li>
                        </ul>
                    </li>
                @endif
                {{-- @if (user_can(['laporan pengunjung']))
					<li>
						<a href="{{admin_url('laporan/pengunjung')}}">
							<i class="uil-megaphone"></i>
							<span>Laporan Pengunjung</span>
						</a>
					</li>
					@endif --}}
                {{-- <li class="menu-title">Master Buku</li> --}}
                @if (user_can([
                        'edit perpustakaan',
                        'add perpustakaan',
                        'delete perpustakaan',
                        'view perpustakaan',
                        'edit bentuk buku',
                        'add bentuk buku',
                        'delete bentuk buku',
                        'view bentuk buku',
                        'edit kategori buku',
                        'add kategori buku',
                        'delete kategori buku',
                        'view kategori buku',
                        'edit buku',
                        'add buku',
                        'delete buku',
                        'view buku',
                        'edit penambahan',
                        'add penambahan',
                        'delete penambahan',
                        'view penambahan',
                        'edit pengurangan',
                        'add pengurangan',
                        'delete pengurangan',
                        'view pengurangan',
                    ]))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="uil-database-alt"></i>
                            <span>Master</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @if (user_can(['edit perpustakaan', 'add perpustakaan', 'delete perpustakaan', 'view perpustakaan']))
                                <li><a href="{{ admin_url('perpustakaan') }}">Perpustakaan</a></li>
                            @endif
                            @if (user_can(['edit bentuk buku', 'add bentuk buku', 'delete bentuk buku', 'view bentuk buku']))
                            @endif
                            <li><a href="{{ admin_url('bentuk-buku') }}">Bentuk Pustaka</a></li>
                            @if (user_can(['edit kategori buku', 'add kategori buku', 'delete kategori buku', 'view kategori buku']))
                                <li><a href="{{ admin_url('kategori-buku') }}">Kategori Buku</a></li>
                            @endif
                            <li><a href="{{ admin_url('subjek-buku') }}">Subjek Buku</a></li>
                            @if (user_can(['edit buku', 'add buku', 'delete buku', 'view buku']))
                                <li><a href="{{ admin_url('buku') }}">Buku</a></li>
                            @endif
                            @if (user_can(['edit penambahan', 'add penambahan', 'delete penambahan', 'view penambahan']))
                                <li><a href="{{ admin_url('penambahan') }}">Jenis Penambahan</a></li>
                            @endif
                            @if (user_can(['edit pengurangan', 'add pengurangan', 'delete pengurangan', 'view pengurangan']))
                                <li><a href="{{ admin_url('pengurangan') }}">Jenis Pengurangan</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (user_can(['edit pengaturan peminjaman', 'view pengaturan peminjaman']))
                    <li class="menu-title">Pengaturan</li>
                    @if (user_can(['edit pengaturan peminjaman', 'view pengaturan peminjaman']))
                        <li>
                            <a href="{{ admin_url('pengaturan/peminjaman') }}">
                                <i class="uil-cog"></i>
                                <span>Peminjaman</span>
                            </a>
                        </li>
                    @endif
                    @if (is_admin())
                        <li>
                            <a href="{{ admin_url('pengaturan/role-issue') }}">
                                <i class="uil-user-exclamation"></i>
                                <span>Role Issue</span>
                            </a>
                        </li>
                    @endif
                @endif
                {{-- <li>
						<a href="{{admin_url('addon-manager')}}">
							<i class="uil-database-alt"></i>
							<span>Addon Manager</span>
						</a>
					</li> --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
