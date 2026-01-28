<?php
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php

=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
require_once __DIR__ . '/../../config/config.php';

class UserProfileEntry extends ConfigClass
{
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
	function __construct()
	{


	}

	public function main_form($data_array = array())
	{
		//var_dump($_SESSION);exit;
		ob_start();

		if (!isset($data_array['mode_name'])) {
			$data_array['mode_class'] = 'btn-success';
			$data_array['mode_icon'] = 'fa fa-floppy-o';
			$data_array['mode_name'] = 'Save';
		}

		$role_code = $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];




		//print_r($data_array);
		// #############

		// PAGE CONTENT START

		// #############

		// PLACE YOUR CODE HERE
		?>
		<script type="text/javascript">
			$(document).ready(function () {

				$(document).on('change', '#dcode', function () {

					if ($('#dcode').val() != '') {

						var dcode = $('#dcode').val();

						$.ajax({
							url: "UserProfileEntry.php",
							type: "post",
							data: {
								"dcode": btoa(dcode),
								"cmd": btoa(1)
							},
							success: function (data) {
								if (data != '') {
									$('#lbcode').html(data);
								}
							},
							dataType: 'html'
						});
						return true;
					} else {
						alert('Select District Name');
						$('#lbcode').html('<option value="">Select Town panchayat</option>');
						return true;
					}

				});


				$(document).on('change', '#user_designation', function () {

					var user_designation = $('#user_designation').val();




					if (user_designation == 21) {

						$.ajax({
							type: "post",
							url: "UserProfileEntry.php",
							data: {
								"designation_id": btoa(user_designation),
								"cmd": btoa(3)
							},
							success: function (data) {
								$('#tr_zone').show();
								$('#dist_display').hide();
								$('#office_level_id').html(data);
							},
							dataType: 'html'
						});
					} else {
						$('#tr_zone').hide();
						$('#dist_display').show();
					}
				});



				$(document).on('click', '#btn_save', function () {


					var Current_Field_id = $(this).attr('id');
					$('#' + Current_Field_id).hide();
					try {

						if ($("#dcode").val().length == '') {
							throw {
								msg: "Select District",
								foc: "#dcode"
							}
						}
						if ($("#lbcode").val().length == '') {
							throw {
								msg: "Select Town Panchayt",
								foc: "#lbcode"
							}
						}
						if ($("#first_name").val().length == '') {
							throw {
								msg: "Enter First Name",
								foc: "#first_name"
							}
						}

						if ($("#last_name").val().length == '') {
							throw {
								msg: "Enter Last Name",
								foc: "#last_name"
							}
						}

						if ($("#user_gender").val().length == '') {
							throw {
								msg: "Choose Gender",
								foc: "#user_gender"
							}
						}

						if ($("#user_mobile").val().length == '') {
							throw {
								msg: "Enter Mobile",
								foc: "#user_mobile"
							}
						}



						if ($("#user_designation").val().length == '') {
							throw {
								msg: "Choose Designation",
								foc: "#user_designation"
							}
						}
						if ($("#user_address").val().length == '') {
							throw {
								msg: "Enter Address",
								foc: "#user_address"
							}
						}


						return true;
					} catch (e) {
						alert(e.stack);
						alert(e);
						alert(e.msg);
						$('#' + Current_Field_id).show();
						$(e.foc).focus();
						return false;
					}
				});
			});
		</script>
		<style>
			.newhead {
				background: linear-gradient(to right, #494889, #3B3A7C, #494889) !important;
				color: white !important;
			}

			.tndtp_form_table {
				font-size: 15px;
				font-weight: bold;
				width: 100%;
			}

			.tndtp_form_table thead {
				padding: 3px
			}

			.tndtp_report_table {
				font-size: 15px;
				font-weight: bold;
				width: 100%;
				border-radius: 10px;
				text-align: center;
			}

			.tndtp_form_report_table th,
			td {
				padding: 10px;
				text-align: center;
			}

			.card {
				padding: 20px;
				margin: 20px;
				border-radius: 7px;
				box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
				background: #fff;
			}

			.schemebuton {
				background: #F56217;
				color: white;
				font-size: 15px;
				border-radius: 7px;
				font-weight: bold;
				padding: 5px;
				margin: 3px;
				border: none;
			}

			@media (max-width: 600px) {

				.tndtp_form_report_table,
				.tndtp_form_table {
					width: 100%;
					display: block;
					overflow-x: auto;
				}

				.tndtp_form_report_table thead,
				.tndtp_form_table thead {
					display: none;
				}
			}
		</style>


		<div class="container">
			<div class="card">
				<div class="card-body">
					<div class="col-lg-12 col-ml-12">
						<?php
						if (isset($data_array["STATUS"])) {
							echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
						}
						$dcode=$this->getCurrentDistrictCode();$lbcode=$this->getCurrentLocalBodyCode();
						?>
						<form name="user_profile" action="" id="user_profile" method="post" autocomplete="off" class='fm-smt'>
							<input class="form-control w-50 " type="hidden" id="profile_entry_token" name='profile_entry_token'
								value="<?php echo htmlentities($this->token("profile_entry_token")); ?>">
							<?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
								?>
								<input class="form-control w-50 " type="hidden" id="profile_edit_id" name='profile_edit_id'
									value="<?php echo htmlentities($data_array["profile_edit_id"]); ?>">
								<?php
								$sel_form_data = "select user_first_name,user_last_name,gender,mobile_no,email_address,a.role_id,user_type,to_char(from_date,'dd-MM-YYYY')as from_date,to_char(to_date,'dd-MM-YYYY')as to_date,a.office_id,user_address,user_setting,role_name,dcode,lbcode from  security.t_accounts_user_profile
												as a  
												left join 
							(select user_profile_id,dcode,lbcode from security.t_accounts_users where del_flag is null )b
							on b.user_profile_id=a.user_profile_id
							left join
							security.m_accounts_role as c on c.role_code=a.role_id  
							where a.user_profile_id=:user_profile_id and a.del_flag is  null";
								$form_data = $this->prepare($sel_form_data, array(":user_profile_id" => $data_array["profile_edit_id"]), 4);

							} else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								?>
									<input class="form-control w-50 " type="hidden" id="profile_delete_id" name='profile_delete_id'
										value="<?php echo htmlentities($data_array["profile_delete_id"]); ?>">
									<?php
									$sel_form_data = "select user_first_name,user_last_name,gender,mobile_no,email_address,a.role_id,user_type,to_char(from_date,'dd-MM-YYYY')as from_date,to_char(to_date,'dd-MM-YYYY')as to_date,a.office_id,user_address,user_setting,role_name, e.dcode, e.lbcode, district_name_en, lbody_name_en from  security.t_accounts_user_profile
                    as a  
                    left join 
                    security.m_accounts_role as c on c.role_code=a.role_id  
					left join 
security.t_accounts_users as e on e.user_profile_id=a.user_profile_id 
=======
    function __construct()
    {

        
    }

