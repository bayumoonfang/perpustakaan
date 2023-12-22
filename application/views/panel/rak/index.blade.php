@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($rak) ? true:false;
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
							<li class="breadcrumb-item"><a href="{{admin_url('perpustakaan')}}">perpustakaan</a></li>
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
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="10%">No</th>
									<th >Nama Rak</th>
									<th width="20%">Status</th>
									
									@if (user_can(['edit perpustakaan','delete perpustakaan']))
										<th width="20%">Action</th>
									@endif
								</tr>
								</thead>
								<tbody>
									@foreach ($list_rak as $item)
										<tr>
											<td>{{$no++}}</td>
											<td>{{$item->rack}}</td>
											<td>{{$item->status=='1' ? 'Aktif' :'Tidak Aktif'}}</td>
											<td>
												@if (!($edit) || ($edit && $item->id !=$rak->id))
												@if (user_can('edit rak'))
													<a href="{{admin_url("perpustakaan/$library->id/rak/$item->id/edit")}}" class=" btn btn-success btn-sm mr-1"><i class="uil-edit-alt"></i></a>
												@endif
												@if (user_can('delete rak'))
													<button data-library="{{$library->id}}" data-id="{{$item->id}}" class="button-delete btn btn-danger btn-sm ml-1" type="button"><i class="uil-trash-alt"></i></button>
												@endif
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> <!-- end col -->
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<label>{{$edit ? 'Edit Rak':'Tambah Rak'}}</label>
						<hr/>
						<form action="{{ !$edit ? admin_url("perpustakaan/$library->id/rak/add"):admin_url("perpustakaan/$library->id/rak/$rak->id/update")}}" method="post">
							{{csrf_token()}}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="rack">Nama Rak</label>
										<input name="rack" type="text" class="form-control {{get_error('rack') ? 'is-invalid':''}}" id="rack" placeholder="Nama Rak" required value="{{$edit ? $rak->rack :''}}" autocomplete="off">
										@if (get_error('rack'))
											<div class="invalid-feedback">{{get_error('rack')}}</div>
										@endif
									</div>
									
									<div class="form-group">
										<label for="status">Status</label>
										<select name='status' required class="form-control {{get_error('status') ? 'is-invalid':''}}">
											<option value="1" {{$edit && $rak->status=='1' ?'selected' :''}} >Aktif</option>
											<option value="0" {{$edit && $rak->status=='0' ?'selected' :''}}>Tidak Aktif</option>
										</select>
										@if (get_error('status'))
											<div class="invalid-feedback">{{get_error('status')}}</div>
										@endif
									</div>
									<button type="submit" class="btn btn-primary btn-block waves-effect">{{$edit ? 'Update':'Simpan'}}</button>
									@if ($edit)
										<a href="{{admin_url("perpustakaan/$library->id/rak")}}" class="btn btn-danger btn-block waves-effect">Batal</a>
									@endif
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
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
        var data_library=$this.data('library');
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
                        window.location.href = "{{admin_url('perpustakaan')}}/"+data_library+'/rak/'+data_id+'/delete';
                    }
                },
            },

        });
    });
</script>
@endsection
