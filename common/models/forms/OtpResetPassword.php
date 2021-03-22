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

/**
 * Used to submit password reset request using otp.
 *
 * @property integer $otp
 * @property string $password
 * @property string $password_repeat
 *
 * @since 1.0.0
 */
class OtpResetPassword extends BaseForm {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $otp;
	public $password;
	public $password_repeat;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

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
			[ [ 'otp', 'password', 'password_repeat' ], 'required' ],
			[ 'password_repeat', 'compare', 'compareAttribute' => 'password' ],
			// Others
			[ 'otp', 'number', 'integerOnly' => true, 'min' => 100000, 'max' => 999999 ],
			[ 'password', 'password' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'otp', 'password', 'password_repeat' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'otp' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OTP ),
			'password' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD ),
			'password_repeat' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PASSWORD_REPEAT )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// OtpResetPassword ----------------------

}
