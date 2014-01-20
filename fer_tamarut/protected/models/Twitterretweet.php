<?php
	
	class Twitterretweet extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $vlasnik;
	 public $vrijeme;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'twitterretweet'; 
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
		  'vlasnik' => 'Prijatelj',
		  'vrijeme' => 'Vrijeme',
        );
     }
	 	 
	}
	
?>