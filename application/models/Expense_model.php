<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

   /* ---------------- CATEGORY ---------------- */

public function get_categories($only_active = false)
{
    if ($only_active) {
        $this->db->where('status', 1);
    }
    return $this->db
        ->order_by('category', 'ASC')
        ->get('tbl_expense_category')
        ->result();
}

public function add_category($category)
{
    // prevent duplicates
    $exists = $this->db
        ->where('category', $category)
        ->get('tbl_expense_category')
        ->row();

    if ($exists) {
        return ['status' => false, 'msg' => 'Category already exists'];
    }

    $insert = [
        'category'       => $category,
        'status'         => 1,
        'insertdatetime' => date('Y-m-d H:i:s')
    ];

    $this->db->insert('tbl_expense_category', $insert);
    return ['status' => true];
}

public function update_category($id, $new_name)
{
    $this->db->where('idtbl_expense_category', $id);

    return $this->db->update(
        'tbl_expense_category',
        [
            'category'       => $new_name,
            'updatedatetime' => date('Y-m-d H:i:s')
        ]
    );
}

public function update_category_status($id, $status)
{
    return $this->db
        ->where('idtbl_expense_category', $id)
        ->update('tbl_expense_category', [
            'status'         => (int) $status,
            'updatedatetime' => date('Y-m-d H:i:s')
        ]);
}

public function delete_category($id)
{
    // Soft delete
    return $this->db
        ->where('idtbl_expense_category', $id)
        ->update('tbl_expense_category', [
            'status'         => 0,
            'updatedatetime' => date('Y-m-d H:i:s')
        ]);
}


    /* ---------------- MANUAL EXPENSE (UPGRADED) ---------------- */

public function add_manual_expense($data)
{
    $insert = [
        'categoryid'     => $data['categoryid'],
        'description'    => trim($data['description']),
        'amount'         => (float)$data['amount'],
        'expdate'        => $data['expdate'],
        'status'         => 1,
        'insertdatetime' => date('Y-m-d H:i:s'),
        'updateuser'     => $this->session->userdata('userid')
    ];

    return $this->db->insert('tbl_expense', $insert);
}

public function update_manual_expense($id, $data)
{
    $update = [
        'categoryid'     => $data['categoryid'],
        'description'    => trim($data['description']),
        'amount'         => (float)$data['amount'],
        'expdate'        => $data['expdate'],
        'updatedatetime' => date('Y-m-d H:i:s'),
        'updateuser'     => $this->session->userdata('userid')
    ];

    return $this->db
        ->where('idtbl_expense', $id)
        ->update('tbl_expense', $update);
}

public function get_manual_expense($id)
{
    return $this->db
        ->where('idtbl_expense', $id)
        //->where('ref_type', 'MANUAL')
        ->get('tbl_expense')
        ->row();
}

public function soft_delete_manual_expense($id)
{
    return $this->db
        ->where('idtbl_expense', $id)
        ->update('tbl_expense', [
            'status'         => 0,
            'updatedatetime' => date('Y-m-d H:i:s'),
            'updateuser'     => $this->session->userdata('userid')
        ]);
}

public function list_manual_expenses()
{
    return $this->db
        ->select('e.*, c.category')
        ->from('tbl_expense e')
        ->join('tbl_expense_category c', 'c.idtbl_expense_category = e.categoryid')
        //->where('e.ref_type', 'MANUAL')
        ->where('e.status', 1)
        ->order_by('e.expdate', 'DESC')
        ->get()
        ->result();
}


    /* ---------------- COMBINED EXPENSE LIST (UPGRADED) ---------------- */

public function get_all_expenses($filters = [])
{
    $date_from = isset($filters['date_from']) ? $filters['date_from'] : null;
    $date_to   = isset($filters['date_to'])   ? $filters['date_to']   : null;
    $category  = isset($filters['category'])  ? $filters['category']  : null;

    $this->db->select("
    e.idtbl_expense AS id,
    e.categoryid AS categoryid,
    'MANUAL' AS source,
    c.category AS category,
    e.description AS description,
    e.amount AS amount,
    e.expdate AS edate,
    1 AS can_edit
");


    $this->db->from("tbl_expense e");
    $this->db->join("tbl_expense_category c", "c.idtbl_expense_category = e.categoryid");
    $this->db->where("e.status", 1);

    if (!empty($date_from)) {
        $this->db->where("e.expdate >=", $date_from);
    }
    if (!empty($date_to)) {
        $this->db->where("e.expdate <=", $date_to);
    }
    if (!empty($category)) {
        $this->db->where("c.category", $category);
    }

    $this->db->order_by("e.expdate", "DESC");

    return $this->db->get()->result();
}


    /* ---- Dashboard quick total ---- */

    public function get_today_total()
    {
        $today = date('Y-m-d');
        $rows = $this->get_all_expenses([
            'date_from' => $today,
            'date_to'   => $today
        ]);
        $total = 0;
        foreach ($rows as $r) {
            $total += (float)$r->amount;
        }
        return $total;
    }

    public function get_month_total()
    {
        $from = date('Y-m-01');
        $to   = date('Y-m-t');
        $rows = $this->get_all_expenses($from, $to);
        $total = 0;
        foreach ($rows as $r) {
            $total += (float)$r->amount;
        }
        return $total;
    }
    public function get_daywise_expenses($from = null, $to = null)
{
    $this->db->select('e.idtbl_expense, e.expdate, e.description, e.amount, e.categoryid, c.category');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c', 'c.idtbl_expense_category = e.categoryid');
    $this->db->where('e.status', 1);

    if ($from && $to) {
        $this->db->where('e.expdate >=', $from);
        $this->db->where('e.expdate <=', $to);
    }

    $this->db->order_by('e.expdate', 'DESC');
    return $this->db->get()->result();
}

public function get_day_expenses($date)
{
    return $this->db
        ->select('e.*, c.category')
        ->from('tbl_expense e')
        ->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid')
        ->where('e.status', 1)
        ->where('e.expdate', $date)
        ->order_by('e.idtbl_expense','ASC')
        ->get()
        ->result();
}

public function get_range_expenses($from, $to, $cat = "")
{
    $this->db->select("e.*, c.category");
    $this->db->from("tbl_expense e");
    $this->db->join("tbl_expense_category c", "c.idtbl_expense_category = e.categoryid");
    $this->db->where("e.status", 1);

    if($from != "")
        $this->db->where("e.expdate >=", $from);

    if($to != "")
        $this->db->where("e.expdate <=", $to);

    if($cat != "")
        $this->db->where("c.category", $cat);

    $this->db->order_by("e.expdate", "ASC");

    return $this->db->get()->result();
}


}
