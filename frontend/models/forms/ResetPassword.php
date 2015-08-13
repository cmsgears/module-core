<?php
namespace cmsgears\core\frontend\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ResetPassword extends Model {

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
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'password' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PASSWORD ),
			'password_repeat' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT )
		];
	}
}

?>