<?php
class SW_Response{
	
	//$this->contentType='text/html';
	
	static function getRequiredFields($fieldsArray,$method='post'){
		
		$method=strtolower($method);
		
		if('post'==$method){
			
			foreach($fieldsArray as $fieldName=>$optionNameArray){
				
				if(in_array('required', $optionNameArray)){
					
					if(
					    !isset($_POST[$fieldName])
					  ){
					  	   
					    	//echo $fieldName.' is required!';
					        SW_Response::sendJsonResponse(false,$fieldName.' is required!'); 
					    	exit;
					    }
				}
			}
		}
		
	} 
	
	/**
	 * array(
	 * "success": false, //bool
	 * "payload": //array
	 *  )
	 * 
	 * @param unknown_type $success => bool
	 * @param String $message
	 * @param unknown_type $payload => array
	 * 
	 */
	static function sendJsonResponse($success,$message,$payload=array() ){
		
		header('Content-type: application/json');
		
		if(empty($payload)){
			$payload=null;
		}
		
		$tmp=array(
		            'success'=>$success,
		             'message'=>$message,
		            'payload'=>$payload
		          );
		          
		ob_flush();
        echo json_encode($tmp);
        exit;
	}
	
}

/**
 * Make wordpress config file get loaded 
 **/

define('NN_MODELS_ABS_PATH',__DIR__ ); //without trail at end
define('NN_MODELS_Collection_ABS_PATH',__DIR__ .DS.'collection'); //without trail at end

/****************----------------Load API ------------------------ ****************/
require_once NN_MODELS_Collection_ABS_PATH.DS.'nn_models_load.php';
?>
