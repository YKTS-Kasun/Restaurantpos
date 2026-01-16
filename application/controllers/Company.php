<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('loggedin')) {
            redirect(base_url());
        }
    }

    /* =============================
     * COMPANY MASTER VIEW
     * ============================= */
    public function index()
    {
         $this->load->model('Commeninfo');
        $this->load->model('Company_model');
        $data['companylist'] = $this->db
            ->where('status', 1)
            ->get('tbl_company')
            ->result();
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('company_master', $data);
    }

    /* =============================
     * COMPANY INSERT / UPDATE
     * ============================= */
    public function companySave()
    {
        $data = [
            'company' => $this->input->post('company'),
            'code'    => $this->input->post('code'),
            'status'  => 1
        ];

        if ($this->input->post('recordOption') == 1) {
            $this->db->insert('tbl_company', $data);
        } else {
            $this->db->where('idtbl_company', $this->input->post('recordID'));
            $this->db->update('tbl_company', $data);
        }

        redirect('Company');
    }

    /* =============================
     * BRANCH INSERT / UPDATE
     * ============================= */
    public function branchSave()
    {
        $data = [
            'tbl_company_idtbl_company' => $this->input->post('company_id'),
            'branch' => $this->input->post('branch'),
            'code'   => $this->input->post('code'),
            'status' => 1
        ];

        if ($this->input->post('recordOption') == 1) {
            $this->db->insert('tbl_company_branch', $data);
        } else {
            $this->db->where('idtbl_company_branch', $this->input->post('recordID'));
            $this->db->update('tbl_company_branch', $data);
        }

        redirect('Company');
    }
}
