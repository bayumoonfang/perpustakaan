@extends('panel.app')
@section('title', $title)
@php
	$no=1;
	$edit=isset($kategori_buku) ? true:false;
	$total_denda=0;
@endphp
@section('styles')
<link href="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{base_url('assets/css/select2-bootstrap.css')}}" rel="stylesheet">
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
							<li class="breadcrumb-item"><a href="{{admin_url('issue')}}">Issue</a></li>
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
							<div class="mb-2 col-md-4">
								<div class="text-muted">Nama:</div>
								<label>{{$issue->user->user_nama}}</label>
							</div>
							<div class="mb-2 col-md-4">
								<div class="text-muted">Alamat:</div>
								<label>{{$issue->user->user_alamat}}</label>
							</div>
							<div class="mb-2 col-md-4">
								<div class="text-muted">UID:</div>
								<label>{{$issue->user->user_uid}}</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="text-muted col-md-3">Judul Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{ucfirst($issue->book->title)}}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Kode Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{$issue->book->code}}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Penulis</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{ucfirst($issue->book->author)}}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Kategori Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{ucfirst($issue->book->category_name)}}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Rak Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{$issue->book->rak_name}}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">ISBN</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>{{$issue->book->isbn}}</label></div>
						</div>
						<hr/>
						<div class="row">
							<div class="text-muted col-md-3">Tgl Pinjam</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label class="text-success">{{date_format(date_create($issue->issue_date), 'd F Y')}}</label></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						{{show_status()}}
						@if ($expired)
							<div class="mt-3 alert alert-danger">Masa peminjaman sudah expired pada tanggal {{date_format(date_create($issue->expired_date), 'd F Y')}}</div>
						@endif
						<form id="form_pengembalian" action="{{admin_url('issue/'.$issue->id.'/kembali')}}" method="post">
							<input id="issue" name="issue" type="hidden" required value="{{$issue->id}}" class="form-control"/>
							<input id="book" name="book" type="hidden" required value="{{$issue->book->id}}" class="form-control"/>
							<input id="user" name="user" type="hidden" required value="{{$issue->user->user_id}}" class="form-control"/>
							<div class="row">
								<div class="col-md-12">
									@if ($expired)
									@php
										$now = strtotime(date('Y-m-d 23:59:59')); // or your date as well
										$your_date = strtotime($issue->expired_date);
										$datediff = $now - $your_date;
										$hari=round($datediff / (60 * 60 * 24));
										$denda_terlambat=intval($hari) * intval($settings->denda_hari);
										$total_denda=$total_denda+$denda_terlambat;
									@endphp	
									<input id="day" name="day" type="hidden" required value="{{$hari}}" class="form-control"/>
									<input id="amount" name="amount" type="hidden" required value="{{$denda_terlambat}}" class="form-control"/>
										<div class="row mb-2">
											<div class="text-muted col-md-4">Hari Lewat</div>
											<div class="text-muted col-md-1">:</div>
											<div class="col-md-7 text-right"><label class="text-danger">{{number_format($hari)}} Hari</label></div>
										</div>
										<div class="row mb-2">
											<div class="text-muted col-md-4"><div>Denda Keterlambatan</div><small class="text-danger">({{number_format($hari)}} Hari X {{number_format($settings->denda_hari)}})</small></div>
											<div class="text-muted col-md-1">:</div>
											<div class="col-md-7 text-right"><label class="text-danger">{{number_format($denda_terlambat)}}</label></div>
										</div>
										<hr/>
									@endif
									<div class="form-group">
										<label for="category">Jenis Pengembalian</label>
										<select name='category' required class="select_perpustakaan form-control {{get_error('category') ? 'is-invalid':''}}">
											<option value="0" selected >Normal</option>
											@foreach ($category as $item)
												<option value="{{$item->id}}">{{ucfirst($item->title)}}</option>
											@endforeach
										</select>
										@if (get_error('category'))
											<div class="invalid-feedback">{{get_error('category')}}</div>
										@endif
									</div>
									<div id="form_denda" class="hidden">
										<div class="form-group">
											<label for="fine">Jumlah denda</label>
											<input id="fine" name="fine" type="number" min="0" value="0" placeholder="Jumlah denda" class="form-control"/>
										</div>
										<div class="form-group">
											<label for="notes">Catatan</label>
											<textarea name="notes" class="form-control" placeholder="Catatan" autocomplete="off" rows="6"></textarea>
										</div>
										<div class="mt-3 alert alert-warning">Pengembalian buku selain kategori <b>"NORMAL"</b> akan tercatat sebagai transaksi buku keluar dan mengurangi stok buku</div>
									</div>
									<hr/>
									<div class="row mb-2">
										<div class="text-muted col-md-4"><h4>Total Denda</h4></div>
										<div class="text-muted col-md-1">:</div>
										<div class="col-md-7 text-right"><h4><label class="text-danger" id="total_denda">{{number_format($total_denda)}}</label></h4></div>
									</div>
									<hr/>
									<button type="submit" id="btn_submit_kembali" class="btn btn-primary btn-block waves-effect" data-dismiss="modal">{{$edit ? 'Update':'Simpan'}} </button>
									<div id="submit_process" class="text-center text-muted mt-2 hidden">
										Loading...
									</div>
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
@section('scripts')
<script src="{{base_url('assets/libs/jquery-confirm/dist/jquery-confirm.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		var selectItem=$(".select_perpustakaan");
		selectItem.select2({theme:'bootstrap'});
	});
	$(function() {
		$('select[name="category"]').on('change', function() {
			var category = $('select[name="category"]').val();
			var form_denda = $("#form_denda");
			var fine = $("#fine");
			if(category!=='0'){
				form_denda.removeClass("hidden");
				fine.attr("required",true);
			}else{
				form_denda.addClass("hidden");
				fine.attr("required",false);
			}
		});
		$('input[name=fine]').keyup(function() {
			var total_denda={{$total_denda}};
			var fine = $('input[name="fine"]').val();
			console.log(fine);
			if(/^\d+$/.test(fine)){
				if(Number(fine)>0){
					total_denda1=Number(total_denda)+Number(fine);
				}else{
					total_denda1=Number(total_denda);
				}
				$('#total_denda').html(numberWithCommas(total_denda1));
			}else{
				$('#total_denda').html(numberWithCommas(total_denda));
			}
			if(fine==='' || fine==='0'){
				$('#total_denda').html(numberWithCommas(total_denda));
			}
		});
		
	})
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$('#form_pengembalian').on('submit', function(e){
		e.preventDefault();
		 $.confirm({
			theme:'supervan',
    		closeAnimation: 'scale',
            title: 'Pengembalian buku',
            content: 'Sebelum melanjutkan, pastikan data inputan telah benar dan UANG DENDA telah diterima sesuai dengan total denda yang tertera sebelum melanjutkan karena proses ini tidak bisa dikembalikan atau dibatalkan setelah dilanjutkan',
            type: 'red',
            typeAnimated: true,
            buttons: {
                'Batal': {
                    btnClass: 'btn-info text-white', // multiple classes.
                    action: function(){}
                },
                'Lanjutkan': {
                    btnClass: 'btn-danger',
                    action: function(){
						proses_pengembalian();
                    }
                },
            },

        });
	});

	function proses_pengembalian(){
		var formData=$('#form_pengembalian').serializeArray();
		let data={}
		formData.forEach((element, index) => {
			data[element.name] = element.value;
		});
		$('#btn_submit_kembali').attr('disabled',true);
		$('#submit_process').removeClass('text-danger');
		$('#submit_process').addClass('text-muted');
		$('#submit_process').html('Loading...');
		$('#submit_process').removeClass('hidden');
		$.ajax({
			type: "POST",
			url: "{{admin_url()}}/book/ajax/proses-kembali",
			data: JSON.stringify(data),
			success: function(data){
				var response=JSON.parse(data);
				$('#btn_submit_kembali').attr('disabled',false);
				if(response.status){
					$('#submit_process').addClass('hidden');
					window.location = "{{admin_url('issue')}}";
				}else{
					$('#submit_process').removeClass('text-muted');
					$('#submit_process').addClass('text-danger');
					$('#submit_process').html(response.message);
				}
			},
		});
	}
	
</script>
@endsection
