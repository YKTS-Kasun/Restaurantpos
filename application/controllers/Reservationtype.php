<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Reservationtype extends CI_Controller {
    public function index(){
		$this->load->model('Reservationtypeinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('reservationtype',$result);
	}
   
	public function Reservationtypeinsertupdate(){
		$this->load->model('Reservationtypeinfo');
        $result=$this->Reservationtypeinfo->Reservationtypeinsertupdate();
	}
	public function Reservationtypeedit(){
		$this->load->model('Reservationtypeinfo');
        $result=$this->Reservationtypeinfo->Reservationtypeedit();
	}
	public function Reservationtypestatus($x, $y){
		$this->load->model('Reservationtypeinfo');
        $result=$this->Reservationtypeinfo->Reservationtypestatus($x, $y);
	}
	
}
