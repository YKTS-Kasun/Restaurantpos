<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_model extends CI_Model {

    /* =========================
     * COMPANY
     * ========================= */

    public function getCompanies()
    {
        return $this->db
            ->where('status', 1)
            ->get('tbl_company')
            ->result();
    }

    public function insertCompany($data)
    {
        return $this->db->insert('tbl_company', $data);
    }

    public function updateCompany($id, $data)
    {
        $this->db->where('idtbl_company', $id);
        return $this->db->update('tbl_company', $data);
    }

    public function getCompanyById($id)
    {
        return $this->db
            ->where('idtbl_company', $id)
            ->get('tbl_company')
            ->row();
    }

    /* =========================
     * COMPANY BRANCH
     * ========================= */

    public function getBranchesByCompany($company_id)
    {
        return $this->db
            ->where('tbl_company_idtbl_company', $company_id)
            ->where('status', 1)
            ->get('tbl_company_branch')
            ->result();
    }

    public function insertBranch($data)
    {
        return $this->db->insert('tbl_company_branch', $data);
    }

    public function updateBranch($id, $data)
    {
        $this->db->where('idtbl_company_branch', $id);
        return $this->db->update('tbl_company_branch', $data);
    }

    public function getBranchById($id)
    {
        return $this->db
            ->where('idtbl_company_branch', $id)
            ->get('tbl_company_branch')
            ->row();
    }
}
