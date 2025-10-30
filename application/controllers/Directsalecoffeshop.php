<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Directsalecoffeshop extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Directsalecoffeshopinfo');
        $result['material']=$this->Directsalecoffeshopinfo->Getmaterial();
        $result['tablecat'] = $this->Directsalecoffeshopinfo->Gettablecat();        
        $result['category']=$this->Directsalecoffeshopinfo->Getcategory();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('directsale', $result);
	}
    public function Directsaletempinsertupdate(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Directsaletempinsertupdate();
	}
    public function Directsaleinsertupdate(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Directsaleinsertupdate();
	}
    public function Directsaleaddpayment(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Directsaleaddpayment();
	}
    public function Getaddeditems(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getaddeditems();
	}
    public function Getonlineorders(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getonlineorders();
	}
    public function Gettablepayments(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Gettablepayments();
	}
    public function Directsalestatus($x, $y){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Directsalestatus($x, $y);
	}
    public function Directsaleedit(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Directsaleedit();
	}

    public function Getproductlist(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getproductlist();
	}

    public function Getproductdetails(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getproductdetails();
	}

    public function Getproductlistaccobarcode(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getproductlistaccobarcode();
	}

    public function Getproductavalaibleqty(){
		$this->load->model('Directsalecoffeshopinfo');
        $result=$this->Directsalecoffeshopinfo->Getproductavalaibleqty();
	}

    public function Getposprintbill($x){
		$this->load->model('Directsalereceiptinfo');
        $result=$this->Directsalereceiptinfo->Getposprintbill($x);
	}
    public function Getcreditprintbill($x){
		$this->load->model('Directsalecreditreceiptinfo');
        $result=$this->Directsalecreditreceiptinfo->Getcreditprintbill($x);
	}
    public function validateUserAndApproveDiscount() {
        $this->load->model('Directsalecoffeshopinfo');
    
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $discountPercent = $this->input->post('discountPercent');
        $invoiceID = $this->input->post('invoiceID');
    
        $result = $this->Directsalecoffeshopinfo->validateUserAndApproveDiscount($username, $password, $discountPercent, $invoiceID);
    
        if ($result['success']) {
            $data = array(
                'discountapprove' => 1,
                'discountapprove_by' => $result['approverId']
            );
            echo json_encode(array('success' => true, 'data' => $data));
        } else {
            echo json_encode(array('success' => false));
        }
    }   
    
    public function getTablesByCategory() {
        $this->load->model('Directsalecoffeshopinfo');
        $categoryId = $this->input->post('category_id');
    
        // Fetch the tables by category
        $tables = $this->Directsalecoffeshopinfo->getTablesByCategory($categoryId);
    
        // Return the result as JSON
        echo json_encode($tables);
    }
    
}