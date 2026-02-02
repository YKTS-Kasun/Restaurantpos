<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockReceive_model extends CI_Model {

    /* =====================================================
     * GET TRANSFER HEADER (WITH COMPANY / BRANCH)
     * ===================================================== */
    public function get_transfer_header($id)
    {
        return $this->db
            ->select("
                st.*,
                fc.company AS from_company,
                fb.branch  AS from_branch,
                tc.company AS to_company,
                tb.branch  AS to_branch
            ")
            ->from('tbl_stock_transfer st')

            ->join('tbl_company fc', 'fc.idtbl_company = st.from_company_id', 'left')
            ->join('tbl_company_branch fb', 'fb.idtbl_company_branch = st.from_branch_id', 'left')

            ->join('tbl_company tc', 'tc.idtbl_company = st.to_company_id', 'left')
            ->join('tbl_company_branch tb', 'tb.idtbl_company_branch = st.to_branch_id', 'left')

            ->where('st.idtbl_stock_transfer', $id)
            ->where_in('st.status', ['APPROVED','RECEIVED'])
            ->get()
            ->row();
    }

    /* =====================================================
     * GET TRANSFER ITEMS
     * ===================================================== */
    public function get_transfer_items($id)
    {
        return $this->db
            ->select('
                d.qty,
                m.material,
                m.idtbl_res_material_info
            ')
            ->from('tbl_stock_transfer_detail d')
            ->join(
                'tbl_res_material_info m',
                'm.idtbl_res_material_info = d.tbl_res_material_info_id'
            )
            ->where('d.tbl_stock_transfer_id', $id)
            ->get()
            ->result();
    }

    /* =====================================================
     * RECEIVE TRANSFER (FINAL – COMPANY / BRANCH AWARE)
     * ===================================================== */
    public function receive_transfer($transfer_id, $user_id)
    {
        $this->db->trans_begin();

        /* 1️⃣ GET ONLY APPROVED TRANSFER */
        $transfer = $this->db
            ->where('idtbl_stock_transfer', $transfer_id)
            ->where('status', 'APPROVED')
            ->get('tbl_stock_transfer')
            ->row();

        if (!$transfer) {
            $this->db->trans_rollback();
            return ['status'=>0,'message'=>'Invalid or already received transfer'];
        }

        /* 2️⃣ GET TRANSFER ITEMS */
        $items = $this->db
            ->where('tbl_stock_transfer_id', $transfer_id)
            ->get('tbl_stock_transfer_detail')
            ->result();

        if (!$items) {
            $this->db->trans_rollback();
            return ['status'=>0,'message'=>'No items found'];
        }

        /* 3️⃣ INSERT INTO STOCK (TO COMPANY / BRANCH) */
        foreach ($items as $row) {

            $this->db->insert('tbl_stock', [
                'batchno'                         => 'TRANSFER',
                'qty'                             => $row->qty,
                'status'                          => 1,
                'insertdatetime'                  => date('Y-m-d H:i:s'),
                'tbl_res_user_idtbl_res_user'     => $user_id,
                'tbl_res_material_info_idtbl_res_material_info'
                                                 => $row->tbl_res_material_info_id,
                'tbl_company_idtbl_company'       => $transfer->to_company_id,
                'tbl_company_branch_idtbl_company_branch'
                                                 => $transfer->to_branch_id
            ]);
        }

        /* 4️⃣ UPDATE TRANSFER STATUS → RECEIVED */
        $this->db->where('idtbl_stock_transfer', $transfer_id)
            ->update('tbl_stock_transfer', [
                'status'        => 'RECEIVED',
                'approved_by'   => $user_id,
                'approved_date' => date('Y-m-d H:i:s')
            ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status'=>0,'message'=>'Database error'];
        }

        $this->db->trans_commit();
        return ['status'=>1];
    }

}
