export const loadEbook=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_ebook?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}

export const loadEbookWeek=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_ebook_week?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadBook=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_book?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadBookWeek=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_book_week?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadPinjam=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_pinjam?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadPinjamWeek=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_pinjam_week?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadHilang=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_keluar?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadHilangWeek=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_keluar_week?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadJudul=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_judul?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadJudulItem=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_judul_item?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadKoleksi=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_buku_koleksi?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadItemPinjam=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_item_pinjam?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadItemOverdue=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_item_overdue?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadMemberPinjam=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_member_issue?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadMemberTidakPinjam=async(url,library)=>{
    const response=await fetch(`${url}dashboard/total_member_not_issue?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? 0;
}
export const loadTopBook=async(url,library)=>{
    const response=await fetch(`${url}dashboard/top_book?library=${library}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? [];
}
export const loadTopMember=async(url,library,startDate,endDate)=>{
    const response=await fetch(`${url}dashboard/top_member?library=${library}&start=${startDate}&end=${endDate}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? [];
}
export const loadLibrary=async(url)=>{
    const response=await fetch(`${url}dashboard/dashobard_library`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? [];
}
export const loadStatistik=async(url,library,years)=>{
    const response=await fetch(`${url}dashboard/statistik_dashboard?library=${library}&year=${years}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? [];
}
export const loadDataPengunjung=async(url,library,years)=>{
    const response=await fetch(`${url}dashboard/data_pengunjung_dashboard?library=${library}&year=${years}`,{
        method:'GET',
        headers: {'Content-Type': 'application/json'}, 
    });
    const data = await response?.json();
    return data?.data ?? [];
}