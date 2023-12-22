@extends('panel.app')
@section('title', $title)
@php
	$no=1;
@endphp
@section('styles')
<link href="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css')}}" rel="stylesheet">
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
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-auto">
								<form action="{{admin_url("laporan/transaksi-buku")}}" method="GET" >
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<input type="date" class=" form-control" name="start" value="{{$default_start}}" />
											</div>
										</div>
										<div class="col-auto">
											<div class="form-group">
												<input type="date" class=" form-control" name="end" value="{{$default_end}}"/>
											</div>
										</div>
										<div class="col-auto">
											<div class="form-group">
												<select name='library' required class=" form-control">
													<option value="" selected disabled>Pilih Perpustakaan</option>
													@foreach ($library as $item)
														<option value="{{$item->id}}" {{$default_library && $default_library==$item->id ? 'selected' :''}} >{{ucfirst($item->library)}}</option>
													@endforeach
												</select>
												@if (get_error('library'))
													<div class="invalid-feedback">{{get_error('library')}}</div>
												@endif
											</div>
										</div>
										<div class="col-auto">
											<div class="row">
												<div class="col-md-auto">
													<input type="submit" name="Filter" value="Filter" class="btn btn-primary btn-block ml-2">
												</div>
												<div class="col-md-auto">
													<input name="cetak" value="Cetak" type="submit" class="btn btn-warning btn-block ml-2"/>
												</div>
												<div class="col-md-auto">
													<a href="{{admin_url("laporan/transaksi-buku")}}" class="btn btn-danger btn-block ml-2">Reset</a>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<hr/>
                        {{-- <div class="col-12 text-right mb-4">
                            <a href="{{admin_url('laporan/transaksi-buku/cetak')}}" class="btn btn-success ml-2">Cetak</a>
                        </div> --}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%" class="text-center">No</th>
									<th width="20%" class="text-center">Tanggal</th>
									<th class="text-center">Buku</th>
									<th width="15%" class="text-center">Jenis</th>
									<th width="10%" class="text-center">Jumlah</th>
									<th width="10%" class="text-center">Tipe</th>
								</tr>
								</thead>
								<tbody>
									@foreach ($books as $item)
										<tr>
											<td class="text-center">{{$no++}}</td>
											<td>{{$item->date}}</td>
											<td><strong>{{$item->book_title}}</strong>
												<br/>{{$item->library_name}}
											</td>
											<td>{{$item->category_name}}</td>
											<td class="text-right">{{number_format($item->qty)}}</td>
											<td class="text-center">
                                                <span class="badge bg-{{$item->type=='in' ? 'success':'danger'}} text-white">{{$item->type=='in' ? 'BUKU MASUK':'BUKU KELUAR'}}</span>
                                            </td>
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

