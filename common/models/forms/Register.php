<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\entities\UserService;

class Register extends \yii\base\Model {

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
    public $username;
    public $firstName;
    public $lastName;
    public $mobile;
    public $terms;

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
			[ [ 'email', 'password', 'password_repeat', 'terms' ], 'required' ],
			[ [ 'firstName', 'lastName' ], 'required', 'on' => [ 'name' ] ],
			[ 'email', 'email' ],
			[ 'password', 'compare' ],
			[ 'password', 'password' ],
			[ 'email', 'validateEmail' ],
			[ 'username', 'validateUsername' ],
			[ 'username', 'alphanumdotu' ],
			[ 'mobile', 'phone' ],
			[ [ 'firstName', 'lastName' ], 'alphanumspace' ],
			[ 'terms', 'termsValidator' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat', 'username', 'mobile', 'firstName', 'lastName' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'password' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD ),
			'password_repeat' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT ),
			'username' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'terms' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TERMS )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    public function validateEmail( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( UserService::isExistByEmail( $this->email ) ) {

                $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

    public function validateUsername( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( UserService::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

    public function termsValidator( $attribute, $params ) {

        if( !isset( $this->terms ) || strlen( $this->terms ) <= 0 ) {

            $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_TERMS ) );
        }
    }

	// Register ------------------------------

}
