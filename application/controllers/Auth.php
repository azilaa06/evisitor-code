<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller{
    /* login admin */
    public function index() {
        $this->load->view('auth/login_admin');
    }

    /*login user */
    public function user(){
        $this->load->view('auth/login_user');
    }
}
