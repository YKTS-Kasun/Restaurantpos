<?php
class Directsaleinfo extends CI_Model{
    public function Getcategory(){
        $this->db->select('`idtbl_res_item_category`, `categoryname`');
        $this->db->from('tbl_res_item_category');
        $this->db->where('status', 1);
        $this->db->where('categorytype', 1);

        return $respond=$this->db->get();
    }
    public function Getmaterial(){
        $this->db->select('`idtbl_res_item`, `itemname`,`image`');
        $this->db->from('tbl_res_item');
        $this->db->where('tbl_res_item.status', 1);
        $this->db->where('itemtype', 1);

        return $respond=$this->db->get();
    }

    public function Gettablecat(){
        $this->db->select('`idtbl_res_reservation_category`, `reservation_category`');
        $this->db->from('tbl_res_reservation_category');
        $this->db->where('tbl_res_reservation_category.status', 1);

        return $respond=$this->db->get();
    }

    public function getTablesByCategory($categoryId) {
        $this->db->select('*');
        $this->db->from('tbl_res_reservation_table');
        $this->db->where('tbl_res_reservation_category_idtbl_res_reservation_category', $categoryId);
        $this->db->where('status', 1);
        $query = $this->db->get();
    
        return $query->result();
    }


    public function Getproductlist() {
        $html = '';
    
        $hiddenmaterialID = $this->input->post('categoryID');
    
        $sql = "SELECT `tbl_res_item`.`idtbl_res_item`, `tbl_res_item`.`itemname`, `tbl_res_item`.`image` FROM `tbl_res_item` WHERE `tbl_res_item`.`tbl_res_item_category_idtbl_res_item_category` = $hiddenmaterialID AND `tbl_res_item`.`status` = 1";
        $respond = $this->db->query($sql);
    
        if ($respond->num_rows() > 0) {
            $html .= '<div class="row row-cols-1 row-cols-md-4">';
            $counter = 0;
    
            foreach ($respond->result() as $rowmaterial) {
                if ($counter % 3 == 0) {
                    $html .= '</div><div class="row row-cols-1 row-cols-md-3">
                    ';
                }
    
                $html .= '
                    <div class="col mb-4 itemdiv" tabindex="0" id="' . $rowmaterial->idtbl_res_item . '">
                        <div class="card card-material h-100 shadow-none" style="background-color: #FFD700; border-color:#FFD700">
                            <div class="card-body p-2 text-center pointer">
                ';
    
                if (!empty($rowmaterial->image)) {
                    $imagePath = "images/Items/" . $rowmaterial->image;
                } else {
                    $imagePath = "images/Items/restaurant2.png";
                }                
    
                $html .= '
                    <img src="' . base_url($imagePath) . '" alt="" style="width: 170px; height: 100px;">
                    <hr class="border-dark my-1">
                    <h4 class="text-dark font-weight-bold" style="font-size:20px;">' . $rowmaterial->itemname . '</h4>
                            </div>
                        </div>
                    </div>
                ';
    
                $counter++;
            }
    
            $html .= '</div>';
        } else {
            $html .= '
                <div class="row row-cols-1">
                </div>
            ';
        }
    
        echo $html;
    }

    public function Getproductdetails(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`idtbl_res_item`, `itemname`, `price`');
        $this->db->from('tbl_res_item');
        $this->db->where('status', 1);
        $this->db->where('idtbl_res_item', $recordID);

        $respond=$this->db->get();

        if($respond->num_rows()>0){
            $obj=new stdClass();
            $obj->id=$respond->row(0)->idtbl_res_item;
            $obj->itemname=$respond->row(0)->itemname;
            $obj->price=$respond->row(0)->price;
        }
        echo json_encode($obj);
    }

