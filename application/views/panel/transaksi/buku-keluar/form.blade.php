@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($buku_keluar) ? true:false;
@endphp
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{base_url('assets/css/select2-bootstrap.css')}}" rel="stylesheet">
@section('content')
<div class="page-content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-flex align-items-center justify-content-between">
					<h4 class="mb-0">
						{{$title}} <a href="{{admin_url('transaksi/buku-keluar')}}" class="btn btn-sm btn-success ml-2">Kembali</a>
					</h4>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="{{admin_url()}}">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="{{admin_url('kategori-buku')}}">Tambah Buku</a></li>
							<li class="breadcrumb-item active">{{$title}}</li>
						</ol>
					</div>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<div class="card">
					<div class="card-body">
						{{show_status()}}
						@if($edit)
							<div class="mt-3 alert alert-warning">Mengubah jumlah buku atau buku saat update transaksi buku keluar, akan merubah stok buku</div>
						@endif
						<form action="{{ $edit ? admin_url("transaksi/buku-keluar/$buku_keluar->id/update"):admin_url('transaksi/buku-keluar/add')}}" method="post">
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="library">Perpustakaan</label>
										<select name='library' required class="select_perpustakaan form-control {{get_error('library') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Perpustakaan</option>
											@foreach ($library as $item)
												<option value="{{$item->id}}"  {{$edit && $item->id==$buku_keluar->library ? 'selected':''}}>{{ucfirst($item->library)}}</option>
											@endforeach
										</select>
										@if (get_error('library'))
											<div class="invalid-feedback">{{get_error('library')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="book">Buku</label>
										<select name='book' required class="select_perpustakaan form-control {{get_error('book') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Buku</option>
											@foreach ($books as $item)
												<option value="{{$item->id}}" {{$item->qty<1 && $item->id!=$buku_keluar->book? 'disabled':''}}  {{$edit && $item->id==$buku_keluar->book ? 'selected':''}}>{{strtoupper($item->code)}} - {{ucfirst($item->title)}} ({{$item->qty}})</option>
											@endforeach
										</select>
										@if (get_error('book'))
											<div class="invalid-feedback">{{get_error('book')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="category">Kategori</label>
										<select name='category' required class="select_perpustakaan form-control {{get_error('category') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Kategori</option>
											@foreach ($categories as $item)
												<option value="{{$item->id}}"  {{$edit && $item->id==$buku_keluar->category ? 'selected':''}}>{{ucfirst($item->title)}}</option>
											@endforeach
										</select>
										@if (get_error('book'))
											<div class="invalid-feedback">{{get_error('book')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="notes">Jumlah</label>
										<input name="qty" type="number" min="1" class="form-control {{get_error('qty') ? 'is-invalid':''}}" id="qty" placeholder="Jumlah" required value="{{$edit ? $buku_keluar->qty : old('qty')}}" autocomplete="off">
										@if (get_error('qty'))
											<div class="invalid-feedback">{{get_error('qty')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="notes">Catatan</label>
										<textarea name="notes" class="form-control {{get_error('notes') ? 'is-invalid':''}}" placeholder="Catatan" autocomplete="off" rows="6">{{$edit ? $buku_keluar->notes : old('notes')}}</textarea>
										@if (get_error('notes'))
											<div class="invalid-feedback">{{get_error('notes')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="notes">Tanggal Transaksi</label>
										<input name="date" type="date" class="form-control {{get_error('date') ? 'is-invalid':''}}" id="date" placeholder="Tanggal Transaksi" required value="{{$edit ? date_format(date_create($buku_keluar->date),'Y-m-d') : old('date')}}" autocomplete="off">
										@if (get_error('date'))
											<div class="invalid-feedback">{{get_error('date')}}</div>
										@endif
									</div>
									<button type="submit" class="btn btn-primary btn-block waves-effect" data-dismiss="modal">{{$edit ? 'Update':'Simpan'}}</button>
									
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		var selectItem=$(".select_perpustakaan");
		selectItem.select2({theme:'bootstrap'});
	});

	$(function() {
		$('select[name="library"]').on('change', function() {
			var libraryId = $('select[name="library"]').val();
			if(libraryId===''){
				return;
			}
			change_libraries(libraryId);
		});
	})

	function change_libraries(libraryId='') {
		$('select[name="book"]').find('option').remove();
		$('select[name="book"]').append('<option value="" selected disabled>Loading...</option>');
		$('select[name="category"]').find('option').remove();
		$('select[name="category"]').append('<option value="" selected disabled>Loading...</option>');
		requestGetJSON('{{admin_url("book/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="book"]').find('option').remove();
			$('select[name="book"]').append('<option value="" selected disabled>Pilih Buku</option>');
			response.forEach(element => {
				$('select[name="book"]').append(`<option ${Number(element.qty)<1 ? 'disabled':''} value="${element.id}">${element.code} - ${element.title} (${element.qty})</option>`);
			});
		})
		requestGetJSON('{{admin_url("kategori-buku-keluar/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="category"]').find('option').remove();
			$('select[name="category"]').append('<option value="" selected disabled>Pilih Kategori</option>');
			response.forEach(element => {
				$('select[name="category"]').append(`<option value="${element.id}">${element.title}</option>`);
			});
		})
	}

	function requestGetJSON(uri, params) {
		params = typeof params == "undefined" ? {} : params;
		params.dataType = "json";
		return requestGet(uri, params);
	}

	function requestGet(uri, params) {
		params = typeof params == "undefined" ? {} : params;
		var options = {
			type: "GET",
			url: uri,
		};
		return $.ajax($.extend({}, options, params));
	}
</script>
@endsection
