<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class StockReceive extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        /* =========================
         * AUTH CHECK
         * ========================= */
        if (!$this->session->userdata('userid')) {
            redirect('Welcome');
        }

        $this->load->model('StockReceive_model');
        $this->load->model('Commeninfo');
    }

    /* =====================================================
     * MAIN VIEW
     * ===================================================== */
    public function index()
    {
        /* =========================
         * MENU PRIVILEGES
         * ========================= */
        $menuaccess = $this->Commeninfo->Getmenuprivilege();

        $receivecheck = 0;
        foreach ($menuaccess as $row) {
            if ($row->menuid == 49 && (int)$row->statuschange === 1) {
                $receivecheck = 1;
                break;
            }
        }

        $data = [
            'menuaccess'  => $menuaccess,
            'receivecheck'=> $receivecheck
        ];

        $this->load->view('stock_receive', $data);
    }

    /* =====================================================
     * VIEW RECEIVE MODAL
     * ===================================================== */
    public function view_modal()
    {
        /* =========================
         * AUTH CHECK
         * ========================= */
        if (!$this->session->userdata('userid')) {
            show_error('Unauthorized');
        }

        $id = (int)$this->input->post('id');
        if ($id === 0) {
            echo '<div class="alert alert-danger">Invalid Transfer</div>';
            return;
        }

        /* =========================
         * GET DATA
         * ========================= */
        $header = $this->StockReceive_model->get_transfer_header($id);
        $items  = $this->StockReceive_model->get_transfer_items($id);

        if (!$header) {
            echo '<div class="alert alert-danger">Invalid Transfer</div>';
            return;
        }

        /* =========================
         * SESSION DATA
         * ========================= */
        $session_company = $this->session->userdata('company_id');
        $session_branch  = $this->session->userdata('branch_id'); // NULL = HO

        /* =========================
         * LOCATION ACCESS RULE
         * HO      → view ALL
         * Branch  → view only TO branch
         * ========================= */
        if (
            $session_branch !== null &&
            (int)$session_branch !== (int)$header->to_branch_id
        ) {
            echo '<div class="alert alert-danger">Access Denied</div>';
            return;
        }

        /* =========================
         * VIEW OUTPUT
         * ========================= */
        echo '
        <p><strong>Transfer No:</strong> '.$header->transfer_no.'</p>
        <p><strong>From:</strong> '.$header->from_company.' - '.$header->from_branch.'</p>
        <p><strong>To:</strong> '.$header->to_company.' - '.$header->to_branch.'</p>
        <p><strong>Date:</strong> '.$header->transfer_date.'</p>
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
    }

    /* =====================================================
     * CONFIRM RECEIVE
     * ===================================================== */
    public function receive($transfer_id = 0)
    {
        /* =========================
         * AUTH CHECK
         * ========================= */
        if (!$this->session->userdata('userid')) {
            echo json_encode(['status'=>0,'message'=>'Unauthorized']);
            return;
        }

        if ((int)$transfer_id === 0) {
            echo json_encode(['status'=>0,'message'=>'Invalid transfer']);
            return;
        }

        $user_id = (int)$this->session->userdata('userid');

        /* =========================
         * PRIVILEGE CHECK (menu 49)
         * ========================= */
        $receivecheck = 0;
        foreach ($this->Commeninfo->Getmenuprivilege() as $row) {
            if ($row->menuid == 49 && (int)$row->statuschange === 1) {
                $receivecheck = 1;
                break;
            }
        }

        if ($receivecheck !== 1) {
            echo json_encode([
                'status'  => 0,
                'message' => 'Permission denied'
            ]);
            return;
        }

        /* =========================
         * GET TRANSFER HEADER
         * ========================= */
        $transfer = $this->db
            ->where('idtbl_stock_transfer', $transfer_id)
            ->where('status', 'APPROVED')
            ->get('tbl_stock_transfer')
            ->row();

        if (!$transfer) {
            echo json_encode([
                'status'=>0,
                'message'=>'Invalid or already received transfer'
            ]);
            return;
        }

        /* =========================
         * LOCATION ACCESS RULE
         * HO      → receive ALL
         * Branch  → receive ONLY own branch
         * ========================= */
        $user_branch = $this->session->userdata('branch_id'); // NULL = HO

        if (
            $user_branch !== null &&
            (int)$user_branch !== (int)$transfer->to_branch_id
        ) {
            echo json_encode([
                'status'=>0,
                'message'=>'You cannot receive stock for this branch'
            ]);
            return;
        }

        /* =========================
         * RECEIVE STOCK
         * ========================= */
        $result = $this->StockReceive_model
            ->receive_transfer($transfer_id, $user_id);

        if ($result['status'] === 1) {
            echo json_encode(['status'=>1]);
        } else {
            echo json_encode($result);
        }
    }

}
