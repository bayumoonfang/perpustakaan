@extends('panel.app')
@section('title', $title)
@php
	$edit=isset($library) ? true:false;
@endphp
@section('content')
<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-flex align-items-center justify-content-between">
					<h4 class="mb-0">
						{{$title}} <a href="{{admin_url('perpustakaan')}}" class="btn btn-info ml-2 btn-sm">Kembali</a>
					</h4>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="{{admin_url('perpustakaan')}}">Perpustakaan</a></li>
							<li class="breadcrumb-item active">{{$title}}</li>
						</ol>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								{{show_status()}}
								<form action="{{ $edit ? admin_url("perpustakaan/$library->id/update"):admin_url('perpustakaan/add')}}" method="post">
									{{csrf_token()}}
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="library">Nama perpustakaan</label>
												<input name="library" type="text" class="form-control {{get_error('library') ? 'is-invalid':''}}" id="library" placeholder="Nama Perpustakaan" required value="{{$edit ? $library->library : old('library')}}" autocomplete="off">
												@if (get_error('library'))
													<div class="invalid-feedback">{{get_error('library')}}</div>
												@endif
											</div>
											<div class="form-group">
												<label for="location">Lokasi</label>
												<input name="location" type="text" class="form-control {{get_error('location') ? 'is-invalid':''}}" id="location" placeholder="Lokasi" value="{{$edit ? $library->location : old('location')}}" autocomplete="off">
												@if (get_error('location'))
													<div class="invalid-feedback">{{get_error('location')}}</div>
												@endif
											</div>
											<div class="form-group">
												<label for="school">Sekolah</label>
												<select name='school' required class="form-control {{get_error('school') ? 'is-invalid':''}}">
													<option value="" selected disabled>Pilih sekolah</option>
													@foreach ($schools as $school)
														<option value="{{$school->sekolah_id}}" {{$edit && $library->school==$school->sekolah_id ? 'selected':''}}>{{ucfirst($school->sekolah_nama)}}</option>
													@endforeach
												</select>
												@if (get_error('school'))
													<div class="invalid-feedback">{{get_error('school')}}</div>
												@endif
											</div>
											<button type="submit" class="btn btn-primary btn-block waves-effect" data-dismiss="modal">{{$edit ? 'Update':'Simpan'}}</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		</div>

	</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection
