<?php

trait HtmlHelper
{

    public function ShowMessage($MessageType = "danger", $Message = "")
    {
        $messageTypeArray = array(
            "ERROR" => "danger",
            "FAIL" => "danger",
            "SUCCESS" => "success"
        );

        return '<div class="row">
        <div class="col-lg-12 col-ml-12">
            <div class="alert alert-' . $messageTypeArray[$MessageType] . '" role="alert">
                 ' . $Message . '
            </div>
        </div>
    </div>  ';
    }
	
	
	public function InvalidRequest($MessageType = "danger", $Message = "")
    {
        $messageTypeArray = array(
            "ERROR" => "danger",
            "FAIL" => "danger",
            "SUCCESS" => "success"
        );

        return '<div class="row">
        <div class="col-lg-12 col-ml-12">
            <div class="card">
  <div class="card-body">
    <h5 class="card-title">Invalid Request</h5>'.
    ($Message==''?'':'<p class="card-text ">'.$Message.'</p>').'
  
  </div>
</div>
        </div>
    </div>  ';
	
    }
	

	public function CreateSelectBox($Fields=array())
	{
		
		
		$result="";
		if($Fields['data']['option_only']=='N')
		{
			$result.="<select ";
				 foreach($Fields['data']['field_attr'] as $Field_attr_key=>$Field_attr_row){ 
				$result.="$Field_attr_key=\"$Field_attr_row\"";
				} 
			$result.=">";
		}
            $result.="<option ";
			 foreach($Fields['data']['field_option']['default_value']['field_attr'] as $Field_attr_key=>$Field_attr_row){ 
				$result.="$Field_attr_key=\"$Field_attr_row\"";
				} 
			 $result.=" value=\"".$Fields['data']['field_option']['default_value']['value']."\">".$Fields['data']['field_option']['default_value']['text']."</option>";	
			
			if(count($Fields['option_data'])>0)
            foreach($Fields['option_data'] as $Field_data_key=>$Field_data_row){
				if(isset($Fields['data']['selected_value']) && $Fields['data']['selected_value']==$Field_data_row[$Fields['data']['field_option']['option'][0]])
				$result.="<option selected=\"selected\" value=\"".$Field_data_row[$Fields['data']['field_option']['option'][0]]."\">".$Field_data_row[$Fields['data']['field_option']['option'][1]]."</option>";
				else
            	$result.="<option value=\"".$Field_data_row[$Fields['data']['field_option']['option'][0]]."\">".$Field_data_row[$Fields['data']['field_option']['option'][1]]."</option>";
            }
		if($Fields['data']['option_only']=='N')
		{	
        	$result.="</select>";
		}
		return $result;
	}







}

