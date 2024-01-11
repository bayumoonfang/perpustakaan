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
                            <div class="row">
                                <div class="col-4"><button type="button" data-toggle="modal" data-target=".filter"
                                        class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button></div>
                                <div class="col-8 d-flex justify-content-end">
                                    <div class="row">

                                        <form action="{{ admin_url('laporan/transaksi-buku') }}" method="GET">

                                            <div class="text-right">
                                                <input type="text" hidden value="{{ $default_jenis }}"
                                                    name="jenis_excel">
                                                <input type="text" hidden value="{{ $default_kategori }}"
                                                    name="kategori_excel">
                                                <input type="text" hidden value="{{ $default_library }}"
                                                    name="library_excel">
                                                <input type="date" hidden value="{{ $default_start }}"
                                                    name="start_excel">
                                                <input type="date" hidden value="{{ $default_end }}" name="end_excel">
                                                <input type="text" value="Cetak" hidden name="cetak">

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
                            {{-- <div class="col-12 text-right mb-4">
                            <a href="{{admin_url('laporan/transaksi-buku/cetak')}}" class="btn btn-success ml-2">Cetak</a>
                        </div> --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="20%" class="text-center">Tanggal</th>
                                            <th class="text-center">Buku</th>
                                            <th width="15%" class="text-center">Jenis</th>
                                            <th width="10%" class="text-center">Jumlah</th>
                                            <th width="10%" class="text-center">Tipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($books as $item)
                                            <tr>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td><strong>{{ $item->book_title }}</strong>
                                                    <br />{{ $item->library_name }}
                                                </td>
                                                <td>{{ $item->category_name }}</td>
                                                <td class="text-right">{{ number_format($item->qty) }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $item->type == 'in' ? 'success' : 'danger' }} text-white">{{ $item->type == 'in' ? 'BUKU MASUK' : 'BUKU KELUAR' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $page !!}
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->

        <div class="modal fade filter" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ admin_url('laporan/transaksi-buku') }}" method="GET">
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
                            <div class="form-group">
                                <label>Jenis Transaksi</label>
                                <select name="jenis" id="" class="select2 form-control" style="width: 100%;">
                                    <option value="">Semua</option>
                                    <option value="beli" {{ $default_jenis && $default_jenis == 1 ? 'selected' : '' }}>
                                        Semua</option>
                                    <option value="2" {{ $default_jenis && $default_jenis == 2 ? 'selected' : '' }}>
                                        Per Anggota</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kategori Transaksi</label>
                                <select name="kategori" id="" class="select2 form-control"
                                    style="width: 100%;">
                                    <option value="">Semua</option>
                                    <option value="in" {{ $default_kategori == 'in' ? 'selected' : '' }}>Buku Masuk
                                    </option>
                                    <option value="out" {{ $default_kategori == 'out' ? 'selected' : '' }}>Buku Keluar
                                    </option>
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
