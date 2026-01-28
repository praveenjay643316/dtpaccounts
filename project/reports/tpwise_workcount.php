<?php
require_once  '../config/config.php';

//require_once __DIR__ . '/../library/mpdf/vendor/autoload.php';

class CategoryWiseDemandListDist extends ConfigClass
{
	
    public function __construct()
    {
		
        if (! isset($this->db)) {
            
			//$this->mpdf = new \Mpdf\Mpdf(["mode" => 'ta']);
        }
    }

    public function main_content($data_array = array())
    {
        $site_data = $this->siteData();

        ob_start();
?>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="145" />
<?php
        // #############

        // PAGE CONTENT START

        // #############
		
	
		
        ?>

		<div class="container">
	    	<div class="card">
                <div class="card-body">
                	 <?php if(!isset($data_array['pdf']) && !isset($data_array['xls'])){ ?>          
                    	<div class="row text-center mt-1 mb-1">
                        	<div class="col-md-12 text-center">
                            <!-- <a href="tpwise_workcount.php?pdf=<?php echo base64_encode(1)?>" target="_blank" class="font-weight-bold">Download PDF<img src="../../../images/pdf.png"></a>  -->
                            <a href="tpwise_workcount.php?xls=<?php echo base64_encode(1)?>" target="_blank" class="font-weight-bold">Download xls<img width="25px" height="25px" src="../../images/excel.png"></a>
                        	</div>
                    	</div>
                    <?php } ?>
					<?php 
                    if(isset($data_array['xls'])){
                        
                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
                    header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");                                     // HTTP/1.0
                    header("Content-type: application/force-download");
                    header("Content-type: application/octet-stream");
                    header("Content-type: application/msexcel");
                    header('Content-Disposition: attachment; filename="report.xls";');  	
                    }
                    ?>  
                    <style>
                    .tndtp_form_table {
                        font-size: 15px;
                        font-weight: bold;
                        width: 100%;
                    }
                    
                    .tndtp_form_table thead {
                        padding: 3px
                    }
                    
                    .tndtp_form_report_table {
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
                    
                    .newhead {
                        background: linear-gradient(to right, #494889, #3B3A7C, #494889);
                        color: white;
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
                    
                    .card {
                        padding: 20px;
                        margin: 20px;
                        border-radius: 7px;
                        box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
                        background: #fff;
                    }
                    
                    </style>

                  	<div id="result_data">
                  	 	<table class="table-bordered tndtp_form_table" <?php if(isset($data_array['pdf']) || isset($data_array['xls'])){ ?>  style="border-collapse:collapse; font-size: 12px;" border="1" width="100%" <?php } ?>>
                        	<thead  class="newhead">
                      			<tr>
                                	<td colspan="4" align="center">Town Panchayats Wise - Works Count
                                    	<button type="button" class="schemebuton float-end" onClick="location.href = '<?php echo htmlentities($site_data->website_url); ?>project/home.php?id=<?php echo htmlentities(base64_encode(4));?>';"><i class="fa fa-arrow-circle-left"></i> Back To Menu</button>
                                    </td>
                                </tr> 
                                <tr>
                                    <td align="center" scope="col">Sl.no</td>
                                    <td align="center" scope="col">Districts</td>
                                    <td align="center" scope="col">Town Panchayats</td>
                                    <td align="center" scope="col">Work Count</td>
                                </tr>
                            </thead>
                            <tbody>
								<?php
                                $sel_town_details="select c.dcode,c.district_name_en,b.lbcode,b.lbody_name_en,work_count from
                                (SELECT dcode,lbcode,count(1) as work_count FROM works.t_works where del_flag is null group by dcode,lbcode )a
                                left join
                                (SELECT dcode,lbcode,lbody_name_en FROM master.m_localbodies where del_flag is null)b
                                on a.lbcode=b.lbcode and a.dcode=b.dcode
                                left join
                                (SELECT dcode,district_name_en from master.m_district)c
                                on a.dcode=c.dcode" ;
                                
                                $sel_town_details_res = $this->prepare($sel_town_details, array(),2);	
                                if(count($sel_town_details_res)>0)
                                {
                                    foreach($sel_town_details_res as $sel_town_details_key=>$sel_town_details_row)
                                    {
                                    ?>
                                        <tr>
                                            <td align="center"><?php echo htmlentities($sel_town_details_key+1); ?></td>
                                            <td align="left"><?php echo htmlentities($sel_town_details_row['district_name_en']!=''?$sel_town_details_row['district_name_en']:''); ?></td>
                                            <td align="left"><?php echo htmlentities($sel_town_details_row['lbody_name_en']!=''?$sel_town_details_row['lbody_name_en']:''); ?></td>
                                            <td align="left"><?php echo htmlentities($sel_town_details_row['work_count']!=''?$sel_town_details_row['work_count']:''); ?></td>
                                        </tr>
                                    <?php
                                    
                                    }
                                }
                                else
                                {
                                ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-danger font-weight-bold h5">Record Not Found</td>
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
		<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        if(!isset($data_array['pdf']) && !isset($data_array['xls']))
		{
		$this->Template($this->getCurrentUserTemplate()!=""?$this->getCurrentUserTemplate():"Template1", "Townpanchayat Wise Property Assessment Details", $ob_output_main_contents,array(),array('page_id'=>12));
        exit();
		}
		else if(isset($data_array['pdf']))
		{
			//$this->mpdf->WriteHTML($ob_output_main_contents);
			//$this->mpdf->Output();

		}
		else if(isset($data_array['xls']))
		{		
				echo $ob_output_main_contents;
		}
        
    }
}

$Home = new CategoryWiseDemandListDist();
/*$Home->main_content();*/
$Home->main_content(array_merge($_POST,$_GET));
?>            