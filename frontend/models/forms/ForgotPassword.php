<?php
namespace cmsgears\core\frontend\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

class ForgotPassword extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;

	// Instance Methods --------------------------------------------
	
	// yii\base\Model

	public function rules() {
		
		return  [
			[ [ 'email' ], 'required' ],
			[ 'email', 'email' ],
		];
	}

	public function attributeLabels() {

		return [
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL )
		];
	}
}

?>