<?php
	
	class Twitterinterests extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $id_prijatelj;
	 public $description;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'twitterinterests'; 
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
		  'id_prijatelj' => 'Prijatelj',
		  'description' => 'Opis',
        );
     }
	 	 
	}
	
?>