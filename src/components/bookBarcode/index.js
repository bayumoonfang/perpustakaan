//import liraries
import React, { Component, useEffect, useState } from "react";
import Modal from "react-bootstrap/Modal";

// create a component
const BarcodeButton = (props) => {
	const { id, library, url } = props;
	const [loadingBook, setLoadingBook] = useState(false);
	const [loadingData, setLoadingData] = useState(false);
	const [showModal, setShowModal] = useState(false);
	const [bookDetail, setBookDetail] = useState(null);
	const [listBarcode, setListBarcode] = useState([]);
	const [errorDetail, setErrorDetail] = useState(null);
	const [errorList, setErrorList] = useState(null);
	const [isAll, setIsAll] = useState(false);
	const [canCetak, setCanCetak] = useState(false);
	const [loadingGenerate, setLoadingGenerate] = useState(false);

	const loadBook = async () => {
		setLoadingBook(true);
		setErrorDetail(null);
		const apiUrl = url + "buku-barcode/" + id + "/library/" + library;
		const response = await fetch(apiUrl, {
			method: "GET",
			headers: { "Content-Type": "application/json" },
		});
		const data = await response?.json();
		setLoadingBook(false);
		if (data?.status) {
			setBookDetail(data?.data);
		} else {
			setErrorDetail(data?.message);
		}
	};

	const loadBarcode = async () => {
		setLoadingData(true);
		setListBarcode([]);
		setErrorList(null);
		const apiUrl = url + "buku-barcode-detail/" + id + "/library/" + library;
		const response = await fetch(apiUrl, {
			method: "GET",
			headers: { "Content-Type": "application/json" },
		});
		const data = await response?.json();
		setLoadingData(false);
		if (data?.status) {
			setListBarcode(data?.data);
		} else {
			setErrorList(data?.message);
		}
	};

	const onRegenerate = async () => {
		setLoadingGenerate(true);
		const response = await fetch(url + "buku-barcode-generate", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({
				book: id,
				library: library,
				url: url,
			}),
		});
		const dataGenerate = await response.json();
		setLoadingGenerate(false);
		loadBarcode();
	};

	useEffect(() => {
		if (showModal) {
			loadBook();
			loadBarcode();
		} else {
			setListBarcode([]);
			setBookDetail(null);
			setIsAll(false);
		}
	}, [showModal]);

	const selectAllBarcode = () => {
		const newList = listBarcode.map((item) => {
			item.selected = isAll ? false : true;
			return item;
		});
		setListBarcode(newList);
		setIsAll(!isAll);
	};

	const selectBarcodeItem = (itemss) => {
		const newList = listBarcode.map((item) => {
			let selecteds = item?.selected;
			if (itemss?.id === item.id) {
				selecteds = !item?.selected;
			}
			item.selected = selecteds;
			return item;
		});
		setListBarcode(newList);
	};

	useEffect(() => {
		if (verifyCetak()) {
			setCanCetak(true);
		} else {
			setCanCetak(false);
		}
	}, [listBarcode]);

	const verifyCetak = () => {
		let selectedss = 0;
		listBarcode.map((itt) => {
			if (itt?.selected) {
				selectedss++;
			}
		});
		if (selectedss < 1) {
			return false;
		}
		return true;
	};

	const cetakBarcode = async () => {
		if (!verifyCetak()) {
			return;
		}
		let arrList = [];
		listBarcode.filter((item) => {
			if (item.selected) {
				arrList.push(item?.url);
			}
		});
		const barcode = JSON.stringify(arrList);
		window.open(url + "buku-barcode-print?barcode=" + barcode, "_blank");
	};

	return (
		<div>
			<button
				class="btn btn-sm btn-outline-success ml-2"
				onClick={() => setShowModal(true)}
			>
				Barcode
			</button>
			<Modal
				size="md"
				backdrop="static"
				keyboard={false}
				show={showModal}
				onHide={() => setShowModal(false)}
			>
				<Modal.Header>
					<h6>Barcode Generator</h6>
				</Modal.Header>
				<Modal.Body>
					{errorDetail ? (
						<>
							<div className="row">
								<div className="col-md-12 text-center">{errorDetail}</div>
							</div>
						</>
					) : (
						<>
							<h6>{bookDetail?.title}</h6>
							<strong>Kode Buku : {bookDetail?.code}</strong>
							<div class="row mt-2 mb-2">
								<div class="col-md-auto">
									<button
										disabled={loadingGenerate}
										className="btn btn-outline-success"
										onClick={onRegenerate}
									>
										{loadingGenerate ? "Loading..." : "Regenerate Barcode"}
									</button>
								</div>
								<div class="col-md-auto">
									<p className="text-warning">
										<em>
											**Regenerate Barcode hanya akan menambah jumlah barcode
											sesuai Qty buku (jika kurang) dan tidak akan menghapus
											atau mengurangi barcode yang telah dibuat sebelumnya.
											**Perubahan Kode buku master tidak akan merubah barcode
											yang telah digenerate sebelumnya.
										</em>
									</p>
								</div>
							</div>
							<hr />
							{listBarcode && listBarcode.length > 0 && (
								<div class="row mt-2 mb-2">
									<div class="col-md-auto">
										<button
											onClick={selectAllBarcode}
											className="btn btn-sm btn-outline-info mb-2"
										>
											{isAll ? "Batal Pilih Semua" : "Pilih Semua"}
										</button>
									</div>
									{canCetak && (
										<div class="col-md-auto">
											<button
												onClick={cetakBarcode}
												className="btn btn-sm btn-outline-primary mb-2"
											>
												Cetak
											</button>
										</div>
									)}
								</div>
							)}
							<div class="table-responsive">
								<table className="table">
									<thead>
										<tr>
											<th width="8%">#</th>
											<th>Barcode</th>
										</tr>
									</thead>
									<tbody>
										{!loadingData &&
											listBarcode.map((item, index) => {
												return (
													<tr>
														<td>
															<div class="form-check">
																<input
																	class="form-check-input position-static"
																	type="checkbox"
																	id="blankCheckbox"
																	checked={item?.selected}
																	onClick={() => selectBarcodeItem(item)}
																	value="option1"
																	aria-label="..."
																/>
															</div>
														</td>
														<td>{item?.barcode}</td>
													</tr>
												);
											})}
									</tbody>
								</table>
								{loadingData && (
									<div className="row">
										<div className="col-md-12 text-center">Loading...</div>
									</div>
								)}
								{!loadingData && errorList && (
									<div className="row">
										<div className="col-md-12 text-center">{errorList}</div>
									</div>
								)}
							</div>
						</>
					)}
				</Modal.Body>
				<Modal.Footer>
					<button
						disabled={loadingGenerate}
						className="btn btn-outline-secondary"
						onClick={() => setShowModal(false)}
					>
						Close
					</button>
				</Modal.Footer>
			</Modal>
		</div>
	);
};

//make this component available to the app
export default BarcodeButton;
