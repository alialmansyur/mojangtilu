<?php

namespace Config;
 
use CodeIgniter\Routing\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
} 

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Auth\Auth::index');
$routes->get('/login', 'Auth\Auth::index');
$routes->post('/authprocess', 'Auth\Auth::authprocess');
$routes->get('/logout', 'Auth\Auth::logout');

$routes->group('', ['filter' => 'jwtauth'], function ($routes) {

    // Route untuk Main Pages
    $routes->get('/home', 'Apps\AppsController::dashboard');
    $routes->get('/explore-task', 'Apps\AppsController::explore');
    $routes->get('/resume-task', 'Apps\AppsController::resume');
    $routes->get('/history-task', 'Apps\AppsController::history');
    $routes->post('/fetch-layanan', 'Apps\FetchData::fetchLayanan');
    $routes->post('/fetch-layanan-enrolled', 'Apps\FetchData::fetchLayananByNIP');
    $routes->post('/store-enroll', 'Apps\FetchData::enrolltask');     

    // ----------------------------------------------------------------
    // -------------------- ROUTING UNTUK LAYANAN ---------------------
    // ----------------------------------------------------------------
    
    // Layanan Kenaikan Pangkat 
    $routes->get('/upload-kp', 'Apps\Pages\KenaikanPangkat::upload');
    $routes->get('/entry-kp', 'Apps\Pages\KenaikanPangkat::entry');
    $routes->get('/info-kp', 'Apps\Pages\KenaikanPangkat::info'); 
    $routes->post('/store/master-data', 'Apps\Pages\KenaikanPangkat::storeMasterData');
    $routes->post('/store/pull-data-entrypage', 'Apps\Pages\StatistikData::getDataEntryPage');
    $routes->get('/allocation/generate-task', 'Apps\Pages\KenaikanPangkat::allocateTask');
    $routes->get('/allocation/pull-task', 'Apps\Pages\KenaikanPangkat::pullTask');

    // Layanan Statistik 
    $routes->get('/upload-statistik', 'Apps\Pages\StatistikData::upload');
    $routes->post('/upload-statistik', 'Apps\Pages\StatistikData::upload');
    $routes->get('/entry-statistik', 'Apps\Pages\StatistikData::entry');
    $routes->get('/info-statistik', 'Apps\Pages\StatistikData::info'); 
    $routes->post('/store/import-excel', 'Apps\Pages\StatistikData::storeData');
    $routes->post('/store/pull-datalist', 'Apps\Pages\StatistikData::getData');
    $routes->post('/store/remove-data', 'Apps\Pages\StatistikData::removeData');

    // Function General
    $routes->post('/change-password', 'Auth\Auth::changePassword');
    $routes->post('/update-profil', 'Apps\AjxController::updateProfile');
    $routes->post('/remove-data', 'Apps\AjxController::killData');
    $routes->post('/status-data', 'Apps\AjxController::statusData');
    $routes->post('/reset-data', 'Apps\AjxController::resetPassword');
    $routes->post('/uploadAvatar', 'Apps\AjxController::uploadAvatar');


    // $routes->get('/sclgn', 'Apps\AppsController::xxx');
    // $routes->get('/manage-user','Apps\AppsController::dataUsers');
    // $routes->get('/manage-pegawai', 'Apps\AppsController::dataPegawai');
    // $routes->get('/manage-movable', 'Apps\AppsController::dataMovable');
    // $routes->get('/manage-presence', 'Apps\AppsController::dataSetup');
    // $routes->get('/manage-schedule','Apps\AppsController::dataSchedule');
    // $routes->get('/manual-presence','Apps\AppsController::dataManualPresence');
    // $routes->post('/store-data-pegawai','Apps\AjxController::storePegawai');
    // $routes->post('/store-data-movable','Apps\AjxController::storeMovable');
    // $routes->get('/presensi-online', 'Apps\PresensiController::presensi');
    // $routes->get('/kalender-presensi', 'Apps\PresensiController::kalender');
    // $routes->get('/riwayat-presensi', 'Apps\PresensiController::riwayat');
    // $routes->post('/riwayat-presensi', 'Apps\PresensiController::riwayat');
    // $routes->get('/pengajuan-cuti', 'Apps\PresensiController::cuti');
    // $routes->post('/pengajuan-cuti', 'Apps\PresensiController::cuti');
    // $routes->get('/pengajuan-izin', 'Apps\PresensiController::izin');
    // $routes->post('/pengajuan-izin', 'Apps\PresensiController::izin');
    // $routes->get('/setting-jadwal-kerja', 'Apps\PresensiController::jadwalkerja');
    // $routes->get('/approval-presence', 'Apps\PresensiController::approval');
    // $routes->get('/activemenu/(:num)', 'Apps\AjxController::updateActiveMenu/$1');
    // $routes->post('/ajxpresencesubmit', 'Apps\AjxController::storedatapresence');
    // $routes->get('/fetchdata', 'Apps\AjxController::fetchdatapresence');
    // $routes->get('/historyPresent', 'Apps\AjxController::historyPresent');
    // $routes->get('/fetchDashboard', 'Apps\AjxController::fetchDashboard');
    // $routes->post('/fetch-agenda-calendar', 'Apps\AjxController::fetchCalendarAgenda');
    // $routes->post('/setup-configure', 'Apps\AjxController::presenceSetup');
    // $routes->post('/submit-installment', 'Apps\AjxController::storeFormIzin');
    // $routes->post('/update-profil-data', 'Apps\AjxController::storeProfil');
    // $routes->post('/cancel-installment', 'Apps\AjxController::cancelFormIzin');
    // $routes->get('/approval-installment','Apps\PresensiController::approvalData');
    // $routes->post('/detail-installment','Apps\AjxController::viewInstallment');
    // $routes->post('/approve-installment','Apps\AjxController::approveInstallment');
    // $routes->post('/recognize','Apps\AjxController::recognize');
});
