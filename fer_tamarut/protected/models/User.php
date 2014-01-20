<?php
		
    class User extends EMongoDocument
    {
     public $login;
     public $name;
     public $pass;

      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }

      // This method is required!
      public function getCollectionName()
      {
        return 'users'; // should be lowercase.
      }

      // same as CActiveRecord
      public function rules()
      {
        return array(
          array('login, pass', 'required'),
          array('login, pass', 'length', 'max' => 20),
          array('name', 'length', 'max' => 255),
        );
      }

      // same as CActiveRecord
      public function attributeLabels()
      {
        return array(
          'login'  => 'User Login',
          'name'   => 'Full name',
          'pass'   => 'Password',
        );
      }
    }
?>