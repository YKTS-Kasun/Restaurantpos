<?php
class Kitcheninfo extends CI_Model{
    public function Kitchenorderstart(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $this->db->select('`processstatus`, `idtbl_res_kot`');
        $this->db->from('tbl_res_kot_detail');
        $this->db->join('tbl_res_kot', 'tbl_res_kot.idtbl_res_kot = tbl_res_kot_detail.tbl_res_kot_idtbl_res_kot', 'left');
        $this->db->where('tbl_res_kot_detail.status', 1);
        $this->db->where('tbl_res_kot_detail.idtbl_res_kot_detail', $recordID);

        $respond=$this->db->get();

        if($respond->row(0)->processstatus==0){
            $kotID=$respond->row(0)->idtbl_res_kot;
            $data = array(
                'processstatus' => '1',
                'startdatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_res_kot', $kotID);
            $this->db->update('tbl_res_kot', $data);
        }

        $dataone = array(
            'startdatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_res_kot_detail', $recordID);
        $this->db->update('tbl_res_kot_detail', $dataone);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-save';
            $actionObj->title='';
            $actionObj->message='Record Start Successfully';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='success';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=1;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=0;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        }
    }
    public function Kitchenorderend(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$this->input->post('recordID');
        $updatedatetime=date('Y-m-d H:i:s');

        $dataone = array(
            'enddatetime'=> $updatedatetime,
            'enddatetime'=> $updatedatetime,
            'completestatus'=> '1'
        );

        $this->db->where('idtbl_res_kot_detail', $recordID);
        $this->db->update('tbl_res_kot_detail', $dataone);

        $this->db->select('tbl_res_kot_idtbl_res_kot');
        $this->db->from('tbl_res_kot_detail');
        $this->db->where('idtbl_res_kot_detail', $recordID);

        $respond=$this->db->get();

        $kotID=$respond->row(0)->tbl_res_kot_idtbl_res_kot;

        $this->db->select('COUNT(*) AS `notcompletecount`');
        $this->db->from('tbl_res_kot_detail');
        $this->db->where('tbl_res_kot_idtbl_res_kot', $kotID);
        $this->db->where('completestatus', 0);

        $respondkotinfo=$this->db->get();

        if($respondkotinfo->row(0)->notcompletecount==0){
            $this->db->select('enddatetime');
            $this->db->from('tbl_res_kot_detail');
            $this->db->where('tbl_res_kot_idtbl_res_kot', $kotID);
            $this->db->order_by('enddatetime', 'DESC');
            $this->db->limit(1);

            $respondkotinfoend=$this->db->get();

            $data = array(
                'enddatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_res_kot', $kotID);
            $this->db->update('tbl_res_kot', $data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-save';
            $actionObj->title='';
            $actionObj->message='Record Start Successfully';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='success';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=1;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=0;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        }
    }
}