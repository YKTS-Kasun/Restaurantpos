<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Reservation extends CI_Controller {
    public function index(){
		$this->load->model('Reservationinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		// $result['menucategories']=$this->Commeninfo->Menucategories();
        $result['tablecategory']=$this->Reservationinfo->Tablecategory();
		$this->load->view('reservation',$result);
	}

	public function Booking(){
		$this->load->model('Reservationinfo');
        $result=$this->Reservationinfo->Booking();
	}
	
	public function Reservationedit(){
		$this->load->model('Reservationinfo');
        $result=$this->Reservationinfo->Reservationedit();
	}
	public function Reservationstatus($x, $y){
		$this->load->model('Reservationinfo');
        $result=$this->Reservationinfo->Reservationstatus($x, $y);
	}
	public function Reservationaccept($x){
		$this->load->model('Reservationinfo');
        $result=$this->Reservationinfo->Reservationaccept($x);
	}
	public function Reservationreject(){
		$this->load->model('Reservationinfo');
        $result=$this->Reservationinfo->Reservationreject();
	}
	public function Tablelist(){
        $this->load->model('Reservationinfo');
        $result['fetch_data']=$this->Reservationinfo->Tablelist();
    }
	
}
