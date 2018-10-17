<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\utilities\DateUtil;

/**
 * VerifyMobileTrait provide methods to verify model using OTP sent to registered mobile number.
 *
 * @since 1.0.0
 */
trait VerifyMobileTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VerifyMobileTrait ---------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	/**
	 * @inheritdoc
	 */
	public function setOtp( $model, $otp ) {

		$model->otp = $otp;

		$model->otpValidTill = DateUtil::addMillis( DateUtil::getDateTime(), Yii::$app->core->otpValidity );

		return parent::update( $model, [
			'attributes' => [ 'otp', 'otpValidTill' ]
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function verifyMobile( $model, $otp ) {

		if( $model->otp == $otp ) {

			$model->mobileVerified = true;

			$model->otpValidTill = null;

			$model->otp = null;

			$model->update();

			return true;
		}

		return false;
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// VerifyMobileTrait ---------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
