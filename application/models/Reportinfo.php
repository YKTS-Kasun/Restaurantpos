<?php
class Reportinfo extends CI_Model{
    public function Getcashierlist(){
        $this->db->select('`idtbl_res_user`, `name`');
        $this->db->from('tbl_res_user');
        $this->db->where('status', 1);
        $this->db->where('tbl_res_user_type_idtbl_res_user_type', 4);

        return $respond=$this->db->get();
    }
}