    public function Getproductlistaccobarcode(){
        $html='';

        $barcode=$this->input->post('barcode');
        // $saletype=$this->input->post('saletype');


        $sql="SELECT `tbl_product`.`idtbl_product`, `tbl_product`.`productcode`,  `tbl_product`.`barcode`, `tbl_product`.`retailprice`, `tbl_product`.`wholesaleprice` 
        FROM `tbl_product` 
        WHERE `tbl_product`.`barcode`= '$barcode' AND `tbl_product`.`status`=1";
        $respond=$this->db->query($sql);

        if($respond->num_rows() > 0){ 
            foreach($respond->result() as $rowlist){
                $productID=$rowlist->idtbl_product;
                $sqlstockcheck="SELECT SUM(`qty`) AS sumqty FROM `tbl_product_stock` WHERE `tbl_product_idtbl_product`='$productID'"; 
                $respondstockcheck=$this->db->query($sqlstockcheck);
                

                if(!empty($respondstockcheck->row(0)->sumqty)){$stockcount=$respondstockcheck->row(0)->sumqty;}
                else{$stockcount=0;}  

                // $saleprice;
                // if($saletype==1){
                //     $saleprice=$rowlist->retailprice;
                // }else{
                //     $saleprice=$rowlist->wholesaleprice;
                // }

                $html.='

                    <tr class=';if($stockcount==0){$html.='table-danger';}else{$html.='pointer';}$html.=' id="'.$rowlist->idtbl_product.'">
                        <td>'.$rowlist->idtbl_product.'</td>
                        <td>'.$rowlist->productcode.'</td>
                        <td>'.$rowlist->barcode.'</td>
                        <td>'.$stockcount.'</td>
                        <td class="text-right">'.$rowlist->retailprice.'</td>

                    </tr>
                ';            
            }
        }else{
            $html.='

            <tr>
                <td colspan="3">No Product To Show</td>
            </tr>
            ';
        }
        echo $html;
    }


