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
                                @if (is_admin())
                                    <div class="col-6 text-left mb-4">
                                        <form>
                                            <div class="row">
                                                <div class="col-auto">
                                                    <div class="form-group">
                                                        <select name='library' required class=" form-control">
                                                            <option value="" selected disabled>Pilih Perpustakaan
                                                            </option>
                                                            @foreach ($library as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $selected_lib && $selected_lib == $item->id ? 'selected' : '' }}>
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
                                                            <input type="submit" class="btn btn-primary btn-block ml-2">
                                                        </div>
                                                        <div class="col-md-auto">
                                                            <a href="{{ admin_url('buku') }}"
                                                                class="btn btn-danger btn-block ml-2">Reset</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="btn-group dropleft">
                                    <button type="button"
                                        class="btn btn-warning btn-soft-warning waves-effect waves-light dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-file-excel"></i>
                                    </button>
                                    <div class="dropdown-menu" style="">
                                        <button class="dropdown-item" data-toggle="modal"
                                            data-target=".import-data">Import</button>
                                        <button class="dropdown-item" data-toggle="modal"
                                            data-target=".export-data">Export</button>
                                    </div>
                                </div>


                                @if (user_can('add buku'))
                                    {{-- <div class="col-6 text-right mb-4"> --}}
                                    <a href="{{ admin_url('buku/new') }}" class="btn btn-success ml-2">Tambah Data</a>
                                    {{-- </div> --}}
                                @endif
                            </div>
                            {{ show_status() }}
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Buku</th>
                                            <th width="10%">Harga</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Barcode</th>
                                            @if (user_can(['edit buku', 'delete buku']))
                                                <th width="100px">Action</th>
                                            @endif
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
                                                        <li>Kode: <b>{{ $item->code ?? '-' }}</b></li>
                                                        <li>ISBN: <b>{{ $item->isbn ?? '-' }}</b></li>
                                                        <li>Pengarang: <b>{{ $item->author ?? '-' }}</b></li>
                                                        <li>Publisher: <b>{{ $item->publisher ?? '-' }}</b> Tahun:
                                                            <b>{{ $item->year ?? '-' }}</b>
                                                        </li>
                                                        <li>Rak: <b>{{ $item->rak_name ?? '-' }}</b></li>
                                                        <li><i class="text-secondary">{{ $item->library_name }}</i></li>
                                                    </ul>

                                                </td>
                                                <td>
                                                    {{ number_format($item->price) }}
                                                </td>
                                                <td>
                                                    {{ number_format($item->qty) }} / {{ number_format($item->issued) }}
                                                </td>
                                                <td>
                                                    @if ($item->status == '1')
                                                        <button class="btn btn-sm btn-success ml-2">Aktif</button>
                                                    @else
                                                        <button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (user_can(['edit buku']))
                                                        <div class="barcode_button" data-id="{{ $item->id }}"
                                                            data-library={{ $item->library }}
                                                            data-url={{ admin_url() }}></div>
                                                    @endif
                                                </td>

                                                @if (user_can(['edit buku', 'delete buku']))
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
                                                                @if (user_can('edit buku'))
                                                                    <a class="dropdown-item"
                                                                        href="{{ admin_url("buku/$item->id/edit") }}">Edit</a>
                                                                @endif
                                                                @if (user_can('delete buku'))
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
                            window.location.href = "{{ admin_url('buku') }}/" + data_id + '/delete';
                        }
                    },
                },

            });
        });
    </script>
    <script src="{{ base_url('assets/js/vendor/vendor.js') }}"></script>
    <script src="{{ base_url('assets/js/vendor/bookBarcode.js') }}"></script>
@endsection
