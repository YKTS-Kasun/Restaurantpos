<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');
class StockTransfer extends CI_Controller {

    /* =====================================================
     * MAIN PAGE – LIST + CREATE MODAL
     * ===================================================== */
public function index()
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        redirect('Welcome');
    }

    $this->load->model('Commeninfo');
    $this->load->model('StockTransfer_model');

    /* =========================
     * SESSION DATA
     * ========================= */
    $company_id  = $this->session->userdata('company_id');
    $branch_id   = $this->session->userdata('branch_id'); // NULL = HO
    $branch_name = $this->session->userdata('branch_name');

    /* =========================
     * MENU PRIVILEGES
     * ========================= */
    $menuaccess = $this->Commeninfo->Getmenuprivilege();

    $approvecheck = 0;
    foreach ($menuaccess as $row) {
        if ($row->menuid == 47 && (int)$row->statuschange === 1) {
            $approvecheck = 1;
            break;
        }
    }

    /* =========================
     * AVAILABLE STOCK
     * (HO → company stock)
     * (Branch → branch stock)
     * ========================= */
    $materiallist = $this->StockTransfer_model
        ->get_available_stock($company_id, $branch_id);

    /* =========================
     * DESTINATION LOCATIONS
     * - HO user → all branches
     * - Branch user → other branches + HO
     * ========================= */
    $branchlist = $this->db
        ->where('tbl_company_idtbl_company', $company_id)
        ->where('status', 1)
        ->get('tbl_company_branch')
        ->result();

    /* =========================
     * DATA TO VIEW
     * ========================= */
    $result = [
        'menuaccess'   => $menuaccess,
        'approvecheck' => $approvecheck,

        'company_id'   => $company_id,
        'branch_id'    => $branch_id,
        'branch_name'  => $branch_name,

        'materiallist' => $materiallist,
        'branchlist'   => $branchlist,

        // permission to add (can extend later)
        'addcheck'     => 1
    ];

    $this->load->view('stock_transfer', $result);
}



    /* =====================================================
     * SAVE STOCK TRANSFER (AJAX)
     * ===================================================== */
public function save()
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    $this->load->model('StockTransfer_model');

    /* =========================
     * SESSION DATA
     * ========================= */
    $session_user    = $this->session->userdata('userid');
    $session_company = $this->session->userdata('company_id');
    $session_branch  = $this->session->userdata('branch_id'); // NULL = HO

    /* =========================
     * DESTINATION PARSE
     * ========================= */
    $to_raw = $this->input->post('to_location'); // company|branch

    if (!$to_raw || strpos($to_raw, '|') === false) {
        echo json_encode(['status' => 0, 'message' => 'Invalid destination']);
        return;
    }

    list($to_company_id, $to_branch_id) = explode('|', $to_raw);
    $to_company_id = (int)$to_company_id;
    $to_branch_id  = ($to_branch_id !== '') ? (int)$to_branch_id : null;

    /* =========================
     * PREVENT SAME LOCATION
     * ========================= */
    if (
        $to_company_id == $session_company &&
        $to_branch_id === $session_branch
    ) {
        echo json_encode([
            'status'  => 0,
            'message' => 'Cannot transfer to same location'
        ]);
        return;
    }

    /* =========================
     * ITEMS VALIDATION
     * ========================= */
    $items  = $this->input->post('items');
    $remark = trim($this->input->post('remark'));

    if (empty($items) || !is_array($items)) {
        echo json_encode(['status' => 0, 'message' => 'No items selected']);
        return;
    }

    /* =========================
     * HEADER DATA
     * ========================= */
    $header = [
        'transfer_no'     => 'ST-' . date('YmdHis'),
        'transfer_date'   => date('Y-m-d H:i:s'),
        'from_company_id' => $session_company,
        'from_branch_id'  => $session_branch, // NULL = HO
        'to_company_id'   => $to_company_id,
        'to_branch_id'    => $to_branch_id,
        'requested_by'    => $session_user,
        'status'          => 'PENDING',
        'remark'          => $remark
    ];

    /* =========================
     * DB TRANSACTION
     * ========================= */
    $this->db->trans_start();

    $result = $this->StockTransfer_model->create_transfer($header, $items);

    $this->db->trans_complete();

    if ($this->db->trans_status() === false || !$result) {
        echo json_encode([
            'status'  => 0,
            'message' => 'Failed to create stock transfer'
        ]);
        return;
    }

    echo json_encode([
        'status'  => 1,
        'message' => 'Stock transfer created successfully'
    ]);
}


    /* =====================================================
     * APPROVE TRANSFER (HO ONLY)
     * ===================================================== */
