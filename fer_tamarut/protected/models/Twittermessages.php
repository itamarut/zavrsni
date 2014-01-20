<?php
	
	class Twittermessages extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $id_poruke;
	 public $type;
	 public $prijatelj;
	 public $vrijeme;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'twittermessages'; 
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
		  'id_poruke' => 'ID_poruke',
		  'prijatelj' => 'Prijatelj',
		  'vrijeme' => 'Vrijeme',
        );
     }
	 	 
	}
	
?>