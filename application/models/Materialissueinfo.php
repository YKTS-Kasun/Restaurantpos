<?php
class Materialissueinfo extends CI_Model{
    public function Getmaterial(){
        $this->db->select('`idtbl_res_material_info`, `material`, `materialinfocode`');
        $this->db->from('tbl_res_material_info');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getbatchno(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `batchno` FROM `tbl_stock` WHERE `tbl_res_material_info_idtbl_res_material_info`=? AND `status`=?";
        $respond=$this->db->query($sql, array($recordID, 1));

        echo json_encode($respond->result());
    }

    public function Materialinsertupdate(){
        $this->db->trans_begin();

        // Get the user ID from the session
        $userID = $_SESSION['userid'];

        // Retrieve the form data
        $tableData = $this->input->post('tableData');
        $issuedate = $this->input->post('issuedate');
        $batchno = $this->input->post('batchno');

        $updatedatetime = date('Y-m-d H:i:s');

        // Prepare the data for insertion
        $data = array(
            'date' => $issuedate,
            'total' => '0',
            'status' => '1',
            'insertdatetime' => $updatedatetime,
            'tbl_res_user_idtbl_res_user' => $userID,
        );

        // Insert the data into tbl_grn table
        $this->db->insert('tbl_res_kitchen_order', $data);

        $orderID = $this->db->insert_id();

        // Insert the details into tbl_grndetail table
        foreach ($tableData as $rowtabledata) {
            $materialID = $rowtabledata['col_2'];
            $qty = $rowtabledata['col_3'];

            $dataone = array(
                'qty' => $qty,
                'price'=> '0', 
                'total'=> '0', 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID, 
                'tbl_res_kitchen_order_idtbl_res_kitchen_order'=> $orderID, 
                'tbl_res_material_info_idtbl_res_material_info'=> $materialID
                );

                $this->db->insert('tbl_res_kitchen_order_detail', $dataone);

                            
                $this->db->select('qty AS stockqty');
                $this->db->from('tbl_stock');
                $this->db->where('batchno', $batchno);
                $this->db->where('tbl_res_material_info_idtbl_res_material_info', $materialID);
                $this->db->where('status', 1);

                $respond=$this->db->get();

                $previousqty=$respond->row(0)->stockqty;

                $newqty = $previousqty-$qty;

                $dataupdate = array(
                    'qty'=> $newqty, 
                );
    
                $this->db->where('batchno', $batchno);
                $this->db->where('tbl_res_material_info_idtbl_res_material_info', $materialID);
                $this->db->update('tbl_stock', $dataupdate);

                $dataone = array(
                    'batchno' => $batchno,
                    'qty'=> $qty, 
                    'status'=> '1', 
                    'insertdatetime'=> $updatedatetime, 
                    'tbl_res_user_idtbl_res_user'=> $userID, 
                    'tbl_res_material_info_idtbl_res_material_info'=> $materialID
                    );
    
                    $this->db->insert('tbl_res_kitchen_stock', $dataone);

            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Added Successfully';
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