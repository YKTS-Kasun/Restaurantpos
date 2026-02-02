<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userlocationinfo extends CI_Model {

/* =========================================
   GET COMPANY LIST
========================================= */
public function Getcompanylist(){

    $this->db->select('idtbl_company, company');
    $this->db->from('tbl_company');
    $this->db->where('status', 1);

    return $this->db->get();
}


    /* =========================================
       USER LOCATION ACCESS INSERT / UPDATE
    ========================================= */
public function Userlocationinsertupdate(){

    $loginUser = $_SESSION['userid'];
    $datetime  = date('Y-m-d H:i:s');

    $user_id     = $this->input->post('user_id');
    $company_ids = (array) $this->input->post('company_id');
    $branch_ids  = (array) $this->input->post('branch_id');
    $status      = $this->input->post('status');

    $recordOption = $this->input->post('recordOption');
    $recordID     = $this->input->post('recordID');

    /* ===============================
       UPDATE (SINGLE RECORD)
    =============================== */
    if ($recordOption == 2) {

        $this->db->trans_begin();

        $data = [
            'tbl_res_user_idtbl_res_user' => $user_id,
            'tbl_company_idtbl_company'   => $company_ids[0],
            'tbl_company_branch_idtbl_company_branch'
                => !empty($branch_ids) ? $branch_ids[0] : NULL,
            'status'        => $status,
            'updateuser'    => $loginUser,
            'updatedatetime'=> $datetime
        ];

        $this->db->where('idtbl_user_location_access', $recordID);
        $this->db->update('tbl_user_location_access', $data);

        $this->db->trans_complete();
    }

    /* ===============================
       INSERT (MULTI COMPANY / BRANCH)
    =============================== */
if ($recordOption == 1) {

    $this->db->trans_begin();

    foreach ($company_ids as $company_id) {

        // 🔥 ADD THIS BLOCK HERE
        if (!empty($branch_ids)) {
            $this->db
                ->where('tbl_res_user_idtbl_res_user', $user_id)
                ->where('tbl_company_idtbl_company', $company_id)
                ->where('tbl_company_branch_idtbl_company_branch IS NULL', null, false)
                ->delete('tbl_user_location_access');
        }

        /* ===== HEAD OFFICE ===== */
        if (empty($branch_ids)) {

            $exists = $this->db
                ->where('tbl_res_user_idtbl_res_user', $user_id)
                ->where('tbl_company_idtbl_company', $company_id)
                ->where('tbl_company_branch_idtbl_company_branch IS NULL', null, false)
                ->where('status !=', 3)
                ->get('tbl_user_location_access')
                ->num_rows();

            if ($exists == 0) {
                $this->db->insert('tbl_user_location_access', [
                    'tbl_res_user_idtbl_res_user' => $user_id,
                    'tbl_company_idtbl_company'  => $company_id,
                    'tbl_company_branch_idtbl_company_branch' => NULL,
                    'status'        => $status,
                    'insertuser'    => $loginUser,
                    'insertdatetime'=> $datetime
                ]);
            }

        }
        /* ===== BRANCHES ===== */
        else {

            foreach ($branch_ids as $branch_id) {

                $exists = $this->db
                    ->where('tbl_res_user_idtbl_res_user', $user_id)
                    ->where('tbl_company_idtbl_company', $company_id)
                    ->where('tbl_company_branch_idtbl_company_branch', $branch_id)
                    ->where('status !=', 3)
                    ->get('tbl_user_location_access')
                    ->num_rows();

                if ($exists == 0) {
                    $this->db->insert('tbl_user_location_access', [
                        'tbl_res_user_idtbl_res_user' => $user_id,
                        'tbl_company_idtbl_company'  => $company_id,
                        'tbl_company_branch_idtbl_company_branch' => $branch_id,
                        'status'        => $status,
                        'insertuser'    => $loginUser,
                        'insertdatetime'=> $datetime
                    ]);
                }
            }
        }
    }

    $this->db->trans_complete();
}

    /* ===============================
       RESULT
    =============================== */
    if ($this->db->trans_status() === TRUE) {

        $this->db->trans_commit();

        $actionObj = new stdClass();
        $actionObj->icon    = 'fas fa-save';
        $actionObj->message = ($recordOption == 1)
            ? 'Location Access Assigned Successfully'
            : 'Location Access Updated Successfully';
        $actionObj->type    = 'success';

    } else {

        $this->db->trans_rollback();

        $actionObj = new stdClass();
        $actionObj->icon    = 'fas fa-warning';
        $actionObj->message = 'Database Error';
        $actionObj->type    = 'danger';
    }

    $this->session->set_flashdata('msg', json_encode($actionObj));
    redirect('Userlocation');
}


    /* =========================================
       EDIT LOCATION ACCESS
    ========================================= */
public function Userlocationedit(){

    $recordID = $this->input->post('recordID');

    $q = $this->db
        ->where('idtbl_user_location_access', $recordID)
        ->where('status !=', 3)
        ->get('tbl_user_location_access')
        ->row();

    if (!$q) {
        echo json_encode(['status' => 0]);
        return;
    }

    echo json_encode([
        'status'     => 1,
        'id'         => $q->idtbl_user_location_access,
        'user_id'    => $q->tbl_res_user_idtbl_res_user,
        'company_id' => $q->tbl_company_idtbl_company,
        'branch_id'  => $q->tbl_company_branch_idtbl_company_branch,
        'status_val' => $q->status
    ]);
}


    /* =========================================
       STATUS CHANGE
    ========================================= */
    public function Userlocationstatus($id, $type){

        $this->db->trans_begin();

        $loginUser = $_SESSION['userid'];
        $datetime  = date('Y-m-d H:i:s');

        $data = [
            'status'        => $type,
            'updateuser'    => $loginUser,
            'updatedatetime'=> $datetime
        ];

        $this->db->where('idtbl_user_location_access', $id);
        $this->db->update('tbl_user_location_access', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        redirect('Userlocation');
    }

    /* =========================================
   GET USER LIST
========================================= */
public function Getuserlist(){

    if($_SESSION['userid'] == 1){
        // Super admin – all users
        $this->db->select('idtbl_res_user, name');
        $this->db->from('tbl_res_user');
        $this->db->where('status', 1);
    } else {
        // Other users – except super admin
        $this->db->select('idtbl_res_user, name');
        $this->db->from('tbl_res_user');
        $this->db->where('idtbl_res_user !=', 1);
        $this->db->where('status', 1);
    }

    return $this->db->get();
}

/* =========================================
   GET BRANCH LIST BY COMPANY (AJAX)
========================================= */
public function Getbranchaccocompany(){

    $company_ids = (array) $this->input->post('company_id');

    if (empty($company_ids)) {
        return;
    }

    $this->db->select('idtbl_company_branch, branch');
    $this->db->from('tbl_company_branch');
    $this->db->where_in('tbl_company_idtbl_company', $company_ids);
    $this->db->where('status', 1);

    $query = $this->db->get();

    foreach ($query->result() as $row) {
        echo '<option value="'.$row->idtbl_company_branch.'">'
           . $row->branch .
           '</option>';
    }
}



}
