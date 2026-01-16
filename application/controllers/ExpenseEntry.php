<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ExpenseEntry extends CI_Controller {

   public function index(){
    $this->load->model('Commeninfo');
    $this->load->model('Expense_model', 'expense');

    $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

    // Filters
    $filters = [
        'date_from' => $this->input->get('date_from'),
        'date_to'   => $this->input->get('date_to'),
        'category'  => $this->input->get('category'),
    ];

    // Keep previous filter values
    $result['date_from'] = $filters['date_from'];
    $result['date_to']   = $filters['date_to'];
    $result['f_category']= $filters['category'];

    // Data
    $result['categories'] = $this->expense->get_categories(true);
    //$result['expenses']   = $this->expense->get_all_expenses($filters);
$location_type = $this->session->userdata('location_type'); // HO / BRANCH
$idtbl_location = $this->session->userdata('idtbl_location');

$result['expenses'] = $this->expense->get_daywise_expenses(
    $filters['date_from'],
    $filters['date_to'],
    $location_type,
    $idtbl_location
);



    // DEFAULT: form empty (add)
    $result['edit_mode'] = false;
    $result['edit_exp']  = null;

    $this->load->view('expense_entry', $result);
}


    // ============================================================================
    // ADD OR UPDATE EXPENSE  (Single method)
    // ============================================================================
    public function save_or_update(){
        $this->load->model('Expense_model', 'expense');
        $this->load->model('Commeninfo');

        $menu = $this->Commeninfo->Getmenuprivilege();

        $id = $this->input->post('id');

$idtbl_location = $this->session->userdata('idtbl_location');

$data = [
    'categoryid'     => $this->input->post('categoryid'),
    'description'    => $this->input->post('description'),
    'amount'         => $this->input->post('amount'),
    'expdate'        => $this->input->post('expdate'),
    'idtbl_location' => $idtbl_location
];

        // ADD NEW
        if ($id == "" || $id == null)
        {
            $this->expense->add_manual_expense($data);
            $this->session->set_flashdata('msg', 'Expense added successfully!');
        }
        else
        {
            $this->expense->update_manual_expense($id, $data);
            $this->session->set_flashdata('msg', 'Expense updated successfully!');
        }

        redirect('ExpenseEntry');
    }

    // ============================================================================
    // LOAD EXPENSE INTO FORM FOR INLINE EDIT
    // ============================================================================
    public function load($id)
    {
        $this->load->model('Expense_model', 'expense');
        $this->load->model('Commeninfo');

        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        // Fetch record (manual only)
$idtbl_location = $this->session->userdata('idtbl_location');

$exp = $this->expense->get_manual_expense($id, $idtbl_location);


        if (!$exp){
            $this->session->set_flashdata('msg', 'Invalid expense record');
            redirect('ExpenseEntry');
            return;
        }

        // Load filters (retain)
        $filters = [
            'date_from' => $this->input->get('date_from'),
            'date_to'   => $this->input->get('date_to'),
            'category'  => $this->input->get('category'),
        ];

        $result['date_from'] = $filters['date_from'];
        $result['date_to']   = $filters['date_to'];
        $result['f_category']= $filters['category'];

        // Load categories & list
        $result['categories'] = $this->expense->get_categories(true);
        $idtbl_location = $this->session->userdata('idtbl_location');

$result['expenses'] = $this->expense->get_daywise_expenses(
    $filters['date_from'],
    $filters['date_to'],
    $idtbl_location
);


        // FORM in Edit Mode
        $result['edit_mode'] = true;
        $result['edit_exp']  = $exp;

        $this->load->view('expense_entry', $result);
    }

    // ============================================================================
    // DELETE MANUAL EXPENSE
    // ============================================================================
    public function delete($id){
        $this->load->model('Expense_model', 'expense');
        $this->load->model('Commeninfo');

        $menu = $this->Commeninfo->Getmenuprivilege();
        $idtbl_location = $this->session->userdata('idtbl_location');

$this->expense->soft_delete_manual_expense($id, $idtbl_location);

        $this->session->set_flashdata('msg', 'Expense deleted');
        redirect('ExpenseEntry');
    }

public function pdf($date)
{
    $this->load->model('Expense_model', 'expense');
    $this->load->library('Dpdf');   // loads as $this->dpdf

    $data['date']  = $date;
$idtbl_location = $this->session->userdata('idtbl_location');

$data['items'] = $this->expense->get_day_expenses($date, $idtbl_location);


    if (empty($data['items'])) {
        echo "No expenses found for this date.";
        return;
    }

    $html = $this->load->view('expense_day_pdf', $data, TRUE);

    $this->dpdf->loadHtml($html);
    $this->dpdf->setPaper('A4', 'portrait');
    $this->dpdf->render();
    $this->dpdf->stream("expense-".$date.".pdf", ["Attachment" => false]);
}

public function pdf_range()
{
    $from = $this->input->get('from');
    $to   = $this->input->get('to');
    $cat  = $this->input->get('cat');

    $this->load->model('Expense_model', 'expense');
    $this->load->library('Dpdf');

    $data['from'] = $from;
    $data['to']   = $to;
    $data['cat']  = $cat;

$idtbl_location = $this->session->userdata('idtbl_location');

$data['items'] = $this->expense->get_range_expenses(
    $from,
    $to,
    $cat,
    $idtbl_location
);


    if(empty($data['items'])){
        echo "No records found!";
        return;
    }

    $html = $this->load->view('expense_range_pdf', $data, TRUE);

    $this->dpdf->loadHtml($html);
    $this->dpdf->setPaper('A4', 'portrait');
    $this->dpdf->render();
    $this->dpdf->stream("expense-report-{$from}-to-{$to}.pdf", ["Attachment" => false]);
}


}
