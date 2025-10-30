<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Item extends CI_Controller {
    public function index(){
		$this->load->model('Iteminfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['categorylist']=$this->Iteminfo->Getitemcategory();
		$this->load->view('item',$result);
	}
   
	public function Iteminsertupdate(){
		$this->load->model('Iteminfo');
        $result=$this->Iteminfo->Iteminsertupdate();
	}
	public function Itemedit(){
		$this->load->model('Iteminfo');
        $result=$this->Iteminfo->Itemedit();
	}
	public function Itemstatus($x, $y){
		$this->load->model('Iteminfo');
        $result=$this->Iteminfo->Itemstatus($x, $y);
	}
	
}
