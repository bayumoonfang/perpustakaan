@extends('panel.app')
@section('title', $title)
@php
	$no=1;
@endphp
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
							<li class="breadcrumb-item"><a href="{{admin_url('addon-manager')}}">Addon Manager</a></li>
							<li class="breadcrumb-item active">{{$title}}</li>
						</ol>
					</div>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						
						{{show_status()}}
						<form action="{{admin_url('addon-manager/upload')}}" method="POST" enctype="multipart/form-data">
							{{csrf_token()}}
							<div class="form-group row">
								<label for="example-text-input" class="col-md-2 col-form-label">Addon</label>
								<div class="col-md-7">
									<input required class="form-control" type="file" placeholder="Addon" name="addon" accept="application/zip,application/x-zip-compressed,multipart/x-zip,application/x-compressed">
								</div>
								<div  class="col-md-3">
									<button class="btn btn-success waves-effect waves-light" type="submit">
										Install
									</button>
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
