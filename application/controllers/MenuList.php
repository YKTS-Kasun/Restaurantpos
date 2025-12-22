<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuList extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Menu_model');
        $this->load->library('pagination');
    }

    public function index(){

        /* ===== PAGINATION CONFIG ===== */
        $config['base_url'] = base_url('MenuList/index');
        $config['total_rows'] = $this->Menu_model->count_all();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        // Bootstrap pagination style
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['prev_link'] = 'Prev';
        $config['next_link'] = 'Next';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['menus'] = $this->Menu_model->get_limit($config['per_page'], $page);
        $data['links'] = $this->pagination->create_links();

        $this->load->view('menu_view', $data);
    }

    public function save(){

        $id = $this->input->post('idtbl_res_menu_list');

        $data = [
            'idtbl_res_menu_list' => $id,
            'menu'       => $this->input->post('menu'),
            'status'          => $this->input->post('status')
        ];

        // 🔥 Insert OR Update logic
        if($this->Menu_model->exists($id)){
            $this->Menu_model->update($id, $data);
        }else{
            $this->Menu_model->insert($data);
        }

        redirect('MenuList');
    }
}
