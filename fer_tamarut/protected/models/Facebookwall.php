<?php
	
	class Facebookwall extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $vrijeme;
	 public $vlasnik;
	 public $lajkovi;
	 public $komentari;
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'facebookwall'; 
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
		  'vrijeme' => 'Vrijeme',
		  'vlasnik' => 'Vlasnik',
		  'lajkovi' => 'Lajkovi',
		  'komentari' => 'Komentari',
        );
     } 
	
	
	}
	
?>