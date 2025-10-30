<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Materialissue extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Materialissueinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['materiallist']=$this->Materialissueinfo->Getmaterial();
		$this->load->view('materialissue', $result);
	}
    public function Materialinsertupdate(){
		$this->load->model('Materialissueinfo');
        $result=$this->Materialissueinfo->Materialinsertupdate();
	}
	public function Getbatchno(){
		$this->load->model('Materialissueinfo');
        $result=$this->Materialissueinfo->Getbatchno();
	}
}