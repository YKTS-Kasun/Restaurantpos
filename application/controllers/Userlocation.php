<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Userlocation extends CI_Controller {

    public function index(){

        $this->load->model('Commeninfo');
        $this->load->model('Userlocationinfo');

        $result['menuaccess']  = $this->Commeninfo->Getmenuprivilege();
        $result['userlist']    = $this->Userlocationinfo->Getuserlist();
        $result['companylist'] = $this->Userlocationinfo->Getcompanylist();

        $this->load->view('user_location_privilege', $result);
    }

    public function Userlocationinsertupdate(){
        $this->load->model('Userlocationinfo');
        $this->Userlocationinfo->Userlocationinsertupdate();
    }

    public function Userlocationstatus($id, $status){
        $this->load->model('Userlocationinfo');
        $this->Userlocationinfo->Userlocationstatus($id, $status);
    }

    public function Userlocationedit(){
        $this->load->model('Userlocationinfo');
        $this->Userlocationinfo->Userlocationedit();
    }

    public function getBranchesByCompany(){
    $this->load->model('Userlocationinfo');
    $this->Userlocationinfo->Getbranchaccocompany();
}

}
