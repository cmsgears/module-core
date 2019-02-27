<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\resources;

/**
 * Used by services with base model having social link trait.
 *
 * @since 1.0.0
 */
trait SocialLinkTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getSocialLinks( $model ) {

		$model->getSocialLinks();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateSocialLinks( $model, $links ) {

		$model->updateSocialLinks( $links );
	}

	public function updateSocialLink( $model, $link ) {

		$model->updateSocialLink( $link );
	}

	// Delete -------------

	public function deleteSocialLink( $model, $link ) {

		$model->deleteSocialLink( $link );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

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
