<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Kitchen extends CI_Controller {
    public function index(){
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('kitchenprocess',$result);
	}
	public function Kitchenorderstart(){
		$this->load->model('Kitcheninfo');
        $result=$this->Kitcheninfo->Kitchenorderstart();
	}
	public function Kitchenorderend(){
		$this->load->model('Kitcheninfo');
        $result=$this->Kitcheninfo->Kitchenorderend();
	}
}
