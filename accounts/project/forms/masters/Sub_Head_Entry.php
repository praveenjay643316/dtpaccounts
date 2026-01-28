<?php   
require_once __DIR__ . '/../../config/config.php';


class AccountNumberEntry extends ConfigClass
{

    public $page_token = "account_head_entry";

    function __construct()
    {       
    
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
         $('#dataTable').DataTable(); // Initialize the DataTable
        <?php if (!isset($data_array["del_id"])) { ?>
        $("#save").on('click',function()
        { 
             var Current_Field_id=$(this).attr('id'); $('#'+Current_Field_id).hide(); try {
            
                if($("#account_head_id").val().length ='' )
                {
                    throw{msg:"Choose Valid  Account Code",foc:"#account_head_id"}
                }
                
                
                if($("#account_name_en").val().length == '')
                {
                    throw{msg:"Enter Account Description in English",foc:"#account_name_en"}
                }
                if($("#account_name_ta").val().length == '')
                {
                    throw{msg:"Enter Account Description in Tamil",foc:"#account_name_ta"}
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
                <input class="form-control w-75  form-control-sm" type="hidden" id="<?php echo htmlentities($this->page_token); ?>" name="<?php echo htmlentities($this->page_token); ?>" value="<?php echo htmlentities($this->token($this->page_token)); ?>">
             <?php
                if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
                    ?>                
                 <?php
                    $data_array_edit_new ="SELECT sub_head_id,account_head_id,sub_head_name_en,sub_head_name_ta FROM accounts_master.m_sub_head where sub_head_id=:edit_id and del_flag is null";
                    $data_array_edit = $this->prepare($data_array_edit_new,array(":edit_id"=>$data_array["edit_id"]),4);
					if(isset($data_array_edit['sub_head_id']) &&  $data_array_edit['sub_head_id']!=''){
						$data_array = array_merge($data_array, $data_array_edit);
					}else{
						?>
						<script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/Accounts/Sub_Head_Entry.php";
                        </script>
                        <?php 
					}
                    
                } else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                    ?>
                 <?php
                    $data_array_delete_new ="SELECT sub_head_id,account_head_id,sub_head_name_en,sub_head_name_ta FROM accounts_master.m_sub_head where sub_head_id=:del_id and del_flag is null";
                     $data_array_delete = $this->prepare($data_array_delete_new,array(":del_id"=>$data_array["del_id"]),4);
					 if(isset($data_array_delete['sub_head_id']) &&  $data_array_delete['sub_head_id']!=''){
						 $data_array = array_merge($data_array, $data_array_delete);
					}else{
						?>
						<script>
                            alert('Invalid Id');
                            window.location.href = "<?php echo $site_data->website_url;?>/project/forms/Accounts/Sub_Head_Entry.php";
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
                        <form action="Sub_Head_Entry.php" method="post" enctype="multipart/form-data" autocomplete="off">

                            <input class="form-control form-control-sm" type="hidden"
                                id="<?php echo htmlentities($this->page_token); ?>"
                                name="<?php echo htmlentities($this->page_token); ?>"
                                value="<?php echo htmlentities($this->token($this->page_token)); ?>">
                            <input class="form-control form-control-sm" type="hidden" id="edit_id" name='edit_id' value="<?php echo htmlentities(isset($data_array["edit_id"])?$data_array["edit_id"]:''); ?>">
                            <input class="form-control form-control-sm" type="hidden" id="del_id" name='del_id' value="<?php echo htmlentities(isset($data_array["del_id"])?$data_array["del_id"]:''); ?>">
                               <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="text-center"><span><?php echo htmlentities('Sub Head Entry Form'); ?></span></td>
                                    </tr>
                                </thead>
                                <tbody>
                                     
                                  <tr>
    <td>
        <span style="color:red">*</span>
        <span><?php echo htmlentities('Choose Account Head'); ?></span>
    </td>
    <td>

        <?php
        $sql = 'SELECT * FROM accounts_master.m_account_head WHERE isactive = 1 ORDER BY old_account_head_code';

        $account_head_res = $this->prepare($sql, [], 2);
        ?>

        <select name="account_head_id" id="account_head_id"
                class="form-control form-control-sm w-75" required>
            <option value="">Choose Account Head</option>

            <?php
            if (!empty($account_head_res)) {
                foreach ($account_head_res as $row) {

                    $selected = (
                        isset($data_array['account_head_id']) &&
                        $data_array['account_head_id'] == $row['account_head_id']
                    ) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlentities($row['account_head_id']); ?>" <?php echo $selected; ?>>
    <?php
    echo htmlentities(
        $row['old_account_head_code'] . ' - ' .
        $row['account_head_name_en'] .
        ' (' . $row['new_account_head_code'] . ')'
    );
    ?>
</option>

            <?php
                }
            }
            ?>
        </select>

    </td>
</tr>

                                    <tr>
                                        <td><span style="color:red";>*</span><span><?php echo htmlentities('Sub Head in English '); ?></span></td>
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
                                            placeholder="Enter  Sub Description in English" id="account_name_en" name='account_name_en'
                                            value="<?php if(isset($data_array['sub_head_name_en'])) { echo htmlentities($data_array['sub_head_name_en']); }?>">
                                             <?php
                                            }
                                            ?>
                                                                        
                                             </td>
                                    </tr>
                                    
                                    <tr>
                                        <td ><span style="color:red";>*</span><span><?php echo htmlentities('Sub Head in Tamil '); ?></span></td>
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
                                            placeholder="Enter Sub Description in Tamil" id="account_name_ta" name='account_name_ta'
                                            value="<?php if(isset($data_array['sub_head_name_ta'])) { echo htmlentities($data_array['sub_head_name_ta']); }?>">
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
                            <a class="btn btn-secondary btn-sm" href="Sub_Head_Entry.php"> <i class="fa fa-eraser pr-1"></i>  Clear</a>
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
                            <span><?php echo htmlentities('Account Head Entry Form '); ?></span> <a href="Sub_Head_Entry.php" class="pull-right btn btn-sm btn-purple btn-primary"><i class="fa fa-plus-square p-1" aria-hidden="true"></i><span><?php echo htmlentities('New Entry '); ?></span></a>
                        </h4>
                        <div class="single-table">
                            
        
                                <table class="table table-bordered m-0 p-0 table-striped tndtp_report_table" id="dataTable">
                                    <thead>
                                        <tr >
                                            <td ><span DisplayLabelID="174"><?php echo htmlentities('Sl. No'); ?></span></td>
                                            <td ><span DisplayLabelID="805"><?php echo htmlentities('Account Head '); ?></span></td>
                                           
                                            <td ><span DisplayLabelID="806"><?php echo htmlentities('Sub Head in English '); ?></span></td>
                                            <td ><span DisplayLabelID="807"><?php echo htmlentities('Sub Head in Tamil '); ?></span></td>
                                           
                                            <td ><span DisplayLabelID="346"><?php echo htmlentities('Actions'); ?></span></td>
                                        </tr>
                                </thead>
                                <tbody>
                                   <?php
                $list_com = "select sub_head_id,a.account_head_id,old_account_head_code,new_account_head_code,account_head_name_en,account_head_name_ta,sub_head_name_en,sub_head_name_ta from 
                (select sub_head_id,account_head_id,sub_head_name_en,sub_head_name_ta from accounts_master.m_sub_head where del_flag is null  ) a

                left join
                (SELECT account_head_id, new_account_head_code,old_account_head_code,account_head_name_en,account_head_name_ta FROM accounts_master.m_account_head where del_flag is null)b
                
                
                on a.account_head_id=b.account_head_id order by old_account_head_code";
                $set = $this->prepare($list_com,array(),2);
                $slno = 1;
                if(count($set)>0){
                foreach ($set as $row) {
                    ?>
                                    
                                        <tr>
                                            <td><?php echo htmlentities($slno++); ?></td>
                                           <td align="left"> <?php echo htmlentities( $row['old_account_head_code'] . ' - ' . $row['account_head_name_en'] . ' (' . $row['new_account_head_code'] . ')'); ?>
                                               
                                           </td>

                            
                                            <td align="left"><?php echo htmlentities($row['sub_head_name_en']); ?></td>
                                            <td align="left"><?php echo htmlentities($row['sub_head_name_ta']); ?></td>
                                           
                                            <td align="center"><a
                                                href="?edit_id=<?php echo base64_encode(htmlentities($row['sub_head_id'])); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                                                <a
                                                href="?del_id=<?php echo base64_encode(htmlentities($row['sub_head_id'])); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1" aria-hidden="true"></i>Delete</a></td>
                                        </tr>
        
                                   
                                     <?php
                }
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

        $this->Template('Template1', "User Role", $ob_output_main_forms, array(
            array(
                "name" => "User Role"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {

        // print_r($save_data);die;
		
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
		 if (!isset($save_data["del_id"]) || $save_data["del_id"] == '') {
            
			 if(isset($save_data['account_head_id']) && $save_data['account_head_id']!=''){
				$account_head_id = $save_data['account_head_id'];
				$account_code_Validation = $this->Field_Validation(
					array
					(
					'Field_Type'=>'number',
					'Field_Value'=>$account_head_id,
					'Field_Name'=>'account_head_id',
					'Field_Max_Length'=>10,
					'Field_Label_Name'=>'Enter Account Head Id',
					)
					);
					
					if ($account_code_Validation['Status'] == "Error") {
						$this->main_form(array_merge(array(
							"STATUS" => "ERROR", 
							"STATUS_TYPE" => "FIELD",
							"FIELD_NAME" => "account_head_id",
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
								"MESSAGE" => 'ENter Account Head id'
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
                        'Field_Max_Length'=>250,
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
                
		 }
		$nameentry = "accounts_master.sp_sub_head_entry";
        $getCurrentUser = $this->getCurrentUser();


        $getIpAddress = $this->getIpAddress();
		$edit_id = isset($save_data["edit_id"])?($save_data["edit_id"]):0;
		$del_id = isset($save_data["del_id"])?($save_data["del_id"]):0;
        // Save Part
        if (isset($save_data["edit_id"]) && $save_data["edit_id"]!='') {
            $save_query = "select " . $nameentry . "(:account_head_id,:account_name_en,:account_name_ta,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id);"; 			
			$res = $this->prepare($save_query,array(":account_head_id"=>$account_head_id,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>$edit_id,":del_id"=>'0'),4);			
			$message='Data Updated SccessFully';			
        } else if (isset($save_data["del_id"]) && $save_data["del_id"]!='') {
            $save_query = "select " . $nameentry . "(:account_head_id,:account_name_en,:account_name_ta,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id);";		   
		    $res = $this->prepare($save_query,array(":account_head_id"=>null,":account_name_en"=>null,":account_name_ta"=>null,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>'0',":del_id"=>$del_id),4);		   
		   $message='Data Deleted SccessFully';		   
        } else {
            $save_query = "select " . $nameentry . "  (:account_head_id,:account_name_en,:account_name_ta,:getCurrentUser,:getIpAddress,now()::timestamp without time zone,:edit_id,:del_id)"; 			
			$res = $this->prepare($save_query,array(":account_head_id"=>$account_head_id,":account_name_en"=>$account_name_en,":account_name_ta"=>$account_name_ta,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":edit_id"=>'0',":del_id"=>'0'),4);			 
			 $message='Data Saved SccessFully';			
        }

        if (!isset($res->errorInfo)) {
            $this->main_form(array(
                "STATUS" => "SUCCESS",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Data Saved Successfully"
            ));
			?>
            <script>
				alert('<?php echo $message; ?>');
				window.location.href = "<?php $site_data->website_url;?>/project/forms/Accounts/Sub_Head_Entry.php";
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
			"FIELD_NAME" => "ID",
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