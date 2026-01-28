<?php
require_once  '../../config/configPublic.php';

class MasterMenu extends ConfigClass
{

    public $page_token = "menu_entry";

    function __construct()
    {        
       // $this->pageRoleAccessCheck(array(1));
    }

    public function main_form($data_array = array())
    {
      
        
        ob_start();

        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE	
		$state_code=$this->getCurrentStateCode();
        ?>
        <div class="container">
            <div class="row">
               <div class="card">
                    <div class="card-body">
                    	<form name="master_menu" action="" id="master_menu" method="post" autocomplete="off" class='fm-smt'>
                        <div class="col-lg-12 col-ml-12">
                            <?php
                            if (isset($data_array["STATUS"])) {
                                echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
                            }
                            ?>                       
                            <input class="form-control w-50 " type="hidden" id="profile_entry_token" name='profile_entry_token' value="<?php echo htmlentities($this->token("profile_entry_token")); ?>">
                                <table class="table table-bordered table-striped tndtp_form_table">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="2">Menu Entry</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="118" scope="col" class="w-50">Menu Name</td>
                                            <td width="144" scope="col">
                                                <?php
                                                if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                    if (isset($form_data)) {
                                                        echo htmlentities($form_data['menu_name']);
                                                    }
                                                } else {
                                                    ?>
                                                <input class="form-control w-50 Tax_Form_English_Ownername form-control-sm" type="text" placeholder="Enter Menu Name" id="menu_name" name='menu_name' value="<?php if(isset($form_data['menu_name'])) { echo htmlentities($form_data['menu_name']); }?>">
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col">Menu URL</td>
                                            <td scope="col">
                                                <?php
                                            if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                if (isset($form_data['menu_url'])) {
                                                    echo htmlentities($form_data['menu_url']);
                                                }
                                            } else {
                                                ?>
                                                <input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
                                                    type="text" placeholder="Enter Menu URL" id="menu_url" name='menu_url'
                                                    value="<?php if(isset($form_data['menu_url'])) { echo htmlentities($form_data['menu_url']); }?>">
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col">Status</td>
                                            <td scope="col">
                                                <?php
                                                  if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                        if (isset($form_data['rflag'])) {
                                                            echo htmlentities(isset($form_data['rflag']) && $form_data['rflag']==1?'Active':'Deactive');
                                                        }
                                                  }else
                                                  {
                                                        ?>
                                                        <input type="radio" id="rflag_y" name="rflag" value="1"	<?php  echo htmlentities(isset($form_data['rflag']) && $form_data['rflag']==1?'checked="checked"':''); ?> />Active
                                                        <input type="radio" id="rflag_n" name="rflag" value="0" <?php echo htmlentities(isset($form_data['rflag']) && $form_data['rflag']==0?'checked="checked"':''); ?> />Deactive
                                                        <?php
                                                  }
                                                  ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col">Type</td>
                                            <td scope="col">
                                                <?php
                                            if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                if (isset($form_data['report_no'])) {
                                                    echo htmlentities($form_data['report_no']=='F'?'Form':'Report');
                                                }
                                            } else {
                                                ?>
                                                <input type="radio" id="type_f" name="type" value="F" <?php  echo htmlentities(isset($form_data['type']) && $form_data['type']=='F'?'checked="checked"':''); ?> />Form
                                                <input type="radio" id="type_r" name="type" value="R" <?php echo htmlentities(isset($form_data['type']) && $form_data['type']=='R'?'checked="checked"':''); ?> />Report
                                                <?php } ?>
                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col">Purpose Of Form or Report</td>
                                            <td scope="col">
                                                <?php
                                            if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
                                                if (isset($form_data['menu_desc'])) {
                                                    echo htmlentities($form_data['menu_desc']);
                                                }
                                            } else {
                                                ?>
                                                <textarea type="menu_desc"
                                                    class="form-control w-50 form-control-sm form-control-primary"
                                                    id="menu_desc" name='menu_desc'
                                                    value='<?php if(isset($form_data['menu_desc'])) { echo htmlentities($form_data['menu_desc']); }?>'> </textarea>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" align="center">
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm text-white"
                                                    name="btn_save" id="btn_save"><i
                                                        class=" pr-1"
                                                        aria-hidden="true"></i>Save</button>
                                                &nbsp;
                                                <a class="btn btn-cancel btn-sm" href="master_menu.php"><i
                                                        class="fa fa-eraser pr-1"></i>Clear</a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </form>
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

        $this->Template('PublicTemplate', "User Role", $ob_output_main_forms, array(
            array(
                "name" => "User Role"
            )
        ));
        exit();
    }

}

$MasterMenu = new MasterMenu();
$MasterMenu->main_form();

?>           