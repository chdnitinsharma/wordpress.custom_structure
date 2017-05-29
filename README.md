# wordpress.custom_structure
Easily insert and update record in wordpress on custom table.


Steps:

1) Add below line in functions.php in your current theme folder for include models.
    //include models
    require get_template_directory().'/models/nn_models_config.php';
    
    date_default_timezone_set('Asia/Kolkata');
    function get_current_time($type='mysql'){
        return date('Y-m-d H:i:s');
    }
    
    
2) Create folder models in your current theme folder.

3) All files under models/collection/mysql prefix name of file must be "nn_mysql_" then only file will be read. Prefix define in models/collection/nn_models_load.php


Demo:

Creating table for testing: "nn_ct_" prefix of table define in models/collection/nn_models_load.php. Must create created_date and modified_date in table.

CREATE TABLE IF NOT EXISTS `nn_ct_users` (
  `id` int(11) NOT NULL COMMENT 'ID ',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `first_name` varchar(35) NOT NULL COMMENT 'first_name',
  `created_date` datetime NOT NULL COMMENT 'created date',
  `modified_date` datetime NOT NULL COMMENT 'modified date'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='contains users data';

ALTER TABLE `nn_ct_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `nn_ct_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID ';


Example:

//create object of class
$NN_CT_USERSObj=new NN_CT_USERS();

//How to add insert in table:
//$NN_CT_USERSObj->insertMysqlData(array("email"=>"testing@gmail.com","first_name"=>"ABC"));
//$NN_CT_USERSObj->insertMysqlData(array("email"=>"admin@gmail.com","first_name"=>"Admin"));

//How to update record in table:
//$NN_CT_USERSObj->updateMysqlData("id",2,array("first_name"=>"Admin1"));

////How to delete record in table:
//$NN_CT_USERSObj->deleteMysqlData(array("id"=>1));

////How to automatic update record in table:
$NN_CT_USERSObj->insertMysqlData(array("email"=>"testing@gmail.com","first_name"=>"ABC"));
NN_API_Stats_Mysql::do_action_InsertMysqlData(array("email"=>"testing@gmail.com","first_name"=>"New ABC"),new NN_CT_USERS(),true,true);
