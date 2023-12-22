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
							<li class="breadcrumb-item"><a href="{{admin_url()}}">Dashboard</a></li>
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
						<div class="col-12 text-right mb-4">
							<a href="{{admin_url('addon-manager/new')}}" type="button" class="btn btn-success ml-2">New Addon</a>
						</div>
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%">No</th>
									<th>Addon</th>
									<th width="20%">Unique Key</th>
									<th width="10%">Version</th>
									<th width="10%">Status</th>
									<th width="10%">Action</th>
								</tr>
								</thead>
								<tbody>
									@foreach ($addons as $item)
										<tr>
											<td>{{$no++}}</td>
											<td>{{$item->name}}</td>
											<td>{{$item->unique_key}}</td>
											<td>{{$item->version}}</td>
											<td><span class="badge badge-{{$item->status=='1'?'success':'secondary'}}">{{$item->status=='1'?'Activated':'Not Activated'}}</span></td>
											<td>&nbsp;</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> <!-- end col -->
		</div> <!-- end row -->
		
	</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection
