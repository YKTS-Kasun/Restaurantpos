<?php
class Location_model extends CI_Model {

    public function get_all_locations() {

        return $this->db
            ->select('l.*, p.location_name AS parent_name')
            ->from('tbl_location l')
            ->join('tbl_location p', 'p.idtbl_location = l.parent_location_id', 'left')
            ->where('l.status', 1)
            ->order_by('l.location_type', 'ASC')
            ->get();
    }

    public function get_active_locations(){
        return $this->db
            ->where('status',1)
            ->get('tbl_location')
            ->result();
    }
    public function get_ho_list() {

        return $this->db
            ->where([
                'status' => 1,
                'location_type' => 'HO'
            ])
            ->get('tbl_location');
    }
}
