<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Rptcashiersale extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Reportinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['cashier']=$this->Reportinfo->Getcashierlist();
		$this->load->view('rptcashiersale', $result);
	}
}