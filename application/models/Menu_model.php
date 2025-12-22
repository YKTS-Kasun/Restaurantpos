<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    public function insert($data){
        return $this->db->insert('tbl_res_menu_list', $data);
    }

    public function update($id, $data){
        return $this->db
            ->where('idtbl_res_menu_list', $id)
            ->update('tbl_res_menu_list', $data);
    }

    public function exists($id){
        return $this->db
            ->where('idtbl_res_menu_list', $id)
            ->get('tbl_res_menu_list')
            ->num_rows() > 0;
    }

    public function count_all(){
        return $this->db->count_all('tbl_res_menu_list');
    }

    public function get_limit($limit, $start){
        return $this->db
            ->order_by('idtbl_res_menu_list', 'DESC')
            ->limit($limit, $start)
            ->get('tbl_res_menu_list')
            ->result();
    }
}
