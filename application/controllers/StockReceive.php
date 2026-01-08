<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockReceive extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('userid')){
            redirect('Welcome');
        }
        $this->load->model('StockReceive_model');
    }

    /* =====================================================
     * MAIN VIEW
     * ===================================================== */
    public function index()
    {
        $this->load->model('Commeninfo');
    $this->load->model('StockTransfer_model');

    $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('stock_receive' , $data);
    }

    /* =====================================================
     * VIEW RECEIVE MODAL
     * ===================================================== */
    public function view_modal()
    {
        $id = (int)$this->input->post('id');

        $data['header'] = $this->StockReceive_model->get_transfer_header($id);
        $data['items']  = $this->StockReceive_model->get_transfer_items($id);

        if(!$data['header']){
            echo '<div class="alert alert-danger">Invalid Transfer</div>';
            return;
        }

        echo '
        <p><strong>Transfer No:</strong> '.$data['header']->transfer_no.'</p>
        <p><strong>From:</strong> '.$data['header']->from_location.'</p>
        <p><strong>Date:</strong> '.$data['header']->transfer_date.'</p>

        <table class="table table-bordered table-sm mt-3">
            <thead>
                <tr>
                    <th>Material</th>
                    <th class="text-center">Qty</th>
                </tr>
            </thead>
            <tbody>';

        foreach($data['items'] as $row){
            echo '
                <tr>
                    <td>'.$row->material.'</td>
                    <td class="text-center">'.$row->qty.'</td>
                </tr>
            ';
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
        if($transfer_id == 0){
            echo json_encode(['status'=>0,'message'=>'Invalid transfer']);
            return;
        }

        $result = $this->StockReceive_model->receive_transfer($transfer_id);

        if($result){
            echo json_encode(['status'=>1]);
        }else{
            echo json_encode([
                'status'=>0,
                'message'=>'Unable to receive stock'
            ]);
        }
    }
}
