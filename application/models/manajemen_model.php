<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

<<<<<<< HEAD
    // 🔹 Ambil semua data kunjungan (yang belum checkout)
=======
    // Ambil semua data kunjungan dengan join ke tabel users dan visitors
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function get_all()
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
<<<<<<< HEAD
        $this->db->where('visits.check_out IS NULL'); // hanya yg belum checkout
        $this->db->order_by('visits.scheduled_date', 'DESC');

        $data = $this->db->get()->result_array();

        // Tambahkan label status berdasarkan tanggal kunjungan
        foreach ($data as &$row) {
            if ($row['status'] == 'approved') {
                $tanggal = date('Y-m-d', strtotime($row['TANGGAL']));
                $hariIni = date('Y-m-d');
                if ($tanggal == $hariIni) {
                    $row['status_label'] = 'Berkunjung';
                } elseif ($tanggal < $hariIni) {
                    $row['status_label'] = 'Telah Berkunjung';
                } else {
                    $row['status_label'] = 'Approved';
                }
            } else {
                $row['status_label'] = ucfirst($row['status']);
            }
        }
        return $data;
=======
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    }

    // 🔹 Ambil data kunjungan berdasarkan status
    public function get_by_status($status)
    {
        $status_map = [
            'ditolak' => 'rejected',
            'berkunjung' => 'approved',
            'menunggu' => 'pending',
            'selesai' => 'completed'
        ];

        $db_status = isset($status_map[strtolower($status)]) ? $status_map[strtolower($status)] : $status;

        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
        $this->db->where('visits.status', $db_status);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

<<<<<<< HEAD
    // ✅ Gabungan versi final: Ambil pengunjung hari ini (lebih lengkap dan aman)
=======
    // Ambil pengunjung hari ini
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function get_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
<<<<<<< HEAD
        $this->db->like('visits.scheduled_date', $today, 'after');
        $this->db->order_by('visits.scheduled_date', 'DESC');

        $data = $this->db->get()->result_array();

        // Tambahkan label status dan kondisi checkout
        foreach ($data as &$row) {
            if (empty($row['check_out'] )) {
                $row['status_label'] = 'Sedang Berkunjung';
            } else {
                $row['status_label'] = 'Telah Selesai';
            }
        }
        return $data;
    }

    // 🔹 Hitung total berdasarkan status
=======
        $this->db->where('DATE(visits.scheduled_date)', $today);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Hitung total berdasarkan status
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('visits');
    }

<<<<<<< HEAD
    // 🔹 Hitung pengunjung yang sedang berkunjung (check_in sudah, tapi check_out belum)
    public function get_count_sedang_berkunjung()
    {
        $this->db->where('check_in IS NOT NULL', null, false);
        $this->db->where('check_out IS NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    // 🔹 Hitung pengunjung yang telah berkunjung (sudah checkout)
    public function get_count_telah_berkunjung()
    {
        $this->db->where('check_out IS NOT NULL', null, false);
        return $this->db->count_all_results('visits');
    }


    // 🔹 Hitung pengunjung hari ini
=======
    // Hitung pengunjung hari ini
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function count_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_date)', $today);
        return $this->db->count_all_results('visits');
    }

<<<<<<< HEAD
    // 🔹 Ambil detail kunjungan berdasarkan ID
    public function get_by_id($visit_id)
    {
=======
    // Ambil detail kunjungan berdasarkan ID
    public function get_by_id($visit_id) //menerima parameter $visit_id (ID kunjungan (visit_id) yang ingin kamu ambil datanya.)
    {
        //menentukan kolom apa aja yang ingin kamu ambil dari database.
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        $this->db->select(' 
            visits.*, 
            visits.fullname AS visitor_name,
            visits.id_number,
            visits.phone,
            visits.institution,
            visits.purpose,
            visits.to_whom,
            users.fullname AS handled_by_name
        ');
        $this->db->from('visits');
<<<<<<< HEAD
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
        $this->db->where('visits.visit_id', $visit_id);
        return $this->db->get()->row_array();
    }

    // 🔹 Update status kunjungan
=======
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left'); //supaya di halaman detail kunjungan (atau manajemen data) kamu bisa menampilkan nama petugas yang menangani tamu tersebut
        $this->db->where('visits.visit_id', $visit_id); //Filter data berdasarkan visit_id tertentu.
        return $this->db->get()->row_array();
    }

    // Update status kunjungan
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function update_status($visit_id, $status, $user_id = null)
    {
        $data = [
            'status' => $status,
            'approved_at' => date('Y-m-d H:i:s')
        ];
<<<<<<< HEAD
=======

>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        if ($user_id) {
            $data['approved_by'] = $user_id;
        }

        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

<<<<<<< HEAD
    // 🔹 Update check in
=======
    // Update check in
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function check_in($visit_id)
    {
        $data = [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'approved'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

<<<<<<< HEAD
    // 🔹 Update check out
=======
    // Update check out
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function check_out($visit_id)
    {
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

<<<<<<< HEAD
    // 🔹 Search kunjungan
=======
    // Search kunjungan
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    public function search($keyword = null)
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
<<<<<<< HEAD
            visits.status,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
=======
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
            visits.institution AS INSTANSI
        ');
        $this->db->from('visits');

        if (!empty($keyword)) {
<<<<<<< HEAD
            $this->db->group_start();
            $this->db->like('visits.fullname', $keyword);
            $this->db->or_like('visits.institution', $keyword);
            $this->db->group_end();
        }
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // 🔹 Hapus data pengunjung
    public function delete_visitor($id)
    {
        $this->db->where('visit_id', $id);
        $this->db->delete('visits');
    }

    // 🔥 Tambahkan ini(tombol kembali)
    public function get_all_visits()
    {
        $query = $this->db->get('visits'); // nama tabel di database kamu
        return $query->result_array();
=======
        $this->db->group_start();
        $this->db->like('visits.fullname', $keyword);
        $this->db->or_like('visits.institution', $keyword);  // ✅ bisa cari berdasarkan instansi
            $this->db->group_end();
        }
        $this->db->order_by('visits.scheduled_date', 'DESC'); //mengurutkan data berdasarkan kolom scheduled_date (tanggal kunjungan).(dari yg aku di urutkan dari yg baru di atas yg lama di bawah)
        return $this->db->get()->result_array();
        
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    }
}
