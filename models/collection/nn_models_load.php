<?php 

abstract class NN_MYSQL{
    
    protected $tp='TABLENAMESPACE_'; //tablename
    protected $wpdbObj=null; //wordpress obj
     
    abstract protected function setTablePrefixName(); //table name prefix
    
    abstract  public function isTableExist($tn=null);
    abstract  public function createTable($tn=null);

   public function __construct(){
        global $wpdb;
        $this->wpdbObj=$wpdb; //setting the wpdb object of wordpress in my custom variable.

        $this->setTablePrefixName();
   }
    
}

abstract class NN_API_Stats_Mysql extends NN_MYSQL{
  
   protected $tn=null;
  
     public static function getTablePrefixName(){
        return 'nn_ct_';
    } 
    
    /**
     * Table name prefix(tp)
     */
    protected function setTablePrefixName(){
       $this->tp=NN_API_Stats_Mysql::getTablePrefixName();
    }
    
    //set table name
    abstract protected function setTableName();

     public function getTableName(){
        return $this->tn;
    }
    
    protected $wpdbObj=null;
    
    public function getMeAccessforDBObj(){
        return $this->wpdbObj;
    }
    

   public function __construct(){
        global $wpdb;
        $this->wpdbObj=$wpdb; //setting the wpdb object of wordpress in my custom variable.

        $this->setTablePrefixName();
        $this->setTableName(); //added this to set table name

     
      //----checking tablename exist or not Start-----   
     $tableExist=$this->isTableExist($this->tn); //checking table exist or not
      
        if(!$tableExist): //table exist not exist
            $this->createTable($this->tn);
         endif;
        //----checking tablename exist or not END-----
      
    }
    
    abstract public  function insertMysqlData($tempArray,$cmDate=true);


   static function do_action_InsertMysqlData($tempArray=array(),$classObj,$cmDate=true,$explicitUpdateRecord=false){
            
       try{
          if(is_object($classObj) && $tempArray ){ //is object and array not empty
                 
               if(method_exists(get_class($classObj), 'insertMysqlData')){
                   $RESponseInserted=$classObj->insertMysqlData($tempArray,$cmDate);

                  //reason => found,failed,inserted,error,updated,  nature=>insert,update and status(0 fail/error, 1 success, 2 found). I will use this while taking decision.
                   if( 'found'==$RESponseInserted['reason'] &&
                       'insert'==$RESponseInserted['nature'] &&
                       2==$RESponseInserted['status']){
                           
                              
                        if(method_exists(get_class($classObj), 'updateMysqlData')){
                            
                           if( ($RESponseInserted['want_to_update_record']) || $explicitUpdateRecord){
                              
                              $RESponseUpdate=$classObj->updateMysqlData($RESponseInserted['table_column'],$RESponseInserted['table_id'],$tempArray);    
                              return $RESponseUpdate; 
                           }else{
                          }
                           
                        }else{
                            //throw new Exception('updateMysqlData Method not found.');
                        }
                   }
                  
                   return $RESponseInserted['table_id'];
                }else{ 
                     
                    throw new Exception('Method not found.');
                }
          }else{  //is not object
              return null;
          }
               
        }catch(Exception  $e){
            return null;
        }
        
         unset($classObj); //delete the object
      }
    

   abstract public function updateMysqlData($table_column,$id,$tempArray,$mDate=true);
   
    
    /**
     * Check Table exist or not
     * 
     * @return bool. true=> table exist, false=>table not exist
     */
    function isTableExist($tn=null){
        
        if(is_null($tn))
        return false;
        
       $sql="SELECT COUNT(1) as tableExist FROM information_schema.tables WHERE table_schema='".DB_NAME."' AND table_name='".$tn."'";
       
       $te = $this->wpdbObj->get_results($sql, OBJECT);
        
        if($te[0]->tableExist):
          return true;
        else:
           return false;
        endif;
    }classObj
    

    function createTable($tn=null){
        
        if(is_null($tn)){
            return false;
        }
        
      exit("Please first create table: ".$tn);
       
    }

   
}

#Load Mysql stats classes file
$statsABSMYSQLPath=NN_MODELS_Collection_ABS_PATH.DS.'mysql';
$allFilesInMysqlFolder = scandir($statsABSMYSQLPath);

foreach ( $allFilesInMysqlFolder as $files){
    $files=strtolower($files);
    
    preg_match("/.php$/", $files, $PHPFilesExt);
    
    if(!$PHPFilesExt){ //php extension not found eg .php.bak
        continue;
    }
    
    preg_match("/^nn_mysql_/", $files, $mysqlFileLoad);
    if(!$mysqlFileLoad){
        continue;
    }
    
   require_once $statsABSMYSQLPath.DS.$files;
}






?>
