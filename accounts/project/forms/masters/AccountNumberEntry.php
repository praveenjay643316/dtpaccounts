<?php   
require_once __DIR__ . '/../../config/config.php';


class AccountNumberEntry extends ConfigClass
{

    public $page_token = "account_number_entry";

    function __construct()
    {        
         
       //$this->pageRoleAccessCheck(array(1));
    
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

<?php if (!isset($data_array["del_id"])) { ?>
$("#save").on('click',function()
{ 
	 var Current_Field_id=$(this).attr('id'); $('#'+Current_Field_id).hide(); try {
	
		if($("#account_code").val().length == '')
		{
			throw{msg:"Enter Account Code",foc:"#account_code"}
		}
		if($("#account_name_en").val().length == '')
		{
			throw{msg:"Enter Account Description in English",foc:"#account_name_en"}
		}
		if($("#account_name_ta").val().length == '')
		{
			throw{msg:"Enter Account Description in Tamil",foc:"#account_name_ta"}
		}
		if($('input:radio[name=isactive]:checked').length==0)
		{
			throw{msg:"Choose Status",foc:"#isactive"}
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
 <?php } ?>	
});
</script>        
        
<div class="row">
	<div class="col-md-12">
	<?php
        if (isset($data_array["STATUS"])) {
            echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
        }
        ?>
		<div class="card">
			<div class="card-body">
				<form  method="post" autocomplete="off">
					<input class="form-control form-control-sm" type="hidden"
						id="<?php echo htmlentities($this->page_token); ?>"
						name="<?php echo htmlentities($this->page_token); ?>"
						value="<?php echo htmlentities($this->token($this->page_token)); ?>">
     <?php

        if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
            ?>
         <input class="form-control form-control-sm" type="hidden"
						id="edit_id" name='edit_id'
						value="<?php echo htmlentities($data_array["edit_id"]); ?>">
         <?php

             $data_array_edit_new ="SELECT account_number_id,account_code,account_desc_en,account_desc_ta,isactive FROM accounts.m_account_number where account_number_id=:edit_id and del_flag is null";
			$data_array_edit = $this->prepare($data_array_edit_new,array(":edit_id"=>$data_array["edit_id"]),4);
			
			
            $data_array = array_merge($data_array, $data_array_edit);
        } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            ?>
         <input class="form-control form-control-sm" type="hidden"
						id="del_id" name='del_id'
						value="<?php echo htmlentities($data_array["del_id"]); ?>">
         <?php

     
			
			$data_array_delete_new ="SELECT account_number_id,account_code,account_desc_en,account_desc_ta,isactive FROM accounts.m_account_number where account_number_id=:del_id and del_flag is null";
			 $data_array_delete = $this->prepare($data_array_delete_new,array(":del_id"=>$data_array["del_id"]),4);
			 
            $data_array = array_merge($data_array, $data_array_delete);
        }

        ?>
     				   <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
						<thead>
                            <tr>
                                <td colspan="2" class="text-center"><span DisplayLabelID="804"><?php echo htmlentities($pageLables[804]); ?></span></td>
                            </tr>
                        </thead>
						<tbody>
							<tr>
								<td><span DisplayLabelID="805"><?php echo htmlentities($pageLables[805]); ?></span></td>
								<td>
								<?php
								if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($data_array)) {
										echo htmlentities($data_array['account_code']);
									}
								} else {
									?>
									<input class="form-control form-control-sm" type="text" placeholder="Enter Account Code" id="account_code" name='account_code' value="<?php if(isset($data_array['account_code'])) { echo htmlentities($data_array['account_code']); }?>">
									<?php
									}
									?>
								</td>
							</tr>
							<tr>
								<td ><span DisplayLabelID="806"><?php echo htmlentities($pageLables[806]); ?></span></td>
								<td >
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['account_desc_en']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control form-control-sm " type="text"
									placeholder="Enter Account Description in English" id="account_name_en" name='account_name_en'
									value="<?php if(isset($data_array['account_desc_en'])) { echo htmlentities($data_array['account_desc_en']); }?>">
                                     <?php
        }
        ?>
                                    
                                     </td>
							</tr>
                            
                            <tr>
								<td ><span DisplayLabelID="807"><?php echo htmlentities($pageLables[807]); ?></span></td>
								<td >
                                     
                                    <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array)) {
                echo htmlentities($data_array['account_desc_ta']);
            }
        } else {
            ?>                                      
                                     <input
									class="form-control form-control-sm " type="text"
									placeholder="Enter Account Description in Tamil" id="account_name_ta" name='account_name_ta'
									value="<?php if(isset($data_array['account_desc_ta'])) { echo htmlentities($data_array['account_desc_ta']); }?>">
                                     <?php
        }
        ?>
                                     
                                     </td>
							</tr>

				 <tr>
                    <td class="text-left font-weight-bold"><span DisplayLabelID="345"><?php echo htmlentities($pageLables[345]); ?></span></td>
                    <td>
                                      <?php
        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
            if (isset($data_array) && $data_array['isactive']==1) {
                echo 'Active';
            } else if(isset($data_array) && $data_array['isactive']==0){
				echo 'Deactive';
			}
        } else {
            ?>                      
               		<div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadio4" name="isactive" value="1" class="custom-control-input" <?php if(isset($data_array['isactive']) && $data_array['isactive']==1){ ?>checked<?php } ?>>
                            <label class="custom-control-label" for="customRadio4"><span DisplayLabelID="371"><?php echo htmlentities($pageLables[371]); ?></span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadio5" name="isactive" value="0" class="custom-control-input" <?php if(isset($data_array['isactive']) && $data_array['isactive']==0){ ?>checked<?php } ?>>
                            <label class="custom-control-label" for="customRadio5"><span DisplayLabelID="372"><?php echo htmlentities($pageLables[372]); ?></span></label>
                    </div>  
                                   <?php
        }
        ?>  
                                                 
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
                    <a class="btn btn-cancel btn-sm" href="AccountNumberEntry.php"><i class="fa fa-eraser pr-1"></i>Clear</a>
								</td>
							</tr>
						</t>

					</table>
				</form>

			</div>
		</div>
	</div>
	<div class="col-md-12 mt-4">
		<div class="card">
			<div class="card-body">
				<h4 class="header-title">
					<span DisplayLabelID="804"><?php echo htmlentities($pageLables[804]); ?></span> <a href="AccountNumberEntry.php"
						class="pull-right btn btn-sm btn-purple"><i class="fa fa-plus-square p-1" aria-hidden="true"></i><span DisplayLabelID="808"><?php echo htmlentities($pageLables[808]); ?></span></a>
				</h4>
				<div class="single-table">
					

						<table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2">
            				<thead>
								<tr >
									<td ><span DisplayLabelID="174"><?php echo htmlentities($pageLables[174]); ?></span></td>
									<td ><span DisplayLabelID="805"><?php echo htmlentities($pageLables[805]); ?></span></td>
									<td ><span DisplayLabelID="806"><?php echo htmlentities($pageLables[806]); ?></span></td>
									<td ><span DisplayLabelID="807"><?php echo htmlentities($pageLables[807]); ?></span></td>
									<td ><span DisplayLabelID="345"><?php echo htmlentities($pageLables[345]); ?></span></td>
									<td ><span DisplayLabelID="346"><?php echo htmlentities($pageLables[346]); ?></span></td>
								</tr>
						</thead>
                           <?php
        $list_com = "SELECT account_number_id,account_code,account_desc_en,account_desc_ta,isactive FROM accounts.m_account_number where del_flag is null";
		$set = $this->prepare($list_com,array(),2);
        $slno = 1;
        foreach ($set as $row) {
            ?>
                            <tbody>
								<tr>
									<td><?php echo htmlentities($slno++); ?></td>
									<td align="left"><?php echo htmlentities($row['account_code']); ?></td>
									<td align="left"><?php echo htmlentities($row['account_desc_en']); ?></td>
									<td align="left"><?php echo htmlentities($row['account_desc_ta']); ?></td>
									<td align="center"><?php if($row['isactive'] == 1){ echo htmlentities('Active'); } else { echo htmlentities('Inactive'); } ?></td>
									<td align="center"><a
										href="?edit_id=<?php echo base64_encode(htmlentities($row['account_number_id'])); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                                        <a
										href="?del_id=<?php echo base64_encode(htmlentities($row['account_number_id'])); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1" aria-hidden="true"></i>Delete</a></td>
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
		//print_r($save_data);exit;
        // TOKEN VALIDATE
        if (! $this->validateToken($this->page_token, $save_data[$this->page_token])) {
            $this->main_form(array_merge(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => $this->page_token,
                "MESSAGE" => "Invalid Token"
            ), $save_data));
        }
        else
        {
            unset($_SESSION[$this->page_token]);
        }

		
		 if (! isset($save_data["del_id"])) {
		$account_code = $save_data['account_code'];
		$account_code_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$account_code,
			'Field_Name'=>'account_code',
			'Field_Label_Name'=>'Enter Type Category English',
			)
			);
			
			if ($account_code_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_en",
                    "MESSAGE" => $account_code_Validation['Message']
                ), $save_data));
			exit;			
            }
		 }
        if (! isset($save_data["del_id"])) {
						if(isset($save_data['account_name_en']) && $save_data['account_name_en'] != '')
		{
			
            $account_name_en = $save_data['account_name_en'];
			
				if ($save_data['account_name_en']=='') {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_ta",
                    "MESSAGE" => 'Enter Slab Name Tamil'
                ), $save_data));
			exit;
			}
				/* $account_name_en_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=>$account_name_en,
			'Field_Name'=>'account_name_en',
			'Field_Label_Name'=>'Enter Type Category English',
			)
			);
			
			if ($account_name_en_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_en",
                    "MESSAGE" => $account_name_en_Validation['Message']
                ), $save_data));
			exit;			
            } */
		}
			else
		{
		$this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_en",
                    "MESSAGE" => "Enter Slab Name English"
                ), $save_data));
		}
		
		
		
		
           		if(isset($save_data['account_name_ta']) && $save_data['account_name_ta'] != '')
		{
			$account_name_ta = $save_data['account_name_ta'];

			if ($save_data['account_name_ta']=='') {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_ta",
                    "MESSAGE" => 'Enter Slab Name Tamil'
                ), $save_data));
			exit;
			}	
		}
		else
		{
		$this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "account_name_ta",
                    "MESSAGE" => 'Enter Slab Name Tamil'
                ), $save_data));
		}
		   
		   
		   if(isset($save_data['isactive']) && $save_data['isactive'] != '')
		{
			$isactive = $save_data['isactive'];

			$isactive_Validation = $this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=>$isactive,
			'Field_Name'=>'isactive',
			'Field_Label_Name'=>'Select Status',
			)
			);
			
			if ($isactive_Validation['Status'] == "Error") {
                $this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "isactive",
                    "MESSAGE" => $isactive_Validation['Message']
                ), $save_data));
			exit;			
            }			
		}
		else
		{
		$this->main_form(array_merge(array(
                    "STATUS" => "ERROR", 
                    "STATUS_TYPE" => "FIELD",
                    "FIELD_NAME" => "isactive",
                    "MESSAGE" => "Select Status"
                ), $save_data));
		}
        }
		$nameentry = "accounts.m_account_number_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
	

        // Save Part
        if (isset($save_data["edit_id"])) {
            $save_query = "select " . $nameentry . "(:account_code,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id)"; 
			
			$res = $this->prepare($save_query,array(":account_code"=>$account_code,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$save_data["edit_id"],":del_id"=>0),4);
			
			$message='Data Updated SccessFully';
			
        } else if (isset($save_data["del_id"])) {
           $save_query = "select " . $nameentry . "(:account_code,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id)";
		   
		  $res = $this->prepare($save_query,array(":account_code"=>null,":account_name_en"=>null,":account_name_ta"=>null,":isactive"=>0,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>0,":del_id"=>$save_data["del_id"]),4);
		   
		   $message='Data Deleted SccessFully';
		   
        } else {
             $save_query = "select " . $nameentry . "(:account_code,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,0,0);"; 
			
			$res = $this->prepare($save_query,array(":account_code"=>$account_code,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress),4);
			 
			 $message='Data Saved SccessFully';
			
        }

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

$AccountNumberEntry = new AccountNumberEntry();

if (isset($_POST["submit"])) {
    $AccountNumberEntry->data_save($_POST);
}
if (isset($_GET["edit_id"])) {
    $edit_id = base64_decode($_GET["edit_id"]);
    $AccountNumberEntry->main_form(array(
        "mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "edit_id" => $edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $del_id = base64_decode($_GET["del_id"]);
    $AccountNumberEntry->main_form(array(
        "mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "del_id" => $del_id
    ));
} else {
    $AccountNumberEntry->main_form(array(
        "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}

?>           