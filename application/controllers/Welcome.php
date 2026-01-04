<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('login');
	}
	public function LoginUser(){

    // 🔥 CLEAR OLD SESSION FIRST
    $this->session->sess_destroy();
    session_start();

    $this->load->model('Userinfo');
    $user = $this->Userinfo->LoginUser();

    if ($user) {

        $this->session->set_userdata([
            'userid'        => $user->idtbl_res_user,
            'name'          => $user->name,
            'usertype'      => $user->tbl_res_user_type_idtbl_res_user_type,
            'typename'      => $user->usertype,

            // 🔥 CRITICAL
            'location_id'   => $user->idtbl_location,
            'location_type' => $user->location_type, // MUST be 'HO'
            'location_name' => $user->location_name,

            'loggedin'      => true
        ]);

        redirect('Welcome/Dashboard');
    }

    $this->session->set_flashdata('msg','Invalid Username or Password');
    redirect();
}



	public function Logout(){

    $this->session->unset_userdata([
        'userid',
        'name',
        'usertype',
        'typename',
        'location_id',
        'location_type',
        'location_name',
        'loggedin'
    ]);

    $this->session->sess_destroy(); // ⭐ extra safety
    $this->cart->destroy();

    redirect(base_url());
}

	public function Dashboard(){
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('dashboard', $result);
	}
}
