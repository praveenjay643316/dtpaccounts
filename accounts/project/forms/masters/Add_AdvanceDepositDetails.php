<?php
require_once  '../../config/config.php';
class Add_AdvanceDepositDetails  extends ConfigClass
{

    public $page_token = "Add_AdvanceDepositDetails";

    function __construct()
    {        
        // $this->pageRoleAccessCheck(array(1));
    }

    public function main_form($data_array = array())
    {
      
        
        ob_start();
$pageLables=$this->GetPageLables(162);
        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
		?>
        <input type="hidden" id="page_lable_id" name="page_lable_id" value="48" />
        <?php
		
	//print_r($data_array);	
		$lang_code_2d=$this->getCurrentUserLanguage2D();
		if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	
$statecode=$this->getCurrentStateCode();
		$dcode=$this->getCurrentDistrictCode();
		$lbcode=$this->getCurrentLocalBodyCode();
		
		$district_name=$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['district_name_'.$lang_code_2d];
	$lbody_name=$_SESSION['USER_DETAILS']['USER_PROFILE']['OFFICE_DETAILS']['lbody_name_'.$lang_code_2d];
		
		
		
		
        ?>

<script type='text/javascript'>
$(document).ready(function(){
	
	
/*	$(document).on('blur','.slab_rate_cls',function(){
	var min_value=parseInt($("#min_value").val());
	var slab_rate=parseInt($("#slab_rate").val());
	
	
	
	if(60000<min_value){
		
		if(slab_rate<1200) {
	alert('Enter  Slab Rate 1200 Above');
	$(this).val('');
		return false; 
		} 
	} 
	
});	*/
	
	
	
$(document).on('blur','.jjjmax_value',function(){
	var min_value=parseInt($("#min_value").val());
	var max_val=parseInt($(this).val());  
	
	
	if(min_value>=max_val){
	alert('Enter maximum value of Minimum Value');
		$(this).val('');
		return false; 
	} 
	
});	

$(document).on('change','#doc_link_id',function(){
	
	$('#resolution_no,#resolution_date').val('');
	$('#span_resolution_no,#span_resolution_date').text('');
	if($(this).val()!='')
	{
		$('#resolution_no').val($("#doc_link_id option:selected").attr('data-roder_no'));
		$('#resolution_date').val($("#doc_link_id option:selected").attr('data-date'));
		
		$('#span_resolution_no').text($("#doc_link_id option:selected").attr('data-roder_no'));
		$('#span_resolution_date').text($("#doc_link_id option:selected").attr('data-date'));
	}
});
	
	


<?php if (!isset($data_array["del_id"])) { ?>
$("#save").on('click',function()
{

	 var Current_Field_id=$(this).attr('id'); $('#'+Current_Field_id).hide(); try {
	
		
		if($("#reg_no").val().length == '')
		{
			throw{msg:"Enter Register Number",foc:"#reg_no"}
		}
		
		if($("#fin_date").val().length == '')
		{
			throw{msg:"Enter Financial Year",foc:"#fin_date"}
		}
		if($("#account_code").val().length == '')
		{
			throw{msg:"Select Account Number",foc:"#account_code"}
		}
		if($("#voucher_type").val().length == '')
		{
			throw{msg:"Select Voucher Type",foc:"#voucher_type"}
		}
		/*if($('input:radio[name=isactive]:checked').length==0)
		{
			throw{msg:"Choose Status",foc:"#isactive"}
		}*/
        if($("#chalan_no").val().length == '')
		{
			throw{msg:"Enter Challan Number",foc:"#chalan_no"}
		}

		if($("#chalan_date").val().length == '')
		{
			throw{msg:"Enter Challan Date",foc:"#chalan_date"}
		}

		if($("#chalan_name").val().length == '')
		{
			throw{msg:"Enter Challan Name",foc:"#chalan_name"}
		}

		if($("#narration").val().length == '')
		{
			throw{msg:"Enter Narration",foc:"#narration"}
		}	

		if($("#amount").val().length == '')
		{
			throw{msg:"Enter Challan Amount",foc:"#amount"}
		}	


	
		return true;
	} 
	catch (e) 
	{ 
		alert(e.msg); $('#'+Current_Field_id).show();
		$(e.foc).focus();
		return false;
	}

});

<?php /*?>$('#resolution_date').datepicker({
uiLibrary: 'bootstrap4',
format: 'dd-mm-yyyy',
//minDate:  '12-12-2014',
minDate:  new Date('01-01-1970'),
maxDate: new Date() 
}); 
<?php */?>

 <?php } ?>	
 
 

		
			
});
</script>        
<style>
.hidden_field_element_value
{
display: none;
}


.bg-table-form-dsg{
	background-color: #35577c !important;
	color:white;
	text-transform:capitalize !important;
	text-align:center !important;
}
.bg-table-report-dsg{
	background-color: #608db9  !important;
	color:white;
	text-transform:capitalize !important;
	text-align:center !important;
}

</style>

        
<div class="row">
	<div class="col-md-12">
	<?php
        if (isset($data_array["STATUS"])) {
            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
        }
        ?>
		<div class="card">
			<div class="card-body">
				<form action="Add_AdvanceDepositDetails.php" method="post" autocomplete="off">
					<input class="form-control w-75  form-control-sm" type="hidden"
						id="<?php echo htmlentities($this->page_token); ?>"
						name="<?php echo htmlentities($this->page_token); ?>"
						value="<?php echo htmlentities($this->token($this->page_token)); ?>">
     <?php

        if (isset($data_array["edit_id"]) && $data_array["edit_id"] != "") {
			$data_array["mode_name"] = "Update";
			$data_array["mode_class"] = "btn-warning"
            ?>
         <input class="form-control w-75  form-control-sm" type="hidden"
						id="edit_id" name='edit_id'
						value="<?php echo htmlentities($data_array["edit_id"]); ?>">
         <?php
             
			
 			$edit_id = htmlentities(base64_decode($data_array["edit_id"]));
			$edit_query = "SELECT advance_deposit_id,reg_no,fin_yr,account_code,voucher_type,chalan_no,chalan_name,narration,chalan_amount,TO_CHAR(chalan_date, 'dd-mm-yyyy')
 as chalan_date FROM accounts_master.m_advance_deposit where  del_flag is null and isactive=:isactive  and advance_deposit_id=:edit_id";
 			$data_array_edit=$this->prepare($edit_query,array(":edit_id"=>$edit_id,":isactive"=>1),4);
 			 $data_array = array_merge($data_array, $data_array_edit);
        } else if (isset($data_array["del_id"]) && $data_array["del_id"] != "") {
			$data_array["mode_name"] = "Delete";
			$data_array["mode_class"] = "btn-danger"
            ?>
         <input class="form-control w-50  form-control-sm" type="hidden"
						id="del_id" name='del_id'
						value="<?php echo htmlentities(base64_decode($data_array["del_id"])); ?>">
         <?php
			$del_id = htmlentities(base64_decode($data_array["del_id"]));
			$delete_query = "SELECT a.advance_deposit_id,a.reg_no,a.fin_yr,a.account_code,a.voucher_type,a.chalan_no,a.chalan_name,a.narration,a.chalan_amount,TO_CHAR(a.chalan_date, 'dd-mm-yyyy')
 as chalan_date,b.account_code,c.voucher_type FROM 
(SELECT advance_deposit_id,reg_no,fin_yr,account_code,voucher_type,chalan_no,chalan_name,narration,chalan_amount,chalan_date FROM accounts_master.m_advance_deposit WHERE isactive=:isactive and del_flag is null)a
LEFT JOIN
(SELECT account_number_id,account_code FROM accounts_master.m_account_number WHERE isactive=:isactive and del_flag is null)b
on a.account_code=b.account_number_id
LEFT JOIN
(SELECT voucher_type_id,voucher_type from accounts_master.voucher_type_new WHERE isactive=:isactive and del_flag is null)c
on a.voucher_type=c.voucher_type_id
 Where a.advance_deposit_id=:del_id";
 			$data_array_delete=$this->prepare($delete_query,array(":del_id"=>$del_id,":isactive"=>1),4);
           
            $data_array = array_merge($data_array, $data_array_delete);
        }
        ?>

     				   <table class="table table-bordered table-striped tndtp_form_table">
						<thead>
                            <tr>
                                <th colspan="4">Advance &amp; Deposit Register</th>
                            </tr>
                        </thead>
                        
                        <?php /*?><tr>
                        <th colspan="2"  scope="col" class="text-center w-50"><span DisplayLabelID="17">District</span> :&nbsp;&nbsp;<?php echo htmlentities($dist_local_body['district_name_en']); ?></th>
                        <th colspan="2" scope="col" class="text-center"><span DisplayLabelID="18">Town Panchayat</span> :&nbsp;&nbsp;<?php echo htmlentities($dist_local_body['lbody_name_en']); ?></th>
                    </tr><?php */?>
                        <tr>
								 <td width="126" class="text-left font-weight-bold">
                                 <span class="text-danger font-weight-bold">*</span>
                                 <span DisplayLabelID="378">Register</span></td>
								<td width="194" scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['reg_no']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control w-75  form-control-sm Number_Field" maxlength="10" type="text"
									placeholder="Enter Chalan Number" id="reg_no" name='reg_no' 
									value="<?php if(isset($data_array['reg_no'])) { echo htmlentities($data_array['reg_no']); }?>">
                                     <?php
        }
        ?>
                                    
