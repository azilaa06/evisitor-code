<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING - FIXED VERSION
| Konsep dari MAIN, struktur lebih clean dan konsisten
| -------------------------------------------------------------------------
*/

// ========================================
// DEFAULT ROUTES
// ========================================
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ========================================
// AUTH & DASHBOARD
// ========================================
$route['dashboard_admin'] = 'auth/dashboard_admin';

// ========================================
// PROFIL ADMIN
// ========================================
$route['profil'] = 'profil_admin/profil';
$route['profil/update'] = 'profil_admin/update';
$route['profil/delete'] = 'profil_admin/delete_account';

// ========================================
// MANAJEMEN KUNJUNGAN (FIXED ROUTING)
// ========================================

// Main route - tampilkan semua data
$route['manajemen_kunjungan'] = 'manajemen_kunjungan/index';

// Route dengan parameter status
$route['manajemen_kunjungan/data'] = 'manajemen_kunjungan/data';
$route['manajemen_kunjungan/data/(:any)'] = 'manajemen_kunjungan/data/$1';

// Filter routes (named routes untuk UI yang lebih clean)
$route['manajemen_kunjungan/menunggu'] = 'manajemen_kunjungan/menunggu';
$route['manajemen_kunjungan/berkunjung'] = 'manajemen_kunjungan/berkunjung';
$route['manajemen_kunjungan/ditolak'] = 'manajemen_kunjungan/ditolak';
$route['manajemen_kunjungan/selesai'] = 'manajemen_kunjungan/selesai';
$route['manajemen_kunjungan/hari_ini'] = 'manajemen_kunjungan/hari_ini';

// Backward compatibility untuk route lama (dari TIA)
$route['admin/manajemen_data'] = 'manajemen_kunjungan/index';
$route['admin/manajemen_data/(:any)'] = 'manajemen_kunjungan/data/$1';

// ========================================
// DETAIL KUNJUNGAN
// ========================================
$route['detail_kunjungan/detail/(:num)'] = 'detail_kunjungan/detail/$1';
$route['kunjungan/detail/(:num)'] = 'detail_kunjungan/detail/$1';

// ========================================
// FORM KUNJUNGAN (PUBLIC)
// ========================================
$route['kunjungan'] = 'kunjungan/index';
$route['kunjungan/submit'] = 'kunjungan/submit';

// ========================================
// DAFTAR KUNJUNGAN (USER VIEW)
// ========================================
$route['daftar_kunjungan'] = 'daftar_kunjungan/index';
$route['daftar_kunjungan/detail/(:num)'] = 'daftar_kunjungan/detail/$1';

// Alias untuk backward compatibility
$route['kunjungan/daftar_kunjungan'] = 'daftar_kunjungan/index';
$route['kunjungan/daftar_kunjungan/detail/(:num)'] = 'daftar_kunjungan/detail/$1';

// ========================================
// TAMU (VISITOR VIEW)
// ========================================
$route['tamu'] = 'tamu/index';
$route['tamu/kunjungan_detail/(:num)'] = 'tamu/kunjungan_detail/$1';

// ========================================
// QR CODE MANAGEMENT
// ========================================

// Main QR Code management page
$route['qr_code'] = 'qr_code/index';

// Generate QR Code setelah approve
$route['qr_code/generate/(:num)'] = 'qr_code/generate/$1';

// View QR Code untuk admin (dengan sidebar)
$route['qr_code/view/(:num)'] = 'qr_code/view/$1';

// View QR Code by token (public - tanpa sidebar untuk visitor)
$route['qr_code/token/(:any)'] = 'qr_code/view_by_token/$1';

// Scan QR Code page & process
$route['qr_code/scan'] = 'qr_code/scan';
$route['scan/process_checkin'] = 'scan/process_checkin'; // AJAX endpoint

// Check-in & Check-out process
$route['qr_code/checkin'] = 'qr_code/checkin';
$route['qr_code/checkout/(:num)'] = 'qr_code/checkout/$1';

// Active visitors list (yang sedang check-in)
$route['qr_code/active'] = 'qr_code/active';