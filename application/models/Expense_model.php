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
    $company_id = (int) $this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id'); // NULL = HO

    if ($company_id <= 0) {
        log_message('error', 'Expense insert blocked: invalid company');
        return false;
    }

    return $this->db->insert('tbl_expense', [
        'tbl_company_idtbl_company' => $company_id,
        'tbl_company_branch_idtbl_company_branch' => $branch_id,
        'categoryid'     => (int)$data['categoryid'],
        'description'    => trim($data['description']),
        'amount'         => (float)$data['amount'],
        'expdate'        => $data['expdate'],
        'status'         => 1,
        'insertdatetime' => date('Y-m-d H:i:s'),
        'insertuser'     => (int)$this->session->userdata('userid')
    ]);
}





public function update_manual_expense($id, $data)
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->where('idtbl_expense', $id);
    $this->db->where('tbl_company_idtbl_company', $company_id);

    // Branch user restriction
    if (!is_null($branch_id)) {
        $this->db->where(
            'tbl_company_branch_idtbl_company_branch',
            $branch_id
        );
    }

    return $this->db->update('tbl_expense', [
        'categoryid'     => $data['categoryid'],
        'description'    => trim($data['description']),
        'amount'         => (float)$data['amount'],
        'expdate'        => $data['expdate'],
        'updatedatetime' => date('Y-m-d H:i:s'),
        'updateuser'     => $this->session->userdata('userid')
    ]);
}



public function get_manual_expense($id)
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->where('idtbl_expense', $id);
    $this->db->where('tbl_company_idtbl_company', $company_id);
    $this->db->where('status', 1);

    if (!is_null($branch_id)) {
        $this->db->where(
            'tbl_company_branch_idtbl_company_branch',
            $branch_id
        );
    }

    return $this->db->get('tbl_expense')->row();
}


public function soft_delete_manual_expense($id)
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->where('idtbl_expense', $id);
    $this->db->where('tbl_company_idtbl_company', $company_id);

    if (!is_null($branch_id)) {
        $this->db->where(
            'tbl_company_branch_idtbl_company_branch',
            $branch_id
        );
    }

    return $this->db->update('tbl_expense', [
        'status'         => 0,
        'updatedatetime' => date('Y-m-d H:i:s'),
        'updateuser'     => $this->session->userdata('userid')
    ]);
}



public function list_manual_expenses()
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->select('
        e.*,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join(
        'tbl_expense_category c',
        'c.idtbl_expense_category = e.categoryid'
    );
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    // Branch restriction
    if (!is_null($branch_id)) {
        $this->db->where(
            'e.tbl_company_branch_idtbl_company_branch',
            $branch_id
        );
    }

    $this->db->order_by('e.expdate', 'DESC');
    return $this->db->get()->result();
}



    /* ---------------- COMBINED EXPENSE LIST (UPGRADED) ---------------- */

