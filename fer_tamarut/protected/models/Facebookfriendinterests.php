<?php
	
	class Facebookfriendinterests extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $id_stranice;
	 public $lajkovi;
	
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'facebookfriendinterests'; 
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
		  'id_stranice' => 'IDstranice',
		  'ime_stranice' => 'Imestranice',
		  'kategorija' => 'Kategorija',
        );
     }
	 	 
	}
	
?>