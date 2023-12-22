import React, { useEffect, useRef, useState } from "react";

const Issue = (props) => {
	const{user,title,admin_url,library,history}=props;
	let searchTiming=useRef(null);
	let bookRef=useRef();
	let no=1;
	const dataUser=JSON.parse(user);
	const dataLibrary=JSON.parse(library);
	const dataHistoryIssue=JSON.parse(history);
	const defaultStatus="Data Buku Kosong";
	const defaultHistoryStatus="Data History Kosong";
	const defaultLoading='Loading..';
	const [processIssueMessage,setProcessIssueMessage]=useState(defaultLoading);
	const [searchStatus,setSearchStatus]=useState(defaultStatus);
	const [historyStatus,setHistoryStatus]=useState(defaultHistoryStatus);
	const [libraryId,setLibraryId]=useState(dataLibrary && dataLibrary.length===1 ? dataLibrary[0].id:null);
	const [bookName,setBookName]=useState(null);
	const [dataSearch,setDataSearch]=useState([]);
	const [dataIssue,setDataIssue]=useState([]);
	const [dataHistory,setDataHistory]=useState(dataHistoryIssue);
	const [loadingHistory,setLoadingHistory]=useState(false);
	const [statusIssue,setIssueStatus]=useState({status:null,message:null});
	const [processIssue,setProcessIssue]=useState(false);
	const searchBook=(el)=>{
		clearTimeout(searchTiming.current);
		searchTiming.current=setTimeout(onSearch.bind(this),800);
	}

	const onSearch=async (library=libraryId,book=bookName)=>{
		setSearchStatus('Loading...')
		const response=await fetch(admin_url+'book/ajax/search',{
			method:'POST',
			headers: {'Content-Type': 'application/json'}, 
			body:JSON.stringify({
				'book' : book,
				'library' : library
			}),
		});
		const dataBook = await response.json();
		let arrResult=[]
		dataBook.map(book=>{
			const aa=dataIssue.filter(searBook=>searBook.id===book.id);
			if(aa && aa.length>0){
				
			}else{
				arrResult.push(book);
			}
		})
		setDataSearch(arrResult);
		setSearchStatus(defaultStatus);
	}

	const changeBook=(el)=>{
		setBookName(el.target.value);
	}

	const selectLibrary=(el)=>{
		const libId=el.target.value;
		setLibraryId(libId);
		if( bookName && bookName?.length>=3){
			clearTimeout(searchTiming.current);
			onSearch(libId)
		}
	}

	useEffect(()=>{
		bookRef.current.focus();
	},[])
	
	useEffect(()=>{
		if(libraryId && bookName){
			if(bookName.length<3){
				setSearchStatus(defaultStatus);
				setDataSearch([]);
				return;
			}
			searchBook();
		}
		if(!libraryId && bookName?.length>=3){
			setSearchStatus("Perpustakaan masih kosong");
			return;
		}else{
			setSearchStatus(defaultStatus);
				return;
		}
	},[bookName]);
	const selectSearch=(items)=>{
		const existsIssue=dataIssue.filter(item=>{
			return item.id===items.id;
		});
		
		if(existsIssue && existsIssue.length>0){
			return;
		}else{
			setDataIssue([...dataIssue,items]);
			// const newDataSearch=dataSearch.filter(item=>item.id!==items.id);
			setDataSearch([]);
			setBookName("")
			bookRef.current.focus();
		}
	}

	const removeItemIssue=(items)=>{
		const newDataIssue=dataIssue.filter(item=>item.id!==items.id);
		setDataIssue(newDataIssue);
	}

	const prosesIssue = async()=>{
		setIssueStatus({status:null,message:null})
		if((!dataIssue) || (dataIssue && dataIssue.length<1)){
			setIssueStatus({status:false,message:'Data issue masih kosong'});
			return;
		}
		if(!dataUser){
			setIssueStatus({status:false,message:'Data user masih kosong'});
			return;
		}
		if(!libraryId){
			setIssueStatus({status:false,message:'Data perpustakaan masih kosong'});
			return;
		}
		let dataBook=[];
		dataIssue.map(item=>{
			dataBook.push(item.id)
		})
		const data={
			library:libraryId,
			user:dataUser.user_id,
			book:dataBook
		}

		setProcessIssue(true);
		setProcessIssueMessage(defaultLoading);
		const response = await fetch(admin_url+'book/ajax/issue',{
			method:'POST',
			headers: {'Content-Type': 'application/json'}, 
			body:JSON.stringify(data),
		});
		const dataBookIssue = await response.json();
		if(dataBookIssue?.status){
			setDataIssue([]);
			setProcessIssueMessage('Mengambil data history peminjaman');
			await get_user_history();
			setProcessIssueMessage(defaultLoading);
			setIssueStatus(dataBookIssue);
			setProcessIssue(false);
			bookRef.current.focus();

			if (window.confirm(`${dataBookIssue?.message}, ingin input user baru ? `)){
				window.location =admin_url+'issue';
			}
		}else{
			setIssueStatus(dataBookIssue);
			setProcessIssue(false);
			bookRef.current.focus();
		}
		
	}

	const get_user_history=async()=>{
		setLoadingHistory(true);
		const response = await fetch(admin_url+`book/ajax/history/${dataUser?.user_id}`,{
			method:'GET',
			headers: {'Content-Type': 'application/json'}, 
		});
		const dataBookHistory = await response.json();
		setDataHistory(dataBookHistory);
		setLoadingHistory(false);
	}

	function capitalizeFirstLetter(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	return (
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between">
							<h4 class="mb-0">
								{title} <a href={admin_url+'issue'} class="btn btn-secondary btn-sm ml-2">Kembali</a>
							</h4>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href={admin_url}>Dashboard</a></li>
									<li class="breadcrumb-item"><a href={admin_url+'issue'}>Issue</a></li>
									<li class="breadcrumb-item active">{title}</li>
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
										<label>{dataUser?.user_nama}</label>
									</div>
									<div class="mb-2 col-md-4">
										<div class="text-muted">Alamat:</div>
										<label>{dataUser?.user_alamat}</label>
									</div>
									<div class="mb-2 col-md-4">
										<div class="text-muted">UID:</div>
										<label>{dataUser?.user_uid}</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								<h5>Cari Buku</h5>
								<hr/>
								{/* <form id="form-search-book" action="{{admin_url('book/ajax/search')}}" method="post"> */}
									{dataLibrary && dataLibrary.length>1 &&
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<select onChange={selectLibrary} name='library' id='select_perpustakaan' class="form-control">
													<option value="" disabled selected>Pilih Perpustakaan</option>
													{dataLibrary.map(item=>{
														return(
															<option value={item.id}>{capitalizeFirstLetter(item.library)}</option>
														)
													})}
												</select>
												</div>
											</div>
										</div>
									}
									{dataLibrary && dataLibrary.length===1 &&
										<div>
											<h5 class="mb-2">{capitalizeFirstLetter(dataLibrary[0]?.library)}</h5>
											<input type="hidden" id='select_perpustakaan' name='library' value={dataLibrary[0]?.id}/>
										</div>
									}
									{<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<input value={bookName} ref={bookRef} onChange={changeBook} autofocus name="book" type="text" class="form-control" id="book-form-issue" placeholder="Masukkan Barcode / Kode / Judul / ISBN buku untuk mencari " required autocomplete="off"/>
											</div>
										</div>
									</div>}
								{/* </form> */}
								<div class="row">
									<div class="col-md-12" id="div-book-search-result">
										{dataSearch && dataSearch.length<1 &&
											<div class="text-muted text-center">{searchStatus}</div>
										}
										{dataSearch && dataSearch.length>0 &&
										dataSearch.map(element=>{
											let booked=false
											const existsHistory=dataHistory.filter(item=>{
												return element.id===item?.book?.id && element.library===item.library;
											});
											if(existsHistory && existsHistory.length>0){
												booked=true;
											}
											return(
												<div>
													<div class="row">
														<div class="text-muted col-md-3">Judul Buku</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{capitalizeFirstLetter(element?.title)}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">Kode Buku</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{element?.code}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">Penulis</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{capitalizeFirstLetter(element?.author)}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">Kategori Buku</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{capitalizeFirstLetter(element?.category_name)}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">Rak Buku</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{element?.rak_name}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">ISBN</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label>{element?.isbn}</label></div>
													</div>
													<div class="row">
														<div class="text-muted col-md-3">Stok Buku</div>
														<div class="text-muted col-md-1">:</div>
														<div class="col-md-8"><label class={`text-${Number(element?.stok)>0 ? 'success':'danger'}`}>{element?.stok}</label></div>
													</div>
													{!booked && element?.stok>0 && !processIssue &&
														<button type="button" onClick={()=>selectSearch(element)} class="btn-choose-book btn btn-block btn-primary btn-sm">Pilih</button>
													}
													{booked &&
														<div class="text-center">
															<label class="text-danger">Buku masih status pinjam. kembalikan terlebih dahulu</label>
														</div>
													}
													<hr/>
												</div>
											)
										})
										}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card">
							<div class="card-body">
								<h5>Data Issue</h5>
								<hr/>
								{dataIssue && dataIssue.map(element=>{
									return(
										<div style={{position:'relative'}}>
											<div class="row">
												<div class="text-muted col-md-3">Judul Buku</div>
												<div class="text-muted col-md-1">:</div>
												<div class="col-md-8"><label>{capitalizeFirstLetter(element?.title)}</label></div>
											</div>
											<div class="row">
												<div class="text-muted col-md-3">Kode Buku</div>
												<div class="text-muted col-md-1">:</div>
												<div class="col-md-8"><label>{element?.code}</label></div>
											</div>
											{!processIssue && <button style={{position:'absolute',top:10,right:10}} onClick={()=>removeItemIssue(element)} class="btn btn-sm btn-danger">Hapus</button>}
											<hr/>
										</div>
									)
								})}
								{dataIssue && dataIssue.length>0 &&
									<button disabled={processIssue} onClick={prosesIssue} class="btn btn-block btn-success">Proses</button>
								}
								{processIssue && <div class="text-center mt-3"><label class="text-muted">{processIssueMessage}</label></div>}
								{statusIssue?.message && 
									<div class="row mt-3">
										<div class="col-md-12">
											<div class={`alert alert-${statusIssue.status ? 'success':'danger'}`}>{statusIssue.message}</div>
										</div>
									</div>
								}
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h5>History Peminjaman</h5>
								<hr/>
								{!loadingHistory && dataHistory?.length<1 && <div class="text-muted text-center">{historyStatus}</div>}
								{loadingHistory && <div class="text-muted text-center">Loding...</div>}
								{!loadingHistory && dataHistory && dataHistory.length>0 &&
								<div class="table-responsive">
									<table class="table table-striped table-hover table-bordered dt-responsive nowrap" style={{borderCollapse:'collapse',borderSpacing:0,width: '100%'}}>
										<thead>
											<tr>
												<th width="5%" class="text-center">No</th>
												<th class="text-center" width="30%">Buku</th>
												<th width="10%" class="text-center">Status</th>
												<th width="20%" class="text-center">Tanggal Pinjam</th>
												<th width="20%" class="text-center">Tanggal Expired</th>
												<th width="10%" class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											{dataHistory.sort((a, b) => b.expired - a.expired).map(element=>{
												return(
													<tr class={element?.expired ? 'bg-danger text-white':''}>
														<td class="text-center">{no++}</td>
														<td>
															<label>{element?.book ? capitalizeFirstLetter(element?.book?.title):'-'}</label><br/>
															<small>ISBN : {element?.book ? element?.book?.isbn:'-'}</small><br/>
															<small>Kode : {element?.book ? element?.book?.code:'-'}</small><br/>
															<small>Penulis : {element?.book ? element?.book?.author:'-'}</small><br/>
															<small><b>{element?.library_name ? element?.library_name:'-'}</b></small>
														</td>
														<td class="text-center">{element?.expired ? 'EXPIRED':element?.status?.toUpperCase()}</td>
														<td class="text-center">{element?.issue_date}</td>
														<td class="text-center">{element?.expired_date}</td>
														<td><a href={admin_url+`issue/${element?.id}/kembali`} class="btn btn-secondary">Pengembalian</a></td>
													</tr>
												)
											})}
										</tbody>
									</table>
								</div>}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	)
}

export default Issue