    public function main_form($data_array = array())
    {
        //var_dump($_SESSION);exit;
		ob_start();

      	if(!isset($data_array['mode_name'])){
			$data_array['mode_class']='btn-success';
			$data_array['mode_icon']='fa fa-floppy-o';
			$data_array['mode_name']='Save';
		}	

		$role_code=$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'];

		

        // #############

        // PAGE CONTENT START

        // #############

        // PLACE YOUR CODE HERE
        ?>
<script type="text/javascript">
$(document).ready(function() {

    $(document).on('change', '#dcode', function() {

        if ($('#dcode').val() != '') {

            var dcode = $('#dcode').val();

            $.ajax({
                url: "UserProfileEntry.php",
                type: "post",
                data: {
                    "dcode": btoa(dcode),
                    "cmd": btoa(1)
                },
                success: function(data) {
                    if (data != '') {
                        $('#lbcode').html(data);
                    }
                },
                dataType: 'html'
            });
            return true;
        } else {
            alert('Select District Name');
            $('#lbcode').html('<option value="">Select Town panchayat</option>');
            return true;
        }

    });


    $(document).on('change', '#user_designation', function() {

        var user_designation = $('#user_designation').val();

        $('input:radio[name=user_type]').removeAttr('checked');

        if (user_designation == 16) {
            $('#tr_user_type').show();
        } else {
            $('#tr_user_type').hide();
        }

        if (user_designation == 21) {

            $.ajax({
                type: "post",
                url: "UserProfileEntry.php",
                data: {
                    "designation_id": btoa(user_designation),
                    "cmd": btoa(3)
                },
                success: function(data) {
                    $('#tr_zone').show();
                    $('#dist_display').hide();
                    $('#office_level_id').html(data);
                },
                dataType: 'html'
            });
        } else {
            $('#tr_zone').hide();
            $('#dist_display').show();
        }
    });



    $(document).on('click', '#btn_save', function() {


        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {

            if ($("#dcode").val().length == '') {
                throw {
                    msg: "Select District",
                    foc: "#dcode"
                }
            }
            if ($("#lbcode").val().length == '') {
                throw {
                    msg: "Select Town Panchayt",
                    foc: "#lbcode"
                }
            }
            if ($("#first_name").val().length == '') {
                throw {
                    msg: "Enter First Name",
                    foc: "#first_name"
                }
            }

            if ($("#last_name").val().length == '') {
                throw {
                    msg: "Enter Last Name",
                    foc: "#last_name"
                }
            }

            if ($("#user_gender").val().length == '') {
                throw {
                    msg: "Choose Gender",
                    foc: "#user_gender"
                }
            }

            if ($("#user_mobile").val().length == '') {
                throw {
                    msg: "Enter Mobile",
                    foc: "#user_mobile"
                }
            }

           

            if ($("#user_designation").val().length == '') {
                throw {
                    msg: "Choose Designation",
                    foc: "#user_designation"
                }
            }
            if ($("#user_address").val().length == '') {
                throw {
                    msg: "Enter Address",
                    foc: "#user_address"
                }
            }


            return true;
        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            $(e.foc).focus();
            return false;
        }
    });
});
</script>
<style>
  .newhead {
	  background: linear-gradient(to right, #494889, #3B3A7C, #494889)!important;
	  color: white!important;
  }
  .tndtp_form_table {
	  font-size: 15px;
	  font-weight: bold;
	  width: 100%;
  }
  
  .tndtp_form_table thead {
	  padding: 3px
  }
  
  .tndtp_report_table {
	  font-size: 15px;
	  font-weight: bold;
	  width: 100%;
	  border-radius: 10px;
	  text-align: center;
  }
  
  .tndtp_form_report_table th,
  td {
	  padding: 10px;
	  text-align: center;
  }
  .card {
	  padding: 20px;
	  margin: 20px;
	  border-radius: 7px;
	  box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
	  background: #fff;
  }
  .schemebuton {
	  background: #F56217;
	  color: white;
	  font-size: 15px;
	  border-radius: 7px;
	  font-weight: bold;
	  padding: 5px;
	  margin: 3px;
	  border: none;
  }
  @media (max-width: 600px) {
  
	  .tndtp_form_report_table,
	  .tndtp_form_table {
		  width: 100%;
		  display: block;
		  overflow-x: auto;
	  }
	  .tndtp_form_report_table thead,
	  .tndtp_form_table thead {
		  display: none;
	  }
  }
</style>


    <div class="container">
    	<div class="card">
        <div class="card-body">
            <div class="col-lg-12 col-ml-12">
                <?php
			if (isset($data_array["STATUS"])) {
				echo $this->ShowMessage($data_array["STATUS"], $data_array["MESSAGE"]);
			}
			
			?>
                <form name="user_profile" action="" id="user_profile" method="post" autocomplete="off" class='fm-smt'>
                    <input class="form-control w-50 " type="hidden" id="profile_entry_token" name='profile_entry_token'
                        value="<?php echo htmlentities($this->token("profile_entry_token")); ?>">
                    <?php
			if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
				?>
                    <input class="form-control w-50 " type="hidden" id="profile_edit_id" name='profile_edit_id'
                        value="<?php echo htmlentities($data_array["profile_edit_id"]); ?>">
                    <?php
			 	$sel_form_data="select user_first_name,user_last_name,gender,mobile_no,email_address,a.role_id,user_type,to_char(from_date,'dd-MM-YYYY')as from_date,to_char(to_date,'dd-MM-YYYY')as to_date,a.office_id,user_address,user_setting,role_name,dcode,lbcode from  security.t_user_profile
                    as a  
                    left join 
(select user_profile_id,dcode,lbcode from security.t_users where del_flag is null )b
on b.user_profile_id=a.user_profile_id
left join
                    security.m_role as c on c.role_code=a.role_id  
                    where a.user_profile_id=:user_profile_id and a.del_flag is  null";
				$form_data = $this->prepare($sel_form_data,array(":user_profile_id"=>$data_array["profile_edit_id"]),4);
				
			} else if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
				?>
                    <input class="form-control w-50 " type="hidden" id="profile_delete_id" name='profile_delete_id'
                        value="<?php echo htmlentities($data_array["profile_delete_id"]); ?>">
                    <?php
			 	$sel_form_data="select user_first_name,user_last_name,gender,mobile_no,email_address,a.role_id,user_type,to_char(from_date,'dd-MM-YYYY')as from_date,to_char(to_date,'dd-MM-YYYY')as to_date,a.office_id,user_address,user_setting,role_name, e.dcode, e.lbcode, district_name_en, lbody_name_en from  security.t_user_profile
                    as a  
                    left join 
                    security.m_role as c on c.role_code=a.role_id  
					left join 
security.t_users as e on e.user_profile_id=a.user_profile_id 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
					left join
					master.m_district as d on e.dcode=d.dcode
					left join
					master.m_localbodies as b on e.dcode=b.dcode and e.lbcode=b.lbcode 
                    where a.user_profile_id=:user_profile_id and a.del_flag is  null";
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
									$form_data = $this->prepare($sel_form_data, array(":user_profile_id" => $data_array["profile_delete_id"]), 4);
							}
							?>
							<table class="table table-bordered tndtp_form_table">
								<thead>
									<tr>
										<td scope="col" colspan="2" class="newhead">User Profile Entry</td>
									</tr>
								</thead>
								<tbody>

