<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

/**
 * VerifyEmailTrait provide methods to verify model using verification token sent via email.
 *
 * @since 1.0.0
 */
trait VerifyEmailTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VerifyEmailTrait ----------------------

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
	public function generateVerifyToken( $model ) {

		$model->generateVerifyToken();

		return parent::update( $model, [
			'attributes' => [ 'verifyToken', 'verifyTokenValidTill' ]
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function validateVerifyToken( $model, $token ) {

		// Check Token
		if( $model->isVerifyTokenValid( $token ) ) {

			$model->emailVerified = true;

			$model->verifyToken = null;

			$model->verifyTokenValidTill = null;

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

	// VerifyEmailTrait ----------------------

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
