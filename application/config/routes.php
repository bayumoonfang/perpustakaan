<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$admin = ADMIN_URL;

$route['default_controller'] = 'home';


//delete after sun first install

$route['db-migrate'] = "migrate/index";
$route['db-migrate/rollback'] = "migrate/rollback";
$route['db-migrate/rollback/(:num)'] = "migrate/rollback/$1";
$route['db-migrate/refresh'] = "migrate/refresh";
$route['buku-tamu'] = "home/buku_tamu";
$route['post-buku-tamu']['POST'] = "home/post_buku_tamu";
$route['post-guest']['POST'] = "home/post_guest";

//end delete run after install

//auth
$route['login']['POST'] = 'authentication/login';
$route['logout'] = 'authentication/logout';

// route admin
$route[$admin] = 'panel/dashboard';

//route perpustakaan 
$route[$admin . '/perpustakaan'] = 'panel/library';
$route[$admin . '/perpustakaan/(:num)'] = 'panel/library';
$route[$admin . '/perpustakaan/new'] = 'panel/library/new';
$route[$admin . '/perpustakaan/add']['POST'] = 'panel/library/store';
$route[$admin . '/perpustakaan/(:num)/edit'] = 'panel/library/edit/$1';
$route[$admin . '/perpustakaan/(:num)/update']['POST'] = 'panel/library/update/$1';
$route[$admin . '/perpustakaan/(:num)/delete'] = 'panel/library/delete/$1';
$route[$admin . '/perpustakaan/(:num)/rak'] = 'panel/rak/rak/$1';
$route[$admin . '/perpustakaan/(:num)/rak/(:num)/edit'] = 'panel/rak/edit_rak/$1/$2';
$route[$admin . '/perpustakaan/(:num)/rak/(:num)/update']['POST'] = 'panel/rak/update_rak/$1/$2';
$route[$admin . '/perpustakaan/(:num)/rak/(:num)/delete'] = 'panel/rak/delete_rak/$1/$2';
$route[$admin . '/perpustakaan/(:num)/rak/add']['POST'] = 'panel/rak/store_rak/$1/';
$route[$admin . '/rak-perpustakaan/ajax/data/(:num)'] = 'panel/rak/ajax_data/$1/';

//route buku
$route[$admin . '/buku'] = 'panel/book/index';
$route[$admin . '/buku/(:num)'] = 'panel/book/index';
$route[$admin . '/buku/new'] = 'panel/book/new';
$route[$admin . '/buku/add']['POST'] = 'panel/book/store';
$route[$admin . '/buku/(:num)/edit'] = 'panel/book/edit/$1';
$route[$admin . '/buku/(:num)/update']['POST'] = 'panel/book/update/$1';
$route[$admin . '/buku/(:num)/delete'] = 'panel/book/delete/$1';
$route[$admin . '/buku-barcode-generate']['POST'] = 'panel/book/book_barcode_generate';
$route[$admin . '/buku-barcode-print'] = 'panel/book/book_barcode_print';
$route[$admin . '/buku-barcode-detail/(:num)/library/(:num)'] = 'panel/book/book_barcode_list/$1/$2';
$route[$admin . '/buku-barcode/(:num)/library/(:num)'] = 'panel/book/book_barcode/$1/$2';
$route[$admin . '/buku/export_excel'] = 'panel/book/export_excel_buku';
$route[$admin . '/buku/template_excel'] = 'panel/book/template_excel_buku';
$route[$admin . '/buku/import_excel'] = 'panel/book/import_excel_buku';

//route kategori-buku
$route[$admin . '/kategori-buku'] = 'panel/book/index_kategori';
$route[$admin . '/kategori-buku/(:num)'] = 'panel/book/index_kategori';
$route[$admin . '/kategori-buku/new'] = 'panel/book/new_kategori';
$route[$admin . '/kategori-buku/add']['POST'] = 'panel/book/add_kategori';
$route[$admin . '/kategori-buku/(:num)/edit'] = 'panel/book/edit_kategori/$1';
$route[$admin . '/kategori-buku/(:num)/update']['POST'] = 'panel/book/update_kategori/$1';
$route[$admin . '/kategori-buku/(:num)/delete'] = 'panel/book/delete_kategori/$1';
$route[$admin . '/kategori-buku/ajax/data/(:num)'] = 'panel/book/ajax_data/$1';
$route[$admin . '/kelas/ajax/data/(:num)'] = 'panel/library/kelas_ajax_data/$1';
$route[$admin . '/mapel/ajax/data/(:num)'] = 'panel/library/mapel_ajax_data/$1';
$route[$admin . '/member/ajax/data/(:any)'] = 'panel/library/member_ajax_data/$1';
// $route[$admin . '/member/ajax/data'] = 'panel/library/member_ajax_data';
$route[$admin . '/issue/ajax/add_duration/(:num)/(:any)'] = 'panel/issue/update_duration/$1/$2';
$route[$admin . '/book/ajax/data/(:any)'] = 'panel/library/book_ajax_data/$1';
$route[$admin . '/kategori-buku-masuk/ajax/data/(:any)'] = 'panel/library/category_buku_masuk_ajax_data/$1';
$route[$admin . '/kategori-buku-keluar/ajax/data/(:any)'] = 'panel/library/category_buku_keluar_ajax_data/$1';

