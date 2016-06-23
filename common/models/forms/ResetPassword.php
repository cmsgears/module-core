<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ResetPassword extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;
	public $password;
	public $password_repeat;

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        $rules = [
			[ [ 'email', 'password', 'password_repeat' ], 'required' ],
			[ 'email', 'email' ],
			[ 'password', 'compare' ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
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