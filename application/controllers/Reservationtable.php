<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Reservationtable extends CI_Controller {
    public function index(){
		$this->load->model('Reservationtableinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['categorylist']=$this->Reservationtableinfo->Getreservationtypes();
		$this->load->view('reservationtable',$result);
	}
   
	public function Reservationtableinsertupdate(){
		$this->load->model('Reservationtableinfo');
        $result=$this->Reservationtableinfo->Reservationtableinsertupdate();
	}
	public function Reservationtableedit(){
		$this->load->model('Reservationtableinfo');
        $result=$this->Reservationtableinfo->Reservationtableedit();
	}
	public function Reservationtablestatus($x, $y){
		$this->load->model('Reservationtableinfo');
        $result=$this->Reservationtableinfo->Reservationtablestatus($x, $y);
	}
	
}
