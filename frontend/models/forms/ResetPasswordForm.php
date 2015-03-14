<?php
namespace cmsgears\core\frontend\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

class ResetPasswordForm extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------
	
	public $email;
	public $password;
	public $password_repeat;

	// Instance Methods --------------------------------------------
	
	// yii\base\Model

	public function rules() {
		
		return  [
			[ [ 'email', 'password', 'password_repeat' ], 'required' ],
			[ 'email', 'email' ],
			[ 'password', 'compare' ]
		];
	}

	public function attributeLabels() {
		
		return [
			'email' => 'Email',
			'password' => 'Password',
			'password_repeat' => 'Confirm Password'
		];
	}
}

?>