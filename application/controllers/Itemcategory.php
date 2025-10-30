<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Itemcategory extends CI_Controller {
    public function index(){
		$this->load->model('Itemcategoryinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('itemcategory',$result);
	}
   
	public function Itemcategoryinsertupdate(){
		$this->load->model('Itemcategoryinfo');
        $result=$this->Itemcategoryinfo->Itemcategoryinsertupdate();
	}
	public function Itemcategoryedit(){
		$this->load->model('Itemcategoryinfo');
        $result=$this->Itemcategoryinfo->Itemcategoryedit();
	}
	public function Itemcategorystatus($x, $y){
		$this->load->model('Itemcategoryinfo');
        $result=$this->Itemcategoryinfo->Itemcategorystatus($x, $y);
	}
	
}