									<tr>
										<?php if ($this->getCurrentZoneCode() == '') {
											if ($this->getCurrentDistrictCode() == '') { ?>
												<td width="118" scope="col" class="w-50">District</td>
												<td width="144" scope="col">
													<?php
													if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
														if (isset($form_data['district_name_en']) && $form_data['district_name_en'] != '') {
															echo htmlentities($form_data['district_name_en']);
														}
													} 
													else if (isset($data_array["mode"]) && $data_array["mode"] == "edit"){ ?>
														<select id="dcode" name="dcode" class="form-control w-50   form-control-sm">
															<option value="" DisplayLabelID="255">Choose District</option>
															<?php

															$sel_dist_detail = "SELECT state_code,dcode,district_name_en FROM master.m_district ORDER BY dcode";
															$sel_dist_detail_res = $this->prepare($sel_dist_detail, array(), 2);
															foreach ($sel_dist_detail_res as $sel_dist_detail_key => $sel_dist_detail_row) {
																?>
																<option value="<?php echo htmlentities($sel_dist_detail_row['dcode']); ?>">
																	<?php echo htmlentities($sel_dist_detail_row['district_name_en']); ?>
																</option>
																<?php
															}
															?>
															<script type="text/javascript">
																document.getElementById('dcode').value =
																	'<?php echo htmlentities(isset($form_data['dcode']) ? $form_data['dcode'] : ''); ?>';
															</script>
														</select>
													<?php }
													else{
														?>
														<select id="dcode" name="dcode" class="form-control w-50   form-control-sm">
															<option value="" DisplayLabelID="255">Choose District</option>
															<?php

															$sel_dist_detail = "SELECT state_code,dcode,district_name_en FROM master.m_district ORDER BY dcode";
															$sel_dist_detail_res = $this->prepare($sel_dist_detail, array(), 2);
															foreach ($sel_dist_detail_res as $sel_dist_detail_key => $sel_dist_detail_row) {
																?>
																<option value="<?php echo htmlentities($sel_dist_detail_row['dcode']); ?>">
																	<?php echo htmlentities($sel_dist_detail_row['district_name_en']); ?>
																</option>
																<?php
															}
															?>
															<script type="text/javascript">
																document.getElementById('dcode').value =
																	'<?php echo htmlentities(isset($data_array['dcode']) ? $data_array['dcode'] : ''); ?>';
															</script>
														</select>


														<?php
													}
											}
										} ?>
										</td>
									</tr>

									<tr>
										<?php if ($this->getCurrentLocalBodyCode() == '') { ?>
											<td width="118" scope="col" class="w-50">Town Panchayat</td>
											<td width="144" scope="col">
												<?php
												if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
													if (isset($form_data['lbody_name_en']) && $form_data['lbody_name_en'] != '') {
														echo htmlentities($form_data['lbody_name_en']);
													}
												} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit") { ?>
													<select id="lbcode" name="lbcode" class="form-control w-50   form-control-sm">
														<option value="" DisplayLabelID="255">Choose Town Panchayat</option>
														<?php
														if (isset($_POST['dcode']) || isset($_GET['edit_id'])) {

															$dcode = isset($_POST['dcode']) ? $_POST['dcode'] : $form_data['dcode'];

															if ($dcode != '') {

																$sel_town_details = "SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE dcode=:dcode AND lbtype=:lbtype and isactive=1 and del_flag is null and closing_date is null order by lbody_name_en asc";
																$sel_town_details_res = $this->prepare($sel_town_details, array(":dcode" => $dcode, ":lbtype" => 'TP'), 2);
																foreach ($sel_town_details_res as $sel_town_details_key => $sel_town_details_row) {

																	?>
																	<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
																		<?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?>
																	</option>
																	<?php
																}
																?>
																<script type="text/javascript">
																	document.getElementById('lbcode').value =
																		'<?php echo htmlentities(isset($form_data['lbcode']) ? $form_data['lbcode'] : ''); ?>';
																</script>
																<?php
															}
														}
														?>
													</select>
												<?php }
												else{
													?>
													<select id="lbcode" name="lbcode" class="form-control w-50   form-control-sm">
														<option value="" DisplayLabelID="255">Choose Town Panchayat</option>
														<?php
														if (isset($_POST['dcode']) || isset($_GET['edit_id'])) {

															$dcode = isset($_POST['dcode']) ? $_POST['dcode'] : $form_data['dcode'];

															if ($dcode != '') {

																$sel_town_details = "SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE dcode=:dcode AND lbtype=:lbtype and isactive=1 and del_flag is null and closing_date is null order by lbody_name_en asc";
																$sel_town_details_res = $this->prepare($sel_town_details, array(":dcode" => $dcode, ":lbtype" => 'TP'), 2);
																foreach ($sel_town_details_res as $sel_town_details_key => $sel_town_details_row) {

																	?>
																	<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
																		<?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?>
																	</option>
																	<?php
																}
																?>
																<script type="text/javascript">
																	document.getElementById('lbcode').value =
																		'<?php echo htmlentities(isset($data_array['lbcode']) ? $data_array['lbcode'] : ''); ?>';
																</script>
																<?php
															}
														}
														?>
													</select>
													<?php
												}
										} ?>


										</td>
									</tr>
									<tr>
										<td width="118" scope="col" class="w-50">First Name</td>
										<td width="144" scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['user_first_name']) && $form_data['user_first_name'] != '') {
													echo htmlentities($form_data['user_first_name']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
												?>
												<input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
													type="text" placeholder="Enter First Name" id="first_name" name='first_name'
													value="<?php if (isset($form_data)) {
														echo htmlentities($form_data['user_first_name']);
													} ?>">
													
											<?php }else{
												?>
												<input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
													type="text" placeholder="Enter First Name" id="first_name" name='first_name'
													value="<?php echo (isset($data_array['first_name']) && $data_array['first_name']!='')?$data_array['first_name']:''; ?>"><?php
											} ?>
										</td>
									</tr>
									<tr>
										<td scope="col">Last Name</td>
										<td scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['user_last_name']) && $form_data['user_last_name'] != '') {
													echo htmlentities($form_data['user_last_name']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit"){
												?>
												<input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
													type="text" placeholder="Enter Last Name" id="last_name" placeholder="Enter Your Last Name" name='last_name' value="<?php if (isset($form_data)) {
														echo htmlentities($form_data['user_last_name']);
													} ?>">
											<?php }
											
											else{

												?>

													<input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
												type="text" placeholder="Enter Last Name" id="last_name" placeholder="Enter Your Last Name" name='last_name' value="<?php if (isset($data_array['last_name'])) {echo htmlentities($data_array['last_name']);}?>">
												

												<?php
											}
											?>
										</td>
									</tr>
									<tr>
										<td scope="col">Gender</td>
										<td scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['gender']) && $form_data['gender'] != '') {
													$sel_gender_exist = "select gender_code,gender_name_en from master.m_gender where gender_code=:gender_code";
													$gender_exist = $this->prepare($sel_gender_exist, array(":gender_code" => $form_data['gender']), 4);
													echo htmlentities($gender_exist['gender_name_en']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit")
											{
												?>
												<select class="form-control w-50   form-control-sm" id="user_gender"
													name='user_gender' onkeyup='emptyValidate(this.id);'>
													<option value=''>Please Select</option>
													<?php
													$sel_gender_data = "select * from master.m_gender";
													$gender_data = $this->prepare($sel_gender_data, array(), 2);
													foreach ($gender_data as $gender) {
														?>
														<option value='<?php echo htmlentities($gender['gender_code']); ?>' <?php if (isset($form_data['gender'])) {
															   if ($gender['gender_code'] == $form_data['gender'])
																   echo htmlentities('selected');
														   } ?>>
															<?php echo htmlentities($gender['gender_name_en']); ?>
														</option>
														<?php
													}
													?>
												</select>
											<?php } 
											else{
												?>
												<select class="form-control w-50   form-control-sm" id="user_gender"
													name='user_gender' onkeyup='emptyValidate(this.id);'>
													<option value=''>Please Select</option>
													<?php
													$sel_gender_data = "select * from master.m_gender";
													$gender_data = $this->prepare($sel_gender_data, array(), 2);
													foreach ($gender_data as $gender) {
														?>
														<option value='<?php echo htmlentities($gender['gender_code']); ?>' <?php if (isset($data_array['user_gender'])) {
															   if ($gender['gender_code'] == $data_array['user_gender'])
																   echo htmlentities('selected');
														   } ?>>
															<?php echo htmlentities($gender['gender_name_en']); ?>
														</option>
														<?php
													}
													?>
												</select>
												<?php
											}
											?>
										</td>
									</tr>
									<tr>
										<td scope="col">Mobile</td>
										<td scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['mobile_no']) && $form_data['mobile_no'] != '') {
													echo htmlentities($form_data['mobile_no']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit"){
												?>
												<input
													class="form-control w-50  form-control-sm mobile_number  Number_Field Tax_Form_Mobileno"
													type="text" placeholder="Enter Mobile Number" maxlength='10' id="user_mobile"
													name='user_mobile' value="<?php if (isset($form_data)) {
														echo htmlentities($form_data['mobile_no']);
													} ?>">
											<?php }
											else{
												?>
												<input
													class="form-control w-50  form-control-sm mobile_number  Number_Field Tax_Form_Mobileno"
													type="text" placeholder="Enter Mobile Number" maxlength='10' id="user_mobile"
													name='user_mobile' value="<?php if (isset($data_array['user_mobile'])) {
														echo htmlentities($data_array['user_mobile']);
													} ?>">
												
												<?php
											}
											?>

										</td>
									</tr>
										<tr>
											<td scope="col">Email</td>
											<td scope="col">
												<?php
												if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
													if (isset($form_data['email_address']) && $form_data['email_address'] != '') {
														echo htmlentities($form_data['email_address']);
													}
												} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit")  {
													?>
													<input type="email"
														class="form-control w-50  form-control-sm form-control-primary  Tax_Form_Mailid"
														id="user_email" name='user_email' placeholder="name@example.com" value='<?php if (isset($form_data)) {
															echo htmlentities($form_data['email_address']);
														} ?>'>
												<?php }
												else{
													?>
													<input type="email"
														class="form-control w-50  form-control-sm form-control-primary  Tax_Form_Mailid"
														id="user_email" name='user_email' placeholder="name@example.com" value='<?php if (isset($data_array["user_email"])) {
															echo htmlentities($data_array['user_email']);
														} ?>'>
													
													<?php
												}
												
												?>
											</td>
										</tr>
								

									<tr>
										<td scope="col">Designation / Role</td>
										<td scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['role_name']) && $form_data['role_name'] != '') {
													echo htmlentities($form_data['role_name']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit"){
												?>
												<select class="form-control w-50   form-control-sm" id="user_designation"
													name='user_designation' onchange="$('#tr_temporary_duration').hide();" <?php echo isset($form_data['role_id']) ? 'readonly' : ''; ?>>
													<option value=''>Please Select</option>
													<?php
													$designation_con = "";
													$designation_con_array = array();



													$sel_designation_data = "select role_code,role_name from  security.m_accounts_role where del_flag is null;";

													$designation_data = $this->prepare($sel_designation_data, array(), 2);

													foreach ($designation_data as $designation) {
														?>
														<option value='<?php echo htmlentities($designation['role_code']); ?>' <?php if (isset($form_data['role_id'])) {
															   if ($designation['role_code'] == $form_data['role_id'])
																   echo htmlentities('selected');
														   } ?>>
															<?php echo htmlentities($designation['role_name']); ?>
														</option>
														<?php
													}
													?>
												</select>
											<?php } 
											else{
												?>
												<select class="form-control w-50   form-control-sm" id="user_designation"
													name='user_designation' onchange="$('#tr_temporary_duration').hide();" <?php echo isset($form_data['role_id']) ? 'readonly' : ''; ?>>
													<option value=''>Please Select</option>
													<?php
													$designation_con = "";
													$designation_con_array = array();



													$sel_designation_data = "select role_code,role_name from  security.m_accounts_role where del_flag is null;";

													$designation_data = $this->prepare($sel_designation_data, array(), 2);

													foreach ($designation_data as $designation) {
														?>
														<option value='<?php echo htmlentities($designation['role_code']); ?>' <?php if (isset($data_array["user_designation"])) {
															   if ($designation['role_code'] == $data_array['user_designation'])
																   echo htmlentities('selected');
														   } ?>>
															<?php echo htmlentities($designation['role_name']); ?>
														</option>
														<?php
													}
													?>
												</select>
												
												<?php
											}
											?>

										</td>
									</tr>


									<tr>
										<td scope="col">Address</td>
										<td scope="col">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
												if (isset($form_data['user_address']) && $form_data['user_address'] != '') {
													echo htmlentities($form_data['user_address']);
												}
											} else if (isset($data_array["mode"]) && $data_array["mode"] == "edit") {
												?>
												<input type="text" class="form-control w-50  form-control-sm Tax_Form_Door_Number "
													id="user_address" name='user_address' placeholder="Enter address" value='<?php if (isset($form_data)) {
														echo htmlentities($form_data['user_address']);
													} ?>'>
											<?php }
											else{
												?>
												<input type="text" class="form-control w-50  form-control-sm Tax_Form_Door_Number "
													id="user_address" name='user_address' placeholder="Enter address" value='<?php if (isset($data_array["user_address"])) {
														echo htmlentities($data_array['user_address']);
													} ?>'>
												
												<?php
											} ?>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" align="center">
											<?php
											if (isset($data_array["mode"]) && $data_array["mode"] == "delete") { ?>
												<button type="submit"
													class="btn <?php echo htmlentities($data_array["mode_class"]); ?> btn-sm text-white"
													name="btn_save" id="btn_delete"><i
														class="<?php echo htmlentities($data_array["mode_icon"]); ?> pr-1"
														aria-hidden="true"></i><?php echo htmlentities($data_array["mode_name"]); ?></button>
											<?php } else { ?>
												<button type="submit"
													class="btn <?php echo htmlentities($data_array["mode_class"]); ?> btn-sm text-white"
													name="btn_save" id="btn_save"><i
														class="<?php echo htmlentities($data_array["mode_icon"]); ?> pr-1"
														aria-hidden="true"></i>
													<?php echo htmlentities($data_array["mode_name"]); ?></button>
											<?php } ?>
											&nbsp;
											<a class="btn btn-cancel btn-sm" href="UserProfileEntry.php"><i
													class="fa fa-eraser pr-1"></i> Clear</a>
										</td>
									</tr>
								</tfoot>
							</table>
						</form>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="col-lg-12 col-ml-12">
						<h4 class="header-title"><a href="UserProfileEntry.php"
								class="pull-right btn btn-sm btn-purple text-white"><i class="fa fa-plus-square p-1"
									aria-hidden="true"></i>New</a>
						</h4>

						<table class="table table-bordered table-responsive tndtp_form_report_table" id="Result_table">
							<thead>
								<tr>
									<th scope="col" class="newhead">S.NO</th>
									<th scope="col" class="newhead">First Name</th>
									<th scope="col" class="newhead">Last Name</th>
									<th scope="col" class="newhead">Gender</th>
									<th scope="col" class="newhead">Mobile</th>
									<th scope="col" class="newhead">Email</th>
									<th scope="col" class="newhead">Password</th>
									<th scope="col" class="newhead">Designation</th>
									<th scope="col" class="newhead">Address</th>
									<th scope="col" class="newhead">Edit Action</th>
									<th scope="col" class="newhead">Delete Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$username = $this->getCurrentUser();


								$office_con = "";
								$office_con_array = array();
