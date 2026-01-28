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
		//$pageLables=$this->GetPageLables(162);
        $site_data = $this->siteData();
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
            
                if($("#account_code4").val().length < 4 )
                {
                    throw{msg:"Enter Valid 4 Digits Account Code",foc:"#account_code4"}
                }
                if($("#account_code7").val().length < 7 )
                {
                    throw{msg:"Enter Valid 7 DigitsAccount Code",foc:"#account_code7"}
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
        <div class="container mt-3">       
        <div class="row">
            <div class="col-md-12">
             <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
                    ?>
                 <input class="form-control form-control-sm" type="hidden"
                                id="edit_id" name='edit_id'
                                value="<?php echo htmlentities($data_array["edit_id"]); ?>">
                 <?php
                     $data_array_edit_new ="SELECT account_head_id,old_account_head_code,new_account_head_code,account_head_name_en,account_head_name_ta,isactive FROM accounts_master.m_account_head where account_head_id=:edit_id and del_flag is null";
                    $data_array_edit = $this->prepare($data_array_edit_new,array(":edit_id"=>$data_array["edit_id"]),4);
					if(isset($data_array_edit['account_head_id']) &&  $data_array_edit['account_head_id']!=''){
						$data_array = array_merge($data_array, $data_array_edit);
					}else{
						?>
						<script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/Accounts/Account_Head_Entry.php";
                        </script>
                        <?php 
					}
                    
                } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    ?>
                 <input class="form-control form-control-sm" type="hidden"
                                id="del_id" name='del_id'
                                value="<?php echo htmlentities($data_array["del_id"]); ?>">
                 <?php
                    $data_array_delete_new ="SELECT account_head_id,old_account_head_code,new_account_head_code,account_head_name_en,account_head_name_ta,isactive FROM accounts_master.m_account_head where account_head_id=:del_id and del_flag is null";
                     $data_array_delete = $this->prepare($data_array_delete_new,array(":del_id"=>$data_array["del_id"]),4);
					 if(isset($data_array_delete['account_head_id']) &&  $data_array_delete['account_head_id']!=''){
						 $data_array = array_merge($data_array, $data_array_delete);
					}else{
						?>
						<script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/Accounts/Account_Head_Entry.php";
                        </script>
                        <?php 
					}
                }
                if (isset($data_array["STATUS"])) {
                    echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                }
                ?>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" class="" enctype="multipart/form-data" autocomplete="off">
                            <input class="form-control form-control-sm" type="hidden"
                                id="<?php echo htmlentities($this->page_token); ?>"
                                name="<?php echo htmlentities($this->page_token); ?>"
                                value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                               <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="text-center"><span><?php echo htmlentities('Account Head Entry Form'); ?></span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                     
                                    <tr>
                                        <td><span><?php echo htmlentities('Account Code '.' (4 digits)'); ?></span></td>
                                        <td>
                                        <?php
                                        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                            if (isset($data_array['old_account_head_code']) && $data_array['old_account_head_code']!='') {
                                                echo htmlentities($data_array['old_account_head_code']);
                                            }
                                        } else {
                                            ?>
                                            <input class="form-control form-control-sm number_field w-50" type="text" placeholder="Enter Account Code" id="account_code4" name='account_code4' maxlength='4' value="<?php if(isset($data_array['old_account_head_code'])) { echo htmlentities($data_array['old_account_head_code']); }?>">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span><?php echo htmlentities('Account Code (7 digits)'); ?></span></td>
                                        <td>
                                        <?php
                                        if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                            if (isset($data_array['new_account_head_code']) && $data_array['new_account_head_code']!='') {
                                                echo htmlentities($data_array['new_account_head_code']);
                                            }
                                        } else {
                                            ?>
                                            <input class="form-control form-control-sm number_field w-50" type="text" placeholder="Enter Account Code" id="account_code7" name='account_code7' maxlength='7' value="<?php if(isset($data_array['new_account_head_code'])) { echo htmlentities($data_array['new_account_head_code']); }?>">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span><?php echo htmlentities('Account Head in English '); ?></span></td>
                                        <td >
                                            <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    if (isset($data_array['account_head_name_en']) && $data_array['account_head_name_en']!='') {
                        echo htmlentities($data_array['account_head_name_en']);
                    }
                } else {
                    ?>                                      
                                             <input
                                            class="form-control form-control-sm name_eng_with_space  w-50" type="text"
                                            placeholder="Enter Account Description in English" id="account_name_en" name='account_name_en'
                                            value="<?php if(isset($data_array['account_head_name_en'])) { echo htmlentities($data_array['account_head_name_en']); }?>">
                                             <?php
                }
                ?>
                                            
                                             </td>
                                    </tr>
                                    
                                    <tr>
                                        <td ><span><?php echo htmlentities('Account Head in Tamil '); ?></span></td>
                                        <td >
                                             
                                            <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    if (isset($data_array['account_head_name_ta']) && $data_array['account_head_name_ta']!='') {
                        echo htmlentities($data_array['account_head_name_ta']);
                    }
                } else {
                    ?>                                      
                                             <input
                                            class="form-control form-control-sm alphanum_tamil_comma_dot Tamil_Font  w-50" type="text"
                                            placeholder="Enter Account Description in Tamil" id="account_name_ta" name='account_name_ta'
                                            value="<?php if(isset($data_array['account_head_name_ta'])) { echo htmlentities($data_array['account_head_name_ta']); }?>">
                                             <?php
                }
                ?>
                                             
                                             </td>
                                    </tr>
        
                         <tr>
                            <td class="text-left font-weight-bold"><span><?php echo htmlentities('Status'); ?></span></td>
                            <td>
                                              <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    if (isset($data_array['isactive']) && $data_array['isactive']==1) {
                        echo 'Active';
                    } else if(isset($data_array['isactive']) && $data_array['isactive']==0){
                        echo 'Deactive';
                    }
                } else {
                    ?>                      
                            <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadio4" name="isactive" value="1" class="custom-control-input" <?php if(isset($data_array['isactive']) && $data_array['isactive']==1){ ?>checked<?php } ?>>
                                    <label class="custom-control-label" for="customRadio4"><span DisplayLabelID="371"><?php echo htmlentities('Active'); ?></span></label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadio5" name="isactive" value="0" class="custom-control-input" <?php if(isset($data_array['isactive']) && $data_array['isactive']==0){ ?>checked<?php } ?>>
                                    <label class="custom-control-label" for="customRadio5"><span DisplayLabelID="372"><?php echo htmlentities('In Active'); ?></span></label>
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
                                             
                                     <button type="submit" class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white" name="submit" id="save"><i class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1" aria-hidden="true"></i> <?php echo htmlentities($data_array["mode_name"]);?></button> 				&nbsp;
                            <a class="btn btn-secondary btn-sm" href="Account_Head_Entry.php"> <i class="fa fa-eraser pr-1"></i>  Clear</a>
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
                            <span><?php echo htmlentities('Account Head Entry Form '); ?></span> <a href="Account_Head_Entry.php" class="pull-right btn btn-sm btn-purple btn-primary"><i class="fa fa-plus-square p-1" aria-hidden="true"></i><span><?php echo htmlentities('New Entry '); ?></span></a>
                        </h4>
                        <div class="single-table">
                            
        
                                <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable2">
                                    <thead>
                                        <tr >
                                            <td ><span DisplayLabelID="174"><?php echo htmlentities('Sl. No'); ?></span></td>
                                            <td ><span DisplayLabelID="805"><?php echo htmlentities('Account Code  (4 digits)'); ?></span></td>
                                            <td ><span DisplayLabelID="805"><?php echo htmlentities('Account Code (7 digits)'); ?></span></td>
                                            <td ><span DisplayLabelID="806"><?php echo htmlentities('Account Head in English '); ?></span></td>
                                            <td ><span DisplayLabelID="807"><?php echo htmlentities('Account Head in Tamil '); ?></span></td>
                                            <td ><span DisplayLabelID="345"><?php echo htmlentities('Status'); ?></span></td>
                                            <td ><span DisplayLabelID="346"><?php echo htmlentities('Actions'); ?></span></td>
                                        </tr>
                                </thead>
                                   <?php
                $list_com = "SELECT account_head_id,new_account_head_code,old_account_head_code,account_head_name_en,account_head_name_ta,isactive FROM accounts_master.m_account_head where del_flag is null";
                $set = $this->prepare($list_com,array(),2);
                $slno = 1;
                if(count($set)>0){
                foreach ($set as $row) {
                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?php echo htmlentities($slno++); ?></td>
                                            <td align="left"><?php echo htmlentities($row['old_account_head_code']); ?></td>
                                            <td align="left"><?php echo htmlentities($row['new_account_head_code']); ?></td>
                                            <td align="left"><?php echo htmlentities($row['account_head_name_en']); ?></td>
                                            <td align="left"><?php echo htmlentities($row['account_head_name_ta']); ?></td>
                                            <td align="center"><?php if($row['isactive'] == 1){ echo htmlentities('Active'); } else { echo htmlentities('Inactive'); } ?></td>
                                            <td align="center"><a
                                                href="?edit_id=<?php echo base64_encode(htmlentities($row['account_head_id'])); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                                                <a
                                                href="?del_id=<?php echo base64_encode(htmlentities($row['account_head_id'])); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1" aria-hidden="true"></i>Delete</a></td>
                                        </tr>
        
                                    </tbody>
                                     <?php
                }
            }else{
                ?>
                <tr>
                    <td colspan="7" class="text-center text-danger">No Records Found.</td>
                </tr>
                <?php
            }
                ?>
                                    
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

        $this->Template('Template1', "User Role", $ob_output_main_forms, array(
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
			exit;
        }
        else
        {
            unset($_SESSION[$this->page_token]);
        }
		$site_data = $this->siteData();
		
		 if (!isset($save_data["del_id"])) {
			 if(isset($save_data['account_code4']) && $save_data['account_code4']!=''){
				$account_code4 = $save_data['account_code4'];
				$account_code_Validation = $this->Field_Validation(
					array
					(
					'Field_Type'=>'number',
					'Field_Value'=>$account_code4,
					'Field_Name'=>'account_code4',
					'Field_Max_Length'=>4,
					'Field_Label_Name'=>'Enter Account Head Code',
					)
					);
					
					if ($account_code_Validation['Status'] == "Error") {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "account_code4",
							"MESSAGE" => $account_code_Validation['Message']
						), $save_data));
						exit;			
					}
				 }
				 else{
						$this->main_form(array_merge(array(
								"STATUS" => "ERROR", 
								"STATUS_TYPE" => "FIELD",
								"FIELD_NAME" => "account_code4",
								"MESSAGE" => 'ENter Account Head Code'
							), $save_data));
							exit;	
				 }
				if (isset($save_data['account_code7']) && $save_data['account_code7']!='') {
					$account_code7 = $save_data['account_code7'];
					$account_code_Validation = $this->Field_Validation(
						array
						(
						'Field_Type'=>'number',
						'Field_Value'=>$account_code7,
						'Field_Name'=>'account_code7',
						'Field_Max_Length'=>4,
						'Field_Label_Name'=>'Enter Old Account Head Code',
						)
						);
					
					if ($account_code_Validation['Status'] == "Error") {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "old_account_head_code",
							"MESSAGE" => $account_code_Validation['Message']
						), $save_data));
						exit;			
					}
				 }else{
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "old_account_head_code",
							"MESSAGE" => 'Enter Account Head Code'
						), $save_data));
						exit;	
				 }
				if(isset($save_data['account_name_en']) && $save_data['account_name_en'] != '')
				{
					
					$account_name_en = $save_data['account_name_en'];
					$account_name_en_Validation = $this->Field_Validation(
					array
					(
						'Field_Type'=>'text_number_hyphen',
						'Field_Value'=>$account_name_en,
						'Field_Name'=>'account_name_en',
						'Field_Label_Name'=>'Account Head Name English',
						)
					);			
					if ($account_name_en_Validation['Status'] == "Error") {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "account_head_name_en",
							"MESSAGE" => $account_name_en_Validation['Message']
						), $save_data));
						exit;			
					} 
				}
				else
				{
					$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "account_name_en",
							"MESSAGE" => "Enter Account Head Name English"
						), $save_data));
				}
				if(isset($save_data['account_name_ta']) && $save_data['account_name_ta'] != '')
				{
					$account_name_ta = $save_data['account_name_ta'];
					if ($save_data['account_name_ta']=='') {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "account_head_name_ta",
							"MESSAGE" => 'Enter Account Head Name Tamil'
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
						"MESSAGE" => 'Enter Account Head Name Tamil'
					), $save_data));
					exit;
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
					exit;
				}
		 }
		$nameentry = "accounts_master.sp_account_head_entry";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();
		$edit_id = isset($save_data["edit_id"])?base64_decode($save_data["edit_id"]):0;
		$del_id = isset($save_data["del_id"])?base64_decode($save_data["del_id"]):0;
		
        // Save Part
        if (isset($save_data["edit_id"])) {
            $save_query = "select " . $nameentry . "(:account_code4,:acc7,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id);"; 
			
			$res = $this->prepare($save_query,array(":account_code4"=>$account_code4,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":acc7"=>$save_data["account_code7"],":del_id"=>'0'),4);
			
			$message='Data Updated SccessFully';
			
        } else if (isset($save_data["del_id"])) {
           $save_query = "select " . $nameentry . "(:account_code,:acc7,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id);";
		   
		  $res = $this->prepare($save_query,array(":account_code"=>null,":account_name_en"=>null,":account_name_ta"=>null,":isactive"=>0,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>'0',":del_id"=>$del_id,":acc7"=>NULL),4);
		   
		   $message='Data Deleted SccessFully';
		   
        } else {
              $save_query = "select " . $nameentry . "  (:account_code4,:acc7,:account_name_en,:account_name_ta,:isactive,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id)"; 
			
			$res = $this->prepare($save_query,array(":account_code4"=>$account_code4,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":isactive"=>$isactive,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>'0',":del_id"=>'0',":acc7"=>$save_data["account_code7"]),4);
			 
			 $message='Data Saved SccessFully';
			
        }

        if (!isset($res->errorInfo)) {
            /*$this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => $message
            ));*/
			?>
            <script>
				alert('<?php echo $message; ?>');
				window.location.href = "<?php $site_data->website_url;?>/project/forms/Accounts/Account_Head_Entry.php";
            </script>
            <?php 
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
    $AccountNumberEntry->data_save(array_merge($_POST,$_GET));
}
if (isset($_GET["edit_id"])) {
    $edit_id = base64_decode($_GET["edit_id"]);
	$edit_id_Validation = $AccountNumberEntry->Field_Validation(
	array
	(
	'Field_Type'=>'number',
	'Field_Value'=>$edit_id,
	'Field_Name'=>'edit_id',
	'Field_Label_Name'=>'Invalid Edit Id',
	)
	);
	
	if ($edit_id_Validation['Status'] == "Error") {
		$AccountNumberEntry->main_form(array_merge(array(
			"STATUS" => "ERROR", 
			"STATUS_TYPE" => "FIELD",
			"FIELD_NAME" => "isactive",
			"MESSAGE" => $edit_id_Validation['Message']
		), $save_data));
	exit;			
	}	
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
	$del_id_Validation = $AccountNumberEntry->Field_Validation(
	array
	(
	'Field_Type'=>'number',
	'Field_Value'=>$del_id,
	'Field_Name'=>'del_id',
	'Field_Label_Name'=>'Invalid Delete Id',
	)
	);
	
	if ($del_id_Validation['Status'] == "Error") {
		$AccountNumberEntry->main_form(array_merge(array(
			"STATUS" => "ERROR", 
			"STATUS_TYPE" => "FIELD",
			"FIELD_NAME" => "del_id",
			"MESSAGE" => $del_id_Validation['Message']
		), $save_data));
		exit;			
	}	
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