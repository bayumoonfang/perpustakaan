@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($buku) ? true:false;
@endphp
@section('styles')
<link href="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{base_url('assets/css/select2-bootstrap.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="page-content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-flex align-items-center justify-content-between">
					<h4 class="mb-0">
						{{$title}}
					</h4>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="{{admin_url()}}">Dashboard</a></li>
							<li class="breadcrumb-item active">{{$title}}</li>
						</ol>
					</div>

				</div>
			</div>
		</div>
		<form action="{{ $edit ? admin_url("buku/$buku->id/update"):admin_url('buku/add')}}" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-6">
					<div class="card">
						<div class="card-body">
							{{show_status()}}
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="library">Perpustakaan</label>
										<select name='library' id='select_perpustakaan' required class="select_perpustakaan select2-container form-control {{get_error('library') ? 'is-invalid':''}}">
											<option value="">Pilih Perpustakaan</option>
											@foreach ($library as $item)
												<option value="{{$item->id}}" {{(!is_admin() && $item->school!= current_user('user_sekolahid')) ? 'disabled':''}} {{($edit && $item->id==$buku->library ? 'selected' : old('library')) ? 'selected':''}}>{{ucfirst($item->library)}}</option>
											@endforeach
										</select>
										@if (get_error('library'))
											<div class="invalid-feedback">{{get_error('library')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="rak">Rak</label>
										<select name='rak' id='select_rak' required class="select_perpustakaan select2-container form-control {{get_error('rak') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Rak</option>
											@foreach ($rak as $item)
												<option value="{{$item->id}}" {{($edit && $item->id==$buku->rak ? 'selected':old('rak')) ? 'selected':''}}>{{ucfirst($item->rack)}}</option>
											@endforeach
										</select>
										@if (get_error('rak'))
											<div class="invalid-feedback">{{get_error('rak')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="status">Kategori Buku</label>
										<select name='category' required class="select_perpustakaan form-control {{get_error('category') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Kategori Buku</option>
											@foreach ($kategori_buku as $item)
												<option value="{{$item->id}}" {{($edit && $item->id==$buku->category ? 'selected':old('rak')) ? 'selected':''}}>{{ucfirst($item->category)}}</option>
											@endforeach
										</select>
										@if (get_error('category'))
											<div class="invalid-feedback">{{get_error('category')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="code">Barcode Buku</label>
										<input name="barcode" type="text" class="form-control {{get_error('barcode') ? 'is-invalid':''}}" id="barcode" placeholder="Barcode Buku" value="{{$edit ? $buku->barcode : old('barcode')}}" autocomplete="off">
										@if (get_error('barcode'))
											<div class="invalid-feedback">{{get_error('barcode')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="code">Kode Buku</label>
										<input name="code" type="text" class="form-control {{get_error('code') ? 'is-invalid':''}}" id="code" placeholder="Kode Buku" required value="{{$edit ? $buku->code : old('code')}}" autocomplete="off">
										@if (get_error('code'))
											<div class="invalid-feedback">{{get_error('code')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="title">Judul Buku</label>
										<input name="title" type="text" class="form-control {{get_error('title') ? 'is-invalid':''}}" id="title" placeholder="Judul Buku" required value="{{$edit ? $buku->title : old('title')}}" autocomplete="off">
										@if (get_error('title'))
											<div class="invalid-feedback">{{get_error('title')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="title">Bentuk</label>
										<input name="bentuk" type="text" class="form-control {{get_error('bentuk') ? 'is-invalid':''}}" id="bentuk" placeholder="Bentuk" required value="{{$edit ? $buku->bentuk : old('bentuk')}}" autocomplete="off">
										@if (get_error('bentuk'))
											<div class="invalid-feedback">{{get_error('bentuk')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="author">Pengarang</label>
										<input name="author" type="text" class="form-control {{get_error('author') ? 'is-invalid':''}}" id="author" placeholder="Pengarang" required value="{{$edit ? $buku->author : old('author')}}" autocomplete="off">
										@if (get_error('author'))
											<div class="invalid-feedback">{{get_error('author')}}</div>
										@endif
									</div>
									<div class="row">
										<div class="col-md-8">
											<div class="form-group">
												<label for="publisher">Penerbit</label>
												<input name="publisher" type="text" class="form-control {{get_error('publisher') ? 'is-invalid':''}}" id="publisher" placeholder="Penerbit" required value="{{$edit ? $buku->publisher : old('publisher')}}" autocomplete="off">
												@if (get_error('publisher'))
													<div class="invalid-feedback">{{get_error('publisher')}}</div>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="year">Tahun</label>
												<input name="year" type="number" min="0" class="form-control {{get_error('year') ? 'is-invalid':''}}" id="year" placeholder="Tahun" required value="{{$edit ? $buku->year : old('year')}}" autocomplete="off">
												@if (get_error('year'))
													<div class="invalid-feedback">{{get_error('year')}}</div>
												@endif
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="kolasi">Kolasi</label>
										<input name="kolasi" type="text" class="form-control {{get_error('kolasi') ? 'is-invalid':''}}" id="kolasi" placeholder="Kolasi" required value="{{$edit ? $buku->kolasi : old('kolasi')}}" autocomplete="off">
										@if (get_error('kolasi'))
											<div class="invalid-feedback">{{get_error('kolasi')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="isbn">ISBN</label>
										<input name="isbn" type="text" class="form-control {{get_error('isbn') ? 'is-invalid':''}}" id="isbn" placeholder="ISBN" required value="{{$edit ? $buku->isbn : old('isbn')}}" autocomplete="off">
										@if (get_error('isbn'))
											<div class="invalid-feedback">{{get_error('isbn')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="price">Harga Buku</label>
										<input name="price" type="text" class="form-control {{get_error('price') ? 'is-invalid':''}}" id="price" placeholder="price" required value="{{$edit ? $buku->price : old('price')}}" autocomplete="off">
										@if (get_error('price'))
											<div class="invalid-feedback">{{get_error('price')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="language">Bahasa</label>
										<select name='language' required class="select_perpustakaan form-control {{get_error('language') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Bahasa</option>
											@foreach ($bahasa as $item)
												<option value="{{$item->id}}" {{($edit && $item->id==$buku->language ? 'selected' : old('language')) ? 'selected':''}}>{{ucfirst($item->name)}}</option>
											@endforeach
										</select>
										@if (get_error('language'))
											<div class="invalid-feedback">{{get_error('language')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="class">Kelas</label>
										<select name='class' required class="select_perpustakaan form-control {{get_error('class') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Kelas</option>
											@if ($edit)
											<option value="0" {{($edit && $buku->class=='0' ? 'selected' : old('class')) ? 'selected':''}}>Umum</option>
											@foreach ($kelas as $item)
												<option value="{{$item->kelas_id}}" {{($edit && $item->kelas_id==$buku->class ? 'selected' : old('class')) ? 'selected':''}}>Kelas {{ucfirst($item->kelas_nama)}} {{$item->sekolah_nama}}</option>
											@endforeach
											@endif
										</select>
										@if (get_error('class'))
											<div class="invalid-feedback">{{get_error('class')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="mapel">Mata Pelajaran</label>
										<select name='mapel' required class="select_perpustakaan form-control {{get_error('mapel') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Mata Pelajaran</option>
											@if ($edit)
												<option value="0" {{($edit && '0'==$buku->mapel ? 'selected' : old('mapel')) ? 'selected':''}}>Umum</option>
												@foreach ($mapel as $item)
													<option value="{{$item->mapel_id}}" {{($edit && $item->mapel_id==$buku->mapel ? 'selected' : old('mapel')) ? 'selected':''}}>{{ucfirst($item->mapel_nama)}} {{is_admin() ? $item->sekolah_nama:null}}</option>
												@endforeach
											@endif
										</select>
										@if (get_error('mapel'))
											<div class="invalid-feedback">{{get_error('mapel')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="status">Status</label>
										<select name='status' required class="form-control {{get_error('status') ? 'is-invalid':''}}">
											<option value="1" {{$edit && $buku->status=='1' ?'selected' :''}} >Aktif</option>
											<option value="0" {{$edit && $buku->status=='0' ?'selected' :''}}>Tidak Aktif</option>
										</select>
										@if (get_error('status'))
											<div class="invalid-feedback">{{get_error('status')}}</div>
										@endif
									</div>
									<button type="submit" class="btn btn-primary btn-block waves-effect" data-dismiss="modal">{{$edit ? 'Update':'Simpan'}}</button>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- end col -->
				<div class="col-md-6">
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label for="cover">Cover buku</label>
								<small class="text-danger">(wajib diisi jika menambahkan E-book)</small>
								<input type="file" name="cover" id="cover" class="form-control {{get_error('cover') ? 'is-invalid':''}}" accept="image/png, image/gif, image/jpeg, image/jpg" >
								@if (get_error('cover'))
									<div class="invalid-feedback">{{get_error('cover')}}</div>
								@endif
							</div>
							@if ($edit && !empty($buku->cover))
								<img class="mb-4" src="{{$buku->cover}}" width="100%"/>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label for="ebook">E-book</label>
								<small  class="text-danger">(Cover buku wajib diisi jika menambahkan E-book)</small>
								<input type="file" name="ebook" id="ebook" class="form-control {{get_error('ebook') ? 'is-invalid':''}}" accept="application/pdf,application/force-download,application/x-download,binary/octet-stream'" >
								@if (get_error('ebook'))
									<div class="invalid-feedback">{{get_error('ebook')}}</div>
								@endif
							</div>
							@if ($edit && !empty($buku->fileurl) && $item->is_digital_book='1')
								<a class="btn btn-sm btn-success" href="{{$buku->fileurl}}" target="_blank">View Ebook</a>
							@endif
						</div>
					</div>
				</div>
			</div> <!-- end row -->
		</form>
	</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection
@section('scripts')
<script src="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
	$(document).ready(function() {
		var selectItem=$(".select_perpustakaan");
		selectItem.select2({theme:'bootstrap'});
	});

    $('.button-delete').on('click', function(e){
        var $this = $(this);
        var data_id=$this.data('id');
        $.confirm({
			animation: 'top',
            title: 'Hapus',
            content: 'Yakin ingin hapus data ini ?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                'Tidak': {
                    btnClass: 'btn-info text-white', // multiple classes.
                    action: function(){}
                },
                'Ya': {
                    btnClass: 'btn-danger',
                    action: function(){
                        window.location.href = "{{admin_url('buku')}}/"+data_id+'/delete';
                    }
                },
            },

        });
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
		// if(libraryId===''){
		// 	return;
		// }
		$('select[name="rak"]').find('option').remove();
		$('select[name="rak"]').append('<option value="" selected disabled>Loading...</option>');
		$('select[name="category"]').find('option').remove();
		$('select[name="category"]').append('<option value="" selected disabled>Loading...</option>');
		$('select[name="class"]').find('option').remove();
		$('select[name="class"]').append('<option value="" selected disabled>Loading...</option>');
		$('select[name="mapel"]').find('option').remove();
		$('select[name="mapel"]').append('<option value="" selected disabled>Loading...</option>');
		requestGetJSON('{{admin_url("rak-perpustakaan/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="rak"]').find('option').remove();
			$('select[name="rak"]').append('<option value="" selected disabled>Pilih Rak</option>');
			response.forEach(element => {
				$('select[name="rak"]').append(`<option value="${element.id}">${element.rack}</option>`);
			});
		})
		requestGetJSON('{{admin_url("kategori-buku/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="category"]').find('option').remove();
			$('select[name="category"]').append('<option value="" selected disabled>Pilih Kategori Buku</option>');
			response.forEach(element => {
				$('select[name="category"]').append(`<option value="${element.id}">${element.category}</option>`);
			});
		})
		requestGetJSON('{{admin_url("kelas/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="class"]').find('option').remove();
			$('select[name="class"]').append('<option value="" selected disabled>Pilih Kelas</option>');
			$('select[name="class"]').append('<option value="0">Umum</option>');
			response.forEach(element => {
				$('select[name="class"]').append(`<option value="${element.kelas_id}">${element.kelas_nama} ${element.sekolah_nama}</option>`);
			});
		})
		requestGetJSON('{{admin_url("mapel/ajax/data")}}'+'/' + libraryId).done(function (response) {
			$('select[name="mapel"]').find('option').remove();
			$('select[name="mapel"]').append('<option value="" selected disabled>Pilih Mata Pelajaran</option>');
			$('select[name="mapel"]').append('<option value="0">Umum</option>');
			response.forEach(element => {
				$('select[name="mapel"]').append(`<option value="${element.mapel_id}">${element.mapel_nama} ${element.sekolah_nama}</option>`);
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
