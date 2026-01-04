<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url','form']);
        $this->load->model('Location_model');
    }

    public function index() {
        $this->load->model('Commeninfo');
        $this->load->model('Location_model');
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $data['locations'] = $this->Location_model->get_all_locations();
        $data['holist']    = $this->Location_model->get_ho_list();

        $this->load->view('location_master', $data);
    }

   public function save() {

    $option = $this->input->post('record_option');
    $id     = $this->input->post('record_id');

    $data = [
        'location_code' => $this->input->post('location_code'),
        'location_name' => $this->input->post('location_name'),
        'location_type' => $this->input->post('location_type'),
        'parent_location_id' => $this->input->post('location_type') == 'HO'
                                ? NULL
                                : $this->input->post('parent_location_id'),
        'updateuser' => $_SESSION['userid'],
        'updatedatetime' => date('Y-m-d H:i:s')
    ];

    if($option == 1){
        $data['insertdatetime'] = date('Y-m-d H:i:s');
        $data['status'] = 1;
        $this->db->insert('tbl_location', $data);
    }else{
        $this->db->where('idtbl_location', $id)
                 ->update('tbl_location', $data);
    }

    echo json_encode(['status'=>1,'message'=>'Location saved successfully']);
}
public function delete($id){

    $this->db->where('idtbl_location', $id)
             ->update('tbl_location', [
                'status' => 0,
                'updateuser' => $_SESSION['userid'],
                'updatedatetime' => date('Y-m-d H:i:s')
             ]);

    redirect('Location');
}

}
