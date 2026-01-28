<?php
//require '../config/config.php';
class Account_head_balance extends ConfigClass
{
    public function voucherFieldNames($voucher_type)
    {   //change these case ids based on the ids of these voucher type in m_voucher_type table.
        switch ($voucher_type) {

            case 1:
                $id_field = "cjv_id";
                $chalan_no_field = "cjv_no";
                $breakup_id_field = "cjv_breakupid";
                $date_field = "cjv_date";
                $voucher_table = "accounts_master.t_cj_voucher";
                $voucher_breakup_table = "accounts_master.t_cj_voucher_breakup";
                $breakup_serial_no_field = "cjv_chalan_no";
                $credit_account_id_field="credit_account_id";
                $debit_account_id_field="debit_account_id";
                $breakup_credit_account_id_field="credit_account_id";
                $breakup_debit_account_id_field="debit_account_id";
                break;
            case 2:
                $id_field = "ejv_id";
                $chalan_no_field = "ejv_chalan_no";
                $breakup_id_field = "ejv_breakupid";
                $date_field = "ejv_date";
                $voucher_table = "accounts_master.t_ej_voucher";
                $voucher_breakup_table = "accounts_master.t_ej_voucher_breakup";
                $breakup_serial_no_field = "ejv_chalan_no";
                $credit_account_id_field="credit_breakup_id";
                $debit_account_id_field="debit_breakup_id";
                $breakup_credit_account_id_field="credit_account_id";
                $breakup_debit_account_id_field="debit_account_id";
                break;
            case 3:
                $id_field = "gjv_id";
                $chalan_no_field = "gjv_no";
                $breakup_id_field = "gjv_breakupid";
                $date_field = "gjv_date";
                $voucher_table = "accounts_master.t_gj_voucher";
                $voucher_breakup_table = "accounts_master.t_gj_voucher_breakup";
                $breakup_serial_no_field = "gjv_serial_no";
                $credit_account_id_field="credit_account_id";
                $debit_account_id_field="debit_account_id";
                $breakup_credit_account_id_field="credit_account_id";
                $breakup_debit_account_id_field="debit_account_id";
                break;
            case 4:
                $id_field = "pjv_id";
                $chalan_no_field = "pjv_no";
                $breakup_id_field = "pjv_breakupid";
                $date_field = "pjv_date";
                $voucher_table = "accounts_master.t_pj_voucher";
                $voucher_breakup_table = "accounts_master.t_pj_voucher_breakup";
                $breakup_serial_no_field = "pjv_serial_no";
                $credit_account_id_field="credit_account_id";
                $debit_account_id_field="debit_account_id";
                $breakup_credit_account_id_field="credit_account_id";
                $breakup_debit_account_id_field="debit_account_id";
                break;
        }
        return ["id_field" => $id_field, "chalan_no_field" => $chalan_no_field, "date_field" => $date_field, "voucher_table" => $voucher_table, "voucher_breakup_table" => $voucher_breakup_table, "breakup_serial_no_field" => $breakup_serial_no_field, "breakup_id_field" => $breakup_id_field,"credit_account_id_field"=>$credit_account_id_field,"debit_account_id_field"=>$debit_account_id_field,"breakup_credit_account_id_field"=>$breakup_credit_account_id_field,"breakup_debit_account_id_field"=>$breakup_debit_account_id_field];
    }

    public function getVoucherBreakUpIdsDetails($voucher_type, $voucher_no)
    {

        $lbcode = $this->getCurrentLocalBodyCode();
        $dcode = $this->getCurrentDistrictCode();
        $voucher_fields = $this->voucherFieldNames($voucher_type);
        $fin_year=$this->getFinYear();


            $query = "select acc_head.account_head_id,jv.".$voucher_fields['breakup_credit_account_id_field'].",jv.credit_amount,jv.".$voucher_fields['breakup_debit_account_id_field'].",jv.debit_amount from " . 
            $voucher_fields['voucher_breakup_table'] . " as jv left join accounts_master.m_account_head as acc_head on (acc_head.account_head_id=jv.debit_account_id 
            or acc_head.account_head_id=jv.credit_account_id) where " 
            . $voucher_fields['breakup_serial_no_field'] . "=:voucher_no and jv.del_flag is null and jv.lbcode=:lbcode and jv.dcode=:dcode and jv.fin_year=:fin_year";

