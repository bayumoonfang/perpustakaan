//import liraries
import React, { Component, useEffect, useState } from 'react';

// create a component
const Catalog = (props) => {
    console.log(props);
    const{library,url}=props;
    const [loadingKategori,setLoadingKategori]=useState(false)
    const [loadingBooks,setLoadingBooks]=useState(false)
    const [selectedKategori,setSelectedKategori]=useState('');
    const [selectedBook,setSelectedBook]=useState(null);
    const [listBook,setListBook] = useState([]);
    const [totalListBook,setTotalListBook] = useState(0);
    const [listKategori,setListKategori] = useState([]);
    const [selectedDetail,setSelectedDetail]=useState(null);
    const [showMore,setShowMore] = useState(false);
    const [searchText,setSearchText] = useState('');
    const [searchInput,setSearchInput] = useState('');
    const perPage=6;
    const [page,setPage]=useState(1);
    const getListBook=async(category=selectedKategori,search=searchText,pages=page)=>{
        const apiUrl=url+'api/general/books';
        if(pages<2){
            setListBook([]);
        }
        setLoadingBooks(true);
        const response=await fetch(apiUrl+'?library='+library+'&category='+category+'&search='+search+'&page='+pages+'&limit='+perPage,{
			method:'GET',
			headers: {'Content-Type': 'application/json'}, 
		});
		const dataBook = await response.json();
        setLoadingBooks(false);
        const currentList = [...listBook]
        if(pages<2){
            setListBook(dataBook?.payload?.data ??[])
        }else{
            const newList=dataBook?.payload?.data ??[];
            currentList.push(...newList)
            setListBook(currentList)
        }
		setPage(pages);
        setTotalListBook(dataBook?.payload?.total ??0)
    }

    const getListCategory=async(search=searchText)=>{
        const apiUrl=url+'api/general/categories?library='+library+'&limit=1000000&page=0&search_book='+search;
        setListKategori([]);
        setLoadingKategori(true);
        const response=await fetch(apiUrl,{
			method:'GET',
			headers: {'Content-Type': 'application/json'}, 
		});
		const dataKategori = await response.json();
        setLoadingKategori(false);
		setListKategori(dataKategori?.payload?.data ??[])
    }

    const selectCategory=async(item)=>{
        setSelectedKategori(item);
        getListBook(item,searchText,1);
    }

    const loadMore=()=>{
        const newPage=page+1;
        getListBook(selectedKategori,searchText,newPage);
    }

    const cariBuku=()=>{
        if(searchInput===''){
            return;
        }
        setSearchText(searchInput)
        setSelectedKategori('');
        getListBook('',searchInput,1)
        getListCategory(searchInput)
    }

    const reset=()=>{
        setSelectedKategori('');
        setSearchInput('');
        setSearchText('');
        getListBook('','',1);
        getListCategory('');
    }
    
    const selectBook=(item)=>{
        setSelectedBook(item)
    }

    useEffect(()=>{
        getListBook();
        getListCategory();
    },[]);

    useEffect(()=>{
        if(listBook.length<totalListBook){
            setShowMore(true);
        }else{
            setShowMore(false);
        }
    },[listBook,totalListBook])

    if(selectedBook){
        return(
            <>
            <div class="row mb-3">
                <div class="d-grid gap-2 d-md-block">
                <button class="btn btn-sm btn-primary" onClick={()=>setSelectedBook(null)}>Kembali</button>
                </div>
            </div>
             <div class="row mb-4">
                <div class="col-md-3">
                    <img src={selectedBook?.cover} onError={({ currentTarget })=>{
                        currentTarget.onerror = null; // prevents looping
                        currentTarget.src="http://localhost:8888/elibrary/assets/read.svg";
                    }} 
                    class="card-img-top " alt="..."/>
                </div>
                <div class="col-md-8">
                   <ul style={{listStyleType:'none',paddingLeft:0}}>
                        <li><h5>{selectedBook?.title}</h5></li>
                        <li>Kode: <b>{selectedBook?.code??'-'}</b></li>
                        <li>ISBN: <b>{selectedBook?.isbn??'-'}</b></li>
                        <li>Pengarang: <b>{selectedBook?.author??'-'}</b></li>
                        <li>Publisher: <b>{selectedBook?.publisher??'-'}</b> Tahun: <b>{selectedBook?.year??'-'}</b></li>
                        <li>Rak: <b>{selectedBook?.rak_name??'-'}</b></li>
                        <li>Stok: <span class={`badge bg-primary rounded-pill`}>{selectedBook?.stok}</span></li>
                        <li><i class="text-secondary">{selectedBook?.library?.library_name}</i></li>
                    </ul>
                </div>
             </div>
            </>
        )
    }
    return (
        <>
        <div class="row mb-3">
            <div class="input-group">
                <input onChange={(el)=>setSearchInput(el.target.value)} value={searchInput} type="text" class="form-control" placeholder="Masukkan Kode / Judul / ISBN buku untuk mencari"/>
                <button disabled={searchInput!=='' ? false:true} class="btn btn-primary" onClick={cariBuku} type="button">Cari Buku</button>
                <button class="btn btn-success" onClick={reset} type="button">Reset</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 border-end">
                <h6>Kategori</h6>
                <hr/>
                {listKategori && listKategori.length>0 &&
                <div class="list-group">
                    {listKategori.map((item,index)=>{
                        return(
                            <button onClick={()=>{selectCategory(item?.id)}} class={`list-group-item list-group-item-action d-flex justify-content-between align-items-start ${selectedKategori && selectedKategori===item?.id ? 'active':''}`} key={index.toString()}>
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{item?.category}</div>
                                </div>
                                <span class={`badge bg-${selectedKategori && selectedKategori===item?.id ? 'secondary':'primary'} rounded-pill`}>{item?.jml_buku}</span>
                            </button>
                        )
                    })}
                </div>}
                {loadingKategori && <div class="text-center">
                    <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                </div>}
               
            </div>
            <div class="col-md-9">
                <h6>Daftar Buku</h6>
                <hr/>
                {searchText && <p><em><small>Pencarian buku untuk <strong>"{searchText}"</strong></small></em></p>}
               {listBook &&listBook.length>0 &&
               <div class="row">
                 {listBook.map((item,index)=>{
                    let itemCover='http://localhost:8888/elibrary/assets/read.svg';
                    if(item?.cover!=='' || item?.cover!==null){
                        itemCover = item?.cover;
                    }
                        return(
                        <div class="col-md-4 mb-3">
                            <div class="card" style={{cursor:'pointer'}} onClick={()=>selectBook(item)}>
                                <img src={itemCover} onError={({ currentTarget })=>{
                                    currentTarget.onerror = null; // prevents looping
                                    currentTarget.src="http://localhost:8888/elibrary/assets/read.svg";
                                }} 
                                class="card-img-top " alt="..." style={{objectFit:'cover',height:249}}/>
                                <div class="card-body">
                                    <h6 class="card-title">{item?.title}</h6>
                                    <ul class="list-unstyled">
                                        <li>- <em>{item?.category?.name}</em></li>
                                        <li>- Author : <strong>{item?.author}</strong></li>
                                        <li>- Rak : <strong>{item?.rak_name}</strong></li>
                                        <li>- Stok : <span class={`badge bg-primary rounded-pill`}>{item?.stok}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        )})}
                </div>}
                {!loadingBooks && listBook.length<1 &&
                <div class="alert alert-danger text-center" role="alert">
                    Tidak ada daftar buku yang ditampilkan
                </div>}
                {loadingBooks && <div class="text-center">
                    <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                </div>}
                {!loadingBooks && showMore && <div class="text-center">
                    <button class="btn btn-block btn-primary" onClick={loadMore}>load more</button>
                </div>}
            </div>
        </div>
        </>
    );
};



//make this component available to the app
export default Catalog;
