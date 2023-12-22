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
                                        data-target="#bentukBukuModal"><i class="fas fa-plus mr-2"></i>Tambah</button>
                                </div>
                            @endif
                            {{ show_status() }}
                            <div class="table-responsive">
                                <table
                                    class="table table-striped table-hover table-bordered dt-responsive nowrap table-bentuk"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kategori Buku</th>
                                            <th width="20%">Status</th>
                                            @if (user_can(['edit bentuk buku', 'delete bentuk buku']))
                                                <th width="200px">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bentuk_buku as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td><strong>{{ $item->name }}</strong>
                                                </td>
                                                <td>
                                                    @if ($item->status == '1')
                                                        <button class="btn btn-sm btn-success ml-2">Aktif</button>
                                                    @else
                                                        <button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>
                                                    @endif
                                                </td>

                                                @if (user_can(['edit bentuk buku', 'delete bentuk buku']))
                                                    <td>
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-block btn-sm btn-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                Action <i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                                style="">
                                                                @if (user_can('edit bentuk buku'))
                                                                    <a class="dropdown-item"
                                                                        href="{{ admin_url("bentuk-buku/$item->id/edit") }}">Edit</a>
                                                                @endif
                                                                @if (user_can('delete bentuk buku'))
                                                                    <button data-id="{{ $item->id }}" type='button'
                                                                        class="dropdown-item button-delete">Hapus</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif
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

            {{-- modal --}}
            <div class="modal fade " id='bentukBukuModal' tabindex="-1" aria-labelledby="myLargeModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myLargeModalLabel">Tambah Bentuk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ show_status() }}
                            <form method="POST" id="bentukBukuForm">
                                {{ csrf_token() }}
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
        $('.button-delete').on('click', function(e) {
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
                            window.location.href = "{{ admin_url('bentuk-buku') }}/" + data_id +
                                '/delete';
                        }
                    },
                },

            });
        });

        $('.button-submit').click(function(e) {
            e.preventDefault();
            $('.table-bentuk tbody').empty();
            var name = $('#name').val();
            $.ajax({
                type: "POST",
                url: "{{ admin_url('bentuk-buku/add') }}",
                data: {
                    name: name
                },
                dataType: "json",
                success: function(res) {
                    $('.table-bentuk tbody').html(res.table);
                    // window.location.href = "{{ admin_url('bentuk-buku') }}";
                    $('#bentukBukuModal').modal("toggle");
                    // alert('Success');
                    // location.reload();
                }
            })
        });
    </script>
@endsection
