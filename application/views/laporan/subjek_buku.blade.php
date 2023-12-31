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
                            <div class="col-12 text-right mb-4">
                                <a href='{{ admin_url('laporan/subjek-buku/cetak') }}'
                                    class="btn btn-success ml-2">Cetak</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kategori Buku</th>
                                            <th width="10%">Dipinjam</th>
                                            <th width="10%">Dibaca</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjek as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <ul style="list-style-type: none;padding-left:0">
                                                        <li>
                                                            <h6>{{ ucfirst($item->name) }}</h6>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    {{ number_format($item->pinjam) }}
                                                </td>
                                                <td>
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
    </div>
    <!-- End Page-content -->
@endsection
