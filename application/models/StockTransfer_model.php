<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockTransfer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /* =====================================================
     * GET AVAILABLE STOCK (HO / BRANCH)
     * ===================================================== */
public function get_available_stock($company_id, $branch_id = null)
{
    $this->db
        ->select('
            m.idtbl_res_material_info AS material_id,
            m.material,
            IFNULL(SUM(s.qty),0) AS qty
        ')
        ->from('tbl_res_material_info m')
        ->join(
            'tbl_stock s',
            's.tbl_res_material_info_idtbl_res_material_info = m.idtbl_res_material_info
             AND s.tbl_company_idtbl_company = '.$company_id.'
             AND s.status = 1',
            'left'
        );

    if (!empty($branch_id)) {
        $this->db->where('s.tbl_company_branch_idtbl_company_branch', $branch_id);
    } else {
        $this->db->where('s.tbl_company_branch_idtbl_company_branch IS NULL', null, false);
    }

    return $this->db
        ->group_by('m.idtbl_res_material_info')
        ->having('qty >', 0)
        ->get()
        ->result();
}


    /* =====================================================
     * CREATE STOCK TRANSFER
     * ===================================================== */
    public function create_transfer($header, $items)
    {
        $this->db->trans_begin();

        $this->db->insert('tbl_stock_transfer', $header);
        $transfer_id = $this->db->insert_id();

        if (!$transfer_id) {
            $this->db->trans_rollback();
            return false;
        }

        foreach ($items as $row) {

            if ((int)$row['qty'] <= 0) continue;

            $this->db->insert('tbl_stock_transfer_detail', [
                'tbl_stock_transfer_id'  => $transfer_id,
                'tbl_res_material_info_id' => $row['material_id'],
                'qty' => $row['qty']
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /* =====================================================
     * APPROVE TRANSFER (FIFO)
     * ===================================================== */
 public function approve_transfer($transfer_id)
{
    $this->db->trans_begin();

    $transfer = $this->db
        ->where('idtbl_stock_transfer', $transfer_id)
        ->where('status', 'PENDING')
        ->get('tbl_stock_transfer')
        ->row();

    if (!$transfer) {
        $this->db->trans_rollback();
        return ['status'=>false,'msg'=>'Invalid or processed transfer'];
    }

    $items = $this->db
        ->where('tbl_stock_transfer_id', $transfer_id)
        ->get('tbl_stock_transfer_detail')
        ->result();

    foreach ($items as $item) {

        $material_id = (int)$item->tbl_res_material_info_id;
        $reqQty      = (float)$item->qty;

        /* 🔒 LOCK STOCK ROWS */
        $sql = "
            SELECT *
            FROM tbl_stock
            WHERE tbl_company_idtbl_company = ?
              AND tbl_res_material_info_idtbl_res_material_info = ?
              AND status = 1
              AND qty > 0
        ";

        $params = [$transfer->from_company_id, $material_id];

        if (!empty($transfer->from_branch_id)) {
            $sql .= " AND tbl_company_branch_idtbl_company_branch = ?";
            $params[] = $transfer->from_branch_id;
        } else {
            $sql .= " AND tbl_company_branch_idtbl_company_branch IS NULL";
        }

        $sql .= " ORDER BY insertdatetime ASC FOR UPDATE";

        $stocks = $this->db->query($sql, $params)->result();

        $available = array_sum(array_column($stocks, 'qty'));

        if ($available < $reqQty) {
            $this->db->trans_rollback();
            return ['status'=>false,'msg'=>'Insufficient stock'];
        }

        /* FIFO DEDUCT */
        $balance = $reqQty;
        foreach ($stocks as $s) {
            if ($balance <= 0) break;

            if ($s->qty <= $balance) {
                $this->db->where('idtbl_stock', $s->idtbl_stock)
                         ->update('tbl_stock', ['qty'=>0]);
                $balance -= $s->qty;
            } else {
                $this->db->set('qty', 'qty - '.$balance, false)
                         ->where('idtbl_stock', $s->idtbl_stock)
                         ->update('tbl_stock');
                $balance = 0;
            }
        }

        /* ADD DESTINATION STOCK */
        $this->db->insert('tbl_stock', [
            'batchno'   => 'TRANSFER',
            'qty'       => $reqQty,
            'status'    => 1,
            'insertdatetime' => date('Y-m-d H:i:s'),
            'tbl_res_user_idtbl_res_user' => $this->session->userdata('userid'),
            'tbl_res_material_info_idtbl_res_material_info' => $material_id,
            'tbl_company_idtbl_company'   => $transfer->to_company_id,
            'tbl_company_branch_idtbl_company_branch' =>
                !empty($transfer->to_branch_id) ? $transfer->to_branch_id : null
        ]);
    }

    $this->db->where('idtbl_stock_transfer', $transfer_id)
        ->update('tbl_stock_transfer', [
            'status'        => 'APPROVED',
            'approved_by'   => $this->session->userdata('userid'),
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
     * VIEW TRANSFER HEADER
     * ===================================================== */
    public function get_transfer_header($id)
    {
        return $this->db
            ->select("
                st.*,
                IF(st.from_branch_id IS NULL,'Head Office',cbf.branch) AS from_location,
                IF(st.to_branch_id IS NULL,'Head Office',cbt.branch)   AS to_location,
                req.name AS requested_user,
                app.name AS approved_by_name
            ")
            ->from('tbl_stock_transfer st')
            ->join('tbl_company_branch cbf','cbf.idtbl_company_branch = st.from_branch_id','left')
            ->join('tbl_company_branch cbt','cbt.idtbl_company_branch = st.to_branch_id','left')
            ->join('tbl_res_user req','req.idtbl_res_user = st.requested_by','left')
            ->join('tbl_res_user app','app.idtbl_res_user = st.approved_by','left')
            ->where('st.idtbl_stock_transfer', $id)
            ->get()
            ->row();
    }

    /* =====================================================
     * VIEW TRANSFER ITEMS
     * ===================================================== */
    public function get_transfer_items($id)
    {
        return $this->db
            ->select('d.qty, m.material, m.materialinfocode')
            ->from('tbl_stock_transfer_detail d')
            ->join(
                'tbl_res_material_info m',
                'm.idtbl_res_material_info = d.tbl_res_material_info_id'
            )
            ->where('d.tbl_stock_transfer_id', $id)
            ->get()
            ->result();
    }
public function reject_transfer($transfer_id)
{
    $this->db->trans_begin();

    $transfer = $this->db
        ->where('idtbl_stock_transfer', $transfer_id)
        ->where('status', 'PENDING')
        ->get('tbl_stock_transfer')
        ->row();

if (!$transfer) {
    $this->db->trans_rollback();
    return ['status'=>false,'msg'=>'Invalid transfer'];
}


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

public function get_stock_qty($material_id, $company_id, $branch_id = null)
{
    $this->db->select_sum('qty')
        ->from('tbl_stock')
        ->where('tbl_res_material_info_idtbl_res_material_info', $material_id)
        ->where('tbl_company_idtbl_company', $company_id)
        ->where('status', 1);

 if ($branch_id !== null && $branch_id !== '' && $branch_id != 0) {
    $this->db->where('tbl_company_branch_idtbl_company_branch', $branch_id);
} else {
    $this->db->where('tbl_company_branch_idtbl_company_branch IS NULL', null, false);
}


    $row = $this->db->get()->row();
    return $row ? (float)$row->qty : 0;
}

    /* =====================================================
     * UPDATE TRANSFER (PENDING ONLY)
     * ===================================================== */
    public function update_transfer($transfer_id, $remark, $items)
    {
        $this->db->trans_begin();

        $transfer = $this->db
            ->where('idtbl_stock_transfer', $transfer_id)
            ->where('status', 'PENDING')
            ->get('tbl_stock_transfer')
            ->row();

        if (!$transfer) return false;

        $this->db->where('idtbl_stock_transfer', $transfer_id)
            ->update('tbl_stock_transfer', ['remark'=>$remark]);

        $this->db->where('tbl_stock_transfer_id', $transfer_id)
            ->delete('tbl_stock_transfer_detail');

        foreach ($items as $row) {
            if ((int)$row['qty'] <= 0) continue;

            $this->db->insert('tbl_stock_transfer_detail', [
                'tbl_stock_transfer_id'  => $transfer_id,
                'tbl_res_material_info_id' => $row['material_id'],
                'qty' => $row['qty']
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
}
