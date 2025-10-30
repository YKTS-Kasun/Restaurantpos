<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Waiterorder extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Waiterorderinfo');
        $result['material']=$this->Waiterorderinfo->Getmaterial();
        $result['tablecat'] = $this->Waiterorderinfo->Gettablecat();
        $result['category']=$this->Waiterorderinfo->Getcategory();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('waiterorder', $result);
	}
    public function Waiterorderinsertupdate(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Waiterorderinsertupdate();
	}
    public function Directsaleinsertupdate(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Directsaleinsertupdate();
	}
    public function Getaddeditems(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getaddeditems();
	}
    public function Getonlineorders(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getonlineorders();
	}
    public function Directsalestatus($x, $y){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Directsalestatus($x, $y);
	}
    public function Directsaleedit(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Directsaleedit();
	}

    public function Getproductlist(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getproductlist();
	}

    public function Getproductdetails(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getproductdetails();
	}

    public function Getproductlistaccobarcode(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getproductlistaccobarcode();
	}

    public function RemoveProduct(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->RemoveProduct();
	}

    public function Getproductavalaibleqty(){
		$this->load->model('Waiterorderinfo');
        $result=$this->Waiterorderinfo->Getproductavalaibleqty();
	}

    public function Getposprintbill($x){
		$this->load->model('Directsalereceiptinfo');
        $result=$this->Directsalereceiptinfo->Getposprintbill($x);
	}
    public function Getcreditprintbill($x){
		$this->load->model('Directsalecreditreceiptinfo');
        $result=$this->Directsalecreditreceiptinfo->Getcreditprintbill($x);
	}
    public function getTablesByCategory() {
        $this->load->model('Waiterorderinfo');
        $categoryId = $this->input->post('category_id');
    
        // Fetch the tables by category
        $tables = $this->Waiterorderinfo->getTablesByCategory($categoryId);
    
        // Return the result as JSON
        echo json_encode($tables);
    }         
}