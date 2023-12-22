//import liraries
import React, { Component, useState } from 'react';
import Modal from 'react-bootstrap/Modal';
import Catalog from './modalBody';
import Button from 'react-bootstrap/Button';
// create a component
const CatalogModule = (props) => {

    const [showCatalog,setShowCatalog]=useState(false);

    return (
        <>
        <button onClick={()=>setShowCatalog(true)} style={{width:"100%",backgroundColor:'#93FFE8',color:'black'}} class="btn-block btn rounded-pill pxp-sign-hero-form-cta mb-2">Katalog Buku</button>
        <Modal 
		size="xl"
		// backdrop="static"
        // keyboard={false}
		show={showCatalog} 
		onHide={()=>setShowCatalog(false)}>
            <Modal.Header>
                <Modal.Title>Katalog Buku</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Catalog {...props}/>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={()=>setShowCatalog(false)}>Batal</Button>
            </Modal.Footer>
        </Modal>
        </>
    );
};


//make this component available to the app
export default CatalogModule;
