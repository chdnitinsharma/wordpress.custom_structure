<?php 
class NN_CT_USERS extends  NN_API_Stats_Mysql{
    
     /**
     * Table name (tn)
     **/
    protected $tn=null;
    
    protected function setTableName(){
         $this->tn=$this->tp.'users';
    }
    

    public function insertMysqlData($tempArray,$cmDate=true){
       
      try{
           if(
              !isset($tempArray['email'])
              ){
               $error = 'Email must exist in array. While inserting into table '.$this->tn;
               throw new Exception($error);
           } 
      //type 1 for split and 2 year to date     
       $findIDinRecord="SELECT count(id) as totalrecord,id FROM ".$this->tn." where email='".$tempArray['email']."' limit 1;";
       $findIDinRecordResultQuery=$this->wpdbObj->get_results($findIDinRecord);    
       
       if($findIDinRecordResultQuery[0]->totalrecord >= 1){
           $returnFound=array('want_to_update_record'=>false,'table_column'=>'id','table_id'=>$findIDinRecordResultQuery[0]->id,'nature'=>'insert','reason'=>'found','status'=>2);
           return $returnFound;
       }
        
       ### Adding owner table
       if($cmDate):
           $tempArray=array_merge($tempArray,array('created_date'=>get_current_time('mysql'),'modified_date'=>get_current_time('mysql')));  
	       $tempArrayFormat=str_repeat('%s,', count($tempArray) -2);
	   else: //no merging take place
           $tempArrayFormat=str_repeat('%s,', count($tempArray));
	   endif;
      	  //----------------------- 
	   
      $inserResponse=$this->wpdbObj->insert($this->tn,$tempArray,explode(',',$tempArrayFormat)); 
      
      if(false===$inserResponse){
         //dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
              $returnFailed=array('table_id'=>null,'nature'=>'insert','reason'=>'failed','status'=>0);
           return $returnFailed ;
      }else{
          $lastInsertedId=$this->wpdbObj->insert_id;
         //dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
               $returnSuccess=array('table_id'=>$lastInsertedId,'nature'=>'insert','reason'=>'inserted','status'=>1);
           return $returnSuccess ;
          
      }
      //----------------------- 
     } catch (Exception $e) {
       //echo 'Caught exception: ',  $e->getMessage(), "\n";
       //-------------
               $returnError=array('table_id'=>null,'nature'=>'insert','reason'=>'error','status'=>0);
           return $returnError ;
           
     //----------------
    
      } 
    }
    

    public function updateMysqlData($table_column,$id,$tempArray,$mDate=true){
        
                 try{

           //delete truck_union_user_id,truck_type_post_id from array. please first check insertMysqlData method, what we are using checking before inserting.
            if(isset($tempArray['email'])){
             unset($tempArray['email']); 
          }
         
         
       ### Adding owner table
       if($mDate):
           $tempArray=array_merge($tempArray,array('modified_date'=>get_current_time('mysql')));  
	       $tempArrayFormat=str_repeat('%s,', count($tempArray) -1);
	   else: //no merging take place
           $tempArrayFormat=str_repeat('%s,', count($tempArray));
	   endif;
	   
	    if('id'==$table_column){
	      $result= $this->wpdbObj->update( 
                        	$this->tn, 
                        	$tempArray, 
                        	array('id' => $id ), 
                        	explode(',',$tempArrayFormat), 
                        	array( '%d' ) 
                        );
	    }else{
            throw new Exception('&&&& Column is not match ... &&&');
	    }
                        
        if( false === $result){
              //dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
             $returnError=array('table_column'=>$table_column, 'no_of_row_affected'=>false,'nature'=>'update','table_id'=>$id,'reason'=>'failed','status'=>0);

            //dont change reason. I will use this while taking decision.
            return $returnError;
        }else{
           //dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
            $returnSuccess=array('table_column'=>$table_column,'no_of_row_affected'=>$result,'nature'=>'update','table_id'=>$id,'reason'=>'updated','status'=>1);
            return $returnSuccess;
        }                
       //----------------------
     } catch (Exception $e) {
       //echo 'Caught exception: ',  $e->getMessage(), "\n";
              //dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
          $returnError=array('table_column'=>$table_column,'no_of_row_affected'=>false,'nature'=>'update','table_id'=>$id,'reason'=>'error','status'=>0);
       return $returnError;
      } 
    }
    
    function getAllRecord($ob_or_array='ARRAY_A'){
		$findIDinRecord="SELECT * from nn_ct_users;";
		//OBJECT
		$findIDinRecordResultQuery=$this->wpdbObj->get_results($findIDinRecord,$ob_or_array);    
		
		return $findIDinRecordResultQuery;
    }
    
    public function deleteMysqlData($tempArray=array()){  
		try{
			
			if(
			   !isset($tempArray['id'])
			   ){
			   $error = 'id must exist. While deleting from table '.$this->tn;
			   throw new Exception($error);
		    }

			$result= $this->wpdbObj->delete( $this->tn, array('id'=>$tempArray['id']), array( '%d' ) );
						
			if( false === $result){
				//dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
				$returnError=array('no_of_row_affected'=>false,'nature'=>'delete','reason'=>'failed','status'=>0);

				//dont change reason. I will use this while taking decision.
				return $returnError;
			}else{
				//dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
				$returnSuccess=array('no_of_row_affected'=>$result,'nature'=>'delete','reason'=>'deleted','status'=>1);
				return $returnSuccess;
			}                
			//----------------------
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			//dont change reason,  nature and status(0 fail/error, 1 success, 2, found). I will use this while taking decision.
			$returnError=array('no_of_row_affected'=>false,'nature'=>'delete','reason'=>'error','status'=>0);
			return $returnError;
		}
    }
    
	
	
}
	
?>
