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
						@if (user_can('add issue'))
							<div class="col-12 text-right mb-4">
								<button name="issue_book"  data-toggle="modal" data-target="#modal-member" class="btn btn-success ml-2">Issue Buku</button>
							</div>
						@endif
						<hr/>
						<div class="col-12 text-right mb-4 float-right">
							<form class="form-inline">
							<label class="my-1 mr-2" for="inlineFormCustomSelectPref">Tgl Pengembalian</label>
							<input type="date" required class="form-control  my-1 mr-sm-2" name="due_date" value="{{$temp_kembali}}"/>
							<button type="submit" class="btn btn-primary my-1">Lihat</button>
							<a href={{admin_url('issue')}} class="btn btn-success my-1 ml-2">Reset</a>
							</form>
						</div>
						{{show_status()}}
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
								<tr>
									<th width="5%" class="text-center">No</th>
									<th class="text-center" width="30%">Member</th>
									<th class="text-center" width="30%">Buku</th>
									<th width="20%" class="text-center">Tanggal</th>
									<th width="10%" class="text-center">Status</th>
									@if (user_can(['edit issue','delete issue']))
										<th width="15%" class="text-center">Action</th>
									@endif
								</tr>
								</thead>
								<tbody>
									@foreach ($issues as $item)
									@php
										
										$now = strtotime(date('Y-m-d 23:59:59')); // or your date as well
										$due_date = strtotime($item->tgl_kembali_time);
										$datediff = $due_date - $now;
										
										$date_count=round($datediff / (60 * 60 * 24));
									@endphp	
										<tr>
											<td>{{$no++}}</td>
											<td>
												<ul style="list-style-type: none;padding-left:0">
													<li><h6>{{$item->user_nama}}</h6></li>
													<li>{{$item->user_no}}</li>
													<li>{{$item->user_alamat}}</li>
												</ul>
											</td>
											<td>
												<ul style="list-style-type: none;padding-left:0">
													<li><h6>{{$item->book_title}}</h6></li>
													<li>{{$item->book_code}}</li>
												</ul>
											</td>
											
											<td>
												<ul style="list-style-type: none;padding-left:0">
													<li>Pinjam: <span class="text-success">{{$item->issue_date}}</span></li>
													<li>Due : <span class="text-danger">{{$item->expired_date}}</span></li>
													@if (!$item->expired)
													@if ($date_count==0)
														<li><span class="badge bg-danger text-white">Hari Ini</span></li>
													@else
														<li><span class="badge bg-warning text-white">{{number_format($date_count)}} Hari lagi</span></li>
													@endif
													@endif
												</ul>
											</td>
											<td class="text-center">
												
												@if ($item->expired)
													<button class="btn btn-sm btn-danger">Expired</button>
												@else
													<button class="btn btn-sm btn-success">{{ucfirst($item->status)}}</button>
												@endif
											</td>
											@if (user_can(['edit issue','delete issue']))
												<td>
													<div class="dropdown">
														<button class="btn btn-block btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Action <i class="mdi mdi-chevron-down"></i>
														</button>
														<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
															@if (user_can('edit issue'))
																<a class="dropdown-item" href="{{admin_url("issue/$item->id/kembali")}}">Pengembalian</a>
															@endif
															@if (user_can('edit issue'))
																<a class="dropdown-item" id="form_modal_duration" data-toggle="modal" data-target="#modal_duration" data-expired="{{$item->tgl_kembali}}"  data-siswa="{{$item->user_nama}}" data-pinjam="{{$item->tgl_pinjam}}" data-id="{{$item->id}}"  href="#">Perpanjang Peminjaman</a>
																
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
<!-- Modal -->
<div class="modal fade" id="modal_duration" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Perpajang Masa Peminjaman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form id="form-modal-duration" action="{{admin_url('issue/ajax/add_duration')}}">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="library" name="nama_siswa">Siswa</label>
				</div>
				<div class="form-group">
					<label for="library">Expired</label>
					<input name="id" type="hidden" class="form-control" id="id-expired-form-modal" placeholder="Nama/No Member" required autocomplete="off">
					<input name="expired" min="2020-06-02" type="date" class="form-control" id="expired-form-modal" placeholder="Nama/No Member" required autocomplete="off">
				</div>
				<button type="submit" id="duration-submit-form-modal" class="btn btn-primary btn-block waves-effect">Update</button>
			</div>
		</div>
		<div class="row my-3">
			<div class="col-md-12" id="modal-div-duration-message">
			</div>
		</div>
	   </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cari Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form id="form-modal-member" action="{{admin_url('member/ajax/data')}}">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="library">Member</label>
					<input name="member" type="text" class="form-control" id="member-name-form-modal" placeholder="Nama/No Member" required autocomplete="off">
				</div>
				<button type="submit" id="member-submit-form-modal" class="btn btn-primary btn-block waves-effect">Cari</button>
			</div>
		</div>
		<div class="row my-3">
			<div class="col-md-12" id="modal-list-member">
			</div>
		</div>
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
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
	$("#form-modal-member").submit(function(e) {
		e.preventDefault();
		var actionurl = e.currentTarget.action;
		var member = $('#member-name-form-modal').val();
		if(member.length<1){
			setListMember([]);
			return;
		}
		$('#modal-list-member').empty();
		$('#modal-list-member').append('<div class="text-center">Loading...</div>');
		$('#member-submit-form-modal').prop('disabled', true);
		$.ajax({
			url: actionurl+'/'+member,
			type: 'get',
			dataType: 'json',
			success: function(data) {
				setListMember(data)
			},
			error: function(e){
				setListMember([])
			}
		});
	});

	function setListMember(data=[]){
		$('#modal-list-member').empty();
		if(data.length<1){
			$('#modal-list-member').append('<div class="text-center">data tidak ditemukan</div>');
			$('#member-submit-form-modal').prop('disabled', false);
		}else{
			data.forEach(element => {
				$('#modal-list-member').append(`
					<div class="row my-2 border-bottom pb-2">
						<div class="col-sm-8">
							<div>${element?.user_nama} (${element?.user_uid})</div>
							<small class="text-muted">${element?.user_alamat}</small>
						</div>
						<div class="col-sm-4 text-right">
							<a href="{{admin_url('issue/add')}}/${element?.user_id}" class="btn btn-sm btn-success">Pilih</a>
						</div>
					</div>
				`);
			});
			$('#member-submit-form-modal').prop('disabled', false);
		}
	}

	$('#modal_duration').on('show.bs.modal', function(e) {

		//get data-id attribute of the clicked element
		var issueId = $(e.relatedTarget).data('id');
		var issueDate = $(e.relatedTarget).data('pinjam');
		var expiredDate = $(e.relatedTarget).data('expired');
		var siswa = $(e.relatedTarget).data('siswa');

		//populate the textbox
		document.getElementById("expired-form-modal").setAttribute("min",issueDate);
		$(e.currentTarget).find('label[name="nama_siswa"]').text(siswa);
		$(e.currentTarget).find('input[name="id"]').val(issueId);
		$(e.currentTarget).find('input[name="expired"]').val(expiredDate);
	});

	$("#form-modal-duration").submit(function(e) {
		e.preventDefault();
		var actionurl = e.currentTarget.action;
		var expired = $('#expired-form-modal').val();
		var expiredId = $('#id-expired-form-modal').val();
		$('#modal-div-duration-message').empty();
		$('#modal-div-duration-message').append('<div class="text-center">Loading...</div>');
		$('#duration-submit-form-modal').prop('disabled', true);
		$('#expired-form-modal').prop('disabled', true);
		$.ajax({
			url: actionurl+'/'+expiredId+'/'+expired,
			type: 'get',
			dataType: 'json',
			success: function(data) {
				$('#modal-div-duration-message').empty();
				$('#duration-submit-form-modal').prop('disabled', false);
				$('#expired-form-modal').prop('disabled', false);
				$('#modal_duration').modal('hide');
				location.reload(); 
			},
			error: function(e){
				console.log(e);
			}
		});
	});
</script>
@endsection