                                     </td>								
                                     <td width="114" class="text-left font-weight-bold">
                               <span class="text-danger font-weight-bold">*</span>Financial Year</td>
								<td width="208" scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['fin_yr']);
            }
        } else {
            ?>                          
             
             <input
									class="form-control w-75  form-control-sm  slab_rate_cls" maxlength="10" type="text"
									placeholder="Enter Financial Year" id="fin_date" name='fin_date'
									value="<?php if(isset($data_array['fin_yr'])) { echo htmlentities($data_array['fin_yr']); }?>">  
                                    
                                     <?php
        }
        ?>
                                    
                                     </td>

							</tr>
                      
 
<tr>
								     
                                     <td class="text-left font-weight-bold">
                                     <span class="text-danger font-weight-bold">*</span>
                                     <span DisplayLabelID="240">Account Code</span></td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['account_code']);
            }
        } else {
            ?>                                      
                                     <select id="account_code" name="account_code" class="form-control form-control-sm w-75">
											<option value="">Choose</option>
                                            <?php
                                        $sel_account_code_id = "SELECT account_number_id,account_code,account_desc_en,account_desc_ta FROM accounts_master.m_account_number WHERE isactive=1 and del_flag is null ORDER BY account_number_id ASC";

                                        $sel_account_codeid_res = $this->prepare($sel_account_code_id, array(), 2);

                                        foreach ($sel_account_codeid_res as $sel_account_codeid_key => $sel_account_codeid_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_account_codeid_row['account_number_id']); ?>">
                                                <?php echo htmlentities($sel_account_codeid_row['account_code']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                     <script>
                                        document.getElementById('account_code').value =
                                            '<?php if (isset($data_array['account_code'])) {
                                                    echo htmlentities($data_array['account_code']);
                                                } ?>';
                                    </script>
                                     <?php
        }
        ?>
                                    
              </td>

            
					<td class="text-left font-weight-bold">
                                     <span class="text-danger font-weight-bold">*</span>
                                     <span DisplayLabelID="240">Voucher Type</span></td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['voucher_type']);
            }
        } else {
            ?>                                      
                                     <select id="voucher_type" name="voucher_type" class="form-control form-control-sm w-75">
											<option value="">Choose</option>
                                            <?php
                                        $sel_voucher_code_id = "SELECT voucher_type_id,voucher_type FROM accounts_master.voucher_type_new WHERE isactive=1 and del_flag is null ORDER BY voucher_type_id ASC";

                                        $sel_voucher_codeid_res = $this->prepare($sel_voucher_code_id, array(), 2);

                                        foreach ($sel_voucher_codeid_res as $sel_voucher_codeid_key => $sel_voucher_codeid_row) {
                                        ?>
                                            <option value="<?php echo htmlentities($sel_voucher_codeid_row['voucher_type_id']); ?>">
                                                <?php echo htmlentities($sel_voucher_codeid_row['voucher_type']); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                     <script>
                                        document.getElementById('voucher_type').value =
                                            '<?php if (isset($data_array['voucher_type'])) {
                                                    echo htmlentities($data_array['voucher_type']);
                                                } ?>';
                                    </script>
                                     <?php
        }
        ?>
                                    
              </td>
                   
                  </tr>

