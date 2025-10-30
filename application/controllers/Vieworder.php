<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Vieworder extends CI_Controller {
    public function index(){
		$this->load->model('Commeninfo');
		$this->load->model('Vieworderinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['activeorderlist']=$this->Vieworderinfo->Getactiveorderlist();
		$result['completeorderlist']=$this->Vieworderinfo->Getcompleteorderlist();
		$this->load->view('vieworder',$result);
	}
	public function Kitchenorderstart(){
		$this->load->model('Vieworderinfo');
        $result=$this->Vieworderinfo->Kitchenorderstart();
	}
	public function Kitchenorderend(){
		$this->load->model('Vieworderinfo');
        $result=$this->Vieworderinfo->Kitchenorderend();
	}
	public function Viewkotorderinfo(){
		$this->load->model('Vieworderinfo');
        $result=$this->Vieworderinfo->Viewkotorderinfo();
	}
}
