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
							<li class="breadcrumb-item"><a href="{{admin_url('pengaturan/role-issue')}}">Role Issue</a></li>
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
						<form action="{{admin_url("pengaturan/role-issue/$library->id/update")}}" method="post">
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<h5>{{strtoupper($library->library)}}</h5>
										<input type="hidden" name="library" value="{{$library->id}}"/>
										<hr/>
										@if (get_error('library'))
											<div class="invalid-feedback">{{get_error('library')}}</div>
										@endif
									</div>
									@foreach ($roles as $item)
									<div class="custom-control custom-checkbox mb-2">
										<input type="checkbox" class="custom-control-input" name="role[]" id="label_{{$item['id']}}" value="{{$item['id']}}" {{has_role_issue($item['id'],$library->id) ? 'checked':''}}>
										<label class="custom-control-label" for="label_{{$item['id']}}">{{ucfirst($item['name'])}}</label>
									</div>
									@endforeach
									
									<button type="submit" class="btn btn-primary btn-block waves-effect mt-2" data-dismiss="modal">{{$edit ? 'Update':'Simpan'}}</button>
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
