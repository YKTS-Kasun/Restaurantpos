<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Directsale extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Directsaleinfo');
        $result['material']=$this->Directsaleinfo->Getmaterial();
        $result['tablecat'] = $this->Directsaleinfo->Gettablecat();
        $result['category']=$this->Directsaleinfo->Getcategory();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('directsale', $result);
	}
    public function Directsaletempinsertupdate(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Directsaletempinsertupdate();
	}
    public function Directsaleinsertupdate(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Directsaleinsertupdate();
	}
    public function Directsaleaddpayment(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Directsaleaddpayment();
	}
    public function Getaddeditems(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getaddeditems();
	}
    public function Getonlineorders(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getonlineorders();
	}
    public function Gettablepayments(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Gettablepayments();
	}
    public function Directsalestatus($x, $y){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Directsalestatus($x, $y);
	}
    public function Directsaleedit(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Directsaleedit();
	}

    public function Getproductlist(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getproductlist();
	}

    public function Getproductdetails(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getproductdetails();
	}

    public function Getproductlistaccobarcode(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getproductlistaccobarcode();
	}

    public function Getproductavalaibleqty(){
		$this->load->model('Directsaleinfo');
        $result=$this->Directsaleinfo->Getproductavalaibleqty();
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
        $this->load->model('Directsaleinfo');
    
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $discountPercent = $this->input->post('discountPercent');
        $invoiceID = $this->input->post('invoiceID');
    
        $result = $this->Directsaleinfo->validateUserAndApproveDiscount($username, $password, $discountPercent, $invoiceID);
    
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
        $this->load->model('Directsaleinfo');
        $categoryId = $this->input->post('category_id');
    
        // Fetch the tables by category
        $tables = $this->Directsaleinfo->getTablesByCategory($categoryId);
    
        // Return the result as JSON
        echo json_encode($tables);
    }
    
}