//route bentuk-buku
$route[$admin . '/daftar-bentuk']['POST'] = 'panel/book/daftar_bentuk';
$route[$admin . '/bentuk-buku'] = 'panel/book/index_bentuk';
$route[$admin . '/bentuk-buku/(:num)'] = 'panel/book/index_bentuk';
$route[$admin . '/bentuk-buku/new'] = 'panel/book/new_bentuk';
$route[$admin . '/bentuk-buku/add']['POST'] = 'panel/book/add_bentuk';
$route[$admin . '/bentuk-buku/edit'] = 'panel/book/get_bentuk_by_id';
$route[$admin . '/bentuk-buku/(:num)/edit'] = 'panel/book/edit_bentuk/$1';
$route[$admin . '/bentuk-buku/(:num)/update']['POST'] = 'panel/book/update_bentuk/$1';
$route[$admin . '/bentuk-buku/(:num)/delete'] = 'panel/book/delete_bentuk/$1';

//route klasifikasi-buku
$route[$admin . '/daftar-subjek']['POST'] = 'panel/book/daftar_subjek';
$route[$admin . '/subjek-buku'] = 'panel/book/index_subjek';
$route[$admin . '/subjek-buku/(:num)'] = 'panel/book/index_subjek';
$route[$admin . '/subjek-buku/add']['POST'] = 'panel/book/add_subjek';
$route[$admin . '/subjek-buku/edit'] = 'panel/book/get_subjek_by_id';
$route[$admin . '/subjek-buku/(:num)/update']['POST'] = 'panel/book/update_subjek/$1';
$route[$admin . '/subjek-buku/(:num)/delete'] = 'panel/book/delete_subjek/$1';

//route master jenis pengurangan
$route[$admin . '/pengurangan'] = 'panel/pengurangan/index';
$route[$admin . '/pengurangan/(:num)'] = 'panel/pengurangan/index';
$route[$admin . '/pengurangan/new'] = 'panel/pengurangan/new';
$route[$admin . '/pengurangan/add']['POST'] = 'panel/pengurangan/store';
$route[$admin . '/pengurangan/(:num)/edit'] = 'panel/pengurangan/edit/$1';
$route[$admin . '/pengurangan/(:num)/update']['POST'] = 'panel/pengurangan/update/$1';
$route[$admin . '/pengurangan/(:num)/delete'] = 'panel/pengurangan/delete/$1';

//route master jenis penambahan
$route[$admin . '/penambahan'] = 'panel/penambahan/index';
$route[$admin . '/penambahan/(:num)'] = 'panel/penambahan/index';
$route[$admin . '/penambahan/new'] = 'panel/penambahan/new';
$route[$admin . '/penambahan/add']['POST'] = 'panel/penambahan/store';
$route[$admin . '/penambahan/(:num)/edit'] = 'panel/penambahan/edit/$1';
$route[$admin . '/penambahan/(:num)/update']['POST'] = 'panel/penambahan/update/$1';
$route[$admin . '/penambahan/(:num)/delete'] = 'panel/penambahan/delete/$1';

//route issue
$route[$admin . '/issue'] = 'panel/issue/index';
$route[$admin . '/issue/(:num)'] = 'panel/issue/index';
$route[$admin . '/issue/(:num)/kembali'] = 'panel/issue/kembali/$1';
$route[$admin . '/issue/add/(:num)'] = 'panel/issue/add/$1';
$route[$admin . '/book/ajax/search']['POST'] = 'panel/book/search';
$route[$admin . '/book/ajax/issue']['POST'] = 'panel/issue/store';
$route[$admin . '/book/ajax/proses-kembali']['POST'] = 'panel/issue/proses_kembali';
$route[$admin . '/book/ajax/history/(:num)'] = 'panel/issue/get_user_issue_history/$1';

