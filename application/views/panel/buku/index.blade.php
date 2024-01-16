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
                                <table id="tableCetakNomorPunggung"
                                    class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Buku</th>
                                            <th width="10%">Harga</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="10%">Status</th>
                                            <th>Barcode</th>
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
                                                        {{-- <div class="barcode_button" data-id="{{ $item->id }}"
                                                            data-library={{ $item->library }}
                                                            data-url={{ admin_url() }}>Nomor Punggung</div> --}}
                                                        <button data-toggle="modal" id="btnCetakNomorPunggung"
                                                            data-target="#cetakNomorPunggungModal"
                                                            data-id="{{ $item->id }}"
                                                            data-library="{{ $item->library }}"
                                                            data-title="{{ $item->title }}"
                                                            data-author="{{ $item->author }}"
                                                            data-call="{{ $item->call }}"
                                                            class="btn btn-sm btn-outline-warning cetak-nomor-punggung">Nomor
                                                            Punggung</button>
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

    <div class="modal fade import-data" id="uploadBukuModal" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="uploadBukuForm" method="post" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Uploud file</label>
                            <input type="file" class="form-control" name="fileExcel">
                        </div>
                        <p>Download Template importnya <a href="{{ admin_url('buku/template_excel') }}"
                                target="_blank">disini</a>.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light button-submit"><i
                                class="uil uil-save mr-2"></i>Import</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade export-data" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setting Export Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ admin_url('buku/export_excel') }}" type="POST" target="_blank">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <input class="form-control" name="export_date_start" type="date">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input class="form-control" name="export_date_end" type="date">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="validationTooltip01">Bentuk</label>
                            <select class="select2 form-control select2-multiple bg-light" name="select_bentuk[]"
                                style="width: 100%" multiple="multiple">
                                @foreach ($bentuk_buku as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="validationTooltip01">Kategori Buku</label>
                            <select class="select2 form-control select2-multiple bg-light" name="select_kategori[]"
                                style="width: 100%" multiple="multiple">
                                @foreach ($kategori_buku as $item)
                                    <option value="{{ $item->id }}">{{ $item->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="validationTooltip01">Subjek Buku</label>
                            <select class="select2 form-control select2-multiple bg-light" name="select_subjek[]"
                                style="width: 100%" multiple="multiple">
                                @foreach ($subjek_buku as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="validationTooltip01">Status</label>
                            <select class="select2 form-control select2-multiple bg-light" name="select_aktif[]"
                                style="width: 100%" multiple="multiple">
                                <option value="">-</option>
                                <option value="">Aktif</option>
                                <option value="">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning waves-effect waves-light"><i
                                    class="uil uil-download-alt mr-2"></i>Export</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade cetak-nomor-punggung" id="cetakNomorPunggungModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Nomor Punggung Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold titleBook">The Captain: A Magazine for Boyz & "Old Boys"</p>
                    <p class="idBook" hidden></p>
                    <p class="libraryBook" hidden></p>
                    <p>Pengarang :
                        <span class="authorBook"></span>
                    </p>
                    <p>Nomor Panggil :

                        <span class="callBook"></span>
                    </p>
                    <form id="btnRegenerateNomorPunggungBuku">
                        <button type="submit" class="btn btn-sm btn-outline-success">Regenerate Nomor Punggung
                            Buku</button>
                    </form>
                    <p class="text-warning"><em>**Regenerate Barcode hanya akan menambah jumlah barcode sesuai Qty buku
                            (jika kurang) dan tidak akan menghapus atau mengurangi barcode yang telah dibuat sebelumnya.
                            **Perubahan Kode buku master tidak akan merubah barcode yang telah digenerate sebelumnya.</em>
                    </p>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group position-relative">
                                <input type="checkbox" class="mr-2" value="1" id="checkboxAll"><span>Pilh
                                    Semua</span>
                                {{-- <button id="selectAll" class="btn btn-sm btn-outline-info mb-3">Pilih Semua</button>
                                <button id="cancelSelectAll" class="btn btn-sm btn-outline-danger mb-3" 
                                 style="display: none;">Batal Pilih Semua</button> --}}
                                <button id="printOut" class="btn btn-sm btn-outline-warning" hidden>Cetak</button>
                                <table id="tableNomorPunggungBuku"
                                    class="table table-striped table-hover table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                #
                                            </th>

                                            <th>Copy ke</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>3</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>4</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>5</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>6</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>7</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="defaultCheck1" checked="">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>8</td>
                                        </tr>
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- End Page-content -->
@endsection
@section('scripts')
    <!-- plugins -->
    <script src="{{ base_url('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();
            $('#tableCetakNomorPunggung').on('click', 'td>button#btnCetakNomorPunggung', function(e) {
                e.preventDefault();
                $('#checkboxAll').prop('checked', false);
                let id = $(this).data("id");
                let library = $(this).data("library");
                let author = $(this).data("author");
                let title = $(this).data("title");
                let call = $(this).data("call");
                $('.idBook').html(id);
                $('.libraryBook').html(library);
                $('.authorBook').html(author);
                $('.titleBook').html(title);
                $('.callBook').html(call);
                $('#tableNomorPunggungBuku').DataTable().destroy();
                var url = "{{ admin_url('buku-nomorpunggung/:id/library/:library') }}";
                var table = $('#tableNomorPunggungBuku').DataTable({
                    ajax: {
                        type: "GET",
                        url: url.replace(":id", id).replace(":library", library),
                    },
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    paging: false,
                    searching: false,
                    bInfo: false,
                    scrollY: 280,
                    columns: [{
                            data: 'id',
                            render: function(data, type, full) {

                                return '<input id="checkboxId" value="' + full.callnumber +
                                    '"  type="checkbox">';
                            },
                        },
                        {
                            data: 'callnumber'
                        }
                    ],
                });
            });

            $('#checkboxAll').on('click', function() {
                $('#tableNomorPunggungBuku tbody input[type="checkbox"][id="checkboxId"]').prop(
                    'checked', this.checked);

                $('#printOut').prop('hidden', !this.checked);
            });

            $('#tableNomorPunggungBuku tbody tr').on('click', 'td input#checkboxId', function() {
                var checked = $(this).prop('checked');

                console.log('asjsjdja');
            });

            $("body").change("#checkboxId", function() {
                if ($("#checkboxId").prop('checked')) {
                    // Do Stuff
                    console.log($(this).val());
                } else {
                    // Do Stuff
                }
            });
            $('#printOut').click(function() {
                var book = $('.idBook').html();
                var library = $('.libraryBook').html();
                var cetak = [];
                var value = [];
                for (i = 0; i < $('#tableNomorPunggungBuku').DataTable().data().count(); i++) {
                    if ($('#checkboxId').is(':checked'))
                        cetak.push($('#checkboxId').val());
                }
                $($('#tableNomorPunggungBuku').DataTable().$('input[id="checkboxId"]:checked').each(
                    function() {

                        value.push($(this).val());

                    }));
                console.log(cetak);
                console.log(value);
                n = JSON.stringify(value);
                window.open("{{ admin_url('buku-nomorpunggung-print') }}" + "?nomorpunggung=" + n +
                    "&idbuku=" + book + "&idlibrary=" + library,
                    "_blank");
            })
        });





        $('#btnRegenerateNomorPunggungBuku').click(function(e) {
            e.preventDefault();
            var book = $('.idBook').html();
            var library = $('.libraryBook').html();
            $.ajax({
                type: "POST",
                url: "{{ admin_url('buku-nomorpunggung-generate') }}",
                data: {
                    book: book,
                    library: library
                },
                dataType: 'json',

                success: function(res) {
                    console.log(res);
                    Swal.fire({
                        icon: res.icon,
                        title: 'Proses ' + res.status + '!',
                        text: res.message,
                        timer: 1000
                    });
                    $('#cetakNomorPunggungModal').modal("toggle");
                }
            })
        });

        $('.button-submit').click(function(e) {
            e.preventDefault();
            var data = new FormData($('#uploadBukuForm')[0]);
            $.ajax({
                type: "POST",
                url: "{{ admin_url('buku/import_excel') }}",
                data: data,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    console.log(res);
                    Swal.fire({
                        icon: res.icon,
                        title: 'Proses ' + res.status + '!',
                        text: res.message,
                        timer: 1000
                    });
                    $('#uploadBukuForm')[0].reset();
                    $('#uploadBukuModal').modal("toggle");
                    window.location.href = "{{ admin_url('buku') }}";
                    // alert('Success');
                }
            })
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
