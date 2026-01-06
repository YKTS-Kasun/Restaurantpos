<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockTransfer extends CI_Controller {

    /* =====================================================
     * MAIN PAGE – LIST + CREATE MODAL
     * ===================================================== */
   public function index()
{
    if (!$this->session->userdata('userid')) {
        redirect('Welcome');
    }

    $location_id = (int)$this->session->userdata('location_id');

    $fromLocation = $this->db
        ->where('idtbl_location', $location_id)
        ->get('tbl_location')
        ->row();

    if (!$fromLocation) {
        show_error('Invalid user location');
    }

    $data['from_location_id']   = $fromLocation->idtbl_location;
    $data['from_location_type'] = $fromLocation->location_type;
    $data['from_location_name'] = $fromLocation->location_name;

    $this->load->model('Commeninfo');
    $this->load->model('StockTransfer_model');

    $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

    // approve permission
    $data['approvecheck'] = 0;
    foreach ($data['menuaccess'] as $row) {
        if ($row->menuid == 47 && (int)$row->remove === 1) {
            $data['approvecheck'] = 1;
            break;
        }
    }

    $data['branchlist'] = $this->StockTransfer_model->get_branches();

    if ($data['from_location_type'] === 'HO') {
        $data['materiallist'] =
            $this->StockTransfer_model->get_ho_stock($data['from_location_id']);
    } else {
        $data['materiallist'] =
            $this->StockTransfer_model->get_branch_stock($data['from_location_id']);
    }

    $data['addcheck'] = 1;

    $this->load->view('stock_transfer', $data);
}



    /* =====================================================
     * SAVE STOCK TRANSFER (AJAX)
     * ===================================================== */
    public function save() {

        if (!isset($_SESSION['userid'])) {
            show_error('Unauthorized');
        }

        if ($this->input->method() !== 'post') {
            show_error('Invalid Request');
        }

        // load model
        $this->load->model('StockTransfer_model');

       $from_location_id   = (int)$this->input->post('from_location_id');
$from_location_type = $this->input->post('from_location_type');

$to_raw = $this->input->post('to_location');
if (!$to_raw || strpos($to_raw, '-') === false) {
    echo json_encode([
        'status' => 0,
        'message' => 'Invalid destination location'
    ]);
    return;
}

list($to_location_type, $to_location_id) = explode('-', $to_raw);

        $remark        = trim($this->input->post('remark'));
        $items         = $this->input->post('items');

       if (empty($to_location_type) || (int)$to_location_id === 0 || empty($items)) {
            echo json_encode([
                'status'  => 0,
                'message' => 'Invalid transfer data'
            ]);
            return;
        }

        $header = [
    'transfer_no'        => 'ST-' . date('YmdHis'),
    'transfer_date'      => date('Y-m-d'),
    'from_location_type' => $from_location_type,
    'from_location_id'   => $from_location_id,
    'to_location_type'   => $to_location_type,
    'to_location_id'     => (int)$to_location_id,
    'requested_by'       => $_SESSION['userid'],
    'status'             => 'PENDING',
    'remark'             => $remark
];

        $result = $this->StockTransfer_model->create_transfer($header, $items);

        echo json_encode([
            'status'  => $result ? 1 : 0,
            'message' => $result ? 'Stock transfer created successfully'
                                 : 'Stock transfer failed'
        ]);
    }

    /* =====================================================
     * APPROVE TRANSFER (HO ONLY)
     * ===================================================== */
public function approve($transfer_id = 0)
{
    if (!$this->session->userdata('userid')) {
        show_error('Unauthorized');
    }

    if ((int)$transfer_id === 0) {
        show_error('Invalid Transfer');
    }

    // 🔐 privilege check (DELETE = APPROVE)
    $this->load->model('Commeninfo');
    $menuprivilegearray = $this->Commeninfo->Getmenuprivilege();

    $approvecheck = 0;
    foreach ($menuprivilegearray as $row) {
        if ($row->menuid == 47 && (int)$row->remove === 1) {
            $approvecheck = 1;
            break;
        }
    }

    if ($approvecheck !== 1) {
        show_error('Permission denied');
    }

    // ✅ CORRECT & SAFE HO CHECK
    if ($this->session->userdata('location_type') !== 'HO') {
        show_error('Only Head Office can approve stock transfers');
    }

    // ✅ approve logic
    $this->load->model('StockTransfer_model');
    $result = $this->StockTransfer_model->approve_transfer($transfer_id);

    if ($result['status'] === false) {
        $this->session->set_flashdata(
            'msg',
            json_encode([
                'type'    => 'danger',
                'title'   => 'Error',
                'message' => $result['msg']
            ])
        );
    } else {
        $this->session->set_flashdata(
            'msg',
            json_encode([
                'type'    => 'success',
                'title'   => 'Success',
                'message' => 'Stock Transfer Approved'
            ])
        );
    }

    redirect('StockTransfer');
}




public function view_modal()
{
    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    $id = (int)$this->input->post('id');

    $this->load->model('StockTransfer_model');

    $header = $this->StockTransfer_model->get_transfer_header($id);
    $items  = $this->StockTransfer_model->get_transfer_items($id);

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

    /* ===============================
       🔥 APPROVE / REJECT BUTTONS
    =============================== */
    if ($header->status === 'PENDING') {

        // privilege + HO check
        $isHO        = ($this->session->userdata('location_type') === 'HO');
        $canApprove  = false;

        $this->load->model('Commeninfo');
        foreach ($this->Commeninfo->Getmenuprivilege() as $row) {
            if ($row->menuid == 47 && (int)$row->remove === 1) {
                $canApprove = true;
                break;
            }
        }

        if ($isHO && $canApprove) {
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
}



public function delete($id = 0) {

    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    // privilege = DELETE
    $this->load->model('Commeninfo');
    $privs = $this->Commeninfo->Getmenuprivilege();

    $allow = 0;
    foreach ($privs as $p) {
        if ($p->menuid == 47 && $p->remove == 1) {
            $allow = 1;
        }
    }

    if ($allow != 1) {
        show_error('Permission denied');
    }

    $this->db->where('idtbl_stock_transfer', $id)
             ->where('status', 'PENDING')
             ->delete('tbl_stock_transfer');

    $this->db->where('tbl_stock_transfer_id', $id)
             ->delete('tbl_stock_transfer_detail');

    redirect('StockTransfer');
}

public function reject($id = 0)
{
    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    // HO only
    if ($this->session->userdata('location_type') !== 'HO') {
        show_error('Only Head Office can reject stock transfers');
    }

    $this->load->model('StockTransfer_model');

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





    /* =====================================================
     * VIEW TRANSFER DETAILS (MODAL)
     * ===================================================== */
    public function view() {

        if (!isset($_SESSION['userid'])) {
            show_error('Unauthorized');
        }

        $id = (int)$this->input->post('recordID');

        // load model
        $this->load->model('StockTransfer_model');

        $data['header'] = $this->StockTransfer_model->get_transfer_header($id);
        $data['items']  = $this->StockTransfer_model->get_transfer_items($id);

        $this->load->view('stock_transfer_view', $data);
    }

    public function edit($id = 0)
{
    if (!isset($_SESSION['userid'])) {
        show_error('Unauthorized');
    }

    $id = (int)$id;
    if ($id === 0) {
        show_error('Invalid transfer');
    }

    $this->load->model('StockTransfer_model');

    $header = $this->StockTransfer_model->get_transfer_header($id);

    // 🔐 Only pending transfers editable
    if (!$header || $header->status !== 'PENDING') {
        show_error('Only pending transfers can be edited');
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
    if (!isset($_SESSION['userid'])) {
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

    $result = $this->StockTransfer_model->update_transfer(
        $transfer_id,
        $remark,
        $items
    );

    echo json_encode([
        'status'  => $result ? 1 : 0,
        'message' => $result ? 'Transfer updated successfully' : 'Update failed'
    ]);
}


}