// route transaksi buku masuk
$route[$admin . '/transaksi/buku-masuk'] = 'panel/transaksi/BukuMasuk/index';
$route[$admin . '/transaksi/buku-masuk/(:num)'] = 'panel/transaksi/BukuMasuk/index';
$route[$admin . '/transaksi/buku-masuk/new'] = 'panel/transaksi/BukuMasuk/add';
$route[$admin . '/transaksi/buku-masuk/add']['POST'] = 'panel/transaksi/BukuMasuk/store';
$route[$admin . '/transaksi/buku-masuk/(:num)/edit'] = 'panel/transaksi/BukuMasuk/edit/$1';
$route[$admin . '/transaksi/buku-masuk/(:num)/update']['POST'] = 'panel/transaksi/BukuMasuk/update/$1';
$route[$admin . '/transaksi/buku-masuk/(:num)/delete'] = 'panel/transaksi/BukuMasuk/delete/$1';

// route transaksi buku keluar
$route[$admin . '/transaksi/buku-keluar'] = 'panel/transaksi/BukuKeluar/index';
$route[$admin . '/transaksi/buku-keluar/(:num)'] = 'panel/transaksi/BukuKeluar/index';
$route[$admin . '/transaksi/buku-keluar/new'] = 'panel/transaksi/BukuKeluar/add';
$route[$admin . '/transaksi/buku-keluar/add']['POST'] = 'panel/transaksi/BukuKeluar/store';
$route[$admin . '/transaksi/buku-keluar/(:num)/edit'] = 'panel/transaksi/BukuKeluar/edit/$1';
$route[$admin . '/transaksi/buku-keluar/(:num)/update']['POST'] = 'panel/transaksi/BukuKeluar/update/$1';
$route[$admin . '/transaksi/buku-keluar/(:num)/delete'] = 'panel/transaksi/BukuKeluar/delete/$1';

//route pengaturan
$route[$admin . '/pengaturan/peminjaman'] = 'settings/peminjaman/index';
$route[$admin . '/pengaturan/peminjaman/(:num)'] = 'settings/peminjaman/index';
$route[$admin . '/pengaturan/peminjaman/(:num)/edit'] = 'settings/peminjaman/edit/$1';
$route[$admin . '/pengaturan/peminjaman/(:num)/update']['POST'] = 'settings/peminjaman/update/$1';

$route[$admin . '/pengaturan/role-issue'] = 'settings/RoleIssue/index';
// $route[$admin . '/pengaturan/role-issue/new'] = 'settings/RoleIssue/add';
// $route[$admin . '/pengaturan/role-issue/add']['POST'] = 'settings/RoleIssue/store';
// $route[$admin . '/pengaturan/role-issue/(:num)/delete'] = 'settings/RoleIssue/delete/$1';
$route[$admin . '/pengaturan/role-issue/(:num)/edit'] = 'settings/RoleIssue/edit/$1';
$route[$admin . '/pengaturan/role-issue/(:num)/update'] = 'settings/RoleIssue/update/$1';

//laporan
$route[$admin . '/laporan/peminjaman'] = 'laporan/Peminjaman/index';
$route[$admin . '/laporan/peminjaman/(:num)'] = 'laporan/Peminjaman/index';
$route[$admin . '/laporan/peminjaman/cetak'] = 'laporan/Peminjaman/export_peminjaman';
$route[$admin . '/laporan/history-buku'] = 'laporan/HistoryBuku/index';
$route[$admin . '/laporan/history-buku/(:num)'] = 'laporan/HistoryBuku/index';
$route[$admin . '/laporan/history-buku/detail/(:num)'] = 'laporan/HistoryBuku/detail/$1';
$route[$admin . '/laporan/history-buku/cetak'] = 'laporan/HistoryBuku/export_history_buku';
$route[$admin . '/laporan/history-buku/cetak/(:num)'] = 'laporan/HistoryBuku/export_history_detail_buku/$1';
$route[$admin . '/laporan/history-buku/detail/(:num)/(:num)'] = 'laporan/HistoryBuku/detail/$1';
$route[$admin . '/laporan/kategori-buku'] = 'laporan/KategoriBuku/index';
$route[$admin . '/laporan/kategori-buku/(:num)'] = 'laporan/KategoriBuku/index';
$route[$admin . '/laporan/kategori-buku/cetak'] = 'laporan/KategoriBuku/export_kategori_buku';
$route[$admin . '/laporan/subjek-buku'] = 'laporan/SubjekBuku/index';
$route[$admin . '/laporan/subjek-buku/(:num)'] = 'laporan/SubjekBuku/index';
$route[$admin . '/laporan/subjek-buku/cetak'] = 'laporan/SubjekBuku/export_subjek_buku';
$route[$admin . '/laporan/transaksi-buku'] = 'laporan/TransaksiKeluarMasuk/index';
$route[$admin . '/laporan/transaksi-buku/(:num)'] = 'laporan/TransaksiKeluarMasuk/index';
$route[$admin . '/laporan/transaksi-buku/cetak'] = 'laporan/TransaksiKeluarMasuk/export_transaksi';

