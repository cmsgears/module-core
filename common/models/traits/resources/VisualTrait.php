<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\models\resources\File;

/**
 * VisualTrait can be used to assist models supporting avatar, banner or video.
 * It might be possible that few models support only one or two among these assets.
 *
 * CodeGenUtil provides utility methods to generate placeholder graphics in case a model
 * does not have Avatar or Banner.
 */
trait VisualTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// VisualTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function getAvatar() {

		$fileTable	= File::tableName();

		return $this->hasOne( File::class, [ 'id' => 'avatarId' ] )->from( "$fileTable as avatar" );
	}

	/**
	 * @inheritdoc
	 */
	public function getAvatarUrl() {

		return isset( $this->avatar ) ? $this->avatar->getFileUrl() : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getBanner() {

		$fileTable	= File::tableName();

		return $this->hasOne( File::class, [ 'id' => 'bannerId' ] )->from( "$fileTable as banner" );
	}

	/**
	 * @inheritdoc
	 */
	public function getBannerUrl() {

		return isset( $this->banner ) ? $this->banner->getFileUrl() : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getMobileBanner() {

		$fileTable	= File::tableName();

		return $this->hasOne( File::class, [ 'id' => 'mbannerId' ] )->from( "$fileTable as mbanner" );
	}

	/**
	 * @inheritdoc
	 */
	public function getMobileBannerUrl() {

		return isset( $this->mobileBanner ) ? $this->mobileBanner->getFileUrl() : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getVideo() {

		$fileTable	= File::tableName();

		return $this->hasOne( File::class, [ 'id' => 'videoId' ] )->from( "$fileTable as video" );
	}

	/**
	 * @inheritdoc
	 */
	public function getVideoUrl() {

		return isset( $this->video ) ? $this->video->getFileUrl() : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getDocument() {

		$fileTable = File::tableName();

		return $this->hasOne( File::class, [ 'id' => 'documentId' ] )->from( "$fileTable as document" );
	}

	/**
	 * @inheritdoc
	 */
	public function getDocumentUrl() {

		return isset( $this->document ) ? $this->document->getFileUrl() : null;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// VisualTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