public function approve($transfer_id = 0)
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    if ((int)$transfer_id === 0) {
        show_error('Invalid Transfer');
    }

    /* =========================
     * LOAD MODELS
     * ========================= */
    $this->load->model('StockTransfer_model');
    $this->load->model('Commeninfo');

    /* =========================
     * GET TRANSFER HEADER
     * ========================= */
    $header = $this->StockTransfer_model->get_transfer_header($transfer_id);

    if (!$header || $header->status !== 'PENDING') {
        show_error('Invalid or already processed transfer');
    }

    /* =========================
     * SESSION DATA
     * ========================= */
    $session_user    = $this->session->userdata('userid');
    $session_company = $this->session->userdata('company_id');
    $session_branch  = $this->session->userdata('branch_id'); // NULL = HO

    /* =========================
     * COMPANY SECURITY
     * ========================= */
    if ((int)$header->from_company_id !== (int)$session_company) {
        show_error('Company mismatch');
    }

    /* =========================
     * PRIVILEGE CHECK
     * ========================= */
    $canApprove = false;
    foreach ($this->Commeninfo->Getmenuprivilege() as $row) {
        if ($row->menuid == 47 && (int)$row->statuschange === 1) {
            $canApprove = true;
            break;
        }
    }

    if (!$canApprove) {
        show_error('Permission denied');
    }

    /* =========================
     * LOCATION RULE
     * HO  → can approve ALL
     * BR  → only own source
     * ========================= */
    if (
        $session_branch !== null &&
        (int)$session_branch !== (int)$header->from_branch_id
    ) {
        show_error('You cannot approve this transfer');
    }

    /* =========================
     * APPROVE TRANSFER
     * ========================= */
    $result = $this->StockTransfer_model->approve_transfer(
        $transfer_id,
        $session_user
    );

    /* =========================
     * FEEDBACK
     * ========================= */
    $this->session->set_flashdata(
        'msg',
        json_encode([
            'type'    => $result['status'] ? 'success' : 'danger',
            'title'   => $result['status'] ? 'Success' : 'Error',
            'message' => $result['status']
                ? 'Stock Transfer Approved Successfully'
                : $result['msg']
        ])
    );

    redirect('StockTransfer');
}




public function view_modal()
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    $id = (int)$this->input->post('id');

    /* =========================
     * LOAD MODELS
     * ========================= */
    $this->load->model('StockTransfer_model');
    $this->load->model('Commeninfo');

    /* =========================
     * GET DATA
     * ========================= */
    $header = $this->StockTransfer_model->get_transfer_header($id);
    $items  = $this->StockTransfer_model->get_transfer_items($id);

    if (!$header) {
        show_error('Invalid transfer');
    }

    /* =========================
     * SESSION DATA
     * ========================= */
    $session_company = $this->session->userdata('company_id');
    $session_branch  = $this->session->userdata('branch_id'); // NULL = HO

    /* =========================
     * COMPANY SECURITY
     * ========================= */
    if ((int)$header->from_company_id !== (int)$session_company) {
        show_error('Company mismatch');
    }

    /* =========================
     * HEADER VIEW
     * ========================= */
    echo '
        <p><strong>Transfer No:</strong> '.$header->transfer_no.'</p>
        <p><strong>From:</strong> '.$header->from_location.'</p>
        <p><strong>To:</strong> '.$header->to_location.'</p>
        <p><strong>Status:</strong> '.$header->status.'</p>

        <table class="table table-bordered table-sm mt-3">
            <thead>
                <tr>
                    <th>Material</th>
                    <th class="text-center">Qty</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($items as $row) {
        echo '
            <tr>
                <td>'.$row->material.'</td>
                <td class="text-center">'.$row->qty.'</td>
            </tr>';
    }

    echo '
            </tbody>
        </table>';

    /* ==================================================
     * APPROVE / REJECT BUTTONS
     * Rules:
     * - Status = PENDING
     * - Same company
     * - HO user OR source branch user
     * - statuschange privilege
     * ================================================== */
    $canApprove = false;

    if ($header->status === 'PENDING') {

        // HO user OR source branch user
        if (
            $session_branch === null ||
            (int)$session_branch === (int)$header->from_branch_id
        ) {
            foreach ($this->Commeninfo->Getmenuprivilege() as $row) {
                if ($row->menuid == 47 && (int)$row->statuschange === 1) {
                    $canApprove = true;
                    break;
                }
            }
        }
    }

    if ($canApprove) {

        echo '
        <div class="text-right mt-3">

            <a href="'.base_url('StockTransfer/approve/'.$id).'"
               class="btn btn-success btn-sm"
               onclick="return confirm(\'Approve this transfer?\')">
                <i class="fas fa-check"></i> Approve
            </a>

            <a href="'.base_url('StockTransfer/reject/'.$id).'"
               class="btn btn-danger btn-sm ml-2"
               onclick="return confirm(\'Reject this transfer?\')">
                <i class="fas fa-times"></i> Reject
            </a>

        </div>';
    }
}



