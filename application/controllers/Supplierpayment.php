<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Supplierpayment extends CI_Controller {
    public function index(){
		$this->load->model('Commeninfo');
        $this->load->model('Supplierpaymentinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['supplierlist']=$this->Supplierpaymentinfo->Getcustomerlist();
		$this->load->view('supplierpayment',$result);
	}

    public function Getgrndetails(){
		$this->load->model('Supplierpaymentinfo');
        $result=$this->Supplierpaymentinfo->Getgrndetails();
	}
    public function Paymentinsertupdate(){
		$this->load->model('Supplierpaymentinfo');
        $result=$this->Supplierpaymentinfo->Paymentinsertupdate();
	}
    public function Getpdf($x, $y){
		$this->load->model('Supplierpaymentreportinfo');
        $result=$this->Supplierpaymentreportinfo->Getpdf($x, $y);
	}
   	
}
