@extends('panel.app')
@section('title', $title)
@php
    $no = 1;
@endphp
@section('styles')
    <link href="{{ base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">
                            {{ $title }}
                        </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ admin_url() }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (user_can('laporan pengunjung'))
                                <div class="row">
                                    <div class="col-4"><button type="button" data-toggle="modal" data-target=".filter"
                                            class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button></div>
                                    <div class="col-8 d-flex justify-content-end">
                                        <div class="row">
                                            <form action="{{ site_url('buku-tamu') }}" target="_blank">
                                                <div class="row mr-2">
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <select name='library' required class=" form-control">
                                                                <option value="" selected disabled>Pilih Perpustakaan
                                                                </option>
                                                                @foreach ($library as $item)
                                                                    <option value="{{ $item->enc }}">
                                                                        {{ ucfirst($item->library) }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if (get_error('library'))
                                                                <div class="invalid-feedback">{{ get_error('library') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="submit" name="Buku Tamu" value="Buku Tamu"
                                                            style="width:100%" class="btn btn-primary btn-block ml-2">

                                                    </div>
                                                </div>
                                            </form>
                                            <form action="{{ admin_url('laporan/pengunjung') }}" method="GET">

                                                <div class="text-right">
                                                    <input type="text" hidden value="{{ $default_status }}"
                                                        name="status_excel">
                                                    <input type="text" hidden value="{{ $default_library }}"
                                                        name="library_excel">
                                                    <input type="date" hidden value="{{ $default_start }}"
                                                        name="start_excel">
                                                    <input type="date" hidden value="{{ $default_end }}"
                                                        name="end_excel">
                                                    <input type="text" value="Cetak" hidden value="{{ $default_end }}"
                                                        name="cetak">

                                                    {{-- <div class="col-6"> --}}

                                                    <button type="submit" class="btn btn-warning ml-2"><i
                                                            class="fas fa-print mr-2"></i>Cetak</button>
                                                    <button type="button" id="cetak-persiswa" class="btn btn-warning ml-2"
                                                        style="display: none;" data-toggle="modal"
                                                        data-target=".cetak-per-siswa"><i
                                                            class="uil-import mr-2"></i>Cetak</button>

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                {{-- <div class="row">
                                    <div class="col-auto">
                                        <form action="{{ admin_url('laporan/pengunjung') }}" method="GET">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <div class="form-group">
                                                        <input type="date" class=" form-control" name="start"
                                                            value="{{ $default_start }}" />
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-group">
                                                        <input type="date" class=" form-control" name="end"
                                                            value="{{ $default_end }}" />
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-group">
                                                        <select name='library' required class=" form-control">
                                                            <option value="" selected disabled>Pilih Perpustakaan
                                                            </option>
                                                            @foreach ($library as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $default_library && $default_library == $item->id ? 'selected' : '' }}>
                                                                    {{ ucfirst($item->library) }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if (get_error('library'))
                                                            <div class="invalid-feedback">{{ get_error('library') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-group">
                                                        <select name='jenis_laporan' required class=" form-control">
                                                            <option selected disabled>Jenis laporan</option>
                                                            <option value="1"
                                                                {{ $default_jenis_laporan && $default_jenis_laporan == 1 ? 'selected' : '' }}>
                                                                Semua</option>
                                                            <option value="2"
                                                                {{ $default_jenis_laporan && $default_jenis_laporan == 2 ? 'selected' : '' }}>
                                                                Per Anggota</option>

                                                        </select>
                                                        @if (get_error('jenis_laporan'))
                                                            <div class="invalid-feedback">{{ get_error('jenis_laporan') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="row">
                                                        <div class="col-md-auto">
                                                            <input type="submit" name="Filter" value="Filter"
                                                                class="btn btn-primary btn-block ml-2">
                                                        </div>
                                                        <div class="col-md-auto">
                                                            <input name="cetak" value="Cetak" type="submit"
                                                                class="btn btn-warning btn-block ml-2" />
                                                        </div>
                                                        <div class="col-md-auto">
                                                            <a href="{{ admin_url('laporan/pengunjung') }}"
                                                                class="btn btn-danger btn-block ml-2">Reset</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> --}}
                            @endif
                            {{ show_status() }}
                            @if (in_array($default_jenis_laporan, [' ', 0, 1]))
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="20%" class="text-center">Nama</th>
                                                <th width="10%" class="text-center">Status</th>
                                                <th width="15%" class="text-center">NIS / NIP</th>
                                                <th width="10%" class="text-center">Tanggal Pengunjung</th>
                                                <th width="10%" class="text-center">Jam</th>
                                                <th class="text-center">Keperluan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengunjung as $item)
                                                <tr>
                                                    @if ($item->is_guest)
                                                        <td>
                                                            <strong>{{ $item->guest_name }}</strong>
                                                            <br />{{ $item->library_detail ? $item->library_detail->library : '' }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <strong>{{ $item->user_detail ? $item->user_detail->user_nama : '' }}</strong>
                                                            <br />{{ $item->library_detail ? $item->library_detail->library : '' }}
                                                        </td>
                                                    @endif
                                                    @if ($item->is_guest)
                                                        <td>{{ $item->institution }}<br><span
                                                                class="badge bg-warning text-white">Guest</span></td>
                                                    @else
                                                        <td>{{ $item->role_detail ? $item->role_detail->role_name : '' }}<br>
                                                            <span
                                                                class="badge bg-warning text-white">{{ $item->school_detail ? $item->school_detail->sekolah_nama : '' }}</span>
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->user_detail ? $item->user_detail->user_no : '' }}</td>
                                                    <td class="text-center">{{ $item->tanggal }}</td>
                                                    <td class="text-center">{{ $item->time }}</td>
                                                    <td class="text-left">{{ $item->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!! $page !!}
                                </div>
                            @else
                                <div id="per-siswa" class="table-responsive">
                                    <table id=""
                                        class="table display table-striped table-hover table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                {{-- <th width="5%">No</th> --}}
                                                <th>Nama</th>
                                                <th>Status</th>
                                                <th>NIS / NIP</th>
                                                <th width="5%">Jumlah Berkunjung</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($per_pengunjung as $item)
                                                <tr>
                                                    @if ($item->is_guest)
                                                        <td>
                                                            <strong>{{ $item->guest_name }}</strong>
                                                            <br />{{ $item->library_detail ? $item->library_detail->library : '' }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            <strong>{{ $item->user_detail ? $item->user_detail->user_nama : '' }}</strong>
                                                            <br />{{ $item->library_detail ? $item->library_detail->library : '' }}
                                                        </td>
                                                    @endif
                                                    @if ($item->is_guest)
                                                        <td>{{ $item->institution }}<br><span
                                                                class="badge bg-warning text-white">Guest</span></td>
                                                    @else
                                                        <td>{{ $item->role_detail ? $item->role_detail->role_name : '' }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->user_detail ? $item->user_detail->user_no : '' }}</td>
                                                    <td class="text-center">{{ $item->jumlah }}</td>
                                                    <td class="text-left">-</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!! $page !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->

        {{-- Modal --}}
        <div class="modal fade filter" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ admin_url('laporan/pengunjung') }}" method="GET">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Perpustakaan</label>
                                <select name='library' required class=" form-control">
                                    <option value="" selected disabled>Pilih Perpustakaan</option>
                                    @foreach ($library as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $default_library && $default_library == $item->id ? 'selected' : '' }}>
                                            {{ ucfirst($item->library) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                                                                                                                                                                                                                        <label>Tahun Pelajaran</label>
                                                                                                                                                                                                                                        <select name="tahunPelajaran" id="pilihTahunPelajaran" class="select2 form-control" style="width: 100%;">
                                                                                                                                                                                                                                            <option value="2025-2026">2025-2026</option>
                                                                                                                                                                                                                                            <option value="2024-2025">2024-2025</option>
                                                                                                                                                                                                                                            <option value="2023-2024">2023-2024</option>
                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                    <div class="form-group">
                                                                                                                                                                                                                                        <label>Semester</label>
                                                                                                                                                                                                                                        <select name="kelas" id="pilihSemester" class="select2 form-control" style="width: 100%;">
                                                                                                                                                                                                                                            <option value="1">1</option>
                                                                                                                                                                                                                                            <option value="2">2</option>
                                                                                                                                                                                                                                            <option value="3">3</option>
                                                                                                                                                                                                                                            <option value="4">4</option>
                                                                                                                                                                                                                                            <option value="5">5</option>
                                                                                                                                                                                                                                            <option value="6">6</option>
                                                                                                                                                                                                                                        </select>
                                                                                                                                                                                                                                    </div> -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="jenis" id="" class="select2 form-control" style="width: 100%;">
                                    <option value="1"
                                        {{ $default_jenis_laporan && $default_jenis_laporan == 1 ? 'selected' : '' }}>
                                        Semua</option>
                                    <option value="2"
                                        {{ $default_jenis_laporan && $default_jenis_laporan == 2 ? 'selected' : '' }}>
                                        Per Anggota</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis Anggota</label>
                                <select name="status" id="" class="select2 form-control" style="width: 100%;">
                                    <option value="">Semua</option>
                                    <option value="6" {{ $default_status == '6' ? 'selected' : '' }}>Siswa</option>
                                    <option value="5" {{ $default_status == '5' ? 'selected' : '' }}>Guru</option>
                                    <option value="1" {{ $default_status == '1' ? 'selected' : '' }}>Guest</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <input class="form-control" type="date" name="start" value="{{ $default_start }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input class="form-control" name="end" type="date" value="{{ $default_end }}">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-soft-danger waves-effect waves-light"
                                id="resetFilterButton" data-dismiss="modal" aria-label="Close"><i
                                    class="fas fa-sync mr-2"></i>Reset Filter</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="filterButton"
                                aria-label="Close"><i class="fas fa-filter mr-2"></i>Filter</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- End Page-content -->
@endsection
@section('scripts')
    <script src="{{ base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js') }}"></script>
    <script>
        $('.button-delete').on('click', function(e) {
            var $this = $(this);
            var data_id = $this.data('id');
            $.confirm({
                animation: 'top',
                title: 'Hapus',
                content: 'Yakin ingin hapus data ini ?. proses ini akan mengupdate stok buku dan tidak bisa dikembalikan',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    'Tidak': {
                        btnClass: 'btn-info text-white', // multiple classes.
                        action: function() {}
                    },
                    'Ya': {
                        btnClass: 'btn-danger',
                        action: function() {
                            window.location.href = "{{ admin_url('transaksi/buku-masuk') }}/" +
                                data_id + '/delete';
                        }
                    },
                },

            });
        });
    </script>
@endsection
