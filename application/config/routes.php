<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING - FIXED VERSION
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
// MANAJEMEN KUNJUNGAN
// ========================================
$route['manajemen_kunjungan'] = 'manajemen_kunjungan/index';
$route['manajemen_kunjungan/data'] = 'manajemen_kunjungan/data';
$route['manajemen_kunjungan/data/(:any)'] = 'manajemen_kunjungan/data/$1';

// Filter routes
$route['manajemen_kunjungan/menunggu'] = 'manajemen_kunjungan/menunggu';
$route['manajemen_kunjungan/berkunjung'] = 'manajemen_kunjungan/berkunjung';
$route['manajemen_kunjungan/ditolak'] = 'manajemen_kunjungan/ditolak';
$route['manajemen_kunjungan/selesai'] = 'manajemen_kunjungan/selesai';
$route['manajemen_kunjungan/hari_ini'] = 'manajemen_kunjungan/hari_ini';

// Backward compatibility
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
$route['kunjungan/daftar_kunjungan'] = 'daftar_kunjungan/index';
$route['kunjungan/daftar_kunjungan/detail/(:num)'] = 'daftar_kunjungan/detail/$1';

// ========================================
// TAMU (VISITOR VIEW)
// ========================================
$route['tamu'] = 'tamu/index';
$route['tamu/kunjungan_detail/(:num)'] = 'tamu/kunjungan_detail/$1';

// ========================================
// QR CODE MANAGEMENT - FIXED
// ========================================

// Main QR Code pages
$route['qr_code'] = 'qr_code/index';
$route['qr_code/scan'] = 'qr_code/scan';
$route['qr_code/active'] = 'qr_code/active';

// Generate & View QR
$route['qr_code/generate/(:num)'] = 'qr_code/generate/$1';
$route['qr_code/view/(:num)'] = 'qr_code/view/$1';
$route['qr_code/token/(:any)'] = 'qr_code/view_by_token/$1';

// ✅ FIXED: Check-in & Check-out AJAX endpoints
$route['qr_code/checkin'] = 'qr_code/checkin';
$route['qr_code/checkout/(:num)'] = 'qr_code/checkout/$1';
$route['qr_code/validate'] = 'qr_code/validate';
