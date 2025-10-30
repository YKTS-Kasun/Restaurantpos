<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class RptCashsale extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');

        $this->db->select('idtbl_invoice,invtype');
        $this->db->from('tbl_invoice');
        $this->db->where('status',1);
        
        $respond = $this->db->get();
        
        $result['type']=$respond;

		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('rptcashsale', $result);
	}
}