<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Orders extends CI_Controller {
    public function index(){
		$this->load->model('Ordersinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('orders',$result);
	}
   
	public function Ordersstatus($x, $y){
		$this->load->model('Ordersinfo');
        $result=$this->Ordersinfo->Ordersstatus($x, $y);
	}
	public function Acceptpaystatus($x, $y){
		$this->load->model('Ordersinfo');
        $result=$this->Ordersinfo->Acceptpaystatus($x, $y);
	}
	public function Ordertransfertokitchen(){
		$this->load->model('Ordersinfo');
        $result=$this->Ordersinfo->Ordertransfertokitchen();
	}
}
