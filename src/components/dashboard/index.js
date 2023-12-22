//import liraries
import React, { Component, useState,useEffect } from 'react';
import * as api from './services';
import Chart from "react-apexcharts";

// create a component
const Dashboard = (props) => {
    const {url,is_admin,year} = props;
    const [listLibrary,setListLibrary]=useState([]);
    const [selectedLibrary,setSelectedLibrary]=useState('');
    const [loadingLibrary,setLoadingLibrary]=useState(false);
    const [totalEbook,setTotalEbook]=useState(0);
    const [loadingTotalEbook,setLoadingTotalEbook]=useState(false);
    const [totalEbookWeek,setTotalEbookWeek]=useState(0);
    const [loadingEbookWeek,setLoadingEbookWeek]=useState(false);
    const [totalBook,setTotalBook]=useState(0);
    const [loadingBook,setLoadingBook]=useState(false);
    const [totalBookWeek,setTotalBookWeek]=useState(0);
    const [loadingBookWeek,setLoadingBookWeek]=useState(false);
    const [totalPinjam,setTotalPinjam]=useState(0);
    const [loadingPinjam,setLoadingPinjam]=useState(false);
    const [totalPinjamWeek,setTotalPinjamWeek]=useState(0);
    const [loadingPinjamWeek,setLoadingPinjamWeek]=useState(false);
    const [totalHilang,setTotalHilang]=useState(0);
    const [loadingHilang,setLoadingHilang]=useState(false);
    const [totalHilangWeek,setTotalHilangWeek]=useState(0);
    const [loadingHilangWeek,setLoadingHilangWeek]=useState(false);
    const [totalJudul,setTotalJudul]=useState(0);
    const [loadingJudul,setLoadingJudul]=useState(false);
    const [totalJudulItem,setTotalJudulItem]=useState(0);
    const [loadingJudulItem,setLoadingJudulItem]=useState(false);
    const [totalKoleksi,setTotalKoleksi]=useState(0);
    const [loadingKoleksi,setLoadingKoleksi]=useState(false);
    const [totalItemPinjam,setTotalItemPinjam]=useState(0);
    const [loadingItemPinjam,setLoadingItemPinjam]=useState(false);
    const [totalOverdue,setTotalOverdue]=useState(0);
    const [loadingOverdue,setLoadingOverdue]=useState(false);
    const [totalMemberPinjam,setTotalMemberPinjam]=useState(0);
    const [loadingMemberPinjam,setLoadingMemberPinjam]=useState(false);
    const [totalMemberBelumPinjam,setTotalMemberBelumPinjam]=useState(0);
    const [loadingMemberBelumPinjam,setLoadingMemberBelumPinjam]=useState(false);
    const [loadingTopMember,setLoadingTopMember]=useState(false);
    const [loadingTopBook,setLoadingTopBook]=useState(false);
    const [listTopMember,setListTopMember]=useState([]);
    const [listTopBook,setListTopBook]=useState([]);
    const [startTopMember,setStartTopMember]=useState('');
    const [selectedYear,setSelectedYear]=useState(year);
    const [listYear,setListYear]=useState([]);
    const [endTopMember,setEndTopMember]=useState('');
    const [loadingStatistik,setLoadingStatistik]=useState(false);
    const [loadingDataPengunjung,setLoadingDataPengunjung]=useState(false);
    const [listDataPengunjung,setListDataPengunjung]=useState([]);
    const [totalListDataPengunjung,setTotalListDataPengunjung]=useState(null);
    const [selectedPengunjungYear,setSelectedPengunjungYear]=useState(year);
    const [options,setOptions]=useState({
        chart: {
          type: "line"
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu','Sep','Okt','Nov','Des' ]
        }});
    const [series,setSeries]=useState([]);

    let noTopBook=1;
    let noTopMember=1;

    const loadEbook=async(library=selectedLibrary)=>{
        setLoadingTotalEbook(true);
        const total=await api.loadEbook(url,library);
        setLoadingTotalEbook(false);
        setTotalEbook(total);
    }

    const loadEbookWeek=async(library=selectedLibrary)=>{
        setLoadingEbookWeek(true);
        const total=await api.loadEbookWeek(url,library);
        setLoadingEbookWeek(false);
        setTotalEbookWeek(total);
    }

    const loadBook=async(library=selectedLibrary)=>{
        setLoadingBook(true);
        const total=await api.loadBook(url,library);
        setLoadingBook(false);
        setTotalBook(total);
    }

    const loadBookWeek=async(library=selectedLibrary)=>{
        setLoadingBookWeek(true);
        const total=await api.loadBookWeek(url,library);
        setLoadingBookWeek(false);
        setTotalBookWeek(total);
    }

    const loadPinjam=async(library=selectedLibrary)=>{
        setLoadingPinjam(true);
        const total=await api.loadPinjam(url,library);
        setLoadingPinjam(false);
        setTotalPinjam(total);
    }

    const loadPinjamWeek=async(library=selectedLibrary)=>{
        setLoadingPinjamWeek(true);
        const total=await api.loadPinjamWeek(url,library);
        setLoadingPinjamWeek(false);
        setTotalPinjamWeek(total);
    }

    const loadHilang=async(library=selectedLibrary)=>{
        setLoadingHilang(true);
        const total=await api.loadHilang(url,library);
        setLoadingHilang(false);
        setTotalHilang(total);
    }

    const loadHilangWeek=async(library=selectedLibrary)=>{
        setLoadingHilangWeek(true);
        const total=await api.loadHilangWeek(url,library);
        setLoadingHilangWeek(false);
        setTotalHilangWeek(total);
    }

    const loadJudul=async(library=selectedLibrary)=>{
        setLoadingJudul(true);
        const total=await api.loadJudul(url,library);
        setLoadingJudul(false);
        setTotalJudul(total);
    }

    const loadJudulItem=async(library=selectedLibrary)=>{
        setLoadingJudulItem(true);
        const total=await api.loadJudulItem(url,library);
        setLoadingJudulItem(false);
        setTotalJudulItem(total);
    }

    const loadKoleksi=async(library=selectedLibrary)=>{
        setLoadingKoleksi(true);
        const total=await api.loadKoleksi(url,library);
        setLoadingKoleksi(false);
        setTotalKoleksi(total);
    }

    const loadItemPinjam=async(library=selectedLibrary)=>{
        setLoadingItemPinjam(true);
        const total=await api.loadItemPinjam(url,library);
        setLoadingItemPinjam(false);
        setTotalItemPinjam(total);
    }

    const loadItemOverdue=async(library=selectedLibrary)=>{
        setLoadingOverdue(true);
        const total=await api.loadItemOverdue(url,library);
        setLoadingOverdue(false);
        setTotalOverdue(total);
    }

    const loadMemberPinjam=async(library=selectedLibrary)=>{
        setLoadingMemberPinjam(true);
        const total=await api.loadMemberPinjam(url,library);
        setLoadingMemberPinjam(false);
        setTotalMemberPinjam(total);
    }

    const loadMemberTidakPinjam=async(library=selectedLibrary)=>{
        setLoadingMemberBelumPinjam(true);
        const total=await api.loadMemberTidakPinjam(url,library);
        setLoadingMemberBelumPinjam(false);
        setTotalMemberBelumPinjam(total);
    }

    const loadTopBook=async(library=selectedLibrary)=>{
        setLoadingTopBook(true);
        const data=await api.loadTopBook(url,library);
        setLoadingTopBook(false);
        setListTopBook(data ?? [])
    }

    const loadStatistik=async(library=selectedLibrary,years=selectedYear)=>{
        setLoadingStatistik(true);
        const data=await api.loadStatistik(url,library,years);
        setLoadingStatistik(false);
        setSeries([
            {
            name: "Pengunjung",
            data: data?.pengunjung
            },
            {
            name: "Peminjaman",
            data: data?.peminjaman
            }
        ])
    }

    const loadDataPengunjung=async(library=selectedLibrary,years=selectedYear)=>{
        setLoadingDataPengunjung(true);
        const data=await api.loadDataPengunjung(url,library,years);
        setLoadingDataPengunjung(false);
        const listTamu=[...data?.internal,data?.external];
        let totalPengunjungArr={
            1:0,
            2:0,
            3:0,
            4:0,
            5:0,
            6:0,
            7:0,
            8:0,
            9:0,
            10:0,
            11:0,
            12:0,
        }
        listTamu.map(item=>{
            totalPengunjungArr[1]=totalPengunjungArr[1]+item[1]
            totalPengunjungArr[2]=totalPengunjungArr[2]+item[2]
            totalPengunjungArr[3]=totalPengunjungArr[3]+item[3]
            totalPengunjungArr[4]=totalPengunjungArr[4]+item[4]
            totalPengunjungArr[5]=totalPengunjungArr[5]+item[5]
            totalPengunjungArr[6]=totalPengunjungArr[6]+item[6]
            totalPengunjungArr[7]=totalPengunjungArr[7]+item[7]
            totalPengunjungArr[8]=totalPengunjungArr[8]+item[8]
            totalPengunjungArr[9]=totalPengunjungArr[9]+item[9]
            totalPengunjungArr[10]=totalPengunjungArr[10]+item[10]
            totalPengunjungArr[11]=totalPengunjungArr[11]+item[11]
            totalPengunjungArr[12]=totalPengunjungArr[12]+item[12]
        })
        setTotalListDataPengunjung(totalPengunjungArr);
        setListDataPengunjung(listTamu);
    }

    const loadTopMember=async(library=selectedLibrary,startDate=startTopMember,endDate=endTopMember)=>{
        setLoadingTopMember(true);
        const data=await api.loadTopMember(url,library,startDate,endDate);
        setLoadingTopMember(false);
        setListTopMember(data ?? [])
    }

    const loadLibrary=async()=>{
        setLoadingLibrary(true);
        const data=await api.loadLibrary(url);
        setLoadingLibrary(false);
        setListLibrary(data ?? [])
    }

    const initLoad=(library=selectedLibrary)=>{
        loadEbook(library);
        loadEbookWeek(library);
        loadBook(library);
        loadBookWeek(library);
        loadPinjam(library);
        loadPinjamWeek(library);
        loadHilang(library);
        loadHilangWeek(library);
        loadJudul(library);
        loadJudulItem(library);
        loadKoleksi(library);
        loadItemPinjam(library);
        loadItemOverdue(library);
        loadMemberPinjam(library);
        loadMemberTidakPinjam(library);
        loadTopBook(library);
        loadTopMember(library);
        loadStatistik(library);
        loadDataPengunjung(library)
    }

    useEffect(()=>{
        initLoad();
        loadLibrary();
    },[]);

    useEffect(()=>{
        let listArr=[];
        for (let index = 2020; index <= year; index++) {
            listArr=[...listArr,index]
        };
        setListYear(listArr);
    },[])

    const viewTopMember=()=>{
        if(!startTopMember && !endTopMember){
            return;
        }
        loadTopMember();
    }

    const libraryChange=(el)=>{
        if(is_admin && is_admin==='1'){
            const val=el.target.value;
            setSelectedLibrary(val);
            initLoad(val)
        }
        return;
    }

    const yearChange=(el)=>{
        const val=el.target.value;
        setSelectedYear(val);
        loadStatistik(selectedLibrary,val);
    }

    const yearPengunjungChange=(el)=>{
        const val=el.target.value;
        setSelectedPengunjungYear(val);
        loadDataPengunjung(selectedLibrary,val);
    }

    return (
        <>
        <div class="page-content">
            <div class="container-fluid">

                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Dashboard</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        {is_admin && is_admin=='1' &&
                            <div class="form-inline float-md-right mb-3">
                                <div class="search-box ml-2">
                                    <div class="position-relative">
                                        <select class="form-control rounded border-0" defaultValue={selectedLibrary} value={selectedLibrary} onChange={libraryChange}>
                                            {!loadingLibrary && <option value="" selected>All Library</option>}
                                            {listLibrary && !loadingLibrary && listLibrary?.map((item,index)=>{
                                                return(
                                                     <option key={index.toString()} value={item?.id}>{item?.library}</option>
                                                )
                                            })}
                                            {loadingLibrary && <option value="" selected disabled>Loading...</option>}
                                        </select>
                                        <i class="mdi mdi-filter-menu-outline search-icon"></i>
                                    </div>
                                </div>
                                
                            </div>
                        }
                    </div>
                </div>
        <div class="row">
			<div class="col-md-6 col-xl-3">
				<div class="card">
					<div class="card-body">
						<div class="float-right" style={{position:'relative'}}>
							<div class="avatar-sm">
								<span class="avatar-title bg-soft-success text-success font-size-16 align-items-center" style={{borderRadius:'5px'}}>
									<i class="uil uil-book-alt text-success h2 mt-1"></i>
								</span>
							</div>
							<div class="resize-triggers"><div class="expand-trigger"><div style={{width:'71px',height:'41px'}}></div></div><div class="contract-trigger"></div></div></div>
						<div>
							<h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingTotalEbook ? 'Loading..':totalEbook}</span></h4>
							<p class="text-muted mb-0">Jumlah E-Book</p>
						</div>
						<p class="text-muted mt-3 mb-0"><span class={`text-${totalEbookWeek>0 ? 'success':'danger'} mr-1`}><i class={`mdi mdi-arrow-${totalEbookWeek>0 ? 'up':'down'}-bold`}></i>{loadingEbookWeek ? 'Loading..':totalEbookWeek} Buku</span> since last week
						</p>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-xl-3">
				<div class="card">
					<div class="card-body">
						<div class="float-right" style={{position:'relative'}}>
							<div class="avatar-sm">
								<span class="avatar-title bg-soft-info text-success font-size-16 align-items-center" style={{borderRadius:'5px'}}>
									<i class="uil uil-book-alt text-info h2 mt-1"></i>
								</span>
							</div>
							<div class="resize-triggers"><div class="expand-trigger"><div style={{width:'71px',height:'41px'}}></div></div><div class="contract-trigger"></div></div></div>
						<div>
							<h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingBook ? 'Loading..':totalBook}</span></h4>
							<p class="text-muted mb-0">Jumlah Buku</p>
						</div>
						<p class="text-muted mt-3 mb-0"><span class={`text-${totalBookWeek>0 ? 'success':'danger'} mr-1`}><i class={`mdi mdi-arrow-${totalBookWeek>0 ? 'up':'down'}-bold`}></i>{loadingBookWeek ? 'Loading..':totalBookWeek} Buku</span> since last week
						</p>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-xl-3">
				<div class="card">
					<div class="card-body">
						<div class="float-right" style={{position:'relative'}}>
							<div class="avatar-sm">
								<span class="avatar-title bg-soft-warning text-success font-size-16 align-items-center" style={{borderRadius:'5px'}}>
									<i class="uil uil-users-alt text-warning h2 mt-1"></i>
								</span>
							</div>
							<div class="resize-triggers"><div class="expand-trigger"><div style={{width:'71px',height:'41px'}}></div></div><div class="contract-trigger"></div></div></div>
						<div>
							<h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingPinjam ? 'Loading..':totalPinjam}</span></h4>
							<p class="text-muted mb-0">Peminjaman</p>
						</div>
						<p class="text-muted mt-3 mb-0"><span class={`text-${totalPinjamWeek>0 ? 'success':'danger'} mr-1`}><i class={`mdi mdi-arrow-${totalPinjamWeek>0 ? 'up':'down'}-bold`}></i>{loadingPinjamWeek ? 'Loading..':totalPinjamWeek} buku</span> since last week
						</p>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-xl-3">
				<div class="card">
					<div class="card-body">
						<div class="float-right" style={{position:'relative'}}>
							<div class="avatar-sm">
								<span class="avatar-title bg-soft-danger text-danger font-size-16 align-items-center" style={{borderRadius:'5px'}}>
									<i class="uil uil-book-alt text-danger h2 mt-1"></i>
								</span>
							</div>
							<div class="resize-triggers"><div class="expand-trigger"><div style={{width:'71px',height:'41px'}}></div></div><div class="contract-trigger"></div></div></div>
						<div>
							<h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingHilang ? 'Loading..':totalHilang}</span></h4>
							<p class="text-muted mb-0">Hilang/Rusak</p>
						</div>
						<p class="text-muted mt-3 mb-0"><span class={`text-${totalHilangWeek>0 ? 'success':'danger'} mr-1`}><i class={`mdi mdi-arrow-${totalHilangWeek>0 ? 'up':'down'}-bold`}></i>{loadingHilangWeek ? 'Loading..':totalHilangWeek} Buku</span> since last week
						</p>
					</div>
				</div>
			</div>
			
		</div>
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-purple text-purple" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-star text-purple h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Judul</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingJudul ? 'Loading..':totalJudul}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-primary text-primary" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-user-tie text-primary h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Judul Dengan Item</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingJudulItem ? 'Loading..':totalJudulItem}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-warning text-warning" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-box text-warning h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Jenis Koleksi</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingKoleksi ? 'Loading..':totalKoleksi}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-warning text-warning" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-hourglass-half text-warning h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Item Yang Masih Dipinjam</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingItemPinjam ? 'Loading..':totalItemPinjam}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-danger text-danger" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-exclamation text-danger h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Peminjaman Overdued</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingOverdue ? 'Loading..':totalOverdue}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-success text-success" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-user-check text-success h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Member Yang Pernah Meminjam</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingMemberPinjam ? 'Loading..':totalMemberPinjam}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body" type="button">
                        <div class="float-right" style={{position:'relative'}}>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-secondary text-secondary" style={{borderRadius:'5px'}}>
                                    <i class="fas fa-user-clock text-secondary h4 mt-2"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Member Yang Belum Pernah Meminjam</p>
                            <h4 class="mb-1 mt-1"><span data-plugin="counterup">{loadingMemberBelumPinjam ? 'Loading..':totalMemberBelumPinjam}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card" style={{minHeight:'473.812px'}}>
                    <div class="card-body">
                        <h4 class="card-title mb-4">Top 10 Anggota Paling Aktif</h4>
                        
                        <div class="row align-items-baseline">
                            <div class="col-auto">
                                <div class="form-group">
                                    <input onChange={(el)=>{
                                        const val=el.target.value
                                        setStartTopMember(val);
                                    }} type="date" class=" form-control" name="start"/>
                                </div>
                            </div>
                            To
                            <div class="col-auto">
                                <div class="form-group">
                                    <input onChange={(el)=>{
                                        const val=el.target.value
                                        setEndTopMember(val);
                                    }} type="date" class=" form-control" name="end"/>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button onClick={viewTopMember} class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div data-simplebar="" style={{maxHeight:'360px'}}>
                            <div class="table-responsive">
                                {!loadingTopMember && <table class="table table-borderless table-centered table-nowrap">
                                    <tbody>
                                        {listTopMember?.map((item,index)=>{
                                            return(
                                                <tr key={index.toString()}>
                                                    <td>{noTopMember++}</td>
                                                    <td>
                                                        <h6 class="font-size-15 mb-1 fw-normal">{item?.detail?.user_nama}</h6>
                                                        <p class="text-muted font-size-13 mb-0"><i class="uil-home-alt"></i> Kelas: {item?.detail?.detail_kelas?.kelasdetail_nama}</p>
                                                    </td>
                                                    <td>{item?.total}</td>
                                                </tr>
                                            )
                                        })}
                                        
                                    </tbody>
                                </table>}
                                {loadingTopMember && 
                                <div className='text-center text-muted'>Loading..</div>
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card" style={{minHeight:'473.812px'}}>
                    <div class="card-body">
                        <h4 class="card-title mb-4">Top 10 Judul Paling Populer</h4>

                        <div data-simplebar="" style={{maxHeight:'388px'}}>
                            <div class="table-responsive">
                                {!loadingTopBook && <table class="table table-borderless table-centered table-nowrap">
                                    <tbody>
                                        {listTopBook.map((item,index)=>{
                                            return(
                                                <tr key={index.toString()}>
                                                    <td>{noTopBook++}</td>
                                                    <td>
                                                        <h6 class="font-size-15 mb-1 fw-normal">{item?.detail?.title}</h6>
                                                        <p class="text-muted font-size-13 mb-0"><i class="uil-th"></i> Kategori: {item?.detail?.category_name}</p>
                                                    </td>
                                                    <td>{item?.total}</td>
                                                </tr>
                                            )
                                        })}
                                    </tbody>
                                </table>}
                                {loadingTopBook && 
                                <div className='text-center text-muted'>Loading..</div>
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div className='row'>
                            <div className='col-md-12'>
                                <h4 class="card-title mb-4">Statistik Peminjaman dan Pengunjung</h4>
                            </div>
                            <div className='col-md-12 text-left'>
                                <div class="row align-items-baseline">
                                    <div class="col-auto">
                                        <div class="form-group">
                                            <select class="form-control" defaultValue={selectedYear} value={selectedYear} onChange={yearChange}>
                                                {listYear?.map((item,index)=>{
                                                    return(
                                                        <option key={index.toString()} value={item}>{item}</option>
                                                    )
                                                })}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!loadingStatistik && <div>
                            <div className="mixed-chart">
                            <Chart
                                options={options}
                                series={series}
                                type="line"
                                width="100%"
                            />
                            </div>
                        </div>}
                        {loadingStatistik && <div className='text-center text-muted'>Loading...</div>}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div className='row'>
                            <div className='col-md-12'>
                                <h4 class="card-title mb-4">Data Pengunjung</h4>
                            </div>
                            <div className='col-md-12 text-left'>
                                <div class="row align-items-baseline">
                                    <div class="col-auto">
                                        <div class="form-group">
                                            <select class="form-control" defaultValue={selectedPengunjungYear} value={selectedPengunjungYear} onChange={yearPengunjungChange}>
                                                {listYear?.map((item,index)=>{
                                                    return(
                                                        <option key={index.toString()} value={item}>{item}</option>
                                                    )
                                                })}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!loadingDataPengunjung && <div>
                            <div class="table-responsive">
							<table class="table table-striped table-hover table-bordered dt-responsive nowrap">
								<thead>
								<tr>
									<th width="15%">Jenis Anggota</th>
									<th>Jan</th>
									<th>Feb</th>
									<th>Mar</th>
									<th>Apr</th>
									<th>Mei</th>
									<th>Jun</th>
									<th>Jul</th>
									<th>Agu</th>
									<th>Sep</th>
									<th>Okt</th>
									<th>Nov</th>
									<th>Des</th>
									
								</tr>
								</thead>
								<tbody>
                                    {listDataPengunjung?.map((item,index)=>{
                                        return(
                                            <tr>
                                                <td>{item?.role_name}</td>
                                                <td>{item?.[1] ?? 0}</td>
                                                <td>{item?.[2] ?? 0}</td>
                                                <td>{item?.[3] ?? 0}</td>
                                                <td>{item?.[4] ?? 0}</td>
                                                <td>{item?.[5] ?? 0}</td>
                                                <td>{item?.[6] ?? 0}</td>
                                                <td>{item?.[7] ?? 0}</td>
                                                <td>{item?.[8] ?? 0}</td>
                                                <td>{item?.[9] ?? 0}</td>
                                                <td>{item?.[10] ?? 0}</td>
                                                <td>{item?.[11] ?? 0}</td>
                                                <td>{item?.[12] ?? 0}</td>
                                            </tr>
                                        )
                                    })}
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[1] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[2] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[3] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[4] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[5] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[6] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[7] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[8] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[9] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[10] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[11] ?? 0}</strong></td>
                                        <td><strong>{totalListDataPengunjung?.[12] ?? 0}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>}
                        {loadingDataPengunjung && <div className='text-center text-muted'>Loading...</div>}
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </>
    );
};

//make this component available to the app
export default Dashboard;
