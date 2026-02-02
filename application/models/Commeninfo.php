<?php
class Commeninfo extends CI_Model{
    public function Getmenuprivilege(){

        $userID    = $_SESSION['userid'];
        $companyID = $_SESSION['company_id'] ?? null;
        $branchID  = $_SESSION['branch_id'] ?? null;

        $menuprivilegearray = array();
    
        $sql = "SELECT `idtbl_res_menu_list`, `menu`
                FROM `tbl_res_menu_list`
                WHERE `status` = ?";
        $respond = $this->db->query($sql, array(1));
        
        foreach ($respond->result() as $row) {

            $menucheckID = $row->idtbl_res_menu_list;
            $menuname    = str_replace(" ","_",$row->menu);
            
            $sqlprivilegecheck = "
                SELECT `add`, `edit`, `statuschange`, `remove`,
                       `access_status`, `tbl_res_menu_list_idtbl_res_menu_list`
                FROM `tbl_res_privilege`
                WHERE `tbl_res_user_idtbl_res_user` = ?
                  AND `tbl_res_menu_list_idtbl_res_menu_list` = ?
                  AND `status` = ?
            ";

            $respondprivilegecheck = $this->db->query(
                $sqlprivilegecheck,
                array($userID, $menucheckID, 1)
            );
            
            if ($respondprivilegecheck->num_rows() > 0) {

                $objmenu = new stdClass();
                $objmenu->add           = $respondprivilegecheck->row(0)->add;
                $objmenu->edit          = $respondprivilegecheck->row(0)->edit;
                $objmenu->statuschange  = $respondprivilegecheck->row(0)->statuschange;
                $objmenu->remove        = $respondprivilegecheck->row(0)->remove;
                $objmenu->access_status = $respondprivilegecheck->row(0)->access_status;
                $objmenu->menuid        = $respondprivilegecheck->row(0)->tbl_res_menu_list_idtbl_res_menu_list;

                // 🔥 ONLY ADDITION (session based)
                $objmenu->company_id = $companyID;
                $objmenu->branch_id  = $branchID;

                array_push($menuprivilegearray, $objmenu);
            }
        }

        return $menuprivilegearray;
    }
}
