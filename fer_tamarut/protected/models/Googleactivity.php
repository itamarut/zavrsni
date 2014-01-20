<?php
	
	class Googleactivity extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $vlasnik;
	 public $vrijeme;
	 public $plusevi;
	 public $komentari;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'googleactivity'; 
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
		  'vlasnik' => 'vlasnik',
		  'vrijeme' => 'Vrijeme',
		  'plusevi' => 'Plusevi',
		  'komentari' => 'Komentari',
        );
     }
	 	 
	}
	
?>