<tr>
<td class="text-left font-weight-bold">
                                     <span class="text-danger font-weight-bold">*</span>
                                     <span DisplayLabelID="240">Challan Number</span></td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['chalan_no']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control w-75  form-control-sm Float_Field slab_rate_cls" maxlength="10" type="text"
									placeholder="Enter Chalan Number" id="chalan_no" name='chalan_no'
									value="<?php if(isset($data_array['chalan_no'])) { echo htmlentities(round($data_array['chalan_no'])); }?>">
                                     <?php
        }
        ?>
                                    
              </td>
              <td class="text-left font-weight-bold">
                                     <span class="text-danger font-weight-bold">*</span> Challan Date</td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['chalan_date']);
            }
        } else {
            ?>                      

            <input type="text" id="chalan_date" name="chalan_date" value="<?php echo htmlentities(isset($data_array['chalan_date'])?htmlentities($data_array['chalan_date']):''); ?>"  class="form-control form-control-sm w-50 field_datepicker user_enter_date" />
                           
                                    
                                     <?php
        }
        ?>
                                    
              </td>

</tr>

<tr>
<td class="text-left font-weight-bold">
 <span class="text-danger font-weight-bold">*</span>Challan Name</td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['chalan_name']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control w-75  form-control-sm slab_rate_cls"  type="text"
									placeholder="Enter Chalan Name" id="chalan_name" name='chalan_name'
									value="<?php if(isset($data_array['chalan_name'])) { echo htmlentities($data_array['chalan_name']); }?>">
                                     <?php
        }
        ?>
                                    
              </td>
              <td class="text-left font-weight-bold">
                                     <span class="text-danger font-weight-bold">*</span>
                                     <span DisplayLabelID="240">Narration</span></td>
								<td scope="col">
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['narration']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control w-75  form-control-sm slab_rate_cls"  type="text"
									placeholder="Enter Some Text" id="narration" name='narration'
									value="<?php if(isset($data_array['narration'])) { echo htmlentities($data_array['narration']); }?>">
                                     <?php
        }
        ?>
                                    
              </td>

