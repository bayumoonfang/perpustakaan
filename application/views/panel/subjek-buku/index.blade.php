@extends('panel.app')
@section('title', $title)
@php
    $no = 1;
    $edit = isset($subjek_buku) ? true : false;
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
                            {{-- @if (user_can('add subjek buku')) --}}
                            <div class="col-12 text-right mb-4">
                                {{-- <a href="{{ admin_url('subjek-buku/new') }}" class="btn btn-success ml-2">Tambah
                                        Data</a> --}}
                                <button type="button" class="btn btn-success ml-2" data-toggle="modal" id="btnModalSubject"
                                    data-target="#subjekBukuModal"><i class="fas fa-plus mr-2"></i>Tambah</button>
                            </div>
                            {{-- @endif --}}
                            <div class="table-responsive">
                                <table id="tableSubjekBuku" class="table table-hover dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kode Klasifikasi</th>
                                            <th>Subjek</th>
                                            <th>Status</th>
                                            <th width="11%" class="text-center">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

            {{-- modal --}}
            <div class="modal fade " id='subjekBukuModal' tabindex="-1" aria-labelledby=#subjectModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id=#subjectModalLabel">Tambah Subjek Buku</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="subjekBukuForm">
                                <div class="row">
                                    <input type="hidden" name="subject_id" id="subject_id">
                                    <input type="hidden" name="subject_method" id="subject_method">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Subjek Buku <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="" id="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Awal <span class="font-italic font-size-12">(Kode
                                                    Klasifikasi)</span> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Nilai Min."
                                                id="min_value">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Akhir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Nilai Max."
                                                id="max_value">
                                        </div>
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
    <script>
        $(document).ready(function() {
            var table, dtable;
            var table = $('#tableSubjekBuku').DataTable({
                ajax: {
                    url: "{{ admin_url('daftar-subjek') }}",
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

            $('body').on('click', '#btnModalSubject', function() {
                $('#subjekBukuForm')[0].reset();
                $('#subjectModalLabel').html('Tambah Subjek Buku');
                $('#subject_method').val('saveSubject');
            });

            $('tbody').on('click', '.button-edit', function() {
                var id = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{ admin_url('subjek-buku/edit') }}",
                    data: {
                        id: id,
                    },

                    dataType: "json",
                    success: function(res) {
                        if (res.success == true) {
                            $('#subjectModalLabel').html('Edit Subjek Buku');
                            $('#subject_method').val('updateSubject');
                            $('#name').val(res.data.name);
                            $('#subject_id').val(res.data.id);
                            $('#min_value').val(res.data.min_value);
                            $('#max_value').val(res.data.max_value);
                            $('.button-submit').html('Update');
                            $('#subjekBukuModal').modal("toggle");
                        }
                    },
                    error: function(res) {
                        console.log('Error', res);
                    }
                })
            });

            $('tbody').on('click', '.button-delete', function() {
                var id = $(this).data("id");
                var name = $(this).data("name");
                Swal.fire({
                    title: "Hapus subjek " + name + "?",
                    text: "Data yang dihapus tidak dapat dikembalikan",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Batal",
                    confirmButtonText: "Ya!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ admin_url('subjek-buku/:id/delete') }}";
                        $.ajax({
                            type: "POST",
                            url: url.replace(":id", id),
                            dataType: "json",
                            success: function(res) {
                                if (res.success == true) {
                                    Swal.fire({
                                        icon: res.icon,
                                        title: 'Proses ' + res.status + '!',
                                        text: res.message,
                                        timer: 1000
                                    });
                                    table.ajax.reload(null, false);
                                }
                            },
                            error: function(res) {
                                console.log('Error', res);
                            }

                        })
                    } else if (result.isDismissed) {
                        Swal.fire("Subjek " + name + " batal dihapus", "", "info");
                    }
                });
            });

            $('.button-submit').click(function(e) {
                e.preventDefault();
                var subjectId = $('#subject_id').val();
                var name = $('#name').val();
                var min_value = $('#min_value').val();
                var max_value = $('#max_value').val();
                var method = $('#subject_method').val();
                if (method == 'saveSubject') {
                    $.ajax({
                        type: "POST",
                        url: "{{ admin_url('subjek-buku/add') }}",
                        data: {
                            name: name,
                            min_value: min_value,
                            max_value: max_value,
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
                            $('#subjekBukuForm')[0].reset();
                            // window.location.href = "{{ admin_url('subjek-buku') }}";
                            $('#subjekBukuModal').modal("toggle");
                            // alert('Success');
                            table.ajax.reload(null, false);
                        }
                    })
                } else {
                    var url = "{{ admin_url('subjek-buku/:id/update') }}";
                    $.ajax({
                        type: "POST",
                        url: url.replace(":id", subjectId),
                        data: {
                            // id: subjectId,
                            name: name,
                            min_value: min_value,
                            max_value: max_value,
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
                            $('#subjekBukuForm')[0].reset();
                            // window.location.href = "{{ admin_url('subjek-buku') }}";
                            $('#subjekBukuModal').modal("toggle");
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
