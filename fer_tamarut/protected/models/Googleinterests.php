<?php
	
	class Googleinterests extends EMongoDocument
    {
     public $user_id;
	 public $datee;
	 public $id_objekta;
	 public $tip_objekta;
	 public $opis_objekta;
	 
	 
	 public static function model($className=__CLASS__)
     {
       return parent::model($className);
     }
	 
	 public function getCollectionName()
     {
       return 'googleinterests'; 
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
		  'id_objekta' => 'ID',
		  'tip_objekta' => 'Tip',
		  'opis_objekta' => 'Opis',
        );
     }
	 	 
	}
	
?>