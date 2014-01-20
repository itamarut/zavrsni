<?php
	
	class Twitterfriends extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $prijatelj;
	 public $id;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'twitterfriends'; 
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
		  'prijatelj' => 'Prijatelj',
		  'id' => 'ID',
        );
     }
	 	 
	}
	
?>