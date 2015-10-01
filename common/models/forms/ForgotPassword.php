<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\base\Model;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ForgotPassword extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;

	// Instance Methods --------------------------------------------
	
	// yii\base\Model

	public function rules() {
		
		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
			[ [ 'email' ], 'required' ],
			[ 'email', 'email' ],
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL )
		];
	}
}

?>