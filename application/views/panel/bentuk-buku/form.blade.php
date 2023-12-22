@extends('panel.app')
@section('title', $title)
@php
    $no = 1;
    $edit = isset($bentuk_buku) ? true : false;
@endphp
@section('styles')
    <link href="{{ base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ base_url('assets/css/select2-bootstrap.css') }}" rel="stylesheet">
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
                                <li class="breadcrumb-item"><a href="{{ admin_url('bentuk-buku') }}">Kategori Buku</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            {{ show_status() }}
                            <form
                                action="{{ $edit ? admin_url("bentuk-buku/$bentuk_buku->id/update") : admin_url('bentuk-buku/add') }}"
                                method="post">
                                {{ csrf_token() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="library">Perpustakaan</label>
                                            <select name='library' required
                                                class="select_perpustakaan form-control {{ get_error('library') ? 'is-invalid' : '' }}">
                                                <option value="" selected disabled>Pilih Perpustakaan</option>
                                                @foreach ($library as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $edit && $item->id == $bentuk_buku->library ? 'selected' : '' }}>
                                                        {{ ucfirst($item->library) }}</option>
                                                @endforeach
                                            </select>
                                            @if (get_error('library'))
                                                <div class="invalid-feedback">{{ get_error('library') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Kategori Buku</label>
                                            <input name="category" type="text"
                                                class="form-control {{ get_error('category') ? 'is-invalid' : '' }}"
                                                id="category" placeholder="Kategori Buku" required
                                                value="{{ $edit ? $bentuk_buku->category : old('category') }}"
                                                autocomplete="off">
                                            @if (get_error('category'))
                                                <div class="invalid-feedback">{{ get_error('category') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name='status' required
                                                class="form-control {{ get_error('status') ? 'is-invalid' : '' }}">
                                                <option value="1"
                                                    {{ $edit && $bentuk_buku->status == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0"
                                                    {{ $edit && $bentuk_buku->status == '0' ? 'selected' : '' }}>Tidak Aktif
                                                </option>
                                            </select>
                                            @if (get_error('status'))
                                                <div class="invalid-feedback">{{ get_error('status') }}</div>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block waves-effect"
                                            data-dismiss="modal">{{ $edit ? 'Update' : 'Simpan' }}</button>
                                    </div>
                                </div>
                            </form>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            var selectItem = $(".select_perpustakaan");
            selectItem.select2({
                theme: 'bootstrap'
            });
        });
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
    </script>
@endsection
