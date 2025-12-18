<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class ExpenseCategory extends CI_Controller {

    public function index()
    {
        $this->load->model('Commeninfo');
        $this->load->model('Expense_model', 'expense');

        // user privilege array
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        // view expects `$categories`
        $result['categories'] = $this->expense->get_categories(false);

        $this->load->view('expense_category', $result);
    }

    // ===============================
    // ADD OR UPDATE (single method)
    // ===============================
    public function save_or_update()
    {
        $this->load->model('Expense_model', 'expense');

        $id       = $this->input->post('id');
        $category = $this->input->post('category');

        if ($category == "") {
            $this->session->set_flashdata('msg', 'Category name is required!');
            redirect('ExpenseCategory');
            return;
        }

        if ($id == "" || $id == null) {
            // ADD NEW CATEGORY
            $this->expense->add_category($category);
            $this->session->set_flashdata('msg', 'Expense Type Added Successfully!');
        } 
        else {
            // UPDATE CATEGORY
            $this->expense->update_category($id, $category);
            $this->session->set_flashdata('msg', 'Expense Type Updated Successfully!');
        }

        redirect('ExpenseCategory');
    }

    // ===============================
    // CHANGE STATUS
    // ===============================
    public function status($id, $status)
    {
        $this->load->model('Expense_model', 'expense');
        $this->expense->update_category_status($id, $status);

        $this->session->set_flashdata('msg', 'Status updated');
        redirect('ExpenseCategory');
    }

    // ===============================
    // DELETE CATEGORY
    // ===============================
    public function delete($id)
    {
        $this->load->model('Expense_model', 'expense');

        $this->expense->delete_category($id);

        $this->session->set_flashdata('msg', 'Category deleted');
        redirect('ExpenseCategory');
    }
}
