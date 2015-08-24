<?php
namespace cmsgears\core\frontend\models\forms;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\base\Model;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\UserService;

class Register extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;
	public $password;
	public $password_repeat;
	public $username;
	public $firstName;
	public $lastName;
	public $mobile;
	public $terms;
	public $newsletter;

	// Instance Methods --------------------------------------------
	
	// yii\base\Model

	public function rules() {
		
		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat', 'username', 'mobile', 'firstName', 'lastName' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
			[ [ 'email', 'password', 'password_repeat', 'terms' ], 'required' ],
			[ 'email', 'email' ],
			[ 'password', 'compare' ],
			[ 'password', 'password' ],
			[ 'email', 'validateEmail' ],
			[ 'username', 'validateUsername' ],
			[ 'username', 'alphanumdotu' ],
			[ 'mobile', 'phone' ],
			[ [ 'firstName', 'lastName' ], 'alphanumspace' ],
			[ 'terms', 'termsValidator' ],
			[ 'newsletter', 'safe' ]
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
			'password_repeat' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT ),
			'username' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'firstName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'terms' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TERMS ),
			'newsletter' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NEWSLETTER )
		];
	}

    public function validateEmail( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( UserService::isExistByEmail( $this->email ) ) {
            	
				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

    public function validateUsername( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( UserService::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

	public function termsValidator( $attribute, $params ) {

		if( !isset( $this->terms ) || strlen( $this->terms ) <= 0 ) {

			$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_TERMS ) );
		}
	}
}

?>