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
						@if (user_can('add pengurangan'))
							<div class="col-12 text-right mb-4">
								<a href="{{admin_url('pengurangan/new')}}" class="btn btn-success ml-2">Tambah Data</a>
							</div>
						@endif
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%">No</th>
									<th>Jenis Pengurangan</th>
									<th width="20%">Status</th>
									@if (user_can(['edit pengurangan','delete pengurangan']))
										<th width="200px">Action</th>
									@endif
								</tr>
								</thead>
								<tbody>
									@foreach ($pengurangan as $item)
										<tr>
											<td>{{$no++}}</td>
											<td><strong>{{ucfirst($item->title)}}</strong>
												<br/><i>{{$item->library_name}}</i>
											</td>
											<td>
												@if ($item->status=='1')
													<button class="btn btn-sm btn-success ml-2">Aktif</button>
												@else
													<button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>
												@endif
											</td>
											
											@if (user_can(['edit pengurangan','delete pengurangan']))
												<td>
													<div class="dropdown">
														<button class="btn btn-block btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Action <i class="mdi mdi-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
															@if (user_can('edit pengurangan'))
																<a class="dropdown-item" href="{{admin_url("pengurangan/$item->id/edit")}}">Edit</a>
															@endif
															@if (user_can('delete pengurangan'))
																<button data-id="{{$item->id}}" type='button' class="dropdown-item button-delete">Hapus</button>
															@endif
														</div>
													</div>
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
@section('scripts')
<script src="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js')}}"></script>
<script>
    $('.button-delete').on('click', function(e){
        var $this = $(this);
        var data_id=$this.data('id');
        $.confirm({
			animation: 'top',
            title: 'Hapus',
            content: 'Yakin ingin hapus data ini ?',
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
                        window.location.href = "{{admin_url('pengurangan')}}/"+data_id+'/delete';
                    }
                },
            },

        });
    });
</script>
@endsection
