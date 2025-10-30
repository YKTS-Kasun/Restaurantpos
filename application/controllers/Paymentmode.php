<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Paymentmode extends CI_Controller {
    public function index(){
		$this->load->model('Paymentmodeinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('paymentmode',$result);
	}
   
	public function Paymentmodeinsertupdate(){
		$this->load->model('Paymentmodeinfo');
        $result=$this->Paymentmodeinfo->Paymentmodeinsertupdate();
	}
	public function Paymentmodeedit(){
		$this->load->model('Paymentmodeinfo');
        $result=$this->Paymentmodeinfo->Paymentmodeedit();
	}
	public function Paymentmodestatus($x, $y){
		$this->load->model('Paymentmodeinfo');
        $result=$this->Paymentmodeinfo->Paymentmodestatus($x, $y);
	}
	
}
