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
						@if (user_can('laporan pengunjung'))
						<div class="row">
							<div class="col-6"></div>
							<div class="col-6 text-right">
								<form action="{{site_url("buku-tamu")}}"  target="_blank">
									<div class="row">
										<div class="col-8">
											<div class="form-group">
												<select name='library' required class=" form-control">
													<option value="" selected disabled>Pilih Perpustakaan</option>
													@foreach ($library as $item)
														<option value="{{$item->enc}}" >{{ucfirst($item->library)}}</option>
													@endforeach
												</select>
												@if (get_error('library'))
													<div class="invalid-feedback">{{get_error('library')}}</div>
												@endif
											</div>
										</div>
										<div class="col-4">
											<input type="submit" name="Buku Tamu" value="Buku Tamu" style="width:100%" class="btn btn-primary btn-block ml-2">
											
										</div>
									</div>
								</form>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="col-auto">
								<form action="{{admin_url("laporan/pengunjung")}}" method="GET" >
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<input type="date" class=" form-control" name="start" />
											</div>
										</div>
										<div class="col-auto">
											<div class="form-group">
												<input type="date" class=" form-control" name="end" />
											</div>
										</div>
										<div class="col-auto">
											<div class="form-group">
												<select name='library' required class=" form-control">
													<option value="" selected disabled>Pilih Perpustakaan</option>
													@foreach ($library as $item)
														<option value="{{$item->id}}" >{{ucfirst($item->library)}}</option>
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
													<a href="{{admin_url("laporan/pengunjung")}}" class="btn btn-danger btn-block ml-2">Reset</a>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						@endif
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="20%" class="text-center">Nama</th>
									<th width="10%" class="text-center">Status</th>
									<th width="15%" class="text-center">NIS / NIP</th>
									<th  width="10%" class="text-center">Tanggal Pengunjung</th>
									<th width="10%" class="text-center">Jam</th>
									<th class="text-center">Keperluan</th>
								</tr>
								</thead>
								<tbody>
									@foreach ($pengunjung as $item)
										<tr>
											@if ($item->is_guest)
												<td>
													<strong>{{$item->guest_name}}</strong>
													<br/>{{$item->library_detail ? $item->library_detail->library:''}}
												</td>
											@else
												<td>
													<strong>{{$item->user_detail ? $item->user_detail->user_nama:''}}</strong>
													<br/>{{$item->library_detail ? $item->library_detail->library:''}}
												</td>
											@endif
											@if ($item->is_guest)
												<td>{{$item->institution}}<br><span class="badge bg-warning text-white">Guest</span></td>
											@else
												<td>{{$item->role_detail ? $item->role_detail->role_name:''}}</td>
											@endif
											<td>{{$item->user_detail ? $item->user_detail->user_no:''}}</td>
											<td class="text-center">{{$item->tanggal}}</td>
											<td class="text-center">{{$item->time}}</td>
											<td class="text-left">{{$item->description}}</td>
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
@section('scripts')
<script src="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js')}}"></script>
<script>
    $('.button-delete').on('click', function(e){
        var $this = $(this);
        var data_id=$this.data('id');
        $.confirm({
			animation: 'top',
            title: 'Hapus',
            content: 'Yakin ingin hapus data ini ?. proses ini akan mengupdate stok buku dan tidak bisa dikembalikan',
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
                        window.location.href = "{{admin_url('transaksi/buku-masuk')}}/"+data_id+'/delete';
                    }
                },
            },

        });
    });
</script>
@endsection
