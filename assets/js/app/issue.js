class Issue{
	// 1. describe and crete/initiate our object
    constructor(){
		this.typingTimer;
		this.bookSearchData=[];
		this.bookIssueData=[];
		this.bookSearch=$('#book-form-issue');
		this.libraryId=$('#select_perpustakaan');
		this.formSearch=$('#form-search-book');
		this.resultContainer=$('#div-book-search-result');
		this.btnChoose=$('.btn-choose-book');
		this.previousBookValue;
		this.previousLibrary;
		this.events();
	}
	// 2. events
	events(){
		this.bookSearch.on('input',this.bookChange.bind(this));
		this.libraryId.on('change',this.libraryChange.bind(this));
		this.formSearch.on('submit',this.submitSearch.bind(this));
		this.btnChoose.on('click',function(e){console.log(e);});
	}

	// 3. method (function/method...)

	submitSearch(e){
		e.preventDefault();
		// clearTimeout(this.typingTimer);
		// if(this.bookSearch.val() && this.bookSearch.val().length>=3){
		// 	if(!this.libraryId.val()){
		// 		this.resultContainer.empty();
		// 		this.resultContainer.append('<div class="text-danger text-center">Pilih perpustakaan terlebih dahulu</div>');
		// 		return;
		// 	}
		// 	this.resultContainer.empty();
		// 	this.resultContainer.append(`<div class="text-muted text-center">Loading...</div>`);
		// 	this.searchBooks();
		// }else{
		// 	this.resultContainer.empty();
		// 	this.resultContainer.append('<div class="text-muted text-center">Data buku kosong</div>');
		// }
	}

	libraryChange(){
		if(this.libraryId.val() !==this.previousLibrary){
			clearTimeout(this.typingTimer);
			if(this.bookSearch.val() && this.bookSearch.val().length>=3){
				this.resultContainer.empty();
				this.resultContainer.append(`<div class="text-muted text-center">Loading...</div>`);
				this.searchBooks();
			}else{
				this.resultContainer.empty();
				this.resultContainer.append('<div class="text-muted text-center">Data buku kosong</div>');
			}
		}
		this.previousLibrary=this.libraryId.val();
	}

	bookChange(){
		if(this.bookSearch.val()!==this.previousBookValue){
			clearTimeout(this.typingTimer);
			if(this.bookSearch.val() && this.bookSearch.val().length>=3){
				if(!this.libraryId.val()){
					this.resultContainer.empty();
					this.resultContainer.append('<div class="text-danger text-center">Pilih perpustakaan terlebih dahulu</div>');
					return;
				}
				this.resultContainer.empty();
				this.resultContainer.append(`<div class="text-muted text-center">Loading...</div>`);
				this.typingTimer=setTimeout(this.searchBooks.bind(this),800)
			}else{
				this.resultContainer.empty();
				this.resultContainer.append('<div class="text-muted text-center">Data buku kosong</div>');
			}
		}
		this.previousBookValue=this.bookSearch.val();
	}

	searchBooks(e){
		var actionUrl=this.formSearch.attr('action');
		this.bookSearchData=[];
		$.post(
			actionUrl,
			{
				'book' : this.bookSearch.val(),
				'library' : this.libraryId.val()
			},
			(data)=>{
				var result=JSON.parse(data);
				this.resultContainer.empty();
				this.bookSearchData=result;
				this.setSearchContainer()
			}
		)
	}

	setSearchContainer(){
		var result=this.bookSearchData;
		if(result.length<1){
			this.resultContainer.append('<div class="text-muted text-center">Data buku kosong</div>');
		}else{
			result.forEach(element => {
				this.resultContainer.append(`
					<div>
						<div class="row">
							<div class="text-muted col-md-3">Judul Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.title}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Kode Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.code}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Penulis</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.author}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Kategori Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.category_name}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Rak Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.rak_name}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">ISBN</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label>${element?.isbn}</label></div>
						</div>
						<div class="row">
							<div class="text-muted col-md-3">Stok Buku</div>
							<div class="text-muted col-md-1">:</div>
							<div class="col-md-8"><label class="text-success">${element?.stok}</label></div>
						</div>
						${element?.stok>0 ? `<button type="button" data-id="${element?.id}" class="btn-choose-book btn btn-block btn-primary btn-sm">Pilih</button>`:''}
						<hr/>
					</div>
				`);
			});
		}
	}

	chooseBook(e){
		console.log("aasasa");
	}
}