    public function Directsaletempinsertupdate() {
        $this->db->trans_begin();
    
        $userID = $_SESSION['userid'];
    
        $tableData = $this->input->post('tableData');
        $tableDataPay = $this->input->post('tableDataPay');
        $total = $this->input->post('total');
        $distotal = $this->input->post('distotal');
        $nettotal = $this->input->post('nettotal');
        $servicecharge = $this->input->post('servicecharge');
        $table = $this->input->post('tableID');
        $paytotal = $this->input->post('paytotal');
        $location = $this->input->post('location');
        $orderid = $this->input->post('orderid');
        $cusID = $this->input->post('cusID');
        $teminvoiceID = $this->input->post('teminvoiceID');
    
        $balance = $nettotal - $paytotal;
        $insertdatetime = date('Y-m-d H:i:s');
        $invdate = date('Y-m-d H:i:s');

    
            $data = array(
                'invdate'=> $invdate, 
                'grosstotal'=> $total, 
                'discount'=> '0', 
                'nettotal'=> $nettotal, 
                'servicecharge'=> $servicecharge, 
                'invtype'=> '0', 
                'orderid'=> '0', 
                'tableid'=> $table, 
                'customerid'=> '0', 
                'paymentcomplete'=> '0', 
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID
            );
    
            $this->db->insert('tbl_invoice', $data);
            $invoiceID = $this->db->insert_id();
    
            foreach ($tableData as $rowtabledata) {
                $dataInvoiceDetail = array(
                    'qty'=> $rowtabledata['col_2'], 
                    'saleprice'=> $rowtabledata['col_3'], 
                    'total'=> $rowtabledata['col_5'],  
                    'status'=> '1', 
                    'insertdatetime'=> $insertdatetime,
                    'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                    'tbl_res_item_idtbl_res_item'=> $rowtabledata['col_6']
                );
    
                $this->db->insert('tbl_invoice_detail', $dataInvoiceDetail);
            }
    
        $updateData = array(
            'billclose' => '1'
        );
        $this->db->where('idtbl_waiter_order', $teminvoiceID);
        $this->db->update('tbl_waiter_order', $updateData);
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = 'Record Added Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '1';
            $obj->invoiceid = $invoiceID;
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-exclamation-triangle';
            $actionObj->title = '';
            $actionObj->message = 'Record Error';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '0';
            $obj->invoiceid = '0';
            
            echo json_encode($obj);
        }
    }
    
    public function Directsaleinsertupdate() {
        $this->db->trans_begin();
    
        $userID = $_SESSION['userid'];
    
        $tableData = $this->input->post('tableData');
        $tableDataPay = $this->input->post('tableDataPay');
        $total = $this->input->post('total');
        $modalTotal = $this->input->post('modalTotal');
        $modalPayment = $this->input->post('modalPayment');
        $modalBalance = $this->input->post('modalBalance');
        $modalDiscountAmount = $this->input->post('modalDiscountAmount');
        $distotal = $this->input->post('distotal');
        $nettotal = $this->input->post('nettotal');
        $table = $this->input->post('tableID');
        $paytotal = $this->input->post('paytotal');
        $location = $this->input->post('location');
        $orderid = $this->input->post('orderid');
        $cusID = $this->input->post('cusID');
        $billtype = $this->input->post('billtype');
        $approveuser = $this->input->post('approveuser');
        $approvestatus = $this->input->post('approvestatus');
        $teminvoiceID = $this->input->post('teminvoiceID');
    
        $balance = $nettotal - $paytotal;
        $insertdatetime = date('Y-m-d H:i:s');
        $invdate = date('Y-m-d H:i:s');
    
        if (empty($table)) {
            // If tableID is empty, insert into tbl_res_kot and tbl_res_kot_detail
            $dataKOT = array(
                'date' => $invdate,
                'tableid' => '0',
                'orderid' => '0',
                'processstatus' => '1',
                'startdatetime' => '',
                'tableorderclose' => '0',
                'enddatetime' => '',
                'status' => '1',
                'insertdatetime' => $insertdatetime,
                'tbl_res_user_idtbl_res_user' => $userID
            );
        
            $this->db->insert('tbl_res_kot', $dataKOT);
            $kotID = $this->db->insert_id();
    
            foreach ($tableData as $rowtabledata) {
                $dataKOTDetail = array(
                    'qty' => $rowtabledata['col_2'],
                    'notes' => '',
                    'status' => '1',
                    'insertdatetime' => $insertdatetime,
                    'tbl_res_user_idtbl_res_user' => $userID,
                    'tbl_res_kot_idtbl_res_kot' => $kotID,
                    'tbl_res_item_idtbl_res_item' => $rowtabledata['col_6']
                );
            
                $this->db->insert('tbl_res_kot_detail', $dataKOTDetail);
            }
    
            $data = array(
                'invdate'=> $invdate, 
                'grosstotal'=> $total, 
                'discount'=> $modalDiscountAmount, 
                'servicecharge'=> '', 
                'nettotal'=> $modalTotal, 
                'invtype'=> $billtype, 
                'orderid'=> '0', 
                'tableid'=> $table, 
                'customerid'=> '0', 
                'paymentcomplete' => '1',
                'discountapprove' => $approvestatus,
                'discountapprove_by' => $approveuser,
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID
            );
    
            $this->db->insert('tbl_invoice', $data);
            $invoiceID = $this->db->insert_id();
    
            foreach ($tableData as $rowtabledata) {
                $dataInvoiceDetail = array(
                    'qty'=> $rowtabledata['col_2'], 
                    'saleprice'=> $rowtabledata['col_3'], 
                    'total'=> $rowtabledata['col_5'],  
                    'status'=> '1', 
                    'insertdatetime'=> $insertdatetime,
                    'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                    'tbl_res_item_idtbl_res_item'=> $rowtabledata['col_6']
                );
    
                $this->db->insert('tbl_invoice_detail', $dataInvoiceDetail);
            }
        } else {
            // If tableID is not empty, insert into tbl_invoice and tbl_invoice_detail
            $data = array(
                'invdate'=> $invdate, 
                'grosstotal'=> $total, 
                'discount'=> $modalDiscountAmount, 
                'servicecharge'=> '', 
                'nettotal'=> $modalTotal, 
                'invtype'=> '1', 
                'orderid'=> '0', 
                'tableid'=> $table, 
                'customerid'=> '0', 
                'paymentcomplete'=> '1',
                'discountapprove' => $approvestatus,
                'discountapprove_by' => $approveuser, 
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID
            );
    
            $this->db->insert('tbl_invoice', $data);
            $invoiceID = $this->db->insert_id();
    
            foreach ($tableData as $rowtabledata) {
                $dataInvoiceDetail = array(
                    'qty'=> $rowtabledata['col_2'], 
                    'saleprice'=> $rowtabledata['col_3'], 
                    'total'=> $rowtabledata['col_5'],  
                    'status'=> '1', 
                    'insertdatetime'=> $insertdatetime,
                    'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                    'tbl_res_item_idtbl_res_item'=> $rowtabledata['col_6']
                );
    
                $this->db->insert('tbl_invoice_detail', $dataInvoiceDetail);
            }
        }
    
        // Handle invoice payment
        $dataPayment = array(
            'paydate'=> $invdate, 
            'nettotal'=> $modalTotal, 
            'payment'=> $modalPayment, 
            'balance'=> $modalBalance, 
            'status'=> '1', 
            'insertdatetime'=> $insertdatetime, 
            'tbl_res_user_idtbl_res_user'=> $userID
        );
    
        $this->db->insert('tbl_invoice_payment', $dataPayment);
        $invoicepayID = $this->db->insert_id();
    
        foreach ($tableDataPay as $rowtableDataPay) {
            $dataPaymentDetail = array(
                'method' => isset($rowtableDataPay['col_1']) ? $rowtableDataPay['col_1'] : '1', 
                'amount' => isset($rowtableDataPay['col_7']) ? $rowtableDataPay['col_7'] : $modalPayment, 
                'bank' => isset($rowtableDataPay['col_3']) ? $rowtableDataPay['col_3'] : null,  
                'branch' => isset($rowtableDataPay['col_4']) ? $rowtableDataPay['col_4'] : null, 
                'chequeno' => isset($rowtableDataPay['col_5']) ? $rowtableDataPay['col_5'] : null, 
                'chequedate' => isset($rowtableDataPay['col_6']) ? $rowtableDataPay['col_6'] : null,  
                'status' => '1', 
                'insertdatetime' => $insertdatetime,
                'tbl_res_user_idtbl_res_user' => $userID,
                'tbl_invoice_payment_idtbl_invoice_payment' => $invoicepayID, 
            );            
    
            $this->db->insert('tbl_invoice_payment_detail', $dataPaymentDetail);
    
            $dataPaymentInvoice = array(
                'tbl_invoice_payment_idtbl_invoice_payment'=> $invoicepayID, 
                'tbl_invoice_idtbl_invoice'=> $invoiceID, 
            );
        
            $this->db->insert('tbl_invoice_payment_has_tbl_invoice', $dataPaymentInvoice);
        }
    
        // Update waiter order
        $updateData = array(
            'billclose' => '1'
        );
        $this->db->where('idtbl_waiter_order', $teminvoiceID);
        $this->db->update('tbl_waiter_order', $updateData);
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = 'Record Added Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '1';
            $obj->invoiceid = $invoiceID;
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-exclamation-triangle';
            $actionObj->title = '';
            $actionObj->message = 'Record Error';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '0';
            $obj->invoiceid = '0';
            
            echo json_encode($obj);
        }
    }

    public function Directsaleaddpayment() {
        $this->db->trans_begin();
    
        $userID = $_SESSION['userid'];
    
        $invoiceID = $this->input->post('invoiceID');
        $grossTotal = $this->input->post('grossTotal');
        $discountAmount = $this->input->post('discountAmount');
        $netTotal = $this->input->post('netTotal');
        $paymentAmount = $this->input->post('paymentAmount');
        $paymentmode = $this->input->post('paymentmode');
        $balance = $this->input->post('balance');
        $approveuser = $this->input->post('approveuser');
        $approvestatus = $this->input->post('approvestatus');
        $last4Digits = $this->input->post('last4Digits');
    
        $insertdatetime = date('Y-m-d H:i:s');
        $invdate = date('Y-m-d H:i:s');
    
    
        // Handle invoice payment
        $dataPayment = array(
            'paydate'=> $invdate, 
            'nettotal'=> $netTotal, 
            'payment'=> $paymentAmount, 
            'balance'=> $balance, 
            'status'=> '1', 
            'insertdatetime'=> $insertdatetime, 
            'tbl_res_user_idtbl_res_user'=> $userID
        );
    
        $this->db->insert('tbl_invoice_payment', $dataPayment);
        $invoicepayID = $this->db->insert_id();
    
            $dataPaymentDetail = array(
                'method' => $paymentmode, 
                'amount' => $paymentAmount, 
                'bank' => '',  
                'branch' => '', 
                'chequeno' => '',
                'cardlastdigits' => $last4Digits, 
                'chequedate' => '',  
                'status' => '1', 
                'insertdatetime' => $insertdatetime,
                'tbl_res_user_idtbl_res_user' => $userID,
                'tbl_invoice_payment_idtbl_invoice_payment' => $invoicepayID
            );            
    
            $this->db->insert('tbl_invoice_payment_detail', $dataPaymentDetail);
    
            $dataPaymentInvoice = array(
                'tbl_invoice_payment_idtbl_invoice_payment'=> $invoicepayID, 
                'tbl_invoice_idtbl_invoice'=> $invoiceID, 
            );
        
            $this->db->insert('tbl_invoice_payment_has_tbl_invoice', $dataPaymentInvoice);

    
        // Update waiter order
        $updateData = array(
            'discount' => $discountAmount,
            'nettotal' => $netTotal,
            'invtype' => $paymentmode,
            'paymentcomplete' => '1',
            'discountapprove' => $approvestatus,
            'discountapprove_by' => $approveuser
        );
        $this->db->where('idtbl_invoice', $invoiceID);
        $this->db->update('tbl_invoice', $updateData);
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = 'Record Added Successfully';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '1';
            $obj->invoiceid = $invoiceID;
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();
    
            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-exclamation-triangle';
            $actionObj->title = '';
            $actionObj->message = 'Record Error';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';
    
            $actionJSON = json_encode($actionObj);
    
            $obj = new stdClass();
            $obj->action = json_encode($actionObj);
            $obj->actiontype = '0';
            $obj->invoiceid = '0';
            
            echo json_encode($obj);
        }
    }

    public function validateUserAndApproveDiscount($username, $password, $discountPercent, $invoiceID) {
        $hashedPassword = md5($password);

        $this->db->where('username', $username);
        $this->db->where('password', $hashedPassword);
        $query = $this->db->get('tbl_res_user');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            $data = array(
                'discountapprove' => 1,
                'discountapprove_by' => $user->idtbl_res_user
            );
            $this->db->where('idtbl_invoice', $invoiceID);
            $this->db->update('tbl_invoice', $data);

            return ['success' => true, 'approverId' => $user->idtbl_res_user];
        } else {
            return ['success' => false];
        }
    }
    

    public function Getproductavalaibleqty(){

        $product=$this->input->post('product');
        $inserted_qty=$this->input->post('qty');

        $this->db->select('qty');
        $this->db->from('tbl_product_stock');
        $this->db->where('tbl_product_idtbl_product', $product);
        $this->db->where('status', 1);
        $respond=$this->db->get();
        
        $availableqty=$respond->row(0)->qty;
    
        $obj=new stdClass();
        
        if($inserted_qty >= $availableqty){
            $qtyresult = 1;
            
        }
        else{
            $qtyresult = 0;
        }
        $obj->checkqty = $qtyresult;

        echo json_encode($obj);

    }

    public function Getaddeditems() {
        $tableID = $this->input->post('tableID');
    
        if (!$tableID) {
            echo json_encode(['error' => 'Table ID not provided']);
            return;
        }
    
        // Define the query for item details
        $this->db->select('tbl_waiter_order_detail.idtbl_waiter_order_detail, tbl_waiter_order.idtbl_waiter_order, tbl_waiter_order_detail.qty, tbl_waiter_order.grosstoral, tbl_waiter_order.discount, tbl_waiter_order.nettotal, tbl_res_item.itemname, tbl_res_item.idtbl_res_item, tbl_waiter_order_detail.saleprice, tbl_waiter_order_detail.total');
        $this->db->from('tbl_waiter_order_detail');
        $this->db->join('tbl_waiter_order', 'tbl_waiter_order.idtbl_waiter_order = tbl_waiter_order_detail.tbl_waiter_order_idtbl_waiter_order', 'left');
        $this->db->join('tbl_res_item', 'tbl_res_item.idtbl_res_item = tbl_waiter_order_detail.tbl_res_item_idtbl_res_item', 'left');
        $this->db->where('tbl_waiter_order_detail.status', 1);
        $this->db->where('tbl_waiter_order.tbl_res_reservation_table_idtbl_res_reservation_table', $tableID);
        $this->db->where('tbl_waiter_order.billclose', 0);
    
        $respond = $this->db->get();
    
        $items = array(); // Array to store the items
    
        foreach ($respond->result() as $row) {
            $item = new stdClass();
            $item->id = $row->idtbl_waiter_order;
            $item->itemname = $row->itemname;
            $item->itemID = $row->idtbl_res_item;
            $item->qty = $row->qty;
            $item->saleprice = $row->saleprice;
            $item->total = $row->total;
            $item->totalamount = $row->grosstoral;
            $item->discountamount = $row->discount;
            $item->totalwithdis = $row->grosstoral - $row->discount; // Adjust if you have discount calculation logic
    
            $items[] = $item;
        }
    
        // Calculate the total sum separately
        $this->db->select_sum('tbl_waiter_order_detail.total', 'total_sum');
        $this->db->from('tbl_waiter_order_detail');
        $this->db->join('tbl_waiter_order', 'tbl_waiter_order.idtbl_waiter_order = tbl_waiter_order_detail.tbl_waiter_order_idtbl_waiter_order', 'left');
        $this->db->where('tbl_waiter_order_detail.status', 1);
        $this->db->where('tbl_waiter_order.tbl_res_reservation_table_idtbl_res_reservation_table', $tableID);
        $this->db->where('tbl_waiter_order.billclose', 0);
        $total_query = $this->db->get();
        $total_row = $total_query->row();
        $total_sum = $total_row->total_sum;
    
        // Add total_sum to response
        $response = array(
            'items' => $items,
            'total_sum' => $total_sum
        );
    
        echo json_encode($response);
    }

    public function Getonlineorders()
    {
        $html = '';
        $sql = "SELECT `tbl_res_order`.`idtbl_res_order`, `tbl_res_order`.`orderdate`, `tbl_res_order`.`total` AS fulltotal, `tbl_res_order`.`discount`, `tbl_res_order`.`nettotal`,  `tbl_res_orderdetail`.`qty`, `tbl_res_orderdetail`.`price`, `tbl_res_orderdetail`.`total`, `tbl_res_customer`.`firstname`, `tbl_res_customer`.`lastname`, `tbl_res_customer`.`contact`, `tbl_res_item`.`itemname`, `tbl_res_item`.`idtbl_res_item` FROM `tbl_res_order` LEFT JOIN `tbl_res_orderdetail` ON `tbl_res_order`.`idtbl_res_order`=`tbl_res_orderdetail`.`tbl_res_order_idtbl_res_order` LEFT JOIN `tbl_res_customer` ON `tbl_res_customer`.`idtbl_res_customer`=`tbl_res_order`.`tbl_res_customer_idtbl_res_customer` LEFT JOIN `tbl_res_item` ON `tbl_res_item`.`idtbl_res_item`=`tbl_res_orderdetail`.`tbl_res_item_idtbl_res_item` WHERE `tbl_res_order`.`status`=? AND `tbl_res_order`.`paystatus`=?";
        $respond = $this->db->query($sql, array(1,0));
    
        foreach ($respond->result() as $rowlist) {
            $html .= '
                <tr>
                    <td>' . $rowlist->idtbl_res_order . '</td>
                    <td>' . $rowlist->firstname . ' '. $rowlist->lastname . '</td>
                    <td>' . $rowlist->contact . '</td>
                    <td>' . $rowlist->orderdate . '</td>
                    <td>' . $rowlist->itemname . '</td>
                    <td>' . $rowlist->qty . '</td>
                    <td>
                        <div class="row ml-5">
                            <button type="button" class="btnaddtocart btn btn-primary btn-sm" data-productid="' . $rowlist->idtbl_res_item . '" data-product="' . $rowlist->itemname . '" data-qty="' . $rowlist->qty . '" data-sale="' . $rowlist->price . '" data-discount="' . $rowlist->discount . '">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </td>
                </tr>';
        }
    
        echo $html;
    }

    public function Gettablepayments()
    {
        $html = '';
        $sql = "SELECT `idtbl_invoice`, `invdate`, `grosstotal`, `discount`, `nettotal` FROM `tbl_invoice` WHERE `orderid`=? AND `tableid`!=? AND `paymentcomplete`=?";
        $respond = $this->db->query($sql, array(0,0,0));
    
        foreach ($respond->result() as $rowlist) {
            $html .= '
                <tr>
                    <td>' . $rowlist->idtbl_invoice . '</td>
                    <td>INV-' . $rowlist->idtbl_invoice . '</td>
                    <td>' . $rowlist->invdate . '</td>
                    <td>' . $rowlist->grosstotal . '</td>
                    <td>' . $rowlist->discount . '</td>
                    <td>' . $rowlist->nettotal . '</td>
                    <td>
                        <div class="row ml-5">
                            <button type="button" class="btnaddpayments btn btn-primary btn-sm" data-id="' . $rowlist->idtbl_invoice . '" data-grosstotal="' . $rowlist->grosstotal . '" data-discount="' . $rowlist->discount . '" data-nettotal="' . $rowlist->nettotal . '">
                                <i class="fas fa-money-check"></i>
                            </button>
                        </div>
                    </td>
                </tr>';

        }
    
        echo $html;
    }

}