=======
				$form_data = $this->prepare($sel_form_data,array(":user_profile_id"=>$data_array["profile_delete_id"]),4);
			}
			?>
                    <table class="table table-bordered tndtp_form_table">
                        <thead>
                            <tr>
                                <td scope="col" colspan="2"  class="newhead">User Profile Entry</td>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <?php if($this->getCurrentZoneCode()=='') { if($this->getCurrentDistrictCode()==''){ ?>
                                <td width="118" scope="col" class="w-50">District</td>
                                <td width="144" scope="col">
								<?php 
								if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($form_data['district_name_en']) && $form_data['district_name_en']!='') {
										echo htmlentities($form_data['district_name_en']);
									}
								} else { ?>
                                    <select id="dcode" name="dcode" class="form-control w-50   form-control-sm">
                                        <option value="" DisplayLabelID="255">Choose District</option>
                                        <?php

										$sel_dist_detail="SELECT state_code,dcode,district_name_en FROM master.m_district ORDER BY dcode";
										$sel_dist_detail_res=$this->prepare($sel_dist_detail,array(),2);
										foreach($sel_dist_detail_res as $sel_dist_detail_key=>$sel_dist_detail_row)
										{
										?>
												<option value="<?php echo htmlentities($sel_dist_detail_row['dcode']); ?>">
													<?php echo htmlentities($sel_dist_detail_row['district_name_en']); ?>
												</option>
												<?php
										}
										?>
                                        <script type="text/javascript">
                                        document.getElementById('dcode').value =
                                            '<?php echo htmlentities(isset($form_data['dcode'])?$form_data['dcode']:''); ?>';
                                        </script>
                                    </select>
                                    <?php } } }?>
                                </td>
                            </tr>

                            <tr>
                                <?php if( $this->getCurrentLocalBodyCode()=='')
								
								{ ?>
                                <td width="118" scope="col" class="w-50">Town Panchayat</td>
                                <td width="144" scope="col">
								<?php
                                if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($form_data['lbody_name_en']) && $form_data['lbody_name_en']!='') {
										echo htmlentities($form_data['lbody_name_en']);
									}
								} else { ?>
                                    <select id="lbcode" name="lbcode" class="form-control w-50   form-control-sm">
                                        <option value="" DisplayLabelID="255">Choose Town Panchayat</option>
                                        <?php
								if(isset($_POST['dcode']) ||  isset($_GET['edit_id']))
								{
								
									$dcode=isset($_POST['dcode'])?$_POST['dcode']:$form_data['dcode'];
									
									if($dcode!='')
									{

									$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE dcode=:dcode AND lbtype=:lbtype and isactive=1 and del_flag is null order by lbody_name_en asc";
									$sel_town_details_res=$this->prepare($sel_town_details,array(":dcode"=>$dcode,":lbtype"=>'TP'),2);
									foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
									{
										
									?>
                                        <option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
                                            <?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?></option>
                                        <?php
									}
									?>
                                        <script type="text/javascript">
                                        document.getElementById('lbcode').value =
                                            '<?php echo htmlentities(isset($form_data['lbcode'])?$form_data['lbcode']:''); ?>';
                                        </script>
                                        <?php
									}
									
									
									
								}
								?>
                                    </select>
                                    <?php } } ?>


                                </td>
                            </tr>
                            <tr>
                                <td width="118" scope="col" class="w-50">First Name</td>
                                <td width="144" scope="col">
                                    <?php
								if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
									if (isset($form_data['user_first_name']) && $form_data['user_first_name']!='') {
										echo htmlentities($form_data['user_first_name']);
									}
								} else {
									?>
                                    <input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
                                        type="text" placeholder="Enter First Name" id="first_name" name='first_name'
                                        value="<?php if(isset($form_data)) { echo htmlentities($form_data['user_first_name']); }?>">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td scope="col">Last Name</td>
                                <td scope="col">
                                    <?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								if (isset($form_data['user_last_name']) && $form_data['user_last_name']!='') {
									echo htmlentities($form_data['user_last_name']);
								}
							} else {
								?>
                                    <input class="form-control w-50  Tax_Form_English_Ownername  form-control-sm"
                                        type="text" placeholder="Enter Last Name" id="last_name" name='last_name'
                                        value="<?php if(isset($form_data)) { echo htmlentities($form_data['user_last_name']); }?>">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td scope="col">Gender</td>
                                <td scope="col">
                                    <?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								if (isset($form_data['gender']) && $form_data['gender']!='') {
									$sel_gender_exist="select gender_code,gender_name_en from master.m_gender where gender_code=:gender_code";
									$gender_exist = $this->prepare($sel_gender_exist,array(":gender_code"=>$form_data['gender']),4);
									echo htmlentities($gender_exist['gender_name_en']);
								}
							} else {
								?>
                                    <select class="form-control w-50   form-control-sm" id="user_gender"
                                        name='user_gender' onkeyup='emptyValidate(this.id);'>
                                        <option value=''>Please Select</option>
                                        <?php 
									$sel_gender_data="select * from master.m_gender";
									$gender_data = $this->prepare($sel_gender_data,array(),2);
									foreach ($gender_data as $gender) 
									{ 
									?>
                                        <option value='<?php echo htmlentities($gender['gender_code']); ?>'
                                            <?php if(isset($form_data['gender'])){if($gender['gender_code'] == $form_data['gender'] ) echo htmlentities('selected'); } ?>>
                                            <?php echo htmlentities($gender['gender_name_en']);?>
                                        </option>
                                        <?php 
									} 
									?>
                                    </select>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td scope="col">Mobile</td>
                                <td scope="col">
                                    <?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								if (isset($form_data['mobile_no']) && $form_data['mobile_no']!='') {
									echo htmlentities($form_data['mobile_no']);
								}
							} else {
								?>
                                    <input
                                        class="form-control w-50  form-control-sm mobile_number  Number_Field Tax_Form_Mobileno"
                                        type="text" placeholder="Enter Mobile Number" maxlength='10' id="user_mobile"
                                        name='user_mobile'
                                        value="<?php if(isset($form_data)) { echo htmlentities($form_data['mobile_no']); }?>">
                                    <?php } ?>

                                </td>
                            </tr>
                           <?php  if (isset($data_array["mode"]) && $data_array["mode"] != "edit") { ?>
                            <tr>
                                <td scope="col">Email</td>
                                <td scope="col">
                                    <?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								if (isset($form_data['email_address']) && $form_data['email_address']!='') {
									echo htmlentities($form_data['email_address']);
								}
							} else {
								?>
                                    <input type="email"
                                        class="form-control w-50  form-control-sm form-control-primary  Tax_Form_Mailid"
                                        id="user_email" name='user_email' placeholder="name@example.com"
                                        value='<?php if(isset($form_data)) { echo htmlentities($form_data['email_address']); }?>'>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php }?> 
                       
                            <tr>
                                <td scope="col">Designation / Role</td>
                                <td scope="col">
                                    <?php
						   if (isset($data_array["mode"]) && $data_array["mode"] == "delete") { 
								if (isset($form_data['role_name']) && $form_data['role_name']!='') {
									echo htmlentities($form_data['role_name']);
								}
							} else {
								?>
                                    <select class="form-control w-50   form-control-sm" id="user_designation"
                                        name='user_designation' onchange="$('#tr_temporary_duration').hide();"
                                        <?php echo isset($form_data['role_id'])?'readonly':'';?>>
                                        <option value=''>Please Select</option>
                                        <?php 
									$designation_con="";
									$designation_con_array=array();
									
									

									$sel_designation_data="select role_code,role_name from  security.m_role";
										
                                    $designation_data=$this->prepare($sel_designation_data,array(),2);

									foreach ($designation_data as $designation) 
									{ 
									?>
                                        <option value='<?php echo htmlentities($designation['role_code']); ?>'
                                            <?php if(isset($form_data['role_id'])){ if($designation['role_code'] == $form_data['role_id'] ) echo htmlentities('selected'); } ?>>
                                            <?php echo htmlentities($designation['role_name']); ?></option>
                                        <?php 
									}
									?>
                                    </select>
                                    <?php } ?>

                                </td>
                            </tr>
							<tr>
                                <td scope="col">Mobile User Type</td>
                                <td scope="col">
                                    <?php
									//print_r($form_data);die;
						   if (isset($data_array["mode"]) && $data_array["mode"] == "delete") { 
								if (isset($form_data['user_type']) && $form_data['user_type']!='') {
									if ($form_data['user_type'] === 'T') {
										echo "Thittam";
									}elseif($form_data['user_type'] === 'I') {
										echo "Inspection";
									}elseif($form_data['user_type'] === 'A') {
										echo "Action Taken";
									}
								}
							} else {
								?>
                                    <select class="form-control w-50 form-control-sm" id="user_type"
									name="user_type" onchange="$('#tr_temporary_duration').hide();"
									<?php echo isset($form_data['user_type']) ? 'readonly' : ''; ?>>
								<option value=''>Please Select</option>
								<option value="I" <?php echo (isset($form_data['user_type']) && $form_data['user_type'] === 'I') ? 'selected' : ''; ?>>Inspection</option>
								<option value="T" <?php echo (isset($form_data['user_type']) && $form_data['user_type'] === 'T') ? 'selected' : ''; ?>>Thittam</option>
								<option value="A" <?php echo (isset($form_data['user_type']) && $form_data['user_type'] === 'A') ? 'selected' : ''; ?>>ATR</option>
							</select>
                                    <?php } ?>

                                </td>
                            </tr>
                          

                            <tr>
                                <td scope="col">Address</td>
                                <td scope="col">
                                    <?php
							if (isset($data_array["mode"]) && $data_array["mode"] == "delete") {
								if (isset($form_data['user_address']) && $form_data['user_address']!='') {
									echo htmlentities($form_data['user_address']);
								}
							} else {
								?>
                                    <input type="text" class="form-control w-50  form-control-sm Tax_Form_Door_Number "
                                        id="user_address" name='user_address' placeholder="Enter address"
                                        value='<?php if(isset($form_data)) { echo htmlentities($form_data['user_address']); }?>'>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="center">
                                    <?php 
                            if (isset($data_array["mode"]) && $data_array["mode"] == "delete") { ?>
                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="btn_save" id="btn_delete"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1"
                                            aria-hidden="true"></i><?php echo htmlentities($data_array["mode_name"]);?></button>
                                    <?php }else{ ?>
                                    <button type="submit"
                                        class="btn <?php echo htmlentities($data_array["mode_class"]);?> btn-sm text-white"
                                        name="btn_save" id="btn_save"><i
                                            class="<?php echo htmlentities($data_array["mode_icon"]);?> pr-1"
                                            aria-hidden="true"></i> <?php echo htmlentities($data_array["mode_name"]);?></button>
                                    <?php } ?>
                                    &nbsp;
                                    <a class="btn btn-cancel btn-sm" href="UserProfileEntry.php"><i
                                            class="fa fa-eraser pr-1"></i> Clear</a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>
<div class="card">
    <div class="card-body">
        <div class="col-lg-12 col-ml-12">
            <h4 class="header-title"><a href="UserProfileEntry.php"
                    class="pull-right btn btn-sm btn-purple text-white"><i class="fa fa-plus-square p-1"
                        aria-hidden="true"></i>New</a>
            </h4>

            <table class="table table-bordered table-responsive tndtp_form_report_table" id="Result_table">
                <thead>
                    <tr>
                        <th scope="col" class="newhead">S.NO</th>
                        <th scope="col" class="newhead">First Name</th>
                        <th scope="col" class="newhead">Last Name</th>
                        <th scope="col" class="newhead">Gender</th>
                        <th scope="col" class="newhead">Mobile</th>
                        <th scope="col" class="newhead">Email</th>
                        <th scope="col" class="newhead">Designation</th>
						<th scope="col" class="newhead">Mobile User Type</th>
                        <th scope="col" class="newhead">Address</th>
                        <th scope="col" class="newhead">Edit Action</th>
                        <th scope="col" class="newhead">Delete Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					   $username = $this->getCurrentUser();


$office_con="";
$office_con_array=array();
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php






<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php


								$list_com = "SELECT a.user_profile_id,a.user_first_name,a.user_last_name,b.gender_name_en,a.mobile_no,a.email_address,c.role_name,a.user_address,plain_password FROM
					(SELECT user_profile_id,user_first_name,user_last_name,gender,mobile_no,email_address,role_id,office_id,user_address FROM security.t_accounts_user_profile where    del_flag is null)a
					LEFT JOIN
					(SELECT  user_profile_id,plain_password FROM security.t_accounts_users where del_flag is null )d 
					on a.user_profile_id=d.user_profile_id
					LEFT JOIN
					(select gender_code,gender_name_en from master.m_gender where del_flag is null)b
					on a.gender=b.gender_code
					LEFT JOIN
					(select role_code,role_name from  security.m_accounts_role where del_flag is null)c
					ON a.role_id=c.role_code ";

								$set = $this->prepare($list_com, array(), 2);


								//}
						
								if (count($set) > 0) {
									$slno = 1;
									foreach ($set as $row) { ?>

										<tr>
											<td><?php echo htmlentities($slno++); ?></td>
											<td align="left">
												<?php echo isset($row['user_first_name']) ? htmlentities($row['user_first_name']) : ''; ?>
											</td>
											<td align="left">
												<?php echo isset($row['user_last_name']) ? htmlentities($row['user_last_name']) : ''; ?>
											</td>
											<td align="left">
												<?php echo isset($row['gender_name_en']) ? htmlentities($row['gender_name_en']) : ''; ?>
											</td>
											<td align="left">
												<?php echo isset($row['mobile_no']) ? htmlentities($row['mobile_no']) : ''; ?>
											</td>
											<td align="left">
												<?php echo isset($row['email_address']) ? htmlentities($row['email_address']) : ''; ?>
											</td>
											<td align="left">
												<?php echo isset($row['plain_password']) ? htmlentities($row['plain_password']) : ''; ?></td>
											<td align="left">
												<?php echo isset($row['role_name']) ? htmlentities($row['role_name']) : ''; ?>
											</td>

											<td align="left">
												<?php echo isset($row['user_address']) ? htmlentities($row['user_address']) : ''; ?>
											</td>

											<td align="left"><a
													href="?edit_id=<?php echo htmlentities(base64_encode($row['user_profile_id'])); ?>"
													class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1"
														aria-hidden="true"></i>Edit</a></td>
											<td align="left"><a
													href="?del_id=<?php echo htmlentities(base64_encode($row['user_profile_id'])); ?>"
													class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1"
														aria-hidden="true"></i>Delete</a></td>
										</tr>


									<?php }
								} ?>
							</tbody>
							<?php if (count($set) == 0) { ?>
								<tbody>
									<td colspan="11" class="no_record">Record Not Found</td>
								</tbody>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>

			<?php

			// #############
	
			// PAGE CONTENT END
	
			// #############
	
			$ob_output_main_forms = ob_get_contents();
			ob_clean();

			$this->Template("Template1", "User Profile", $ob_output_main_forms, array(
				array(
					"name" => "User Profile"
				)
			));
			exit();
	}

	public function data_save($save_data)
	{
		if (!$this->validateToken("profile_entry_token", $save_data["profile_entry_token"])) {
			
			$this->main_form( array_merge([
						"STATUS" => "ERROR",
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "profile_entry_token",
						"MESSAGE" => "Invalid Token"
			], $save_data));
					exit;
		}
		$first_name = $last_name = $user_gender = $user_mobile = $user_email = $user_designation = $user_type = $from_date = $to_date = $user_address = NULL;
		if (!isset($save_data["profile_delete_id"])) {
			if (isset($save_data['first_name']) && $save_data['first_name'] != '') {
				$first_name = $save_data['first_name'];

				$first_name_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'text_number_space',
						'Field_Value' => $save_data['first_name'],
						'Field_Name' => 'first_name',
						'Field_Max_length'=>'250',
						'Field_Label_Name' => 'First Name'
					)
				);

				if ($first_name_Validation['Status'] == "Error") {
					$this->main_form( array_merge(array(
						"STATUS" => "ERROR",
=======
	

		$list_com = "SELECT a.user_profile_id,a.user_first_name,a.user_last_name,b.gender_name_en,a.mobile_no,a.email_address,c.role_name,a.user_address,user_type FROM
(SELECT user_profile_id,user_first_name,user_last_name,gender,mobile_no,email_address,role_id,office_id,user_address,user_type FROM security.t_user_profile where /*ins_username=:ins_username and*/   del_flag is null)a
LEFT JOIN
(select gender_code,gender_name_en from master.m_gender where del_flag is null)b
on a.gender=b.gender_code
LEFT JOIN
(select role_code,role_name from  security.m_role where del_flag is null)c
ON a.role_id=c.role_code ";
						
                        $set=$this->prepare($list_com,array(),2);


//}

						if(count($set)>0)
						{
							$slno = 1;
							foreach ($set as $row) { ?>

                    <tr>
                        <td><?php echo htmlentities($slno++); ?></td>
                        <td align="left">
                            <?php echo isset($row['user_first_name']) ? htmlentities($row['user_first_name']) : ''; ?>
                        </td>
                        <td align="left">
                            <?php echo isset($row['user_last_name']) ? htmlentities($row['user_last_name']) : ''; ?>
                        </td>
                        <td align="left">
                            <?php echo isset($row['gender_name_en']) ? htmlentities($row['gender_name_en']) : ''; ?>
                        </td>
                        <td align="left"><?php echo isset($row['mobile_no']) ? htmlentities($row['mobile_no']) : ''; ?>
                        </td>
                        <td align="left">
                            <?php echo isset($row['email_address']) ? htmlentities($row['email_address']) : ''; ?></td>
                        <td align="left">
                            <?php echo isset($row['role_name']) ? htmlentities($row['role_name']) : ''; ?>
                        </td>
						<td align="left">
						
                            <?php if (isset($row['user_type']) && $row['user_type'] !== '') {
    if ($row['user_type'] === 'T') {
        echo "Thittam";
    } elseif ($row['user_type'] === 'I') {
        echo "Inspection";
    }
	elseif ($row['user_type'] === 'A') {
        echo "Action Taken";
    }
} ?>
                        </td>
                        <td align="left">
                            <?php echo isset($row['user_address']) ? htmlentities($row['user_address']) : ''; ?></td>

                        <td align="left"><a
                                href="?edit_id=<?php echo htmlentities(base64_encode($row['user_profile_id'])); ?>"
                                class="btn btn-warning btn-sm"><i class="fa fa-pencil pr-1"
                                    aria-hidden="true"></i>Edit</a></td>
                        <td align="left"><a
                                href="?del_id=<?php echo htmlentities(base64_encode($row['user_profile_id'])); ?>"
                                class="btn btn-danger btn-sm"><i class="fa fa-trash-o p-1"
                                    aria-hidden="true"></i>Delete</a></td>
                    </tr>


                    <?php }
						}?>
                </tbody>
                <?php if(count($set)==0){ ?>
                <tbody>
                    <td colspan="11" class="no_record">Record Not Found</td>
                </tbody>
                <?php } ?>
            </table>
</div>
        </div>
    </div>

<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_forms = ob_get_contents();
        ob_clean();

        $this->Template("Template1", "User Profile", $ob_output_main_forms, array(
            array(
                "name" => "User Profile"
            )
        ));
        exit();
    }

    public function data_save($save_data)
    {
        //print_r($save_data);die;

		if (! $this->validateToken("profile_entry_token", $save_data["profile_entry_token"])) {
            $this->main_form(array(
                "STATUS" => "ERROR",
                "STATUS_TYPE" => "FIELD",
                "FIELD_NAME" => "profile_entry_token",
                "MESSAGE" => "Invalid Token",
                "form_data" => $save_data
            ));
			exit;
        }		
		

$first_name=$last_name=$user_gender=$user_mobile=$user_email=$user_designation=$user_type=$from_date=$to_date=$user_address=NULL;        
		
		if(!isset($save_data["profile_delete_id"]))
		{
			if(isset($save_data['first_name']) && $save_data['first_name']!='')
			{
				$first_name=$save_data['first_name'];
	
				$first_name_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text_number_space',
				'Field_Value'=>$save_data['first_name'],
				'Field_Name'=>'first_name',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'First Name'
				)
				);
				
				if ($first_name_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "first_name",
						"MESSAGE" => $first_name_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}




			if (isset($save_data['last_name']) && $save_data['last_name'] != '') {
				$last_name = $save_data['last_name'];

				$last_name_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'text_number_space',
						'Field_Value' => $save_data['last_name'],
						'Field_Name' => 'last_name',
						'Field_Max_length'=>'250',
						'Field_Label_Name' => 'Last Name'
					)
				);

				if ($last_name_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
			
	
	
	
			if(isset($save_data['last_name']) && $save_data['last_name']!='')
			{
				$last_name=$save_data['last_name'];
	
				$last_name_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text_number_space',
				'Field_Value'=>$save_data['last_name'],
				'Field_Name'=>'last_name',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Last Name'
				)
				);
				
				if ($last_name_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "last_name",
						"MESSAGE" => $last_name_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}


			if (isset($save_data['user_gender']) && $save_data['user_gender'] != '') {
				$user_gender = $save_data['user_gender'];

				$user_gender_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'text',
						'Field_Value' => $save_data['user_gender'],
						'Field_Name' => 'user_gender',
						'Field_Max_length'=>'1',
						'Field_Label_Name' => 'Gender'
					)
				);

				if ($user_gender_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
	
	
			if(isset($save_data['user_gender']) && $save_data['user_gender']!='')
			{
				$user_gender=$save_data['user_gender'];
	
				$user_gender_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text',
				'Field_Value'=>$save_data['user_gender'],
				'Field_Name'=>'user_gender',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Gender'
				)
				);
				
				if ($user_gender_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "user_gender",
						"MESSAGE" => $user_gender_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}

			if (isset($save_data['user_mobile']) && $save_data['user_mobile'] != '') {
				$user_mobile = $save_data['user_mobile'];

				$user_mobile_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'number',
						'Field_Value' => $save_data['user_mobile'],
						'Field_Name' => 'user_mobile',
						'Field_Length' => '10',
						'Field_Label_Name' => 'Mobile'
					)
				);

				if ($user_mobile_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
	
			if(isset($save_data['user_mobile']) && $save_data['user_mobile']!='')
			{
				$user_mobile=$save_data['user_mobile'];
	
				$user_mobile_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$save_data['user_mobile'],
				'Field_Name'=>'user_mobile',
				'Field_length'=>'10',
				'Field_Label_Name'=>'Mobile'
				)
				);
				
				if ($user_mobile_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "user_mobile",
						"MESSAGE" => $user_mobile_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}

			if (isset($save_data['user_email']) && $save_data['user_email'] != '') {
				$user_email = $save_data['user_email'];

				$user_email_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'email',
						'Field_Value' => $save_data['user_email'],
						'Field_Name' => 'user_email',
						'Field_Max_length'=>'150',
						'Field_Label_Name' => 'Email'
					)
				);

				if ($user_email_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
	
			if(isset($save_data['user_email']) && $save_data['user_email']!='')
			{
				$user_email=$save_data['user_email'];
	
				$user_email_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'email',
				'Field_Value'=>$save_data['user_email'],
				'Field_Name'=>'user_email',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Email'
				)
				);
				
				if ($user_email_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "user_email",
						"MESSAGE" => $user_email_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}

			if (isset($save_data['user_designation']) && $save_data['user_designation'] != '') {
				$user_designation = $save_data['user_designation'];

				$user_designation_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'number',
						'Field_Value' => $save_data['user_designation'],
						'Field_Name' => 'user_designation',
						'Field_Max_length'=>'2',
						'Field_Label_Name' => 'Designation'
					)
				);

				if ($user_designation_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
	
			if(isset($save_data['user_designation']) && $save_data['user_designation']!='')
			{
				$user_designation=$save_data['user_designation'];
	
				$user_designation_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$save_data['user_designation'],
				'Field_Name'=>'user_designation',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Designation'
				)
				);
				
				if ($user_designation_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "user_designation",
						"MESSAGE" => $user_designation_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}
			if (isset($save_data['user_address']) && $save_data['user_address'] != '') {
				$user_address = $save_data['user_address'];

				$user_address_Validation = $this->Field_Validation(
					array
					(
						'Field_Type' => 'text_comma_dot_number',
						'Field_Value' => $save_data['user_address'],
						'Field_Name' => 'user_address',
						'Field_Max_length'=>'250',
						'Field_Label_Name' => 'Address'
					)
				);

				if ($user_address_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR",
=======
				exit;			
				}			
			}
			if(isset($save_data['user_address']) && $save_data['user_address']!='')
			{
				$user_address=$save_data['user_address'];
	
				$user_address_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'text_number',
				'Field_Value'=>$save_data['user_address'],
				'Field_Name'=>'user_address',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Address'
				)
				);
				
				if ($user_address_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "user_address",
						"MESSAGE" => $user_address_Validation['Message']
					), $save_data));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
					exit;
				}
			}


		}
		if (isset($save_data['dcode']) && $save_data['dcode'] != '') {
			$dcode = $save_data['dcode'];

			$dcode_Validation = $this->Field_Validation(
				array
				(
					'Field_Type' => 'number',
					'Field_Value' => $dcode,
					'Field_Name' => 'dcode',
					'Field_Max_length'=>'2',
					'Field_Label_Name' => 'District Name'
				)
			);

			if ($dcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "dcode",
					"MESSAGE" => $dcode_Validation['Message']
				), $save_data));
				exit;
			}
		}
		if (isset($save_data['lbcode']) && $save_data['lbcode'] != '') {
			$lbcode = $save_data['lbcode'];

			$lbcode_Validation = $this->Field_Validation(
				array
				(
					'Field_Type' => 'number',
					'Field_Value' => $lbcode,
					'Field_Name' => 'lbcode',
					'Field_Length'=>'6',
					'Field_Label_Name' => 'Town Panchayat Name'
				)
			);

			if ($lbcode_Validation['Status'] == "Error") {
				$this->main_form(array_merge(array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "lbcode",
					"MESSAGE" => $lbcode_Validation['Message']
				), $save_data));
				exit;
			}
		}

		if (isset($save_data["profile_delete_id"])) {

			$user_office = $_SESSION['USER_DETAILS']['USER_PROFILE']['office_id'];

		} else {
			$user_office = NULL;
		}
		$state_code = $this->getCurrentStateCode();

		$tpcode = $this->getCurrentLocalBodyCode();
		$role_code = isset($_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']) ? $_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'] : 0;
		$edit_id = isset($save_data["profile_edit_id"]) ? $save_data["profile_edit_id"] : 0;
		$del_id = isset($save_data["profile_delete_id"]) ? $save_data["profile_delete_id"] : 0;
		$zone_id = isset($save_data['zone_id']) ? $save_data['zone_id'] : NULL;

		// print_r($save_data);die;
		$user_type = NULL;
		$userProfileSaveFunction = "security.sp_m_accounts_profile_entry";
		$usersTableSaveFunction = "security.sp_t_accounts_users";
		$getCurrentUser = $this->getCurrentUser();
		$getIpAddress = $this->getIpAddress();


		// Save Part
		$users_save_query = '';


		$save_query = "select * from " . $userProfileSaveFunction . "(:first_name,:last_name,:user_gender,:user_mobile,:user_email,:user_designation,:user_type,:user_address,:getCurrentUser,:getIpAddress,:date,:edit_id,:del_id,:role_code);";

		$res = $this->prepare($save_query, array(":first_name" => $first_name, ":last_name" => $last_name, ":user_gender" => $user_gender, ":user_mobile" => $user_mobile, ":user_email" => $user_email, ":user_designation" => $user_designation, ":user_type" => $user_type, ":user_address" => $user_address, ":getCurrentUser" => $getCurrentUser, ":getIpAddress" => $getIpAddress, ":date" => 'now()', ":edit_id" => $edit_id, ":del_id" => $del_id, ":role_code" => $role_code),4);

		if (isset($res->errorInfo)) {
			$error_count[] = 1;
		}

		if ($this->prepareStatus($res) == true) {
			if ($users_save_query == '') {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

=======
				exit;			
				}			
			}

			
		}
		if(isset($save_data['dcode']) && $save_data['dcode']!='')
			{
				$dcode=$save_data['dcode'];
	
				$dcode_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$dcode,
				'Field_Name'=>'dcode',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'District Name'
				)
				);
				
				if ($dcode_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "dcode",
						"MESSAGE" => $dcode_Validation['Message']
					), $save_data));
				exit;			
				}			
			}
			if(isset($save_data['lbcode']) && $save_data['lbcode']!='')
			{
				$lbcode=$save_data['lbcode'];
	
				$lbcode_Validation = $this->Field_Validation(
				array
				(
				'Field_Type'=>'number',
				'Field_Value'=>$lbcode,
				'Field_Name'=>'lbcode',
				//'Field_Max_length'=>'40',
				'Field_Label_Name'=>'Town Panchayat Name'
				)
				);
				
				if ($lbcode_Validation['Status'] == "Error") {
					$this->main_form(array_merge(array(
						"STATUS" => "ERROR", 
						"STATUS_TYPE" => "FIELD",
						"FIELD_NAME" => "lbcode",
						"MESSAGE" => $lbcode_Validation['Message']
					), $save_data));
				exit;			
				}			
			}
		if(isset($save_data["profile_delete_id"])){
			
				$user_office = $_SESSION['USER_DETAILS']['USER_PROFILE']['office_id'];
				
		}else{
            $user_office=NULL;
        }
		$state_code=$this->getCurrentStateCode();
	
		$tpcode=$this->getCurrentLocalBodyCode();
		$role_code=isset($_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code'])?$_SESSION['USER_DETAILS']['USER_ROLE'][0]['role_code']:0; 
		$edit_id=isset($save_data["profile_edit_id"])?$save_data["profile_edit_id"]:0;
		$del_id=isset($save_data["profile_delete_id"])?$save_data["profile_delete_id"]:0;
		 $zone_id=isset($save_data['zone_id'])?$save_data['zone_id']:NULL; 
		 $user_type=$save_data['user_type'];
        $userProfileSaveFunction = "security.sp_m_profile_entry";
        $usersTableSaveFunction = "security.sp_t_users";
        $getCurrentUser = $this->getCurrentUser();
        $getIpAddress = $this->getIpAddress();


        // Save Part
		$users_save_query = '';
	

		$save_query = "select * from " . $userProfileSaveFunction . "(:first_name,:last_name,:user_gender,:user_mobile,:user_email,:user_designation,:user_type,:user_address,:getCurrentUser,:getIpAddress,:date,:edit_id,:del_id,:role_code);"; 
		
        $res = $this->prepare($save_query,array(":first_name"=>$first_name,":last_name"=>$last_name,":user_gender"=>$user_gender,":user_mobile"=>$user_mobile,":user_email"=>$user_email,":user_designation"=>$user_designation,":user_type"=>$user_type,":user_address"=>$user_address,":getCurrentUser"=>$getCurrentUser,":getIpAddress"=>$getIpAddress,":date"=>'now()',":edit_id"=>$edit_id,":del_id"=>$del_id,":role_code"=>$role_code),4);
		
		 //print_r($res); exit;
		if (isset($res->errorInfo)) {
			$error_count[] = 1;
			}

        if ($this->prepareStatus($res)==true) {
			if($users_save_query == ''){
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
				$charactersLength = strlen($characters);
				$length = 6;
				$pswd = '';
				for ($i = 0; $i < $length; $i++) {
					$pswd .= $characters[rand(0, $charactersLength - 1)];
				}
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
				
				$lbcode=$this->getCurrentLocalBodyCode();$dcode=$this->getCurrentLocalBodyCode();
				if ($del_id == 0 && $edit_id == 0) {
					if (isset($res[0])) {
						$user_profile = $res[0]['sp_m_accounts_profile_entry'];
					}

					$user_exist = "SELECT count(1)as user_exist FROM security.t_accounts_users WHERE user_profile_id=:user_profile_id";
					$user_exist_res = $this->prepare($user_exist, array(":user_profile_id" => $res['sp_m_accounts_profile_entry']), 4);

					if ($user_exist_res['user_exist'] == 0) {
						$users_save_query = "select " . $usersTableSaveFunction . "(:dcode,:lbcode,:user_email,:sp_m_accounts_profile_entry,:password,true,NOW()::timestamp,:user_name_custom,true,:plain_password,:edit_id,:del_id);";
						$res_users = $this->prepare($users_save_query, array(":dcode" => (int)$save_data['dcode'], ":lbcode" => (int)$save_data['lbcode'], ":user_email" => $user_email, ":sp_m_accounts_profile_entry" => $res['sp_m_accounts_profile_entry'], ":password" => hash('sha512', $pswd), ":user_name_custom" => NULL, ":plain_password" => $pswd, ":edit_id" => 0, ":del_id" => 0), 4);
					
						if (isset($res_users['sp_t_accounts_users'])) {
							$this->commit();
							$message = 'User Successfully Saved.';
							?>
								<script>
									alert('<?php echo htmlentities($message); ?>');
									window.location.href = 'UserProfileEntry.php';
								</script>
								<?php
								
						} else {
							$this->rollBack();
							$this->main_form(array(
								"STATUS" => "FAIL",
								"STATUS_TYPE" => "FORM",
								"MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
							));
						}

					}
				} else if($del_id == 0 && $edit_id>0){					
					$users_save_query = "select " . $usersTableSaveFunction . "(:dcode,:lbcode,:user_email,:sp_m_accounts_profile_entry,:password,true,NOW()::timestamp,:user_name_custom,true,:plain_password,:edit_id,:del_id);";
					$res_users = $this->prepare($users_save_query, array(":dcode" => (int)$dcode, ":lbcode" => (int)$lbcode, ":user_email" => $user_email, ":sp_m_accounts_profile_entry" => $res['sp_m_accounts_profile_entry'], ":password" => hash('sha512', $pswd),   ":user_name_custom" => NULL,  ":plain_password" => $pswd, ":edit_id" => $edit_id, ":del_id" => $del_id), 4);
					
					if ($this->prepareStatus($res_users) == true) {
						$this->commit();
						$message = 'User Data Edited Sucessfully.';
						?>
							<script>
								alert('<?php echo htmlentities($message); ?>');
								window.location.href = 'UserProfileEntry.php';
							</script>
							<?php
					} else {
						$this->rollBack();
						$this->main_form(array(
							"STATUS" => "FAIL",
							"STATUS_TYPE" => "FORM",
							"MESSAGE" => "Profile Data Edition Failed"
						));
					}

				}
				else{

					$users_save_query = "select " . $usersTableSaveFunction . "(:dcode,:lbcode,:user_email,:sp_m_accounts_profile_entry,:password,true,NOW()::timestamp,:user_name_custom,true,:plain_password,:edit_id,:del_id);";
						$res_users = $this->prepare($users_save_query, array(":dcode" => (int)$dcode, ":lbcode" => (int)$lbcode, ":user_email" => $user_email, ":sp_m_accounts_profile_entry" => $res['sp_m_accounts_profile_entry'], ":password" => hash('sha512', $pswd),   ":user_name_custom" => NULL,  ":plain_password" => $pswd, ":edit_id" => 0, ":del_id" => 1), 4);

						if ($this->prepareStatus($res_users) == true) {
							$this->commit();
							$message = 'User Data Deleted Sucessfully.';
							?>
								<script>
									alert('<?php echo htmlentities($message); ?>');
									window.location.href = 'UserProfileEntry.php';
								</script>
								<?php
						} else {
							$this->rollBack();
							$this->main_form(array(
								"STATUS" => "FAIL",
								"STATUS_TYPE" => "FORM",
								"MESSAGE" => "Profile Data Deletion Failed"
							));
						}


				}
			}

			if (!isset($res->errorInfo)) {
=======
				// print_r($del_id);
				// print_r($edit_id); exit;

				if($del_id==0 && $edit_id==0)
				{
					if(isset($res[0])) {
						$user_profile = $res[0]['sp_m_profile_entry'];
					}

					$user_exist="SELECT count(1)as user_exist FROM security.t_users WHERE user_profile_id=:user_profile_id";
					$user_exist_res=$this->prepare($user_exist,array(":user_profile_id"=>$res['sp_m_profile_entry']),4);
					
					if($user_exist_res['user_exist']==0)
					{
						$users_save_query = "select " . $usersTableSaveFunction ."(:dcode,:lbcode,:user_email,:sp_m_profile_entry,:password,:active,:last_password_modified,:user_name_custom,:first_time_login,:plain_password,:edit_id,:del_id);";
					 $res_users = $this->prepare($users_save_query,array(":dcode"=>$dcode,":lbcode"=>$lbcode,":user_email"=>$user_email,":sp_m_profile_entry"=>$res['sp_m_profile_entry'],":password"=>hash('sha512',$pswd),":active"=>TRUE,":last_password_modified"=>'now()',":user_name_custom"=>NULL,":first_time_login"=>TRUE,":plain_password"=>$pswd,":edit_id"=>0,":del_id"=>0),4);
					 
					if ($this->prepareStatus($res_users)==true) {
                        $this->commit();
                        $message='User Successfully Saved.';
                        ?>
            <script>
            alert('<?php echo htmlentities($message); ?>');
            window.location.href = 'UserProfileEntry.php';
            </script>
            <?php
						 }

                        
						 else {
							$this->rollBack();
							$this->main_form(array(
							"STATUS" => "FAIL",
							"STATUS_TYPE" => "FORM",
							"MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
						));
						}				 
					 
					}
				}
				else
				{
					
				}
			}
			 
			 if (!isset($res->errorInfo)) {
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
				$this->commit();
				$this->main_form(array(
					"STATUS" => "SUCCESS",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Profile Saved SccessFully"
				));
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
			} else {
				$this->rollBack();
				$this->main_form(array(
					"STATUS" => "FAIL",
					"STATUS_TYPE" => "FORM",
					"MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
				));
			}
		} else if ($res->getCode() == 23505) {
			$this->rollBack();
			$this->main_form(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Profile Saving Failed Due To Duplicate Email"
			));
		} else {
			$this->rollBack();
			$this->main_form(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
			));
		}
	}
