<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation $form_validation
 * @property Admin_model $Admin_model
 * @property CI_Upload $upload
 */
class Profil_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('Admin_model');
    }

    public function profil()
    {
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

<<<<<<< HEAD
        if (!$user_id && !$username) {
            redirect('index.php/auth/login');
        }

        if ($user_id) {
            $data['user'] = $this->Admin_model->get_user_by_id($user_id);
        } else {
            $data['user'] = $this->db->get_where('users', ['username' => $username])->row();
        }

        $data['active_page'] = 'profil';
=======
        
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        $this->load->view('admin/profil', $data);
    }

    public function update()
    {
        if ($this->input->method() !== 'post') {
            redirect('index.php/profil_admin/profil');
        }

        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

        if (!$user_id && !$username) {
            redirect('index.php/auth/login');
        }

        if (!$user_id) {
            $user = $this->db->get_where('users', ['username' => $username])->row();
            $user_id = $user->user_id;
        }

        // Sesuaikan dengan kolom database
        $update_data = [
            'fullname' => $this->input->post('fullname'),
            'username' => $this->input->post('username'),
        ];

        // Password disimpan tanpa hash untuk bisa dilihat
        if (!empty($this->input->post('password'))) {
            $update_data['password'] = $this->input->post('password');
        }

        // Upload foto profil
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = './uploads/profil/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp';
            $config['max_size'] = 2048;
            $config['file_name'] = 'profil_' . $user_id . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $user = $this->Admin_model->get_user_by_id($user_id);
                if (!empty($user->foto)) {
                    $this->Admin_model->delete_old_photo($user->foto);
                }
                $update_data['foto'] = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }

        // Update database
        if ($this->Admin_model->update_user($user_id, $update_data)) {
            // Update session jika username berubah
            $this->session->set_userdata('username', $update_data['username']);

            $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil.');
        }

        redirect('index.php/profil_admin/profil');
    }

    public function delete_account()
    {
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

        if (!$user_id && !$username) {
            redirect('index.php/auth/login');
        }

        if (!$user_id) {
            $user = $this->db->get_where('users', ['username' => $username])->row();
            $user_id = $user->user_id;
        }

        $user = $this->Admin_model->get_user_by_id($user_id);
        if (!empty($user->foto)) {
            $this->Admin_model->delete_old_photo($user->foto);
        }

        $this->Admin_model->delete_user($user_id);
        $this->session->sess_destroy();
        redirect('index.php/auth/login');
    }
}
