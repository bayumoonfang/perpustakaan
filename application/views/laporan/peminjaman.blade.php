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
                            <form action="{{ admin_url('laporan/peminjaman') }}" method="GET">
                                <div class="row">

                                    <div class="col-6">
                                        <button type="button" data-toggle="modal" data-target=".filter"
                                            class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <input type="text" hidden value="{{ $default_status }}" name="status_excel">
                                        <input type="text" hidden value="{{ $default_library }}" name="library_excel">
                                        <input type="date" hidden value="{{ $default_start }}" name="start_excel">
                                        <input type="date" hidden value="{{ $default_end }}" name="end_excel">
                                        <input type="text" value="Cetak" hidden value="{{ $default_end }}"
                                            name="cetak">

                                        {{-- <div class="col-6"> --}}

                                        <button type="submit" class="btn btn-warning ml-2"><i
                                                class="fas fa-print mr-2"></i>Cetak</button>
                                        <button type="button" id="cetak-persiswa" class="btn btn-warning ml-2"
                                            style="display: none;" data-toggle="modal" data-target=".cetak-per-siswa"><i
                                                class="uil-import mr-2"></i>Cetak</button>
                                        {{-- </div> --}}
                                        {{-- <div class="col-6">
                                            <input name="cetak" value="Cetak" type="submit"
                                                class="btn btn-warning btn-block " />
                                        </div> --}}
                                    </div>
                                </div>
                                <br>
                                {{-- <div class="row">
                                    <div class="col-auto">
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
                                                        <option value="" selected disabled>Pilih Perpustakaan</option>
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
                                                        <a href="{{ admin_url('laporan/peminjaman') }}"
                                                            class="btn btn-danger btn-block ml-2">Reset</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </form>
                            <hr />
                            {{-- <div class="col-12 text-right mb-4">
                            <a href='{{admin_url("laporan/peminjaman/cetak")}}' class="btn btn-success ml-2">Cetak</a>
					</div> --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th class="text-center" width="30%">Member</th>
                                            <th class="text-center" width="30%">Buku</th>
                                            <th width="20%" class="text-center">Tanggal Pinjam</th>
                                            <th width="10%" class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($books as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <ul style="list-style-type: none;padding-left:0">
                                                        <li>
                                                            <h6>{{ $item->user_nama }}</h6>
                                                        </li>
                                                        <li>{{ $item->user_no }}</li>
                                                        <li>{{ $item->user_alamat }}</li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul style="list-style-type: none;padding-left:0">
                                                        <li>
                                                            <h6>{{ $item->book_title }}</h6>
                                                        </li>
                                                        <li>{{ $item->book_code }}</li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul style="list-style-type: none;padding-left:0">
                                                        <li>Pinjam: <span
                                                                class="text-success">{{ $item->issue_date }}</span></li>
                                                        <li>Kembali: <span
                                                                class="text-success">{{ $item->status !== 'pinjam' ? $item->return_date : '-' }}</span>
                                                        </li>
                                                        <li>Denda: <span class="text-danger">Rp.
                                                                {{ $item->status !== 'pinjam' ? number_format($item->denda) : '-' }}</span>
                                                            {{-- class="text-success">{{ $item->denda }}</span> --}}
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-{{ $item->status == 'kembali' ? 'success' : 'secondary' }} text-white">{{ ucfirst($item->status) }}</span>

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
                    <form action="{{ admin_url('laporan/peminjaman') }}" method="GET" id="filterForm">
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
                                <label>Jenis Anggota</label>
                                <select name="kelas" id="" class="select2 form-control" style="width: 100%;">
                                    <option value="semua">Semua</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="guest">Guest</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="" class="select2 form-control" style="width: 100%;">
                                    <option value="">Semua</option>
                                    <option value="kembali" {{ $default_status == 'kembali' ? 'selected' : '' }}>Kembali
                                    </option>
                                    <option value="pinjam" {{ $default_status == 'pinjam' ? 'selected' : '' }}>Dipinjam
                                    </option>
                                    <option value="rusak" {{ $default_status == 'rusak' ? 'selected' : '' }}>Rusak
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
