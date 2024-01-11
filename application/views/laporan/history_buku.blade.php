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
                            {{-- <div class="col-12 text-right mb-4">
                            <a href="{{admin_url('laporan/history-buku/cetak')}}" class="btn btn-success ml-2">Cetak</a>
                        </div> --}}
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" data-toggle="modal" data-target=".filter"
                                        class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
                                </div>
                                <div class="col-6 text-right">
                                    <form action="{{ admin_url('laporan/history-buku/cetak') }}" method="GET">
                                        <input type="text" hidden value="{{ $default_library }}" name="library_excel">
                                        <input type="text" value="Cetak" hidden name="cetak">
                                        <button type="submit" class="btn btn-warning ml-2"><i
                                                class="fas fa-print mr-2"></i>Cetak</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Buku</th>
                                            <th width="10%">Dipinjam</th>
                                            <th width="10%">Dibaca</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($books as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <ul style="list-style-type: none;padding-left:0">
                                                        <li>
                                                            <h5>{{ $item->title }}</h5>
                                                        </li>
                                                        <li><i>{{ $item->is_physical_book == '1' ? '[Buku Fisik]' : '' }}</i>
                                                            <i>{{ $item->is_digital_book == '1' ? '[Ebook]' : '' }}</i>
                                                        </li>

                                                        <li><i class="text-secondary">{{ $item->library_name }}</i></li>
                                                    </ul>

                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($item->pinjam) }}<br />
                                                    @if ($item->pinjam > 0)
                                                        <a href="{{ admin_url("laporan/history-buku/detail/$item->id") }}"
                                                            class="btn btn-primary btn-sm mt-1">Detail</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($item->baca) }}
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
                    <form action="{{ admin_url('laporan/history-buku') }}" method="GET" id="filterForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Perpustakaan</label>
                                <select name='library' required class=" form-control">
                                    <option value="" selected disabled>Pilih Perpustakaan</option>
                                    <option value="">Semua</option>
                                    @foreach ($library as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $default_library && $default_library == $item->id ? 'selected' : '' }}>
                                            {{ ucfirst($item->library) }}</option>
                                    @endforeach
                                </select>
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
