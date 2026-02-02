<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ExpenseEntry extends CI_Controller {

    protected $user_id;
    protected $company_id;
    protected $branch_id;
    protected $is_ho;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('userid')) {
            redirect('Login');
        }

        $this->user_id    = (int)$this->session->userdata('userid');
        $this->company_id = (int)$this->session->userdata('company_id');
        $this->branch_id  = $this->session->userdata('branch_id'); // NULL = HO
        $this->is_ho      = is_null($this->branch_id);

        $this->load->model('Commeninfo');
        $this->load->model('Expense_model', 'expense');
    }

    /* =====================================================
       LIST + ADD FORM
    ===================================================== */
    public function index()
    {
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $filters = [
            'date_from' => $this->input->get('date_from', true),
            'date_to'   => $this->input->get('date_to', true),
            'category'  => $this->input->get('category', true)
        ];

        $data['date_from']  = $filters['date_from'];
        $data['date_to']    = $filters['date_to'];
        $data['f_category'] = $filters['category'];

        $data['categories'] = $this->expense->get_categories(true);

        // ✅ SECURE LIST (HO + Branch safe)
        $data['expenses'] = $this->expense->get_daywise_expenses_secure(
            $filters['date_from'],
            $filters['date_to'],
            $this->user_id,
            $this->company_id,
            $this->branch_id
        );

        $data['edit_mode'] = false;
        $data['edit_exp']  = null;

        $this->load->view('expense_entry', $data);
    }

    /* =====================================================
       ADD / UPDATE
    ===================================================== */
    public function save_or_update()
    {
        // ❌ No company/location access at all
        if (!$this->expense->user_has_any_location($this->user_id, $this->company_id)) {
            show_error('Unauthorized', 403);
        }

        $id = $this->input->post('id');

        $data = [
            'categoryid'  => (int)$this->input->post('categoryid'),
            'description' => $this->input->post('description', true),
            'amount'      => (float)$this->input->post('amount'),
            'expdate'     => $this->input->post('expdate', true)
        ];

        /* ---------------- INSERT ---------------- */
        if (empty($id)) {

            // ✅ Branch user → must have branch access
            if (!$this->is_ho) {
                if (!$this->expense->user_has_any_location(
                    $this->user_id,
                    $this->company_id
                )) {
                    show_error('Unauthorized', 403);
                }
            }

            $this->expense->add_manual_expense($data);
            $this->session->set_flashdata('msg', 'Expense added successfully');

        }
        /* ---------------- UPDATE ---------------- */
        else {

            if (!$this->expense->can_edit_expense(
                $id,
                $this->user_id,
                $this->company_id,
                $this->branch_id
            )) {
                show_error('Unauthorized', 403);
            }

            $this->expense->update_manual_expense($id, $data);
            $this->session->set_flashdata('msg', 'Expense updated successfully');
        }

        redirect('ExpenseEntry');
    }

    /* =====================================================
       LOAD FOR EDIT
    ===================================================== */
    public function load($id)
    {
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $exp = $this->expense->get_manual_expense_secure(
            $id,
            $this->user_id,
            $this->company_id,
            $this->branch_id
        );

        if (!$exp) {
            show_error('Expense not found or access denied', 404);
        }

        $filters = [
            'date_from' => $this->input->get('date_from', true),
            'date_to'   => $this->input->get('date_to', true),
            'category'  => $this->input->get('category', true)
        ];

        $data['date_from']  = $filters['date_from'];
        $data['date_to']    = $filters['date_to'];
        $data['f_category'] = $filters['category'];

        $data['categories'] = $this->expense->get_categories(true);

        $data['expenses'] = $this->expense->get_daywise_expenses_secure(
            $filters['date_from'],
            $filters['date_to'],
            $this->user_id,
            $this->company_id,
            $this->branch_id
        );

        $data['edit_mode'] = true;
        $data['edit_exp']  = $exp;

        $this->load->view('expense_entry', $data);
    }

    /* =====================================================
       DELETE
    ===================================================== */
    public function delete($id)
    {
        if (!$this->expense->can_edit_expense(
            $id,
            $this->user_id,
            $this->company_id,
            $this->branch_id
        )) {
            show_error('Unauthorized', 403);
        }

        $this->expense->soft_delete_manual_expense($id);
        $this->session->set_flashdata('msg', 'Expense deleted');
        redirect('ExpenseEntry');
    }

    /* =====================================================
       PDF – DAY (FIXED)
    ===================================================== */
    public function pdf($date)
    {
        $this->load->library('Dpdf');

        $data['date'] = $date;

        // ✅ FIX: correct secure method
        $data['items'] = $this->expense->get_daywise_expenses_secure(
            $date,
            $date,
            $this->user_id,
            $this->company_id,
            $this->branch_id
        );

        if (!$data['items']) {
            echo "No expenses found.";
            return;
        }

        $html = $this->load->view('expense_day_pdf', $data, true);

        $this->dpdf->loadHtml($html);
        $this->dpdf->setPaper('A4', 'portrait');
        $this->dpdf->render();
        $this->dpdf->stream("expense-{$date}.pdf", ["Attachment" => false]);
    }

    /* =====================================================
       PDF – RANGE
    ===================================================== */
    public function pdf_range()
    {
        $from = $this->input->get('from', true);
        $to   = $this->input->get('to', true);
        $cat  = $this->input->get('cat', true);

        $this->load->library('Dpdf');

        $data['from'] = $from;
        $data['to']   = $to;
        $data['cat']  = $cat;

        $data['items'] = $this->expense->get_range_expenses_secure(
            $from,
            $to,
            $cat,
            $this->user_id,
            $this->company_id,
            $this->branch_id
        );

        if (!$data['items']) {
            echo "No records found!";
            return;
        }

        $html = $this->load->view('expense_range_pdf', $data, true);

        $this->dpdf->loadHtml($html);
        $this->dpdf->setPaper('A4', 'portrait');
        $this->dpdf->render();
        $this->dpdf->stream(
            "expense-report-{$from}-to-{$to}.pdf",
            ["Attachment" => false]
        );
    }
}
