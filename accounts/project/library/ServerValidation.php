<?php

trait ServerValidation
{
  
   
    public $Error_Code_Status = array(
        '1' => 'Function Not Exists',
        '2' => 'Empty',
        '3' => 'Invalid Data Type',
        '4' => 'Condition Not Satisfied',
        '5' => 'Success',
        '6' => 'Undefind Array Value'
    );

    public function Field_Value_Special_Char_exist($Field_Value = '')
    {
        $Special_Char = array(
            '~',
            '!',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '_',
            '-',
            '+',
            '=',
            '{',
            '}',
            '[',
            ']',
            '|',
            '\\',
            '/',
            '>',
            '<',
            '\'',
            '"',
            ':',
            ';',
            ',',
            '.'
        );

        foreach ($Special_Char as $Special_Char_row) {
            if (stripos($Field_Value, $Special_Char_row)) {
                return false;
            }
        }
    }

    public function Field_Value_Length_Check($Field_Value = '', $Field_length = '')
    {
        if (strlen($Field_Value) != $Field_length) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                'Message' => 'Enter Valid ',
                'Field_Name' => ""
            );
        }
        else 
        {
            return array(
                'Status' => 'Success',
                'Status_Code' => '5'
            );
        }
    }

    public function Field_Value_Min_Max_Length_Check($Field_Value = '', $Field_Min_length = '', $Field_Max_length = '')
    {
		if($Field_Min_length!='' && $Field_Max_length!='')
		{
			if (strlen($Field_Value) < $Field_Min_length || strlen($Field_Value) > $Field_Max_length) {
				return array(
					'Status' => 'Error',
					'Status_Code' => '4',
					'Message' => 'Given '. $Field_Value .' Should be Minimum Length ' . $Field_Min_length . ' and Maximum Length ' . $Field_Max_length . ''
				);
			}
			else {
				return array(
					'Status' => 'Success',
					'Status_Code' => '5'
				);
			}
		}
		else if($Field_Min_length!='' && $Field_Max_length=='')
		{
			if (strlen($Field_Value) < $Field_Min_length) {
				return array(
					'Status' => 'Error',
					'Status_Code' => '4',
					'Message' => 'Given '. $Field_Value .' Should be Minimum Length ' . $Field_Min_length . ' and Maximum Length ' . $Field_Max_length . ''
				);
			}
			else {
				return array(
					'Status' => 'Success',
					'Status_Code' => '5'
				);
			}
		}
		else if($Field_Max_length!='' && $Field_Min_length=='')
		{
			if (strlen($Field_Value) > $Field_Max_length) {
				return array(
					'Status' => 'Error',
					'Status_Code' => '4',
					'Message' => 'Given '. $Field_Value .' Should be Minimum Length ' . $Field_Min_length . ' and Maximum Length ' . $Field_Max_length . ''
				);
			}
			else {
				return array(
					'Status' => 'Success',
					'Status_Code' => '5'
				);
			}
		}
        
    }

    public function Field_Value_Date_Format($Field_Value = '', $Field_Format = '')
    {}

    public function Field_Validation($Field_Conditions = array())
    {
        switch ($Field_Conditions['Field_Type']) {
            case "text":
                return $this->Text_Field_Validation($Field_Conditions);
                break;
			case "text_comma_dot_number":
                return $this->Text_comma_dot_Field_Validation($Field_Conditions);
                break;
			case "text_ta":
                return $this->Text_Tamil_Field_Validation($Field_Conditions);
                break;					
			case "text_area":
				return $this->Text_area_Field_Validation($Field_Conditions);
				break;
            case "text_area_ta":
				return $this->Text_area_Field_Validation_ta($Field_Conditions);
				break;
            case "text_underscore":
                return $this->Text_underscore_Field_Validation($Field_Conditions);
                break;
            case "text_space":
                return $this->Text_space_Field_Validation($Field_Conditions);
                break;
            case "text_number_underscore":
                return $this->Text_number_underscore_Field_Validation($Field_Conditions);
                break;
            case "text_number_space":
                return $this->Text_number_space_Field_Validation($Field_Conditions);
                break;
			case "text_comma_dot_space_slash":
                return $this->Text_comma_dot_space_slash_Field_Validation($Field_Conditions);
                break;
            case "text_comma_dot_space_slash_brackets":
                return $this->Text_comma_dot_space_slash_brackets_Field_Validation($Field_Conditions);
                break;
			case "text_comma_dot_space_slash_ta":
                return $this->Text_comma_dot_space_slash_ta_Field_Validation($Field_Conditions);
                break;	
            case "text_comma_dot_space_slash_brackets_ta":
                return $this->Text_comma_dot_space_slash_ta_Field_Validation($Field_Conditions);
                break;
            case "text_number_comma_dot_space_slash":
                return $this->Text_number_comma_dot_space_slash_Field_Validation($Field_Conditions);
                break;
            case "text_number_hyphen":
                return $this->Text_number_hyphen_Field_Validation($Field_Conditions);
                break;
            case "text_number":
                return $this->Text_Number_Field_Validation($Field_Conditions);
                break;
             case "order_number":
                return $this->Order_Number_Field_Validation($Field_Conditions);
                break;    
            case "number":
                return $this->Number_Field_Validation($Field_Conditions);
                break;
            case "date":
                return $this->Date_Field_Validation($Field_Conditions);
                break;
            case "time":
                return $this->Time_Field_Validation($Field_Conditions);
                break;
            case "date_time":
                return $this->Date_Time_Field_Validation($Field_Conditions);
                break;
            case "email":
                return $this->Email_Field_Validation($Field_Conditions);
                break;
            case "fin_year":
                return $this->Finyear_Field_Validation($Field_Conditions);
                break;
            case "year":
                return $this->Year_Field_Validation($Field_Conditions);
                break;
            case "float":
                return $this->Float_Field_Validation($Field_Conditions);
                break;
            case "door_no":
                return $this->Door_Number_Field_Validation($Field_Conditions);
                break;
			case "table_name":
                return $this->Table_Name_Field_Validation($Field_Conditions);
                break;
			case "receipt_no":
                return $this->Receipt_No_Field_Validation($Field_Conditions);
                break;	
			case "text_number_character":
                return $this->Text_Number_Character_Field_Validation($Field_Conditions);
                break;
<<<<<<< HEAD:accounts/project/library/ServerValidation.php
            case "number_slash_hyphen":
                return $this->Number_Slash_Hyphen_Field_Validation($Field_Conditions);
                break;
=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/library/ServerValidation.php


            default:
                return array(
                    'Status' => 'Error',
                    'Status_Code' => '6',
                    'Message' => 'Technical Error Occurs'
                );
        }
    }

    public function Text_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //..'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	
	
	public function Text_comma_dot_Field_Validation($Field_Conditions = array())
    {
		
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[1-9a-zA-Z., ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Order_Number_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9-\/.]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Text_Tamil_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲  ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Text_underscore_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z_]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Text_space_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	public function Text_comma_dot_space_slash_brackets_Field_ta_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲  0-9)(.,\/]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
    public function Text_comma_dot_space_slash_ta_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[அ ஆ இ ஈ உ ஊ எ ஏ ஐ ஒ ஓ ஔ ஃ க  கா  கி  கீ  கு  கூ  கெ  கே  கை  கொ  கோ  கௌ  க்  ங  ஙா  ஙி  ஙீ  ஙு  ஙூ  ஙெ  ஙே  ஙை  ஙொ  ஙோ  ஙௌ  ங்  ச  சா  சி  சீ  சு  சூ  செ  சே  சை  சொ  சோ  சௌ  ச்  ஞ  ஞா  ஞி  ஞீ  ஞு  ஞூ  ஞெ  ஞே  ஞை  ஞொ  ஞோ  ஞௌ  ஞ்  ட  டா  டி  டீ  டு  டூ  டெ  டே  டை  டொ  டோ  டௌ  ட்  ண  ணா  ணி  ணீ  ணு  ணூ  ணெ  ணே  ணை  ணொ  ணோ  ணௌ  ண்  த  தா  தி  தீ  து  தூ  தெ  தே  தை  தொ  தோ  தௌ  த்  ந  நா  நி  நீ  நு  நூ  நெ  நே  நை  நொ  நோ  நௌ  ந்  ப  பா  பி  பீ  பு  பூ  பெ  பே  பை  பொ  போ  பௌ  ப்  ம  மா  மி  மீ  மு  மூ  மெ  மே  மை  மொ  மோ  மௌ  ம்  ய  யா  யி  யீ  யு  யூ  யெ  யே  யை  யொ  யோ  யௌ  ய்  ர  ரா  ரி  ரீ  ரு  ரூ  ரெ  ரே  ரை  ரொ  ரோ  ரௌ  ர்  ல  லா  லி  லீ  லு  லூ  லெ  லே  லை  லொ  லோ  லௌ  ல்  வ  வா  வி  வீ  வு  வூ  வெ  வே  வை  வொ  வோ  வௌ  வ்  ழ  ழா  ழி  ழீ  ழு  ழூ  ழெ  ழே  ழை  ழொ  ழோ  ழௌ  ழ்  ள  ளா  ளி  ளீ  ளு  ளூ  ளெ  ளே  ளை  ளொ  ளோ  ளௌ  ள்  ற  றா  றி  றீ  று  றூ  றெ  றே  றை  றொ  றோ  றௌ  ற்  ன  னா  னி  னீ  னு  னூ  னெ  னே  னை  னொ  னோ  னௌ  ன்   ஜ  ஜா  ஜி  ஜீ  ஜு  ஜூ  ஜெ  ஜே  ஜை  ஜொ  ஜோ  ஜௌ  ஜ்  ஷ  ஷா  ஷி  ஷீ  ஷு  ஷூ  ஷெ  ஷே  ஷை  ஷொ  ஷோ  ஷௌ  ஷ்  ஸ  ஸா  ஸி  ஸீ  ஸு  ஸூ  ஸெ  ஸே  ஸை  ஸொ  ஸோ  ஸௌ  ஸ்  ஹ  ஹா  ஹி  ஹீ  ஹு  ஹூ  ஹெ  ஹே  ஹை  ஹொ  ஹோ  ஹௌ  ஹ்  க்ஷ  க்ஷா  க்ஷி  க்ஷீ  க்ஷு  க்ஷூ  க்ஷெ  க்ஷே  க்ஷை  க்ஷொ  க்ஷோ  க்ஷௌ  க்ஷ்   ஸ்ரீ   ஃப  ஃபா  ஃபி  ஃபீ  ஃபு  ஃபூ  ஃபெ  ஃபே  ஃபை  ஃபொ  ஃபோ  ஃபௌ  ஃப்  ஃஜ  ஃஜா  ஃஜி  ஃஜீ  ஃஜு  ஃஜூ  ஃஜெ  ஃஜே  ஃஜை  ஃஜொ  ஃஜோ  ஃஜௌ  ஃஜ்  ஃஸ  ஃஸா  ஃஸி  ஃஸீ  ஃஸு  ஃஸூ  ஃஸெ  ஃஸே  ஃஸை  ஃஸொ  ஃஸோ  ஃஸௌ  ஃஸ்   ௧  ௨  ௩  ௪  ௫  ௬  ௭  ௮  ௯  ௰  ௲  0-9)(.,\/]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

	public function Text_comma_dot_space_slash_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9,.)(\/ ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
 
	public function Text_comma_dot_space_slash_brackets_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9,.\-\/() ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
    public function Text_number_underscore_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9_]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Text_number_space_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
       
        if (preg_match("/^[a-zA-Z0-9 ]+$/", $Field_Conditions['Field_Value'])==0) {
          
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Text_number_comma_dot_space_slash_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9,.)(\/ ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	
	
	 public function Text_area_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
       
        if (preg_match("/^[a-zA-Z0-9 \.\(\)\[\],\-]+$/", $Field_Conditions['Field_Value'])==0) {
            
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }



     public function Text_area_Field_Validation_ta($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
       
        if (preg_match("/^[அ-௿0-9 \.\(\)\[\],\-]+$/", $Field_Conditions['Field_Value'])==0) {
            
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	

    public function Text_number_hyphen_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
       
        if (preg_match("/^[a-zA-Z0-9-]+/", $Field_Conditions['Field_Value'])==0) {
            echo "Ok";
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }


    public function Text_Number_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Number_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        //if (! preg_match("/^[0-9]+/", $Field_Conditions['Field_Value'])) {
		if (preg_match("/^[0-9]+$/", $Field_Conditions['Field_Value'])==0) {	
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	public function Text_Number_Character_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z0-9-&_\/(),.+'@ ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
<<<<<<< HEAD:accounts/project/library/ServerValidation.php

    public function Number_Slash_Hyphen_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[0-9-\/]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
=======
>>>>>>> 0f5a28046b261ab90f4819e1c5bb0f7243272645:project/library/ServerValidation.php

    public function Date_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0 ) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if ($Field_Conditions['Field_Format'] == '') {
            return array(
                'Status' => 'Error',
                'Status_Code' => '6',
                'Message' => 'Technical Error Occurs'
            );
        }

        if ($Field_Conditions['Field_Format'] == 'dd-mm-yyyy') {
			
			if (strpos($Field_Conditions['Field_Value'],'-') !== false) 
			{
					$Date_Split=explode('-', $Field_Conditions['Field_Value']);
					
					if(count($Date_Split)==3)
					{
						list ($date, $month, $year) = explode('-', $Field_Conditions['Field_Value']);
			
						$Temp_Date = strtotime($date . '-' . $month . '-' . $year);
						$Temp_Date_Format = date('d-m-Y', $Temp_Date);
						list ($date_temp, $month_temp, $year_temp) = explode('-', $Temp_Date_Format);
			
						if ($date != $date_temp || $month != $month_temp || $year != $year_temp) {
							return array(
								'Status' => 'Error',
								'Status_Code' => '3',
								//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
								'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
							);
						}
					}
					else
					{
						return array(
							'Status' => 'Error',
							'Status_Code' => '3',
							//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
							'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
						);
					}
				
				}
				else
				{
					return array(
						'Status' => 'Error',
						'Status_Code' => '3',
						//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
						'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
					);
				}
			
			
			
        } else if ($Field_Conditions['Field_Format'] == 'yyyy-mm-dd') {
			
			if (strpos($Field_Conditions['Field_Value'],'-') !== false) 
			{
				$Date_Split=explode('-', $Field_Conditions['Field_Value']);
					
				if(count($Date_Split)==3)
				{
					list ($year, $month, $date) = explode('-', $Field_Conditions['Field_Value']);
		
					$Temp_Date = strtotime($date . '-' . $month . '-' . $year);
					$Temp_Date_Format = date('d-m-Y', $Temp_Date);
					list ($date_temp, $month_temp, $year_temp) = explode('-', $Temp_Date_Format);
		
					if ($date != $date_temp || $month != $month_temp || $year != $year_temp) {
						return array(
							'Status' => 'Error',
							'Status_Code' => '3',
							//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
							'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
						);
					}
				}
				else
				{
					return array(
						'Status' => 'Error',
						'Status_Code' => '3',
						//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
						'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
					);
				}
			}
			else
			{
				return array(
					'Status' => 'Error',
					'Status_Code' => '3',
					//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
					'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
				);
			}
				
        } else if ($Field_Conditions['Field_Format'] == 'dd/mm/yyyy') {
			
			if (strpos($Field_Conditions['Field_Value'],'/') !== false) 
			{
				$Date_Split=explode('/', $Field_Conditions['Field_Value']);
					
				if(count($Date_Split)==3)
				{			
					list ($date, $month, $year) = explode('/', $Field_Conditions['Field_Value']);
		
					$Temp_Date = strtotime($date . '/' . $month . '/' . $year);
					$Temp_Date_Format = date('d/m/Y', $Temp_Date);
					list ($date_temp, $month_temp, $year_temp) = explode('/', $Temp_Date_Format);
		
					if ($date != $date_temp || $month != $month_temp || $year != $year_temp) {
						return array(
							'Status' => 'Error',
							'Status_Code' => '3',
							//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
							'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
						);
					}
				}
				else
				{
					return array(
						'Status' => 'Error',
						'Status_Code' => '3',
						//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
						'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
					);	
				}
			}
			else
			{
				return array(
					'Status' => 'Error',
					'Status_Code' => '3',
					//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
					'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
				);	
			}
			
        } else if ($Field_Conditions['Field_Format'] == 'yyyy/mm/dd') {
			
			if (strpos($Field_Conditions['Field_Value'],'/') !== false) 
			{
				$Date_Split=explode('/', $Field_Conditions['Field_Value']);
					
				if(count($Date_Split)==3)
				{
					list ($year, $month, $date) = explode('/', $Field_Conditions['Field_Value']);
		
					$Temp_Date = strtotime($date . '/' . $month . '/' . $year);
					$Temp_Date_Format = date('d/m/Y', $Temp_Date);
					list ($date_temp, $month_temp, $year_temp) = explode('/', $Temp_Date_Format);
		
					if ($date != $date_temp || $month != $month_temp || $year != $year_temp) {
						return array(
							'Status' => 'Error',
							'Status_Code' => '3',
							//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
							'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
						);
					}
				}
				else
				{
					return array(
						'Status' => 'Error',
						'Status_Code' => '3',
						//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
						'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
					);
				}
			}
			else
			{
				return array(
					'Status' => 'Error',
					'Status_Code' => '3',
					//'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
					'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
				);
			}
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Time_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
    }

    public function Date_Time_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
    }

    public function Email_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', strtolower($Field_Conditions['Field_Value']))==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Finyear_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        $Split_Finyear = explode('-', $Field_Conditions['Field_Value']);

        if (( preg_match("/^[0-9]+$/", $Split_Finyear[0])==0 || strlen($Split_Finyear[0]) != 4) || (preg_match("/^[0-9]+$/", $Split_Finyear[1])==0 || strlen($Split_Finyear[1]) != 4)) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Year_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[0-9]+$/", $Field_Conditions['Field_Value'])==0 || strlen($Field_Conditions['Field_Value']) != 4) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }

    public function Float_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^-?(?:\d+|\d*\.\d+)$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
    
	
	public function Door_Number_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
		
		if (preg_match("/^[a-zA-Z0-9-\/]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
		
		/*if (preg_match("/^[a-zA-Z0-9-\/]+$/", $Field_Conditions['Field_Value'])==0 || preg_match('/[a-zA-Z]/',$Field_Conditions['Field_Value'])==0 || preg_match('/\d/',$Field_Conditions['Field_Value'])==0 || preg_match('/[^a-zA-Z\d]/',$Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }*/

        if (isset($Field_Conditions['Field_length']) && $Field_Conditions['Field_length']!='') {
            return $this->Field_Value_Length_Check($Field_Conditions['Field_Value'], $Field_Conditions['Field_length']);
        }

        if (isset($Field_Conditions['Field_Min_length']) || isset($Field_Conditions['Field_Max_length'])) {
            return $this->Field_Value_Min_Max_Length_Check($Field_Conditions['Field_Value'], ((isset($Field_Conditions['Field_Min_length']) && $Field_Conditions['Field_Min_length']!='')?$Field_Conditions['Field_Min_length']:'') , ((isset($Field_Conditions['Field_Max_length']) && $Field_Conditions['Field_Max_length']!='')?$Field_Conditions['Field_Max_length']:'') );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	public function Table_Name_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        if (preg_match("/^[a-zA-Z_,. ]+$/", $Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
	
	
	public function Receipt_No_Field_Validation($Field_Conditions = array())
    {
        if (! isset($Field_Conditions['Field_Value']) ||  strlen($Field_Conditions['Field_Value'])==0) {
            return array(
                'Status' => 'Error',
                'Status_Code' => '2',
                'Message' => 'Enter ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }
		
		if(count(explode('/',$Field_Conditions['Field_Value']))!=4)
		{
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
		}
		
		list($fin_year,$lbcode,$taxtype,$slno)=explode('/',$Field_Conditions['Field_Value']);
		
		$fin_year_validation=$this->Field_Validation(array('Field_Type'=>'fin_year','Field_Value'=>$fin_year));
		$lbcode_validation=$this->Field_Validation(array('Field_Type'=>'number','Field_Value'=>$lbcode));
		$taxtype_validation=$this->Field_Validation(array('Field_Type'=>'number','Field_Value'=>$taxtype));
		$slno_validation=$this->Field_Validation(array('Field_Type'=>'number','Field_Value'=>$slno));
		
       if ($fin_year_validation['Status'] == "Error" || $lbcode_validation['Status'] == "Error" || $taxtype_validation['Status'] == "Error" || $slno_validation['Status'] == "Error") {
            return array(
                'Status' => 'Error',
                'Status_Code' => '3',
                //'Message' => 'Enter Valid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
				'Message' => 'Invalid ' . (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:""),
                'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
            );
        }

        return array(
            'Status' => 'Success',
            'Status_Code' => '5',
            'Message' => (isset($Field_Conditions['Field_Label_Name'])?$Field_Conditions['Field_Label_Name']:"") . ' is Valid',
            'Field_Name' => isset($Field_Conditions['Field_Name'])?$Field_Conditions['Field_Name']:""
        );
    }
}
?>