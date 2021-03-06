<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\resources;

// Yii Imports
use Yii;

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

	public function clearAvatar( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->avatar );

		$model->avatarId = null;

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

	public function clearBanner( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->banner );

		$model->bannerId = null;

		return parent::update( $model, [
			'attributes' => [ 'bannerId' ]
		]);
	}

	public function updateMobileBanner( $model, $banner ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'mbannerId' => $banner ] );

		return parent::update( $model, [
			'attributes' => [ 'mbannerId' ]
		]);
	}

	public function clearMobileBanner( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->mobileBanner );

		$model->mbannerId = null;

		return parent::update( $model, [
			'attributes' => [ 'mbannerId' ]
		]);
	}

	public function updateVideo( $model, $video ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'videoId' => $video ] );

		return parent::update( $model, [
			'attributes' => [ 'videoId' ]
		]);
	}

	public function clearVideo( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->video );

		$model->videoId = null;

		return parent::update( $model, [
			'attributes' => [ 'videoId' ]
		]);
	}


	public function updateMobileVideo( $model, $video ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'mvideoId' => $video ] );

		return parent::update( $model, [
			'attributes' => [ 'mvideoId' ]
		]);
	}

	public function clearMobileVideo( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->mobileBanner );

		$model->mvideoId = null;

		return parent::update( $model, [
			'attributes' => [ 'mvideoId' ]
		]);
	}

	public function updateDocument( $model, $document ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->saveFiles( $model, [ 'documentId' => $document ] );

		return parent::update( $model, [
			'attributes' => [ 'documentId' ]
		]);
	}

	public function clearDocument( $model ) {

		$fileService = Yii::$app->factory->get( 'fileService' );

		$fileService->delete( $model->document );

		$model->documentId = null;

		return parent::update( $model, [
			'attributes' => [ 'documentId' ]
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
