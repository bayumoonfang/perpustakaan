@extends('panel.app')
@section('title', 'Laporan Subjek Buku')
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
                            Laporan Subjek Buku
                        </h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ admin_url() }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Laporan Subjek Buku</li>
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
                                <a href='{{ admin_url('laporan/kategori-buku/cetak') }}'
                                    class="btn btn-success ml-2">Cetak</a>
                            </div>
                            <div class="table-responsive">
                                <table id="tableSubjekBuku" class="table table-hover dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Subjek Buku</th>
                                            <th width="5%">Dipinjam</th>
                                            <th width="5%">Dibaca</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table, dtable;
            render();
        });

        function render() {
            var table = $('#tableSubjekBuku').DataTable({
                ajax: {
                    url: "{{ admin_url('laporan/subjek-buku') }}",
                },
                responsive: true,
                processing: true,
                serverSide: true,
                buttons: {
                    buttons: [{
                            extend: 'excel',
                            className: 'btn btn-sm',
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm'
                        }
                    ]
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'no_faktur',
                        name: 'no_faktur',
                        render: function(data) {
                            return "<a href = '#' class = 'text-danger font-weight-bolder table_link'>" +
                                data + "<a/>";
                        },
                        className: 'table_link2'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                    },
                    {
                        data: 'bayar',
                        name: 'bayar',
                    },
                    {
                        data: null,
                        render: function(data, type, full) {
                            return '<p class="font-weight-bolder">' + (full.harga - full.bayar)
                                .toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }) + '</p>';
                        }
                    },
                    {
                        data: null,
                        // width: 70,
                        render: function(data, type, full) {
                            return full.harga - full.bayar == 0 ?
                                '<span class="badge badge-success">Lunas</span>' :
                                '<span class="badge badge-danger">Belum Lunas</span>';
                        },
                        className: "text-center"
                    }, //mpwp
                ],
                columnDefs: [{
                    render: function(data) {
                        return data.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                    },
                    targets: [4, 5]
                }],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [6, 10, 20, 50, -1],
                    [6, 10, 20, 50, "Semua"]
                ],
            });
        };
    </script>
@endsection
