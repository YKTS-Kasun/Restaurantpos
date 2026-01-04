<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockTransfer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /* =====================================================
     * GET ALL BRANCHES
     * ===================================================== */
    public function get_branches() {

        return $this->db
            ->where('location_type', 'BRANCH')
            ->where('status', 1)
            ->get('tbl_location');
    }

    /* =====================================================
     * GET HO STOCK (ONLY AVAILABLE QTY)
     * ===================================================== */
 public function get_ho_stock($ho_location_id) {

    return $this->db
        ->select('
            m.idtbl_res_material_info AS material_id,
            m.material,
            SUM(s.qty) AS qty
        ')
        ->from('tbl_stock s')
        ->join(
            'tbl_res_material_info m',
            'm.idtbl_res_material_info = s.tbl_res_material_info_idtbl_res_material_info'
        )
        ->where('s.idtbl_location', $ho_location_id)
        ->where('s.status', 1)
        ->group_by('m.idtbl_res_material_info')
        ->get()
        ->result();
}


public function get_branch_stock($branch_id) {
    return $this->db
        ->select('
            m.idtbl_res_material_info AS material_id,
            m.material,
            SUM(s.qty) AS qty
        ')
        ->from('tbl_stock s')
        ->join(
            'tbl_res_material_info m',
            'm.idtbl_res_material_info = s.tbl_res_material_info_idtbl_res_material_info'
        )
        ->where('s.idtbl_location', $branch_id)
        ->where('s.status', 1)
        ->group_by('m.idtbl_res_material_info')
        ->get()
        ->result();
}


    /* =====================================================
     * CREATE STOCK TRANSFER
     * ===================================================== */
    public function create_transfer($header, $items) {

        $this->db->trans_begin();

        // insert header
        $this->db->insert('tbl_stock_transfer', $header);
        $transfer_id = $this->db->insert_id();

        if(!$transfer_id){
            $this->db->trans_rollback();
            return false;
        }

        // insert details
        foreach($items as $row){

            if((int)$row['qty'] <= 0){
                continue;
            }

$this->db->insert('tbl_stock_transfer_detail', [
    'tbl_stock_transfer_id'      => $transfer_id,
    'tbl_res_material_info_id'   => $row['material_id'],
    'qty'                        => $row['qty']
]);


        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

public function approve_transfer($transfer_id)
{
    $this->db->trans_begin();

    // 1️⃣ GET TRANSFER
    $transfer = $this->db
        ->where('idtbl_stock_transfer', $transfer_id)
        ->get('tbl_stock_transfer')
        ->row();

    if (!$transfer) {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Invalid transfer'];
    }

    // 2️⃣ 🔐 CHECK LOGGED-IN USER IS HO (🔥 REAL FIX)
    if (
        !isset($_SESSION['location_type']) ||
        $_SESSION['location_type'] !== 'HO'
    ) {
        $this->db->trans_rollback();
        return [
            'status'=>false,
            'msg'=>'Only Head Office can approve stock transfers'
        ];
    }

    // 3️⃣ IF ALREADY APPROVED → SKIP
    if ($transfer->status === 'APPROVED') {
        return ['status'=>true];
    }

    // 4️⃣ GET ITEMS
    $items = $this->db
        ->where('tbl_stock_transfer_id', $transfer_id)
        ->get('tbl_stock_transfer_detail')
        ->result();

    foreach ($items as $item) {

        $material_id = (int)$item->tbl_res_material_info_id;
        $qty         = (float)$item->qty;

        // 5️⃣ CHECK STOCK AT SOURCE
        $stock = $this->db
            ->where('idtbl_location', $transfer->from_location_id)
            ->where('tbl_res_material_info_idtbl_res_material_info', $material_id)
            ->get('tbl_stock')
            ->row();

        if (!$stock || $stock->qty < $qty) {
            $this->db->trans_rollback();
            return [
                'status'=>false,
                'msg'=>'Insufficient stock'
            ];
        }

        // 6️⃣ DEDUCT FROM SOURCE
        $this->db->set('qty', 'qty - '.$qty, false)
            ->where('idtbl_location', $transfer->from_location_id)
            ->where('tbl_res_material_info_idtbl_res_material_info', $material_id)
            ->update('tbl_stock');

        // 7️⃣ ADD TO DESTINATION
        $this->db->query("
            INSERT INTO tbl_stock
            (batchno, qty, status, insertdatetime,
             tbl_res_user_idtbl_res_user,
             tbl_res_material_info_idtbl_res_material_info,
             idtbl_location)
            VALUES ('TRANSFER', ?, 1, NOW(), ?, ?, ?)
            ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)
        ", [
            $qty,
            $_SESSION['userid'],
            $material_id,
            $transfer->to_location_id
        ]);
    }

    // 8️⃣ UPDATE TRANSFER STATUS
    $this->db->where('idtbl_stock_transfer', $transfer_id)
        ->update('tbl_stock_transfer', [
            'status'        => 'APPROVED',
            'approved_by'   => $_SESSION['userid'],
            'approved_date' => date('Y-m-d H:i:s')
        ]);

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Database error'];
    }

    $this->db->trans_commit();
    return ['status'=>true];
}


    /* =====================================================
     * GET TRANSFER HEADER (VIEW MODAL)
     * ===================================================== */
 public function get_transfer_header($id) {

    return $this->db
        ->select("
            st.*,
            IF(st.from_location_type='HEAD','Head Office',l1.location_name) AS from_location,
            IF(st.to_location_type='HEAD','Head Office',l2.location_name) AS to_location,
            u.name AS requested_user
        ")
        ->from('tbl_stock_transfer st')
        ->join('tbl_location l1',
            "l1.idtbl_location = st.from_location_id AND st.from_location_type='BRANCH'",
            'left'
        )
        ->join('tbl_location l2',
            "l2.idtbl_location = st.to_location_id AND st.to_location_type='BRANCH'",
            'left'
        )
        ->join('tbl_res_user u','u.idtbl_res_user = st.requested_by')
        ->where('st.idtbl_stock_transfer', $id)
        ->get()
        ->row();
}


    /* =====================================================
     * GET TRANSFER ITEMS (VIEW MODAL)
     * ===================================================== */
    public function get_transfer_items($id) {

        return $this->db
            ->select('d.qty, m.material')
            ->from('tbl_stock_transfer_detail d')
            ->join('tbl_res_material_info m',
       'm.idtbl_res_material_info = d.tbl_res_material_info_id')
->where('d.tbl_stock_transfer_id', $id)
            ->get()
            ->result();
    }
    
    public function reject_transfer($transfer_id)
{
    $this->db->trans_begin();

    // 1️⃣ GET TRANSFER
    $transfer = $this->db
        ->where('idtbl_stock_transfer', $transfer_id)
        ->get('tbl_stock_transfer')
        ->row();

    if (!$transfer) {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Invalid transfer'];
    }

    // ❌ Already rejected
    if ($transfer->status === 'REJECTED') {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Transfer already rejected'];
    }

    // 2️⃣ GET ITEMS
    $items = $this->db
        ->where('tbl_stock_transfer_id', $transfer_id)
        ->get('tbl_stock_transfer_detail')
        ->result();

    // 3️⃣ IF APPROVED → ROLLBACK STOCK
    if ($transfer->status === 'APPROVED') {

        foreach ($items as $item) {

            $material_id = (int)$item->tbl_res_material_info_id;
            $qty         = (float)$item->qty;

            // 🔄 ADD BACK TO SOURCE
            $this->db->set('qty', 'qty + '.$qty, false)
                ->where('idtbl_location', $transfer->from_location_id)
                ->where('tbl_res_material_info_idtbl_res_material_info', $material_id)
                ->update('tbl_stock');

            // 🔄 DEDUCT FROM DESTINATION
            $this->db->set('qty', 'qty - '.$qty, false)
                ->where('idtbl_location', $transfer->to_location_id)
                ->where('tbl_res_material_info_idtbl_res_material_info', $material_id)
                ->update('tbl_stock');
        }
    }

    // 4️⃣ UPDATE TRANSFER STATUS
    $this->db->where('idtbl_stock_transfer', $transfer_id)
        ->update('tbl_stock_transfer', [
            'status'        => 'REJECTED',
            'approved_by'   => $_SESSION['userid'],
            'approved_date' => date('Y-m-d H:i:s')
        ]);

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Database error'];
    }

    $this->db->trans_commit();
    return ['status'=>true];
}

   public function get_all_branches() {
        return $this->db
            ->where('status', 1)
            ->get('tbl_branch')
            ->result();
    }

    public function get_branch_name($id) {
        return $this->db
            ->where('idtbl_branch', $id)
            ->get('tbl_branch')
            ->row();
    }

    public function get_head_office() {
        return $this->db->get('tbl_head_office')->row();
    }

}