public function delete($id = 0)
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    /* =========================
     * SESSION DATA
     * ========================= */
    $session_company = $_SESSION['company_id'];
    $session_branch  = $_SESSION['branch_id']; // NULL if HO user

    /* =========================
     * PRIVILEGE = DELETE
     * ========================= */
    $this->load->model('Commeninfo');
    $privs = $this->Commeninfo->Getmenuprivilege();

    $allow = 0;
    foreach ($privs as $p) {
        if ($p->menuid == 47 && (int)$p->remove === 1) {
            $allow = 1;
            break;
        }
    }

    if ($allow !== 1) {
        show_error('Permission denied');
    }

    /* =========================
     * GET TRANSFER (SECURITY)
     * ========================= */
    $transfer = $this->db
        ->where('idtbl_stock_transfer', $id)
        ->where('status', 'PENDING')
        ->get('tbl_stock_transfer')
        ->row();

    if (!$transfer) {
        show_error('Invalid or non-pending transfer');
    }

    /* =========================
     * COMPANY CHECK
     * ========================= */
    if ((int)$transfer->from_company_id !== (int)$session_company) {
        show_error('Company mismatch');
    }

    /* =========================
     * BRANCH CHECK
     * ========================= */
    // Branch user → must match branch
    if (!empty($session_branch)) {
        if ((int)$transfer->from_branch_id !== (int)$session_branch) {
            show_error('Branch mismatch');
        }
    }

    /* =========================
     * DELETE (DETAIL → HEADER)
     * ========================= */
    $this->db->trans_start();

    $this->db->where('tbl_stock_transfer_id', $id)
             ->delete('tbl_stock_transfer_detail');

    $this->db->where('idtbl_stock_transfer', $id)
             ->where('status', 'PENDING')
             ->delete('tbl_stock_transfer');

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        show_error('Delete failed');
    }

    redirect('StockTransfer');
}


public function reject($id = 0)
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    if ((int)$id === 0) {
        show_error('Invalid Transfer');
    }

    $this->load->model('Commeninfo');
    $this->load->model('StockTransfer_model');

    /* =========================
     * GET TRANSFER HEADER
     * ========================= */
    $header = $this->StockTransfer_model->get_transfer_header($id);

    if (!$header || $header->status !== 'PENDING') {
        show_error('Invalid transfer');
    }

    /* =========================
     * PRIVILEGE CHECK (statuschange)
     * ========================= */
    $canReject = 0;
    foreach ($this->Commeninfo->Getmenuprivilege() as $row) {
        if ($row->menuid == 47 && (int)$row->statuschange === 1) {
            $canReject = 1;
            break;
        }
    }

    if ($canReject !== 1) {
        show_error('Permission denied');
    }

    /* =========================
     * LOCATION RULE (IMPORTANT)
     * Company (HO) → reject ALL
     * Branch       → reject ONLY own
     * ========================= */
    $user_branch = $this->session->userdata('branch_id'); // NULL = HO

    if ($user_branch !== null && $user_branch != $header->from_branch_id) {
        show_error('You cannot reject this transfer');
    }

    /* =========================
     * REJECT TRANSFER
     * ========================= */
    $result = $this->StockTransfer_model->reject_transfer($id);

    $this->session->set_flashdata(
        'msg',
        json_encode([
            'type'    => $result['status'] ? 'success' : 'danger',
            'title'   => $result['status'] ? 'Success' : 'Error',
            'message' => $result['status']
                ? 'Stock Transfer Rejected'
                : $result['msg']
        ])
    );

    redirect('StockTransfer');
}


   public function view()
{
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    $id = (int)$this->input->post('recordID');
    if ($id === 0) {
        show_error('Invalid transfer');
    }

    $this->load->model('StockTransfer_model');

    $header = $this->StockTransfer_model->get_transfer_header($id);
    if (!$header) {
        show_error('Invalid transfer');
    }

    /* =========================
     * LOCATION ACCESS RULE
     * HO → view ALL
     * Branch → view only FROM / TO
     * ========================= */
    $user_branch = $this->session->userdata('branch_id'); // NULL = HO

    if (
        $user_branch !== null &&
        $user_branch != $header->from_branch_id &&
        $user_branch != $header->to_branch_id
    ) {
        show_error('Access denied');
    }

    $data['header'] = $header;
    $data['items']  = $this->StockTransfer_model->get_transfer_items($id);

    $this->load->view('stock_transfer_view', $data);
}


