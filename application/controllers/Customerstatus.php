<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Customerstatus extends CI_Controller {
    public function index(){
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('customerstatus',$result);
	}
	public function Kitchenorderstart(){
		$this->load->model('Customerstatusinfo');
        $result=$this->Customerstatusinfo->Kitchenorderstart();
	}
	public function Kitchenorderend(){
		$this->load->model('Customerstatusinfo');
        $result=$this->Customerstatusinfo->Kitchenorderend();
	}
}
