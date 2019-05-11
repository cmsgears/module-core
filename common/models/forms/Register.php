<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\forms\BaseForm;

/**
 * Used to register users.
 *
 * @property string $email
 * @property string $password
 * @property string $password_repeat
 * @property string $username
 * @property string $type
 * @property integer $localeId
 * @property integer $genderId
 * @property integer $templateId
 * @property string $title
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $dob
 * @property string $mobile
 * @property string $phone
 * @property string $description
 * @property string $timeZone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property boolean $terms
 *
 * @since 1.0.0
 */
class Register extends BaseForm {

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

	public $type;

	public $localeId;
	public $genderId;
	public $templateId;

	public $title;
	public $firstName;
	public $middleName;
	public $lastName;

	public $dob;
	public $mobile;
	public $phone;
	public $description;
	public $timeZone;
	public $avatarUrl;
	public $websiteUrl;

	public $terms;

	// Protected --------------

	protected $userService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	/**
	 * @inheritdoc
	 */
	public function init() {

		parent::init();

		$this->userService = Yii::$app->factory->get( 'userService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'email', 'password', 'password_repeat', 'terms' ], 'required' ],
			[ [ 'firstName', 'lastName' ], 'required', 'on' => [ 'name' ] ],
			[ [ 'username' ], 'required', 'on' => [ 'username' ] ],
			[ 'email', 'email' ],
			[ 'password_repeat', 'compare', 'compareAttribute' => 'password' ],
			[ 'password', 'password' ],
			[ 'email', 'validateEmail' ],
			[ 'username', 'validateUsername' ],
			[ 'username', 'alphanumdotu' ],
			[ [ 'mobile', 'phone' ], 'phone' ],
			[ [ 'firstName', 'middleName', 'lastName' ], 'alphanumspace' ],
			[ 'terms', 'termsValidator' ],
			// Text Limit
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'username', 'mobile', 'phone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'email', 'firstName', 'middleName', 'lastName', 'timeZone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'localeId', 'genderId', 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ 'dob', 'date' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password', 'password_repeat', 'username', 'mobile', 'phone', 'firstName', 'middleName', 'lastName', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'genderId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GENDER ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'password' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD ),
			'password_repeat' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT ),
			'username' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'type' => 'User Type',
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'middleName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MIDDLENAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'dob' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DOB ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'timeZone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TIME_ZONE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'terms' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TERMS )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	/**
	 * Check whether the email is available.
	 *
	 * @param string $attribute
	 * @param array $params
	 */
	public function validateEmail( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			if( $this->userService->isExistByEmail( $this->email ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
		}
	}

	/**
	 * Check whether the username is available.
	 *
	 * @param string $attribute
	 * @param array $params
	 */
	public function validateUsername( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			if( $this->userService->isExistByUsername( $this->username ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
		}
	}

	/**
	 * Check whether user agreed to terms and conditions.
	 *
	 * @param string $attribute
	 * @param array $params
	 */
	public function termsValidator( $attribute, $params ) {

		if( $this->terms === 'on' ) {

			return;
		}

		if( !isset( $this->terms ) || $this->terms <= 0 ) {

			$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_TERMS ) );
		}
	}

	// Register ------------------------------

}
