<?php

class Login extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Login';
        $this->load->view('admin/templates/admin_header', $data);
        $this->load->view('admin/login', $data);
        $this->load->view('admin/templates/admin_footer');
    }

    public function proses_login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $tbl_user = $this->db->get_where('tbl_user', ['email' => $email])->row_array();
        if ($tbl_user) {
            if (password_verify($password, $tbl_user['password'])) {
                // Set session data
                $data = [
                    'email' => $tbl_user['email'],
                    'role' => $tbl_user['role'], // Menyimpan role pengguna
                ];
                $this->session->set_userdata($data);

                // Redirect berdasarkan role
                if ($tbl_user['role'] === 'admin') {
                    redirect('dashboard'); // Arahkan ke dashboard untuk admin
                } else {
                    redirect('beranda'); // Arahkan ke beranda untuk pengguna biasa
                }
            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Password Salah!</div>');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Email Belum Terdaftar!</div>');
            redirect('login');
        }
    }

    public function logout() 
    {
        $this->session->unset_userdata('email');     
        $this->session->unset_userdata('role'); // Menghapus role dari session
        $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Silahkan Login Kembali!</div>');
        redirect('login');  
    }
}
?>