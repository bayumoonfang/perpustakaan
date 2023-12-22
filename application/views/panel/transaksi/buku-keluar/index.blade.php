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
							@if (is_admin())
							<div class="col-6 text-left mb-4">
								<form >
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<select name='library' required class=" form-control">
													<option value="" selected disabled>Pilih Perpustakaan</option>
													@foreach ($library as $item)
														<option value="{{$item->id}}" {{$selected_lib && $selected_lib==$item->id ? 'selected':''}} >{{ucfirst($item->library)}}</option>
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
													<input type="submit" class="btn btn-primary btn-block ml-2">
												</div>
												<div class="col-md-auto">
													<a href="{{admin_url("transaksi/buku-keluar")}}" class="btn btn-danger btn-block ml-2">Reset</a>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						@endif
						@if (user_can('add transaksi buku keluar'))
							<div class="col-12 text-right mb-4">
								<a href="{{admin_url('transaksi/buku-keluar/new')}}" class="btn btn-success ml-2">Tambah Data</a>
							</div>
						@endif
						</div>
						
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%" class="text-center">No</th>
									<th width="20%" class="text-center">Tanggal</th>
									<th class="text-center">Buku</th>
									<th width="15%" class="text-center">Jenis</th>
									<th width="10%" class="text-center">Jumlah</th>
									@if (user_can(['edit transaksi buku keluar','delete transaksi buku keluar']))
										<th width="15%" class="text-center">Action</th>
									@endif
								</tr>
								</thead>
								<tbody>
									@foreach ($buku_keluar as $item)
										<tr>
											<td class="text-center">{{$no++}}</td>
											<td>{{$item->date}}</td>
											<td><strong>{{$item->book_title}}</strong>
												<br/>{{$item->library_name}}
											</td>
											<td>{{$item->category_name}}</td>
											<td class="text-right">{{number_format($item->qty)}}</td>
											@if ($item->reff!=null)
												<td class="text-center text-danger">[Pengembalian Buku]</td>
											@else
												@if (user_can(['edit transaksi buku keluar','delete transaksi buku keluar']))
													<td>
														<div class="dropdown">
															<button class="btn btn-block btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Action <i class="mdi mdi-chevron-down"></i>
															</button>
															<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
																@if (user_can('edit transaksi buku keluar'))
																	<a class="dropdown-item" href="{{admin_url("transaksi/buku-keluar/$item->id/edit")}}">Edit</a>
																@endif
																@if (user_can('delete transaksi buku keluar'))
																	<button data-id="{{$item->id}}" type='button' class="dropdown-item button-delete">Hapus</button>
																@endif
															</div>
														</div>
													</td>
												@endif
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
                        window.location.href = "{{admin_url('transaksi/buku-keluar')}}/"+data_id+'/delete';
                    }
                },
            },

        });
    });
</script>
@endsection
