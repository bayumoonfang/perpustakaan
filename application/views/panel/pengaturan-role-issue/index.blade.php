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
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%" class="text-center">No</th>
									<th  class="text-center">Perpustakaan</th>
									<th width="35%" class="text-center">Role</th>
									<th width="15%" class="text-center">Action</th>
								</tr>
								</thead>
								<tbody>
									@foreach ($data as $item)
										<tr>
											<td>{{$no++}}</td>
											<td class="text-left">
												<strong>{{ucfirst($item->library_name)}}</strong>
											</td>
											<td class="text-left">
												<span>{{ucfirst($item->role_name)}}</span>
											</td>
											
											<td class="text-center">
												<a href="{{admin_url('pengaturan/role-issue/'.$item->id.'/edit')}}" class="btn btn-sm btn-primary">Edit</a>
												{{-- <button data-id="{{$item->id}}" class="btn btn-sm btn-danger button-delete">Hapus</button> --}}
											</td>
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
                        window.location.href = "{{admin_url('pengaturan/role-issue')}}/"+data_id+'/delete';
                    }
                },
            },

        });
    });
</script>
@endsection
