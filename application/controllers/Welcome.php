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
    $data['companylist'] = $this->db
        ->where('status', 1)
        ->get('tbl_company')
        ->result();

    $this->load->view('login', $data);
}

public function LoginUser()
{
    // clear old session
    $this->session->unset_userdata([
        'userid','name','usertype','typename',
        'company_id','company_name',
        'branch_id','branch_name',
        'loggedin'
    ]);

    $username   = $this->input->post('username');
    $password   = $this->input->post('password');
    $company_id = $this->input->post('company_id');
    $branch_id  = $this->input->post('branch_id');

    /* =========================
     * USER VALIDATION (ONLY USER)
     * ========================= */
    $user = $this->db
        ->select('u.*, ut.usertype')
        ->from('tbl_res_user u')
        ->join(
            'tbl_res_user_type ut',
            'ut.idtbl_res_user_type = u.tbl_res_user_type_idtbl_res_user_type'
        )
        ->where('u.username', $username)
        ->where('u.password', md5($password))
        ->where('u.status', 1)
        ->get()
        ->row();

    if (!$user) {
        $this->session->set_flashdata('msg','Invalid Username or Password');
        redirect();
    }

    /* =========================
     * COMPANY VALIDATION
     * ========================= */
    $company = $this->db
        ->where('idtbl_company', $company_id)
        ->where('status', 1)
        ->get('tbl_company')
        ->row();

    if (!$company) {
        $this->session->set_flashdata('msg','Invalid Company');
        redirect();
    }

 /* =========================
 * BRANCH VALIDATION (ONLY IF USER HAS BRANCH)
 * ========================= */
$branch = null;

if (!empty($branch_id)) {

    $branch = $this->db
        ->where('idtbl_company_branch', $branch_id)
        ->where('tbl_company_idtbl_company', $company_id)
        ->where('status', 1)
        ->get('tbl_company_branch')
        ->row();

    if (!$branch) {
        $this->session->set_flashdata('msg','Invalid Branch');
        redirect();
    }
}

/* =========================
 * LOCATION PRIVILEGE CHECK (STRICT)
 * ========================= */
$hasAccess = false;

// Super Admin bypass
if ($user->tbl_res_user_type_idtbl_res_user_type == 1) {
    $hasAccess = true;
} else {

    // HO LOGIN (branch NULL)
    if (empty($branch_id)) {

        $hasAccess = $this->db
            ->where('tbl_res_user_idtbl_res_user', $user->idtbl_res_user)
            ->where('tbl_company_idtbl_company', $company_id)
            ->where('tbl_company_branch_idtbl_company_branch IS NULL', null, false)
            ->where('status', 1)
            ->get('tbl_user_location_access')
            ->num_rows() > 0;

    }
    // BRANCH LOGIN
    else {

        $hasAccess = $this->db
            ->where('tbl_res_user_idtbl_res_user', $user->idtbl_res_user)
            ->where('tbl_company_idtbl_company', $company_id)
            ->where('tbl_company_branch_idtbl_company_branch', $branch_id)
            ->where('status', 1)
            ->get('tbl_user_location_access')
            ->num_rows() > 0;
    }
}

if (!$hasAccess) {
    $this->session->set_flashdata(
        'msg',
        'You do not have access to this Company / Branch'
    );
    redirect();
}

    /* =========================
     * SET SESSION (🔥 KEY PART)
     * ========================= */
    $this->session->sess_regenerate(true);

$this->session->set_userdata([
    'userid'   => $user->idtbl_res_user,
    'name'     => $user->name,
    'usertype' => $user->tbl_res_user_type_idtbl_res_user_type,
    'typename' => $user->usertype,

    'company_id'   => $company->idtbl_company,
    'company_name' => $company->company,

    // ✅ CORRECT
    'branch_id'   => $branch ? $branch->idtbl_company_branch : null,
    'branch_name' => $branch ? $branch->branch : null,

    'loggedin' => true
]);


    redirect('Welcome/Dashboard');
}


public function getBranchesByCompany()
{
    $company_id = $this->input->post('company_id');

    $branches = $this->db
        ->where('tbl_company_idtbl_company', $company_id)
        ->where('status', 1)
        ->get('tbl_company_branch')
        ->result();

    echo '<option value="">Select</option>';
    foreach ($branches as $b) {
        echo '<option value="'.$b->idtbl_company_branch.'">'.$b->branch.'</option>';
    }
}


public function Logout()
{
    $this->session->unset_userdata([
        'userid',
        'name',
        'usertype',
        'company_id',
        'branch_id',
        'loggedin'
    ]);

    $this->session->sess_destroy();
    $this->cart->destroy();

    redirect(base_url());
}


	public function Dashboard(){
		$this->load->model('Commeninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('dashboard', $result);
	}
}
