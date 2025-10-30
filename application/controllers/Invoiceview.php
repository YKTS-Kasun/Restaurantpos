<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Invoiceview extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
		$this->load->model('Invoiceviewinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('invoiceview', $result);
	}

	public function Getinvoicedetails(){
        $this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Getinvoicedetails();
    }

    public function Invoiceupdate(){
        $this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Invoiceupdate();
    }

    public function Invoiceedit(){
        $this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Invoiceedit();
    }

    public function Getposprintbill($x){
		$this->load->model('Directsalereceiptinfo');
        $result=$this->Directsalereceiptinfo->Getposprintbill($x);
	}
    public function Invoicestatus($x, $y){
		$this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Invoicestatus($x, $y);
	}
}