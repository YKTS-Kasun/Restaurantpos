<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Waitorordercoffeeshop extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Waitorordercoffeeshopinfo');
        $result['material']=$this->Waitorordercoffeeshopinfo->Getmaterial();
        $result['tablecat'] = $this->Waitorordercoffeeshopinfo->Gettablecat();
        $result['category']=$this->Waitorordercoffeeshopinfo->Getcategory();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('waiterorder', $result);
	}
    public function Waiterorderinsertupdate(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Waiterorderinsertupdate();
	}
    public function Directsaleinsertupdate(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Directsaleinsertupdate();
	}
    public function Getaddeditems(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getaddeditems();
	}
    public function Getonlineorders(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getonlineorders();
	}
    public function Directsalestatus($x, $y){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Directsalestatus($x, $y);
	}
    public function Directsaleedit(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Directsaleedit();
	}

    public function Getproductlist(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getproductlist();
	}

    public function Getproductdetails(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getproductdetails();
	}

    public function Getproductlistaccobarcode(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getproductlistaccobarcode();
	}

    public function RemoveProduct(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->RemoveProduct();
	}

    public function Getproductavalaibleqty(){
		$this->load->model('Waitorordercoffeeshopinfo');
        $result=$this->Waitorordercoffeeshopinfo->Getproductavalaibleqty();
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
        $this->load->model('Waitorordercoffeeshopinfo');
        $categoryId = $this->input->post('category_id');
    
        // Fetch the tables by category
        $tables = $this->Waitorordercoffeeshopinfo->getTablesByCategory($categoryId);
    
        // Return the result as JSON
        echo json_encode($tables);
    }
}