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
 * Used by services with base model having visual trait.
 *
 * @since 1.0.0
 */
trait VisualTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

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

	public function updateAvatar( $model, $avatar ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		return parent::update( $model, [
			'attributes' => [ 'avatarId' ]
		]);
	}

	public function updateBanner( $model, $banner ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'bannerId' => $banner ] );

		return parent::update( $model, [
			'attributes' => [ 'bannerId' ]
		]);
	}

	public function updateVideo( $model, $video ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'videoId' => $video ] );

		return parent::update( $model, [
			'attributes' => [ 'videoId' ]
		]);
	}

	// Delete -------------

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
