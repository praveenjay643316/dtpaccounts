<?php   
require_once __DIR__ . '/../../config/config.php';


class ChangeTransactionDate extends ConfigClass
{

    public $page_token = "Change_Transaction_Date";

    function __construct()
    {        
         
      
    
    }

    public function main_form($data_array = array())
    {
		ob_start();
	$pageLables=$this->GetPageLables(162);
		// #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
		if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	

        ?>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="162" />
<script type='text/javascript'>
$(document).ready(function(){

<?php /*?><?php if (!isset($data_array["del_id"])) { ?><?php */?>
$("#save").on('click',function()
{ 
	try {
	
		if($("#transaction_no").val().length == '')
		{
			throw{msg:"Enter Transaction No",foc:"#transaction_no"}
		}
		if($("#chalan_no").val().length == '')
		{
			throw{msg:"Enter Chalan No",foc:"#chalan_no"}
		}
		if($("#existing_date").val().length == '')
		{
			throw{msg:"Enter Existing Date",foc:"#existing_date"}
		}
		if($("#new_date").val().length == '')
		{
			throw{msg:"Enter New Date",foc:"#new_date"}
		}

	
		return true;
	} 
	catch (e) 
	{ 
		alert(e.msg); 
		$(e.foc).focus();
		return false;
	}

});
});
 <?php /*?><?php } ?>	
<?php */?>
</script>        
        
<div class="row">
	<div class="col-md-12">
	<?php
        if (isset($data_array["STATUS"])) {
            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
			 header("refresh: 2; url=ChangeTransactionDate.php");
        }
        ?>
		<div class="card">
			<div class="card-body">
				<form  method="post" autocomplete="off">
					 <input class="form-control  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
     
     				   <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
						<thead>
                            <tr>
                                <td colspan="2" class="text-center"><span DisplayLabelID="804">Change Transaction Date</span></td>
                            </tr>
                        </thead>
						<tbody>
							<tr>
								<td><span DisplayLabelID="805">Transaction</span></td>
								<td>
								<input class="form-control form-control-sm Number_Field" type="text" placeholder="Enter Transaction No" id="transaction_no" name='transaction_no'>	
								</td>
							</tr>
							<tr>
								<td><span DisplayLabelID="806">Chalan No</span></td>
								<td>
                                 <input class="form-control form-control-sm Number_Field" type="text" placeholder="Enter Chalan No" id="chalan_no" name='chalan_no'/>
                             </td>
							</tr>
                            <tr>
						      <td><span DisplayLabelID="807">Existing Date</span></td>
								<td>
                                  <input type="text" id="existing_date" name="existing_date"  class="form-control form-control-sm w-50 field_datepicker user_enter_date" />
                               
                               </td>
							</tr>

				            <tr>
                            <td><span DisplayLabelID="807">New Date</span></td>
					        <td>
                                <input type="text" id="new_date" name="new_date" class="form-control form-control-sm w-50 field_datepicker user_enter_date" />
                                 
                           </td>
                          </tr>
                         <tr>
                    <td colspan="2" class="text-center">
                                   <?php

        if (! isset($data_array["mode_name"])) {
            $data_array["mode_name"] = "Save";
        }

        ?>
                                     
                             <button type="submit" class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white" name="submit" id="save"><i class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1" aria-hidden="true"></i><?php echo htmlentities($data_array["mode_name"]);?></button> 				&nbsp;
                    <a class="btn btn-cancel btn-sm" href="ChangeTransactionDate.php"><i class="fa fa-eraser pr-1"></i>Clear</a>
								</td>
							</tr>
						</tbody>

					</table>
				</form>

			</div>
		</div>
	</div>
	<div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body" >

                    <div class="single-table">
                        <table class="table table-bordered text-center table-striped tndtp_report_table" id="dataTable2">
                            <thead class="text-left">

                                <tr>
                                    <th scope="col"><span DisplayLabelID="311">S.No</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Transaction No</span></th>
                                    <th scope="col"><span DisplayLabelID="311">Chalan No</span></th>
                                    <th scope="col"><span DisplayLabelID="186">Existing date</span></th>
                                    <th scope="col"><span DisplayLabelID="354">New Date</span></th>
                                    
                                </tr>
                            </thead>
                            <tbody id="tradedetails_data">
                                <?php
                                $sel_qry = "select change_trans_date_id as edit_id, transaction_no, chalan_no, existing_date, new_date from accounts_master.change_transaction_date order by change_trans_date_id";

                                $sel_qry_res = $this->prepare($sel_qry, array(), 2);
                               
                                if (count($sel_qry_res) > 0) {
                                    foreach ($sel_qry_res as $sel_qry_key => $sel_qry_row) {
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo htmlentities($sel_qry_key + 1); ?></td>
                                           
                                            <td class="text-left">
                                                <?php echo htmlentities($sel_qry_row['transaction_no']); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities($sel_qry_row['chalan_no']); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities(date('d-m-Y', strtotime($sel_qry_row['existing_date']))); ?>
                                            </td>
                                             <td class="text-left">
                                                <?php echo htmlentities(date('d-m-Y', strtotime($sel_qry_row['new_date']))); ?>
                                            </td>

                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="6" style="color:#F00;" class="font-weight-bold">No Record Found
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
         </div>   
</div>


<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template($this->getCurrentUserTemplate(), "Change Transaction date", $ob_output_main_forms, array(
            array(
                "name" => "Change Transaction date"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
		 // var_dump($save_data);exit();
        // TOKEN VALIDATE
        if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
            exit;
        }

		
       // if (! isset($save_data["del_id"])) {
			
			if(isset($save_data['transaction_no'])){
				$transaction_no=$save_data['transaction_no'];
				$transaction_no_Validation = $this->Field_Validation(
				array(
				'Field_Type'=>'number',
				'Field_Value'=>$transaction_no,
				'Field_Name'=>'transaction_no',
				'Field_Label_Name'=>'Transaction No',
				)
				);
			 if($transaction_no_Validation['Status'] == "Error"){
				$this->main_form(array_merge(array(
				 "STATUS" =>"ERROR",
				 "STATUS_TYPE"=>"FIELD",
				 "FIELD_NAME"=>"transaction_no",
				 "MESSAGE"=>$transaction_no_Validation['Message']
				), $save_data)); 
			  exit; 	 
			 }
			}
			if(isset($save_data['chalan_no'])){
				$chalan_no=$save_data['chalan_no'];
				$chalan_no_Validation = $this->Field_Validation(
				array(
				'Field_Type'=>'number',
				'Field_Value'=>$chalan_no,
				'Field_Name'=>'chalan_no',
				'Field_Label_Name'=>'Chalan No',
				)
				);
			 if($chalan_no_Validation['Status'] == "Error"){
				$this->main_form(array_merge(array(
				 "STATUS" =>"ERROR",
				 "STATUS_TYPE"=>"FIELD",
				 "FIELD_NAME"=>"chalan_no",
				 "MESSAGE"=>$chalan_no_Validation['Message']
				), $save_data)); 
			  exit; 	 
			 }
			}
			if(isset($save_data['existing_date'])){
				if($save_data['existing_date']!=''){
				$existing_date=date('Y-m-d', strtotime($save_data['existing_date'])); 
				}else{
				$existing_date='';	
				}
				$existing_date_Validation = $this->Field_Validation(
				array(
				'Field_Type'=>'date',
				'Field_Value'=>$existing_date,
				'Field_Name'=>'existing_date',
				'Field_Format'=>'yyyy-mm-dd',
				'Field_Label_Name'=>'Existing Date',
				)
				);
				//print_r($existing_date_Validation['Status']); exit;
			 if($existing_date_Validation['Status'] == "Error"){
				$this->main_form(array_merge(array(
				 "STATUS" =>"ERROR",
				 "STATUS_TYPE"=>"FIELD",
				 "FIELD_NAME"=>"existing_date",
				 "MESSAGE"=>$existing_date_Validation['Message']
				), $save_data)); 
			  exit; 	 
			 }
			}
			if(isset($save_data['new_date'])){
				if($save_data['new_date']!=''){
				$new_date=date('Y-m-d', strtotime($save_data['new_date']));
				}else{
				$new_date='';	
				}
				$new_date_Validation = $this->Field_Validation(
				array(
				'Field_Type'=>'date',
				'Field_Value'=>$new_date,
				'Field_Name'=>'new_date',
				'Field_Format'=>'yyyy-mm-dd',
				'Field_Label_Name'=>'New Date',
				)
				);
			 if($new_date_Validation['Status'] == "Error"){
				$this->main_form(array_merge(array(
				 "STATUS" =>"ERROR",
				 "STATUS_TYPE"=>"FIELD",
				 "FIELD_NAME"=>"new_date",
				 "MESSAGE"=>$new_date_Validation['Message']
				), $save_data)); 
			  exit; 	 
			 }
			}
       // }
		$insert_sp = "accounts_master.sp_change_transaction_date";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		
        // Save Part
      
             $save_query = "select " . $insert_sp . "(:transaction_no,:chalan_no,:existing_date,:new_date,:getCurrentUser,now()::timestamp without time zone,:getIpAddress);"; 
			
			$res = $this->prepare($save_query,array(":transaction_no"=>$transaction_no,":chalan_no"=>$chalan_no,":existing_date"=>$existing_date,":new_date"=>$new_date,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress),4);
			 
			 $message='Data Saved SccessFully';
			

        if (! isset($res->errorInfo)) {
            $this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $message
            ));
			
        } else {
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Save Failed Due To Duplicate Entry"
            ));
        }
    }
}

$ChangeTransactionDate = new ChangeTransactionDate();

if (isset($_POST["submit"])) {
    $ChangeTransactionDate->data_save($_POST);
}
if (isset($_GET["edit_id"])) {
    $edit_id = base64_decode($_GET["edit_id"]);
    $ChangeTransactionDate->main_form(array(
        "mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "edit_id" => $edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $del_id = base64_decode($_GET["del_id"]);
    $ChangeTransactionDate->main_form(array(
        "mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "del_id" => $del_id
    ));
} else {
    $ChangeTransactionDate->main_form(array(
        "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}

?>           