<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class RptTodaysales extends CI_Controller {
    public function index(){
		$this->load->model('Commeninfo');

		$this->db->select('idtbl_res_item,itemname');
        $this->db->from('tbl_res_item');
        $this->db->where('status',1);
        
        $respond = $this->db->get();
        
        $result['item']=$respond;
		
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('rpttodaysales',$result);
	}
   
	
	
}