</tr>

<tr>
<td class="text-left font-weight-bold">
<span class="text-danger font-weight-bold">*</span>
<span DisplayLabelID="42">Amount</span>
</td>
<td scope="col">

<?php
if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
if (isset($data_array)) {
echo htmlentities($data_array['chalan_amount']);
}
} else {
?>                                      
 <input class="form-control w-75  form-control-sm Float_Field slab_rate_cls" maxlength="10" type="text" placeholder="Enter Chalan Amount" id="amount" name='amount'
value="<?php if(isset($data_array['chalan_amount'])) { echo htmlentities(round($data_array['chalan_amount'])); }?>">
<?php
}
?>

</td>
</tr>

							<tr>
                    <td colspan="4" class="text-center">
           <?php

        if (! isset($data_array["mode_name"])) {
            $data_array["mode_name"] = "Save";
        }

        ?>
                                     
                             <input type="submit" class="btn <?php echo $data_array["mode_class"];?> btn-md text-white" name="save" id="save" value="<?php echo $data_array["mode_name"];?>" />&nbsp;&nbsp;
                             <input type="button" class="btn btn-cancel btn-md" onclick="window.location='Add_AdvanceDepositDetails.php'" value="Clear"   />            
                             
                            
								</td>
							</tr>
						</thead>

					</table>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-12 mt-4">
		<div class="card">
			<div class="card-body">
				<h4 class="header-title">
					<span DisplayLabelID="804"><?php echo 'Advance & Deposit Register'; ?></span> <a href="Add_AdvanceDepositDetails.php"
						class="pull-right btn btn-sm btn-purple"><i class="fa fa-plus-square p-1" aria-hidden="true"></i><span DisplayLabelID="808"><?php echo htmlentities($pageLables[808]); ?></span></a>
				</h4>
				<div class="single-table">
					

						<table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2">
            				<thead>
								<tr >
									<td ><span DisplayLabelID="174"><?php echo htmlentities($pageLables[174]); ?></span></td>
									<td ><span DisplayLabelID="174"><?php echo 'Register'; ?></span></td>
									<td ><span DisplayLabelID="805"><?php echo 'Financial Year'; ?></span></td>
									<td ><span DisplayLabelID="806"><?php echo 'Account Code'; ?></span></td>
									<td ><span DisplayLabelID="807"><?php echo 'Voucher Type'; ?></span></td>
									<td ><span DisplayLabelID="345"><?php echo 'Challan Number'; ?></span></td>
									<td ><span DisplayLabelID="346"><?php echo 'Challan Date'; ?></span></td>
									<td ><span DisplayLabelID="807"><?php echo 'Challan Name'; ?></span></td>
									<td ><span DisplayLabelID="345"><?php echo 'Narration'; ?></span></td>
									<td ><span DisplayLabelID="346"><?php echo 'Amount'; ?></span></td>
									<td ><span DisplayLabelID="346"><?php echo htmlentities($pageLables[346]); ?></span></td>
								</tr>
						</thead>
                           <?php
        $list_com = "SELECT a.advance_deposit_id,a.reg_no,a.fin_yr,a.account_code,a.voucher_type,a.chalan_no,a.chalan_name,a.narration,a.chalan_amount,TO_CHAR(a.chalan_date, 'dd-mm-yyyy')
 as chalan_date,b.account_code,c.voucher_type FROM 
