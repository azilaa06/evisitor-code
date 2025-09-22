<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tamu extends CI_Controller {

    public function dashboard() {
        $data = array(
            'title' => 'Form Pendaftaran',
            'active_page' => 'dashboard'
        );

        $this->load->view('layouts/sidebar', $data);
        $this->load->view('tamu/dashboard', $data);
    }
}
