//import liraries
import React, { Component, useEffect, useState } from 'react';
import Modal from 'react-bootstrap/Modal';
import { Button } from 'react-bootstrap';
// create a component
const Guest = (props) => {
    const {library,url}=props;
    const [showModal,setShowModal]=useState(false);
    const [loadingSubmit,setLoadingSubmit] = useState(false);
    const [nama,setNama]=useState(null);
    const [institusi,setInstitusi]=useState(null);
    const [tujuan,setTujuan]=useState(null);
    const [invalid,setInvalid]=useState(null);
    const [successSave,setSuccessSave]=useState(false);
    const [successMessage,setSuccessMessage]=useState(null);

    const setInvalidInput=(input,status=true)=>{
		setInvalid(prevData=>({...prevData,[input]:status}))
	};

    const namaChange=(el)=>{
        setNama(el.target.value);
        if(invalid?.nama){
			setInvalidInput('nama',false)
		}
    }
    const institusiChange=(el)=>{
        setInstitusi(el.target.value);
        if(invalid?.institusi){
			setInvalidInput('institusi',false)
		}
    }
    const tujuanChange=(el)=>{
        setTujuan(el.target.value);
        if(invalid?.tujuan){
			setInvalidInput('tujuan',false)
		}
    }

    const validateInput=()=>{
		let err=0;
		if(!nama){
			setInvalidInput('nama');
			err++;
		}
		if(!institusi){
			setInvalidInput('institusi');
			err++;
		}
		
		if(!tujuan){
			setInvalidInput('tujuan');
			err++;
		}
		
		if(err>0){
			return false;
		}else{
			return true;
		}
	}

    const submitForm=async()=>{
        if(!validateInput()){
			return;
		}
        setLoadingSubmit(true);
        const response=await fetch(url+'post-guest',{
			method:'POST',
			headers: {'Content-Type': 'application/json'}, 
			body:JSON.stringify({
				'nama' : nama,
				'institusi' : institusi,
				'tujuan' : tujuan,
				'library' : library,
			}),
		});
		const dataBook = await response.json();
        setLoadingSubmit(false);
        if(dataBook?.status){
            setSuccessSave(true);
            setSuccessMessage(dataBook?.message);
            resetForm();
        }
    }

    useEffect(()=>{
        if(!showModal){
            resetForm();
            setSuccessMessage(null);
            setSuccessSave(false);
        }
    },[showModal]);

    const resetForm=()=>{
        setNama(null);
        setInstitusi(null);
        setTujuan(null);
        setInvalid(null);
        setLoadingSubmit(false);
    }

    return (
        <>
            <button onClick={()=>setShowModal(true)} style={{width:'100%',backgroundColor:'#6698FF'}} class="btn-block btn rounded-pill pxp-sign-hero-form-cta">Guest</button>
            <Modal 
            size="md"
            backdrop={loadingSubmit ? false : true}
            show={showModal} 
            onHide={()=>setShowModal(false)}>
                <Modal.Header>
                    <Modal.Title>Guest Form</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {!loadingSubmit && successSave ? 
                        <>
                        <div class="alert alert-success text-center" role="alert">
                           {successMessage}
                        </div>
                        </> 
                        :
                        <>
                        <div class="mb-3">
                            <label>Nama</label>
                            <input name="name" required type="text" class={`form-control ${invalid && invalid?.nama ? 'is-invalid':''}`} value={nama} onChange={(el)=>namaChange(el)}/>
                            <div class="invalid-feedback">
                                Nama Wajib Diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Institusi</label>
                            <input name="institusi" required type="text" class={`form-control ${invalid && invalid?.institusi ? 'is-invalid':''}`} value={institusi} onChange={(el)=>institusiChange(el)}/>
                            <div class="invalid-feedback">
                                Institusi Wajib Diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Tujuan</label>
                            <textarea class={`form-control ${invalid && invalid?.tujuan ? 'is-invalid':''}`} required placeholder='Tujuan' name="tujuan" rows={3} value={tujuan} onChange={(el)=>tujuanChange(el)}></textarea>
                            <div class="invalid-feedback">
                                Tujuan Wajib Diisi
                            </div>
                        </div>
                        <button disabled={loadingSubmit} onClick={submitForm} class="mb-2 btn btn-success" style={{width:'100%'}}>{loadingSubmit ? 'Loading...':'Submit'}</button>
                        </>
                        }
                        <hr/>
                        <button disabled={loadingSubmit} onClick={()=>setShowModal(false)} class="btn btn-secondary" style={{width:'100%'}}>Tutup</button>
                </Modal.Body>
                
            </Modal>
        </>
        
    );
};


//make this component available to the app
export default Guest;
