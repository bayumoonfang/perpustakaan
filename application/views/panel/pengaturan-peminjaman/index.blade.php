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
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%" class="text-center">No</th>
									<th  class="text-center">Perpustakaan</th>
									<th width="16%" class="text-center">Max Hari Pinjam</th>
									<th width="16%" class="text-center">Max Jumlah Pinjam</th>
									<th width="16%" class="text-center">Denda per Hari</th>
									@if (user_can(['edit pengaturan peminjaman','delete pengaturan peminjaman']))
										<th width="10%" class="text-center">Action</th>
									@endif
								</tr>
								</thead>
								<tbody>
									@foreach ($pengaturan_peminjaman as $item)
										<tr>
											<td>{{$no++}}</td>
											<td class="text-left">
												<strong>{{ucfirst($item->library)}}</strong>
											</td>
											<td class="text-right">
												<strong>{{number_format($item->hari_pinjam)}}</strong>
											</td>
											<td class="text-right">
												<strong>{{number_format($item->jml_pinjam)}}</strong>
											</td>
											<td class="text-right">
												<strong>{{number_format($item->denda_hari)}}</strong>
											</td>
											
											@if (user_can(['edit pengaturan peminjaman']))
												<td class="text-center">
													@if (user_can('edit pengaturan peminjaman'))
														<a class="btn btn-sm btn-success" href="{{admin_url("pengaturan/peminjaman/$item->id/edit")}}">Edit</a>
													@endif
												</td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
							{!!$page!!}
						</div>
					</div>
				</div>
			</div> <!-- end col -->
		</div> <!-- end row -->
	</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection

