@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($pengaturan_peminjaman) ? true:false;
@endphp
@section('styles')
<link href="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{base_url('assets/css/select2-bootstrap.css')}}" rel="stylesheet">
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
							<li class="breadcrumb-item"><a href="{{admin_url('pengaturan/peminjaman')}}">Pengaturan Peminjaman</a></li>
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
						<form action="{{ $edit ? admin_url("pengaturan/peminjaman/$pengaturan_peminjaman->id/update"):admin_url('pengaturan/peminjaman/add')}}" method="post">
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									@if ($edit)
										<div class="form-group">
											<label for="library">Perpustakaan</label>
											<div>
												<label for="library"><strong>{{$pengaturan_peminjaman->library}}</strong></label>
											</div>
											@if (get_error('library'))
												<div class="invalid-feedback">{{get_error('library')}}</div>
											@endif
										</div>
									@endif
									<div class="form-group">
										<label for="hari_pinjam">Max Hari Pinjam</label>
										<input name="hari_pinjam" type="number" min="0" class="form-control {{get_error('hari_pinjam') ? 'is-invalid':''}}" id="hari_pinjam" placeholder="Max Hari Pinjam" required value="{{$edit ? $pengaturan_peminjaman->hari_pinjam : old('hari_pinjam')}}" autocomplete="off">
										@if (get_error('hari_pinjam'))
											<div class="invalid-feedback">{{get_error('hari_pinjam')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="jml_pinjam">Max Jumlah Pinjam</label>
										<input name="jml_pinjam" type="number" min="0" class="form-control {{get_error('jml_pinjam') ? 'is-invalid':''}}" id="jml_pinjam" placeholder="Max Jumlah Pinjam" required value="{{$edit ? $pengaturan_peminjaman->jml_pinjam : old('jml_pinjam')}}" autocomplete="off">
										@if (get_error('jml_pinjam'))
											<div class="invalid-feedback">{{get_error('jml_pinjam')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="denda_hari">Denda per Hari</label>
										<input name="denda_hari" type="number" min="0" class="form-control {{get_error('denda_hari') ? 'is-invalid':''}}" id="denda_hari" placeholder="Max Hari Pinjam" required value="{{$edit ? $pengaturan_peminjaman->denda_hari : old('denda_hari')}}" autocomplete="off">
										@if (get_error('denda_hari'))
											<div class="invalid-feedback">{{get_error('denda_hari')}}</div>
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
<script src="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		var selectItem=$(".select_perpustakaan");
		selectItem.select2({theme:'bootstrap'});
	});
</script>
@endsection
