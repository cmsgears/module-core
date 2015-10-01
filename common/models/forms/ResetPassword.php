<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
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
		
		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
			[ [ 'email', 'password', 'password_repeat' ], 'required' ],
			[ 'email', 'email' ],
			[ 'password', 'compare' ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

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