<?php
class Reservationinfo extends CI_Model{

    public function Tablecategory(){
        $this->db->select('idtbl_res_reservation_category, reservation_category');
        $this->db->from('tbl_res_reservation_category');
        $this->db->where('status', '1');
        $this->db->order_by('idtbl_res_reservation_category', 'ASC');
        $respond = $this->db->get();

        return $respond;
    }

    public function Tablelist(){
        $resdate=$this->input->post('resdate');
        $restime=$this->input->post('restime');
        $rescategory=$this->input->post('rescategory');

        if(date("H:i:s", strtotime('06:00:00'))<=date('H:i:s',strtotime($restime)) && date("H:i:s", strtotime('12:00:00'))>date('H:i:s',strtotime($restime))){
            $fromtime=date("H:i:s", strtotime('06:00:00'));
            $totime=date("H:i:s", strtotime('11:59:59'));
        }
        else if(date("H:i:s", strtotime('12:00:00'))<=date('H:i:s',strtotime($restime)) && date("H:i:s", strtotime('18:00:00'))>date('H:i:s',strtotime($restime))){
            $fromtime=date("H:i:s", strtotime('12:00:00'));
            $totime=date("H:i:s", strtotime('17:59:59'));
        }
        else if(date("H:i:s", strtotime('18:00:00'))<=date('H:i:s',strtotime($restime))){
            $fromtime=date("H:i:s", strtotime('18:00:00'));
            $totime=date("H:i:s", strtotime('23:59:59'));
        }

        $sql="SELECT `idtbl_res_reservation_table`, `table` FROM `tbl_res_reservation_table` WHERE `status`=? AND `idtbl_res_reservation_table` NOT IN (SELECT `tbl_res_reservation_table_idtbl_res_reservation_table` FROM `tbl_res_reservation` WHERE `tbl_res_reservation_category_idtbl_res_reservation_category`=? AND `resdate`=? AND `restime` BETWEEN ? AND ?) AND `tbl_res_reservation_category_idtbl_res_reservation_category`=?";
        $respond=$this->db->query($sql, array(1, $rescategory, $resdate, $fromtime, $totime, $rescategory));

        echo json_encode($respond->result());
    }

    public function Booking(){
        $this->db->trans_begin();

        $name=$this->input->post('name');
        $guest=$this->input->post('guest');
        if(!empty($this->input->post('child'))){$child=$this->input->post('child');}else{$child=0;}
        $category=$this->input->post('category');
        $restable=$this->input->post('restable');
        $email=$this->input->post('email');
        $phone=$this->input->post('phone');
        $date_res=$this->input->post('date_res');
        $time=$this->input->post('time');
        $suggestions=$this->input->post('suggestions');

        $totalpax=$guest+$child;
        $paymentmethod=0;

        $updatedatetime=date('Y-m-d h:i:s');

        //Insert reservation
        $datareservation= array(
            'name'=> $name, 
            'resdate'=> $date_res, 
            'restime'=> $time, 
            'code'=> '', 
            'totalpax'=> $totalpax, 
            'adult'=> $guest, 
            'child'=> $child, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_res_customer_idtbl_res_customer'=> '0', 
            'tbl_res_reservation_category_idtbl_res_reservation_category'=> $category,
            'tbl_res_reservation_table_idtbl_res_reservation_table'=> $restable
        );
        $reservationinsert=$this->db->insert('tbl_res_reservation', $datareservation);

        //Insert Transaction
        $datatransaction = array(
            'paymethod'=> $paymentmethod, 
            'subtotal'=> '0', 
            'discount'=> '0', 
            'nettotal'=> '0', 
            'orderid'=> '', 
            'reservationid'=> $reservationID, 
            'paycomplete'=> '0', 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime
        );  
        $transactioninsert=$this->db->insert('tbl_res_transaction', $datatransaction);

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
            
            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Reservation');                
        } else {
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-warning';
            $actionObj->title='';
            $actionObj->message='Record Error';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);
            
            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Reservation');
        }

    }

    public function Reservationstatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');
            }
        }
        else if($type==3){
			$data = array(
                'status' => '3',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');
            }
        }
    }



    public function Reservationaccept($x){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;

        $updatedatetime=date('Y-m-d H:i:s');

            $data = array(
                'confirmstatus' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data);


            $data2 = array(
                'rejectstatus' => '0',
                'rejectreason' => '-',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

			$this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data2);


            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Accept Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');
            }
        
    }


    public function Reservationreject(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $reason=$this->input->post('reason');
        if(!empty($this->input->post('rejectID'))){$recordID=$this->input->post('rejectID');}

        $insertdatetime=date('Y-m-d H:i:s');

            $data = array(
                'rejectreason'=> $reason, 
                'rejectstatus'=> '1', 
                'updatedatetime'=> $insertdatetime, 
                'updateuser'=> $userID,
            );

            $this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data);

            $data2 = array(
                'confirmstatus' => '0',
            );
            $this->db->where('idtbl_res_reservation', $recordID);
            $this->db->update('tbl_res_reservation', $data2);
            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Reject Reservation Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Reservation');
            }
        
       
    }


    public function Reservationedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_res_reservation');
        $this->db->where('idtbl_res_reservation', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_res_reservation;
        echo json_encode($obj);
    }
}
