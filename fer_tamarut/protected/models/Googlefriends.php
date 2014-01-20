<?php
	
	class Googlefriends extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $id;
	 public $prijatelj;
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'googlefriends'; 
     }
	 
	 public function rules()
     {
       return array(
          array('user_id, datee', 'required'),
        );
     }
	 
	 public function attributeLabels()
     {
        return array(
          'user_id'  => 'User Login',
		  'datee'  => 'Datum',
		  'id' => 'ID',
		  'prijatelj' => 'Prijatelj',
        );
     }
	 	 
	}
	
?>