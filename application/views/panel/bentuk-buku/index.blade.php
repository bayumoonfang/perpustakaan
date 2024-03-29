@extends('panel.app')
@section('title', $title)
@php
    $no = 1;
    $edit = isset($bentuk_buku) ? true : false;
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
                            @if (user_can('add bentuk buku'))
                                <div class="col-12 text-right mb-4">
                                    {{-- <a href="{{ admin_url('bentuk-buku/new') }}" class="btn btn-success ml-2">Tambah
                                        Data</a> --}}
                                    <button type="button" class="btn btn-success ml-2" data-toggle="modal"
                                        id="btnModalBentuk" data-target="#bentukBukuModal"><i
                                            class="fas fa-plus mr-2"></i>Tambah</button>
                                </div>
                            @endif
                            {{ show_status() }}
                            <div class="table-responsive">
                                <table id="tableBentukBuku"
                                    class="table table-striped table-hover table-bordered dt-responsive nowrap table-bentuk"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kategori Buku</th>
                                            <th width="20%">Status</th>
                                            {{-- @if (user_can(['edit bentuk buku', 'delete bentuk buku'])) --}}
                                            <th width="200px">Action</th>
                                            {{-- @endif --}}
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

            {{-- modal --}}
            <div class="modal fade " id='bentukBukuModal' tabindex="-1" aria-labelledby="bentukModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="bentukModalLabel">Tambah Bentuk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="bentukBukuForm">
                                <input type="hidden" name="bentuk_id" id="bentuk_id">
                                <input type="hidden" name="bentuk_method" id="bentuk_method">
                                <div class="form-group row">
                                    <label for="name" class="col-md-3 col-form-label">Bentuk</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="Bentuk" id="name">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Kembali</button>
                            <button type="button"
                                class="btn btn-primary waves-effect waves-light button-submit">Simpan</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@section('scripts')

    <script src="{{ base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#tableBentukBuku').DataTable({
                ajax: {
                    url: "{{ admin_url('daftar-bentuk') }}",
                    type: "POST"
                },
                responsive: true,
                processing: true,
                serverSide: true,
                // buttons: {
                //     buttons: [{
                //             extend: 'excel',
                //             className: 'btn btn-sm',
                //         },
                //         {
                //             extend: 'print',
                //             className: 'btn btn-sm'
                //         }
                //     ]
                // },
                // columns: [
                // 	{
                //         data: null,
                //         render: function(data, type, full) {
                //             return full.min_value + ' - ' + full.max_value;
                //         }
                //     },
                //     {
                //         data: 'name',
                //     },
                //     {
                //         data: 'status',
                //         render: function(data, type, full) {
                //             return full.harga - full.bayar == 0 ?
                //                 '<span class="badge badge-success">Lunas</span>' :
                //                 '<span class="badge badge-danger">Belum Lunas</span>';
                //         },
                //         className: "text-center"
                //     },
                // ],
                // order: [
                //     [0, 'desc']
                // ],
                pageLength: 10,
                lengthMenu: [
                    [6, 10, 20, 50, -1],
                    [6, 10, 20, 50, "Semua"]
                ],
            });

            $('body').on('click', '#btnModalBentuk', function() {
                $('#bentukBukuForm')[0].reset();
                $('#bentukModalLabel').html('Tambah Bentuk Buku');
                $('#bentuk_method').val('saveBentuk');
            });

            $('tbody').on('click', '.button-edit', function() {
                var id = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{ admin_url('bentuk-buku/edit') }}",
                    data: {
                        id: id,
                    },

                    dataType: "json",
                    success: function(res) {
                        if (res.success == true) {
                            $('#bentukModalLabel').html('Edit Bentuk Buku');
                            $('#bentuk_method').val('updateBentuk');
                            $('#name').val(res.data.name);
                            $('#bentuk_id').val(res.data.id);
                            $('.button-submit').html('Update');
                            $('#bentukBukuModal').modal("toggle");
                        }
                    },
                    error: function(res) {
                        console.log('Error', res);
                    }
                })
            });

            $('tbody').on('click', '.button-delete', function(e) {
                var $this = $(this);
                var data_id = $this.data('id');
                $.confirm({
                    animation: 'top',
                    title: 'Hapus',
                    content: 'Yakin ingin hapus data ini ?',
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
                                window.location.href = "{{ admin_url('bentuk-buku') }}/" +
                                    data_id +
                                    '/delete';
                            }
                        },
                    },

                });
            });

            $('.button-submit').click(function(e) {
                e.preventDefault();
                var bentukId = $('#bentuk_id').val();
                var name = $('#name').val();
                var method = $('#bentuk_method').val();
                if (method == "saveBentuk") {
                    $.ajax({
                        type: "POST",
                        url: "{{ admin_url('bentuk-buku/add') }}",
                        data: {
                            name: name
                        },
                        dataType: "json",
                        success: function(res) {
                            res.status == 'Berhasil' ? $('.table-bentuk tbody').empty() : '';
                            console.log(res);
                            Swal.fire({
                                icon: res.icon,
                                title: 'Proses ' + res.status + '!',
                                text: res.message,
                                timer: 1000
                            });
                            $('#bentukBukuForm')[0].reset();
                            // window.location.href = "{{ admin_url('bentuk-buku') }}";
                            $('#bentukBukuModal').modal("toggle");
                            // alert('Success');
                            // location.reload();
                            table.ajax.reload(null, false);
                        }
                    })
                } else {
                    var url = "{{ admin_url('bentuk-buku/:id/update') }}";
                    $.ajax({
                        type: "POST",
                        url: url.replace(":id", bentukId),
                        data: {
                            name: name,
                        },

                        dataType: "json",
                        success: function(res) {
                            console.log(res);
                            Swal.fire({
                                icon: res.icon,
                                title: 'Proses ' + res.status + '!',
                                text: res.message,
                                timer: 1000
                            });
                            $('#bentukBukuForm')[0].reset();
                            // window.location.href = "{{ admin_url('bentuk-buku') }}";
                            $('#bentukBukuModal').modal("toggle");
                            // alert('Success');
                            table.ajax.reload(null, false);
                        },
                        error: function(res) {
                            console.log('Error', res);
                        }
                    })
                }
            });
        });
    </script>
@endsection