public function get_all_expenses($filters = [])
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $date_from = $filters['date_from'] ?? null;
    $date_to   = $filters['date_to']   ?? null;
    $category  = $filters['category']  ?? null;

    $this->db->select("
        e.idtbl_expense AS id,
        e.categoryid,
        'MANUAL' AS source,
        c.category,
        b.branch AS branch_name,
        e.description,
        e.amount,
        e.expdate AS edate,
        1 AS can_edit
    ");
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if (!is_null($branch_id)) {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

if ($date_from) $this->db->where('e.expdate >=', $date_from);
if ($date_to)   $this->db->where('e.expdate <=', $date_to);

if (!empty($category)) {
    $this->db->where('e.categoryid', (int)$category);
}

    $this->db->order_by('e.expdate','DESC');
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

    $rows = $this->get_all_expenses([
        'date_from' => $from,
        'date_to'   => $to
    ]);

    $total = 0;
    foreach ($rows as $r) {
        $total += (float)$r->amount;
    }
    return $total;
}


public function get_daywise_expenses($from = null, $to = null)
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->select('
        e.idtbl_expense,
        e.categoryid,
        e.expdate,
        e.description,
        e.amount,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if (!is_null($branch_id)) {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    if ($from && $to) {
        $this->db->where('e.expdate >=', $from);
        $this->db->where('e.expdate <=', $to);
    }

    $this->db->order_by('e.expdate','DESC');
    return $this->db->get()->result();
}

public function get_day_expenses($date)
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->select('
        e.*,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.expdate', $date);
    $this->db->where('e.status', 1);

    if (!is_null($branch_id)) {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    return $this->db->get()->result();
}

public function get_range_expenses($from, $to, $cat = "")
{
    $company_id = (int)$this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id');

    $this->db->select('
        e.*,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if (!is_null($branch_id)) {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

if ($from) $this->db->where('e.expdate >=', $from);
if ($to)   $this->db->where('e.expdate <=', $to);

if (!empty($cat)) {
    $this->db->where('e.categoryid', (int)$cat);
}

    $this->db->order_by('e.expdate','ASC');
    return $this->db->get()->result();
}

public function user_has_any_location($user_id, $company_id)
{
    return $this->db
        ->where('tbl_res_user_idtbl_res_user', (int)$user_id)
        ->where('tbl_company_idtbl_company', (int)$company_id)
        ->where('status', 1)
        ->count_all_results('tbl_user_location_access') > 0;
}



public function can_edit_expense($expense_id, $user_id, $company_id, $branch_id)
{
    $this->db->from('tbl_expense e');
    $this->db->where('e.idtbl_expense', $expense_id);
    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if ($branch_id === null) {
        // HO → must have explicit access
$this->db->where("
    EXISTS (
        SELECT 1
        FROM tbl_user_location_access ula
        WHERE ula.tbl_res_user_idtbl_res_user = {$this->db->escape($user_id)}
        AND ula.tbl_company_idtbl_company = e.tbl_company_idtbl_company
        AND ula.status = 1
        AND (
            ula.tbl_company_branch_idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch
            OR (
                ula.tbl_company_branch_idtbl_company_branch IS NULL
                AND e.tbl_company_branch_idtbl_company_branch IS NULL
            )
        )
    )
", null, false);


    } else {
        // Branch user → same branch only
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    return $this->db->count_all_results() === 1;
}

public function get_manual_expense_secure($id, $user_id, $company_id, $branch_id)
{
    $this->db->select('e.*, c.category, b.branch AS branch_name');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.idtbl_expense', $id);
    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);
$this->db->order_by('e.expdate','ASC');

    if ($branch_id === null) {
 $this->db->where("
    EXISTS (
        SELECT 1
        FROM tbl_user_location_access ula
        WHERE ula.tbl_res_user_idtbl_res_user = {$this->db->escape($user_id)}
        AND ula.tbl_company_idtbl_company = e.tbl_company_idtbl_company
        AND ula.status = 1
        AND (
            ula.tbl_company_branch_idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch
            OR (
                ula.tbl_company_branch_idtbl_company_branch IS NULL
                AND e.tbl_company_branch_idtbl_company_branch IS NULL
            )
        )
    )
", null, false);


    } else {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    return $this->db->get()->row();
    
}

public function get_daywise_expenses_secure(
    $from,
    $to,
    $user_id,
    $company_id,
    $branch_id
){
    $this->db->select('
        e.idtbl_expense,
        e.categoryid,
        e.expdate,
        e.description,
        e.amount,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if ($from) $this->db->where('e.expdate >=', $from);
    if ($to)   $this->db->where('e.expdate <=', $to);

    if ($branch_id === null) {
 $this->db->where("
    EXISTS (
        SELECT 1
        FROM tbl_user_location_access ula
        WHERE ula.tbl_res_user_idtbl_res_user = {$this->db->escape($user_id)}
        AND ula.tbl_company_idtbl_company = e.tbl_company_idtbl_company
        AND ula.status = 1
        AND (
            ula.tbl_company_branch_idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch
            OR (
                ula.tbl_company_branch_idtbl_company_branch IS NULL
                AND e.tbl_company_branch_idtbl_company_branch IS NULL
            )
        )
    )
", null, false);


    } else {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    $this->db->order_by('e.expdate','DESC');
    return $this->db->get()->result();
}

public function get_range_expenses_secure(
    $from,
    $to,
    $cat,
    $user_id,
    $company_id,
    $branch_id
){
    $this->db->select('
        e.*,
        c.category,
        b.branch AS branch_name
    ');
    $this->db->from('tbl_expense e');
    $this->db->join('tbl_expense_category c','c.idtbl_expense_category = e.categoryid');
    $this->db->join(
        'tbl_company_branch b',
        'b.idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch',
        'left'
    );

    $this->db->where('e.tbl_company_idtbl_company', $company_id);
    $this->db->where('e.status', 1);

    if ($from) $this->db->where('e.expdate >=', $from);
    if ($to)   $this->db->where('e.expdate <=', $to);
    if ($cat)  $this->db->where('e.categoryid', (int)$cat);

    if ($branch_id === null) {
 $this->db->where("
    EXISTS (
        SELECT 1
        FROM tbl_user_location_access ula
        WHERE ula.tbl_res_user_idtbl_res_user = {$this->db->escape($user_id)}
        AND ula.tbl_company_idtbl_company = e.tbl_company_idtbl_company
        AND ula.status = 1
        AND (
            ula.tbl_company_branch_idtbl_company_branch = e.tbl_company_branch_idtbl_company_branch
            OR (
                ula.tbl_company_branch_idtbl_company_branch IS NULL
                AND e.tbl_company_branch_idtbl_company_branch IS NULL
            )
        )
    )
", null, false);


    } else {
        $this->db->where('e.tbl_company_branch_idtbl_company_branch', $branch_id);
    }

    return $this->db->get()->result();
}

}
