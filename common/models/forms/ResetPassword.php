<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ResetPassword extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $email;
	public $password;
	public $password_repeat;
	public $oldPassword;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		$rules = [
			[ [ 'email', 'password', 'password_repeat' ], 'required' ],
			[ 'oldPassword', 'required', 'on' => [ 'oldPassword' ] ],
			[ 'password_repeat', 'compare', 'compareAttribute'=>'password' ],
			[ 'email', 'email' ],
			[ 'password', 'password' ],
			[ 'oldPassword', 'oldPasswordValidator' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat', 'oldPassword' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'password' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD ),
			'password_repeat' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT ),
			'oldPassword' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_OLD )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	public function oldPasswordValidator( $attribute, $params ) {

		$user	= Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			if( !Yii::$app->getSecurity()->validatePassword( $this->oldPassword, $user->passwordHash) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_OLD ) );
			}
		}
	}

	// ResetPassword -------------------------

}
