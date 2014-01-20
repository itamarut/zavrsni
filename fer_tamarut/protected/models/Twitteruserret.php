<?php
	
	class Twitteruserret extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $prijatelj;
	 public $vrijeme;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'twitteruserret'; 
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
		  'vrijeme' => 'Vrijeme',
        );
     }
	 	 
	}
	
?>