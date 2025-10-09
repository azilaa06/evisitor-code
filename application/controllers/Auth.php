<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** 
 * @property CI_Session      $session
*/

class Auth extends CI_Controller{
    /* login admin */
    public function index() {
        $this->load->view('auth/login_admin');
    }

    /*login user */
    public function user(){
        $this->load->view('auth/login_user');
    }

    public function logout() {
    // hapus semua session
    $this->session->sess_destroy();

    // redirect ke halaman login user
    redirect('auth/user');
}
}