(SELECT advance_deposit_id,reg_no,fin_yr,account_code,voucher_type,chalan_no,chalan_name,narration,chalan_amount,chalan_date FROM accounts_master.m_advance_deposit WHERE isactive=1 and del_flag is null)a
LEFT JOIN
(SELECT account_number_id,account_code FROM accounts_master.m_account_number WHERE isactive=1 and del_flag is null)b
on a.account_code=b.account_number_id
LEFT JOIN
(SELECT voucher_type_id,voucher_type from accounts_master.voucher_type_new WHERE isactive=1 and del_flag is null)c
on a.voucher_type=c.voucher_type_id";
		$set = $this->prepare($list_com,array(),2);
        $slno = 1;
        foreach ($set as $row) {
            ?>
                            <tbody>
								<tr>
									<td><?php echo htmlentities($slno++); ?></td>
									<td align="left"><?php echo htmlentities($row['reg_no']); ?></td>
									<td align="left"><?php echo htmlentities($row['fin_yr']); ?></td>
									<td align="left"><?php echo htmlentities($row['account_code']); ?></td>
									<td align="left"><?php echo htmlentities($row['voucher_type']); ?></td>
									<td align="left"><?php echo htmlentities($row['chalan_no']); ?></td>
									<td align="left"><?php echo htmlentities($row['chalan_date']); ?></td>
									<td align="left"><?php echo htmlentities($row['chalan_name']); ?></td>
									<td align="left"><?php echo htmlentities($row['narration']); ?></td>
									<td align="left"><?php echo htmlentities($row['chalan_amount']); ?></td>
									
									<td align="center"><a
										href="?edit_id=<?php echo base64_encode(htmlentities($row['advance_deposit_id'])); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                                        <a
										href="?del_id=<?php echo base64_encode(htmlentities($row['advance_deposit_id'])); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1" aria-hidden="true"></i>Delete</a></td>
								</tr>

							</tbody>
                             <?php
        }
        ?>
                            
                        </table>
			
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

        $this->Template($this->getCurrentUserTemplate(), "User Role", $ob_output_main_forms, array(
            array(
                "name" => "User Role"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
/*print_r($save_data);exit;*/
        // TOKEN VALIDATE
        /*if (! $this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
			exit;
        }
        else
        {
            unset($_SESSION[$this->page_token]);
        }
*/
        if (! isset($save_data["del_id"])) {
			
			
		
				
				$reg_no = $save_data['reg_no'];
				 $fin_date = $save_data['fin_date'];
				$account_code = $save_data['account_code'];
				$voucher_type = $save_data['voucher_type'];
				$chalan_no = $save_data['chalan_no'];
				$chalan_date1 = $save_data['chalan_date'];
				$chalan_date = date('Y-m-d',strtotime($save_data['chalan_date']));
				$chalan_name = $save_data['chalan_name'];
				$chalan_date = $save_data['chalan_date'];
				$narration = $save_data['narration'];
				$chalan_amount = $save_data['amount'];
				
					/*list($date_licence,$month_licence,$year_licence)=explode('-',$save_data['resolution_date']);
			$resolution_date=$year_licence.'-'.$month_licence.'-'.$date_licence;*/


/*$doc_link_id = $save_data['doc_link_id'];
				
				$isactive = $save_data['isactive'];
				
				if(999999999==$max_value){
					$slab_description=$min_value.' - Above';
				} else {
					$slab_description=$min_value.' - '.$max_value;
				}*/
				
				
			    $reg_value_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["reg_no"],
                "Field_Max_length" => 250,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Register No"
            ));
			
			if ($reg_value_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "reg_no",
                    "MESSAGE" => "Invalid Register Number"
                ), $save_data));
            }	
				
			    $finyr_value_Validation = $this->Field_Validation(array(
                "Field_Type" => "fin_year",
                "Field_Value" => $fin_date,
                "Field_Max_length" => 250,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Financial Year"
            ));
			
			if ($finyr_value_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "fin_date",
                    "MESSAGE" => "Invalid Financial Year"
                ), $save_data));
            }	
				
		$account_code_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["account_code"],
                "Field_Max_length" => 250,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Account Code"
            ));

            if ($account_code_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_code",
                    "MESSAGE" => "Choose Account Code"
                ), $save_data));
            }						
			
			$voucher_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["voucher_type"],
                "Field_Max_length" => 250,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Voucher Type"
            ));

            if ($voucher_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "voucher_type",
                    "MESSAGE" => "Choose Voucher Type"
                ), $save_data));
            }




			$challan_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $chalan_no,
                "Field_Max_length" => 250,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Challan Number"
            ));

            if ($challan_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "chalan_no",
                    "MESSAGE" => "Invalid Challan Number"
                ), $save_data));
            }



            $challan_date_Validation = $this->Field_Validation(array(
                "Field_Type" => "date",
                "Field_Value" => $chalan_date,
                'Field_Format'=>'Y-m-d',
				"Field_Label_Name"=>"Challan Date"
            ));

            if ($challan_date_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "chalan_date",
                    "MESSAGE" => "Invalid Challan Date"
                ), $save_data));
            }

            $challan_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "text",
                "Field_Value" => $chalan_name,
                
				"Field_Label_Name"=>"Challan Name"
            ));

            if ($challan_name_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "chalan_name",
                    "MESSAGE" => "Invalid Challan Name"
                ), $save_data));
            }
            $narration_Validation = $this->Field_Validation(array(
                "Field_Type" => "text",
                "Field_Value" => $narration,
                "Field_Max_length" => 500,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Narration"
            ));

            if ($narration_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "narration",
                    "MESSAGE" => "Invalid Narration"
                ), $save_data));
            }
             $amount_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $chalan_amount,
                "Field_Max_length" => 10,
                "Field_Min_length"=>0,
				"Field_Label_Name"=>"Amount"
            ));

            if ($amount_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "amount",
                    "MESSAGE" => "Invalid Amount"
                ), $save_data));
            }
           
        }

        if (isset($save_data["del_id"])) {
            $role_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $save_data["del_id"],
                "Field_Max_length" => 100,
                "Field_Min_length"=>0
            ));
            if ($role_name_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "del_id",
                    "MESSAGE" => "Invalid data"
                ), $save_data));
            }
        }

        if (isset($save_data["edit_id"])) {
            $edit_id = base64_decode($save_data["edit_id"]);
			$role_name_Validation = $this->Field_Validation(array(
                "Field_Type" => "number",
                "Field_Value" => $edit_id,
                "Field_Max_length" => 100,
                "Field_Min_length"=>0
            ));
			
            if ($role_name_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR",
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "edit_id",
                    "MESSAGE" => "Invalid data 1"
                ), $save_data));
            }
        }
		
		
		$statecode=$this->getCurrentStateCode();
		$dcode=$this->getCurrentDistrictCode();
		$lbcode=$this->getCurrentLocalBodyCode();
		
        $waterslabSaveFunction = "professionaltax.professional_tax_slab";
         $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		$edit_id = isset($save_data["edit_id"])?$save_data["edit_id"]:0;
		$del_id = isset($save_data["del_id"])?$save_data["del_id"]:0;
 $ins_date=date('Y-m-d h:i:s');
 
 // Save Part
        if (isset($save_data["edit_id"])) {
        	$edits_id = htmlentities(base64_decode($save_data["edit_id"]));
        	$chalan_amtdate = date('Y-m-d',strtotime($chalan_date));
			
         

			   $save_query = "UPDATE accounts_master.m_advance_deposit SET reg_no=:reg_no,fin_yr=:fin_yr,account_code=:account_code,voucher_type=:voucher_type,chalan_no=:chalan_no,chalan_date=:chalan_date,chalan_name=:chalan_name,narration=:narration,chalan_amount=:chalan_amount, isactive=:isactive,upd_username=:upd_username,upd_ipaddress=:upd_ipaddress,upd_date=:upd_date WHERE advance_deposit_id=:edit_id";
			 
			 $result=$this->prepare($save_query,array(":reg_no"=>$reg_no,":fin_yr"=>$fin_date,":account_code"=>$account_code,":voucher_type"=>$voucher_type,":chalan_no"=>$chalan_no,":chalan_name"=>$chalan_name,":chalan_date"=>$chalan_amtdate,":narration"=>$narration,":chalan_amount"=>$chalan_amount,":isactive"=>1,":upd_username"=>$getCurrentUser,":upd_ipaddress"=>$getIpAddress,":upd_date"=>$ins_date,":edit_id"=>$edits_id),4);
				  
			
			$message='Data Updated SccessFully';
			
        } else if (isset($save_data["del_id"])) {
        	
              
			
            $save_query="UPDATE accounts_master.m_advance_deposit SET isactive=:isactive,del_flag=:del_flag,del_username=:del_username,del_ipaddress=:del_ipaddress,del_upd_date=:del_upd_date WHERE advance_deposit_id=:del_id";
			$result=$this->prepare($save_query,array(":isactive"=>0,":del_flag"=>'Y',":del_username"=>$getCurrentUser,":del_ipaddress"=>$getIpAddress,":del_upd_date"=>$ins_date,":del_id"=>$del_id),4);
			$message='Data Deleted SccessFully';
			
			
        } else {
			$chalan_amtdate = date('Y-m-d',strtotime($chalan_date));

                  $save_query = "INSERT INTO accounts_master.m_advance_deposit(reg_no,fin_yr,account_code,voucher_type,chalan_no,chalan_date,chalan_name,narration,chalan_amount, isactive,ins_username,ins_ipaddress,ins_date) Values(:reg_no,:fin_yr,:account_code,:voucher_type,:chalan_no,:chalan_date,:chalan_name,:narration,:chalan_amount, :isactive,:ins_username,:ins_ipaddress,:ins_date);";
			 
			 $result=$this->prepare($save_query,array(":reg_no"=>$reg_no,":fin_yr"=>$fin_date,":account_code"=>$account_code,":voucher_type"=>$voucher_type,":chalan_no"=>$chalan_no,":chalan_name"=>$chalan_name,":chalan_date"=>$chalan_amtdate,":narration"=>$narration,":chalan_amount"=>$chalan_amount,":isactive"=>1,":ins_username"=>$getCurrentUser,":ins_ipaddress"=>$getIpAddress,":ins_date"=>$ins_date),4);
				  
			  //var_dump($result);exit;
			  $message='Data Saved SccessFully';
			  
        }
      
        if($this->prepareStatus($result)==true){
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

$home = new Add_AdvanceDepositDetails();


if(!isset($_POST['cmd'])){
			
if (isset($_POST["save"])) {
    $home->data_save($_POST);
}
if (isset($_GET["edit_id"])) {
    $edit_id = base64_decode($_GET["edit_id"]);
	/***********************  Check *****************************/
	
	$edit_id_Validation = $home->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$edit_id,
			'Field_Name'=>'otax_two_txt',
			//'Field_Max_length'=>'30',
			'Field_Label_Name'=>'Invalied Edit ID',
			)
			);
			
			if ($edit_id_Validation['Status'] == "Error") {
                $home->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "otax_two_txt",
                    "MESSAGE" => $edit_id_Validation['Message']
                ), $edit_id));
			exit;			
            }
	
	/*********************** End Check *****************************/ 
	$home->main_form(array_merge
			(array(
			"mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "edit_id" => $edit_id
		),$_POST,$_GET));
	
	
   
}
if (isset($_GET["del_id"])) {
    $del_id = base64_decode($_GET["del_id"]);
	
	/***********************  Check *****************************/
	
	$delete_id_Validation = $home->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$del_id,
			'Field_Name'=>'otax_two_txt',
			//'Field_Max_length'=>'30',
			'Field_Label_Name'=>'Invalied Edit ID',
			)
			);
			
			if ($delete_id_Validation['Status'] == "Error") {
                $home->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "otax_two_txt",
                    "MESSAGE" => $delete_id_Validation['Message']
                ), $del_id));
			exit;			
            }
	
	/*********************** End Check *****************************/ 
	$home->main_form(array_merge
			(array(
			"mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "del_id" => $del_id
		),$_POST,$_GET));
	
    
} else {
    $home->main_form(array(
        "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}
}
else
{
	
 $cmd=base64_decode($_POST['cmd']); 


	
}

?>           