<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Tableno extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Tablenoinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('tableno', $result);
	}
    public function Tablenoinsertupdate(){
		$this->load->model('Tablenoinfo');
        $result=$this->Tablenoinfo->Tablenoinsertupdate();
	}
    public function Tablenostatus($x, $y){
		$this->load->model('Tablenoinfo');
        $result=$this->Tablenoinfo->Tablenostatus($x, $y);
	}
    public function Tablenoedit(){
		$this->load->model('Tablenoinfo');
        $result=$this->Tablenoinfo->Tablenoedit();
	}
}