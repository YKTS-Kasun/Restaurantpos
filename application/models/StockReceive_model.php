<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockReceive_model extends CI_Model {

    /* =====================================================
     * GET TRANSFER HEADER
     * ===================================================== */
    public function get_transfer_header($id)
    {
        return $this->db
            ->select("
                st.*,
                l.location_name AS from_location
            ")
            ->from('tbl_stock_transfer st')
            ->join('tbl_location l','l.idtbl_location = st.from_location_id')
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
            ->select('d.qty, m.material')
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
     * RECEIVE TRANSFER (FINAL)
     * ===================================================== */
   public function receive_transfer($transfer_id)
{
    
    $this->db->trans_begin();

    // 1️⃣ GET ONLY APPROVED TRANSFER
    $transfer = $this->db
        ->where('idtbl_stock_transfer', $transfer_id)
        ->where('status', 'APPROVED')
        ->get('tbl_stock_transfer')
        ->row();

    if (!$transfer) {
        $this->db->trans_rollback();
        return ['status'=>0,'message'=>'Invalid or already received transfer'];
    }

    // 2️⃣ UPDATE STATUS → RECEIVED
    $this->db->where('idtbl_stock_transfer', $transfer_id)
        ->update('tbl_stock_transfer', [
            'status'        => 'RECEIVED',
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