//laporan pengunjung
$route[$admin . '/laporan/pengunjung'] = 'panel/pengunjung/index';
$route[$admin . '/laporan/pengunjung/(:num)'] = 'panel/pengunjung/index';

//route addon
$route[$admin . '/addon-manager'] = 'panel/addonmanager';
$route[$admin . '/addon-manager/new'] = 'panel/addonmanager/new';
$route[$admin . '/addon-manager/upload']['POST'] = 'panel/addonmanager/upload_file';


//api routes
$api = API_URL;
$route[$api . '/books'] = 'api/book/index';
$route[$api . '/books/read'] = 'api/book/book_data_read';
$route[$api . '/books/like'] = 'api/book/book_data_like';
$route[$api . '/books/categories'] = 'api/book/category';
$route[$api . '/books/categories/(:num)'] = 'api/book/book_by_category/$1';
$route[$api . '/books/(:num)'] = 'api/book/book_detail/$1';
$route[$api . '/books/(:num)/like'] = 'api/book/book_like/$1';
$route[$api . '/languages'] = 'api/book/language';
$route[$api . '/user-overview'] = 'api/book/current_user_overview';

//api generals
$route[$api . '/general/books'] = 'api/general/get_books';
$route[$api . '/general/categories'] = 'api/general/get_categories';
$route[$api . '/general/mapel'] = 'api/general/get_mapel';
$route[$api . '/general/kelas'] = 'api/general/get_kelas';

//api dashboard
$route[$api . '/dashboard/total_book'] = 'api/dashboard/total_book';
$route[$api . '/dashboard/total_ebook'] = 'api/dashboard/total_ebook';
$route[$api . '/dashboard/total_pinjam'] = 'api/dashboard/total_pinjam';
$route[$api . '/dashboard/total_keluar'] = 'api/dashboard/total_keluar';
$route[$api . '/dashboard/total_book_week'] = 'api/dashboard/total_book_week';
$route[$api . '/dashboard/total_ebook_week'] = 'api/dashboard/total_ebook_week';
$route[$api . '/dashboard/total_pinjam_week'] = 'api/dashboard/total_pinjam_week';
$route[$api . '/dashboard/total_keluar_week'] = 'api/dashboard/total_keluar_week';
$route[$api . '/dashboard/total_judul'] = 'api/dashboard/total_judul';
$route[$api . '/dashboard/total_judul_item'] = 'api/dashboard/total_judul_item';
$route[$api . '/dashboard/total_buku_koleksi'] = 'api/dashboard/total_buku_koleksi';
$route[$api . '/dashboard/total_item_pinjam'] = 'api/dashboard/total_item_pinjam';
$route[$api . '/dashboard/total_item_overdue'] = 'api/dashboard/total_item_overdue';
$route[$api . '/dashboard/total_member_issue'] = 'api/dashboard/total_member_issue';
$route[$api . '/dashboard/total_member_not_issue'] = 'api/dashboard/total_member_not_issue';
$route[$api . '/dashboard/top_member'] = 'api/dashboard/top_member';
$route[$api . '/dashboard/top_book'] = 'api/dashboard/top_book';
$route[$api . '/dashboard/dashobard_library'] = 'api/dashboard/dashobard_library';
$route[$api . '/dashboard/statistik_dashboard'] = 'api/dashboard/statistik_dashboard';
$route[$api . '/dashboard/data_pengunjung_dashboard'] = 'api/dashboard/data_pengunjung_dashboard';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// $route['(.*)'] = "none";