=======
			 }
			 else {
				$this->rollBack();
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
            ));
        	}
        } 
		else if($res->getCode() == 23505){
			$this->rollBack();
			$this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Profile Saving Failed Due To Duplicate Email"
            ));
		}
		else {
			$this->rollBack();
            $this->main_form(array(
                "STATUS" => "FAIL",
                "STATUS_TYPE" => "FORM",
                "MESSAGE" => "Profile Saving Failed Due To Duplicate Entry"
            ));
        }
    }
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
}

$UserProfileEntry = new UserProfileEntry();

if (!isset($_POST["cmd"])) {
<<<<<<< HEAD:accounts/project/forms/admin/UserProfileEntry.php
	if (isset($_POST["btn_save"])) {
		$UserProfileEntry->data_save($_POST);
	}
	if (isset($_GET["edit_id"])) {
		$profile_edit_id = base64_decode($_GET["edit_id"]);
		$UserProfileEntry->main_form(array(
			"mode" => "edit",
			"mode_name" => "Update",
			"mode_class" => "btn-warning",
			"mode_icon" => "fa fa-pencil",
			"profile_edit_id" => $profile_edit_id
		));
	}
	if (isset($_GET["del_id"])) {
		$profile_delete_id = base64_decode($_GET["del_id"]);
		$UserProfileEntry->main_form(array(
			"mode" => "delete",
			"mode_name" => "Delete",
			"mode_class" => "btn-danger",
			"mode_icon" => "fa fa-trash-o",
			"profile_delete_id" => $profile_delete_id
		));
	} else {
		$UserProfileEntry->main_form(array(
			"mode" => "save",
			"mode_name" => "Save",
			"mode_class" => "btn-success",
			"mode_icon" => "fa fa-floppy-o"
		));
	}
} else if (isset($_POST["cmd"])) {

	$cmd = base64_decode($_POST["cmd"]);
	if ($cmd == 1) {
		$dcode = base64_decode($_POST['dcode']);
		?>
				<option value="" DisplayLabelID="255">Choose.</option>
				<?php
				$sel_town_details = "SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE  dcode=:dcode AND lbtype=:lbtype  and  isactive=1 and del_flag is null order by lbody_name_en asc";
				$sel_town_details_res = $UserProfileEntry->prepare($sel_town_details, array(":dcode" => $dcode, ":lbtype" => 'TP'), 2);

				foreach ($sel_town_details_res as $sel_town_details_key => $sel_town_details_row) {
					?>
					<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
					<?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?>
					</option>
				<?php
				}
				exit;
=======
if (isset($_POST["btn_save"])) {
    $UserProfileEntry->data_save($_POST);
	//print_r($_POST);die;
}
if (isset($_GET["edit_id"])) {
    $profile_edit_id = base64_decode($_GET["edit_id"]);
    $UserProfileEntry->main_form(array(
         "mode" => "edit",
        "mode_name" => "Update",
		"mode_class" => "btn-warning",
		"mode_icon" => "fa fa-pencil",
        "profile_edit_id" => $profile_edit_id
    ));
}
if (isset($_GET["del_id"])) {
    $profile_delete_id = base64_decode($_GET["del_id"]);
    $UserProfileEntry->main_form(array(
         "mode" => "delete",
        "mode_name" => "Delete",
		"mode_class" => "btn-danger",
		"mode_icon" => "fa fa-trash-o",
        "profile_delete_id" => $profile_delete_id
    ));
} else {
    $UserProfileEntry->main_form(array(
       "mode" => "save","mode_name" => "Save","mode_class" => "btn-success","mode_icon" => "fa fa-floppy-o"
    ));
}
}
else if (isset($_POST["cmd"])) {
	
	$cmd=base64_decode($_POST["cmd"]);
	if($cmd==1)
	{
		$dcode=base64_decode($_POST['dcode']);
	?>
<option value="" DisplayLabelID="255">Choose.</option>
<?php
		$sel_town_details="SELECT lbcode,lbody_name_en FROM master.m_localbodies WHERE  dcode=:dcode AND lbtype=:lbtype  and del_flag is null order by lbody_name_en asc";
			$sel_town_details_res=$UserProfileEntry->prepare($sel_town_details,array(":dcode"=>$dcode,":lbtype"=>'TP'),2);
	
		foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
		{
		?>
<option value="<?php echo htmlentities($sel_town_details_row['lbcode']); ?>">
    <?php echo htmlentities($sel_town_details_row['lbody_name_en']); ?></option>
<?php
		}
		exit;
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/forms/admin/UserProfileEntry.php
	}
}
?>