            $res2 = $this->prepare($query, [":lbcode" => $lbcode, ":dcode" => $dcode,":voucher_no"=>$voucher_no,":fin_year"=>$fin_year], 2);
            $return_arr = [];
            foreach ($res2 as $row2) {
                if ($row2['credit_account_id'] != NULL) {
                    $return_arr[] = ["account_head_id" => $row2['account_head_id'], "amount" => $row2['credit_amount'], "type" => 2];
                } else if ($row2['debit_account_id'] != NULL) {
                    $return_arr[] = ["account_head_id" => $row2['account_head_id'], "amount" => $row2['debit_amount'], "type" => 1];
                }

            }

            
        
        return $return_arr;


    }

    public function getBRVBreakUpIdsDetails($chalan_no)
    {
        $lbcode = $this->getCurrentLocalBodyCode();
        $dcode = $this->getCurrentDistrictCode();
        $fin_year=$this->getFinYear();
        $return_arr = [];
            $query = "select acc_head.account_head_id,jv.account_type,jv.credit_amount,jv.debit_amount from accounts_master.t_bank_receipt_voucher_breakup as jv left join accounts_master.m_account_head as acc_head on (acc_head.account_head_id=jv.acc_code_id) where  brv_serial_no::integer=:brv_chalan_no and jv.del_flag is null and jv.lbcode=:lbcode and jv.dcode=:dcode and jv.fin_year=:fin_year";

            $res = $this->prepare($query, [":lbcode" => $lbcode, ":dcode" => $dcode,":fin_year"=>$fin_year,":brv_chalan_no"=>$chalan_no], 2);
            foreach ($res as $row) {
                if ($row['account_type']==2) {
                    $return_arr[] = ["account_head_id" => $row['account_head_id'], "amount" => $row['credit_amount'], "type" => 2];
                } else if ($row['account_type']==1) {
                    $return_arr[] = ["account_head_id" => $row['account_head_id'], "amount" => $row['debit_amount'], "type" => 1];
                }

            }

            
        
        return $return_arr;

    }


    public function getBPVBreakUpIdsDetails($voucher_no)
    {
        $lbcode = $this->getCurrentLocalBodyCode();
        $dcode = $this->getCurrentDistrictCode();
        $fin_year=$this->getFinYear();
        $return_arr = [];
        $query = "select acc_head.account_head_id,jv.acc_amount as amount from accounts_master.t_bpv_accounthead_breakup as jv left join accounts_master.m_account_head as acc_head on (acc_head.account_head_id=jv.acc_code::integer) where  bpv_voucher_no=:bpv_voucher_no and jv.del_flag is null and jv.lbcode=:lbcode and jv.dcode=:dcode and jv.fin_year=:fin_year";
        $res = $this->prepare($query, [":lbcode" => $lbcode, ":dcode" => $dcode,":fin_year"=>$fin_year,":bpv_voucher_no"=>$voucher_no], 2);
        foreach ($res as $row) {
            $return_arr[] = ["account_head_id" => $row['account_head_id'], "amount" => $row['amount'], "type" => 1];                
        }        
        return $return_arr;
    }

    public function getTriplicateBreakUpIdsDetails($chalan_no)
    {
        //return arr : account_head_id , amount , type=1

        $lbcode = $this->getCurrentLocalBodyCode();
        $dcode = $this->getCurrentDistrictCode();
        $fin_year=$this->getFinYear();
        // $query = 'SELECT credit_account_id , debit_account_id FROM accounts_master.t_triplicate_chalan_details where del_flag is null
        // and fin_year=:fin_year and lbcode=:lbcode and dcode=:dcode and isactive=1 and  chalan_no=:chalan_no';
        // $res = $this->prepare($query, [":fin_year"=>$fin_year,":lbcode"=>$lbcode,":dcode"=>$dcode,":chalan_no"=>$chalan_no], 2);
        $return_arr=[];
       
          
        //    $curr_credit_breakup_id=json_decode($row['credit_account_id']);
        //    $curr_debit_breakup_id=json_decode($row['debit_account_id']);
        //    $breakup_ids = array_merge($curr_credit_breakup_id, $curr_debit_breakup_id);

            $query = "select acc_head.account_head_id,jv.credit_account_id,jv.credit_amount,jv.debit_account_id,jv.debit_amount from accounts_master.t_triplicate_accounthead_breakup as jv left join accounts_master.m_account_head as acc_head on (acc_head.account_head_id=jv.debit_account_id or acc_head.account_head_id=jv.credit_account_id) where jv.del_flag is null and jv.lbcode=:lbcode and jv.dcode=:dcode and tc_serial_no::integer=:triplicate_chalan_no and jv.fin_year=:fin_year" ;

            $res = $this->prepare($query, [":lbcode" => $lbcode, ":dcode" => $dcode, ":triplicate_chalan_no"=>$chalan_no,":fin_year"=>$fin_year], 2);
            $return_arr = [];
            foreach ($res as $row) {
                if ($row['credit_account_id'] != NULL) {
                    $return_arr[] = ["account_head_id" => $row['account_head_id'], "amount" => $row['credit_amount'], "type" => 2];
                } else if ($row['debit_account_id'] != NULL) {
                    $return_arr[] = ["account_head_id" => $row['account_head_id'], "amount" => $row['debit_amount'], "type" => 1];
                }

            }


        
        return $return_arr;
    }


    public function update_account_head_amount(int $amount, int $account_head_id, int $type, bool $revert_flag)
    {
        $dcode=$this->getCurrentDistrictCode();
        $lbcode=$this->getCurrentLocalBodyCode();
        $fin_year=$this->getFinYear();
        if (!$revert_flag) {
            switch ($type) {
                case 1:
                    $query = 'update accounts_master.m_tp_opening_closing_balance set amount_spent_so_far=amount_spent_so_far+:amount,balance_amount=total_amount-(amount_spent_so_far+:amount) where account_head_id=:account_head_id and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year and del_flag is null';
                    //$res=$this->prepare($query,[":amount"=>$amount,":account_head_id"=>$account_head_id],return_array_type: 7);
                    break;
                case 2:
                    $query = 'update accounts_master.m_tp_opening_closing_balance set total_amount=total_amount+:amount , balance_amount=(total_amount+:amount)-amount_spent_so_far where account_head_id=:account_head_id and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year and del_flag is null';
                    //$res=$this->prepare($query,[":amount"=>$amount,":account_head_id"=>$account_head_id],7);
                    break;

            }
            $res = $this->prepare($query, [":amount" => $amount, ":account_head_id" => $account_head_id,":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year], 4);

        } else {
            switch ($type) {
                case 1:
                    $query = 'update accounts_master.m_tp_opening_closing_balance set amount_spent_so_far=amount_spent_so_far-:amount,balance_amount=total_amount-(amount_spent_so_far-:amount) where account_head_id=:account_head_id and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year and del_flag is null';
                    break;
                case 2:
                    $query = 'update accounts_master.m_tp_opening_closing_balance set total_amount=total_amount-:amount , balance_amount=(total_amount-:amount)+amount_spent_so_far where account_head_id=:account_head_id and lbcode=:lbcode and dcode=:dcode and fin_year=:fin_year and del_flag is null';
                    break;

            }
            $res = $this->prepare($query, [":amount" => $amount, ":account_head_id" => $account_head_id,":dcode"=>$dcode,":lbcode"=>$lbcode,":fin_year"=>$fin_year], 4);
        }



    }

    public function update_voucher_head_amount($voucher_type, $chalan_no, $revert_flag)
    {
        $breakup_ids_account_heads = $this->getVoucherBreakUpIdsDetails($voucher_type, $chalan_no);//getbreakupids
        #print_r($breakup_ids_account_heads);die;
        foreach ($breakup_ids_account_heads as $row) {


            $this->update_account_head_amount($row['amount'], $row['account_head_id'], $row['type'], $revert_flag);
        }
    }

    public function update_triplicate_chalan_head_amount($chalan_no, $revert_flag)
    {
        $breakup_ids_account_heads = $this->getTriplicateBreakUpIdsDetails($chalan_no);
        foreach ($breakup_ids_account_heads as $row) {
            $this->update_account_head_amount($row['amount'], $row['account_head_id'], $row['type'], $revert_flag);
        }
    }

    public function update_bank_receipt_voucher_head_amount($voucher_no,$revert_flag)
    {
        $breakup_ids_account_heads = $this->getBRVBreakUpIdsDetails($voucher_no);
        foreach ($breakup_ids_account_heads as $row) {
            $this->update_account_head_amount($row['amount'], $row['account_head_id'], $row['type'], $revert_flag);
        }

    }

    public function update_bank_payment_voucher_head_amount($voucher_no,$revert_flag)
    {
            $breakup_ids_account_heads = $this->getBPVBreakUpIdsDetails($voucher_no);
            foreach ($breakup_ids_account_heads as $row) {
            $this->update_account_head_amount($row['amount'], $row['account_head_id'], $row['type'], $revert_flag);
        }

    }


}