public function edit($id = 0)
{
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    $id = (int)$id;
    if ($id === 0) {
        show_error('Invalid transfer');
    }

    $this->load->model('StockTransfer_model');

    $header = $this->StockTransfer_model->get_transfer_header($id);

    /* =========================
     * STATUS CHECK
     * ========================= */
    if (!$header || $header->status !== 'PENDING') {
        show_error('Only pending transfers can be edited');
    }

    /* =========================
     * LOCATION RULE
     * HO → edit ALL
     * Branch → edit ONLY own
     * ========================= */
    $user_branch = $this->session->userdata('branch_id'); // NULL = HO

    if ($user_branch !== null && $user_branch != $header->from_branch_id) {
        show_error('You cannot edit this transfer');
    }

    $items = $this->StockTransfer_model->get_transfer_items($id);

    echo json_encode([
        'status' => 1,
        'header' => $header,
        'items'  => $items
    ]);
}

public function update()
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    if ($this->input->method() !== 'post') {
        show_error('Invalid request');
    }

    $this->load->model('StockTransfer_model');

    $transfer_id = (int)$this->input->post('transfer_id');
    $remark      = trim($this->input->post('remark'));
    $items       = $this->input->post('items');

    if ($transfer_id === 0 || empty($items)) {
        echo json_encode(['status'=>0,'message'=>'Invalid data']);
        return;
    }

    /* =========================
     * GET TRANSFER HEADER
     * ========================= */
    $header = $this->StockTransfer_model->get_transfer_header($transfer_id);

    if (!$header || $header->status !== 'PENDING') {
        echo json_encode([
            'status'  => 0,
            'message' => 'Only pending transfers can be updated'
        ]);
        return;
    }

    /* =========================
     * LOCATION RULE
     * Company (HO) → update ALL
     * Branch       → update ONLY own
     * ========================= */
    $user_branch = $this->session->userdata('branch_id'); // NULL = HO

    if ($user_branch !== null && $user_branch != $header->from_branch_id) {
        echo json_encode([
            'status'  => 0,
            'message' => 'You cannot update this transfer'
        ]);
        return;
    }

    /* =========================
     * UPDATE TRANSFER
     * ========================= */
    $result = $this->StockTransfer_model->update_transfer(
        $transfer_id,
        $remark,
        $items
    );

    echo json_encode([
        'status'  => $result ? 1 : 0,
        'message' => $result
            ? 'Transfer updated successfully'
            : 'Update failed'
    ]);
}

public function get_available_qty()
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        echo json_encode([
            'status'  => 0,
            'message' => 'Unauthorized'
        ]);
        return;
    }

    $material_id = (int)$this->input->post('material_id');

    if ($material_id === 0) {
        echo json_encode([
            'status'  => 0,
            'message' => 'Invalid material'
        ]);
        return;
    }

    $company_id = $this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id'); // NULL = HO

    $this->load->model('StockTransfer_model');

    /* =========================
     * GET AVAILABLE QTY
     * HO      → company stock
     * Branch  → branch stock
     * ========================= */
    $qty = $this->StockTransfer_model
        ->get_stock_qty($material_id, $company_id, $branch_id);

    echo json_encode([
        'status' => 1,
        'qty'    => (double)$qty
    ]);
}


public function dispatch_report_pdf($transfer_id = 0)
{
    /* =========================
     * AUTH CHECK
     * ========================= */
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    $transfer_id = (int)$transfer_id;
    if ($transfer_id === 0) {
        show_error('Invalid Transfer');
    }

    $company_id = $this->session->userdata('company_id');
    $branch_id  = $this->session->userdata('branch_id'); // NULL = HO

    $this->load->model('StockTransfer_model');
    $this->load->library('Dpdf');

    /* =========================
     * GET TRANSFER DATA
     * ========================= */
    $header = $this->StockTransfer_model->get_transfer_header($transfer_id);
    $items  = $this->StockTransfer_model->get_transfer_items($transfer_id);

    if (!$header) {
        show_404();
    }

    /* =========================
     * LOCATION ACCESS RULE
     * HO      → view ALL
     * Branch  → view only own
     * ========================= */
    if (
        $branch_id !== null &&
        $branch_id != $header->from_branch_id &&
        $branch_id != $header->to_branch_id
    ) {
        show_error('You are not allowed to view this report');
    }

    /* =========================
     * STATUS RULE
     * Only APPROVED / RECEIVED
     * ========================= */
    if (!in_array($header->status, ['APPROVED', 'RECEIVED'])) {
        show_error('Dispatch report not available for this status');
    }

    /* =========================
     * PDF GENERATION
     * ========================= */
    $data = [
        'header' => $header,
        'items'  => $items
    ];

    $html = $this->load->view(
        'dispatch_report_pdf',
        $data,
        true
    );

    $this->dpdf->loadHtml($html);
    $this->dpdf->setPaper('A4', 'portrait');
    $this->dpdf->render();

    $filename = 'Dispatch_Report_' . $header->transfer_no . '.pdf';

    // Attachment = false → open in browser
    $this->dpdf->stream($filename, ['Attachment' => false]);
}


}
