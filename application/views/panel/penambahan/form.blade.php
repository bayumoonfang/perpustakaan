@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($penambahan) ? true:false;
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
							<li class="breadcrumb-item"><a href="{{admin_url('penambahan')}}">Jenis Penambahan</a></li>
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
						<form action="{{ $edit ? admin_url("penambahan/$penambahan->id/update"):admin_url('penambahan/add')}}" method="post">
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="library">Perpustakaan</label>
										<select name='library' required class="select_perpustakaan form-control {{get_error('library') ? 'is-invalid':''}}">
											<option value="" selected disabled>Pilih Perpustakaan</option>
											@foreach ($library as $item)
												<option value="{{$item->id}}"  {{$edit && $item->id==$penambahan->library ? 'selected':''}}>{{ucfirst($item->library)}}</option>
											@endforeach
										</select>
										@if (get_error('library'))
											<div class="invalid-feedback">{{get_error('library')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="title">Jenis Penambahan</label>
										<input name="title" type="text" class="form-control {{get_error('title') ? 'is-invalid':''}}" id="title" placeholder="Jenis penambahan" required value="{{$edit ? $penambahan->title : old('title')}}" autocomplete="off">
										@if (get_error('title'))
											<div class="invalid-feedback">{{get_error('title')}}</div>
										@endif
									</div>
									<div class="form-group">
										<label for="status">Status</label>
										<select name='status' required class="form-control {{get_error('status') ? 'is-invalid':''}}">
											<option value="1" {{$edit && $penambahan->status=='1' ?'selected' :''}} >Aktif</option>
											<option value="0" {{$edit && $penambahan->status=='0' ?'selected' :''}}>Tidak Aktif</option>
										</select>
										@if (get_error('status'))
											<div class="invalid-feedback">{{get_error('status')}}</div>
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
