<?php
	
	class Facebookfriends extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $idprijatelj;
	 public $imeprijatelj;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'facebookfriends'; 
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
		  'idprijatelj' => 'IDPrijatelj',
		  'imeprijatelj' => 'Imeprijatelj',
        );
     }
	 	 
	}
	
?>