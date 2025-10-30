<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class RptTablewise extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');

        $this->db->select('idtbl_res_reservation_table,table');
        $this->db->from('tbl_res_reservation_table');
        $this->db->where('status',1);
        
        $respond = $this->db->get();
        $result['table']=$respond;
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('rpttablewise', $result);
	}
}
