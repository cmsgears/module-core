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
use cmsgears\core\common\models\base\CoreTables;
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

		$fileTable	= CoreTables::getTableName( CoreTables::TABLE_FILE );

		return $this->hasOne( File::class, [ 'id' => 'avatarId' ] )->from( "$fileTable as avatar" );
	}

	/**
	 * @inheritdoc
	 */
	public function getAvatarUrl() {

		$avatar		= $this->avatar;
		$avatarUrl	= isset( $avatar ) ? $avatar->getFileUrl() : null;

		return $avatarUrl;
	}

	/**
	 * @inheritdoc
	 */
	public function getBanner() {

		$fileTable	= CoreTables::getTableName( CoreTables::TABLE_FILE );

		return $this->hasOne( File::class, [ 'id' => 'bannerId' ] )->from( "$fileTable as banner" );
	}

	/**
	 * @inheritdoc
	 */
	public function getBannerUrl() {

		$banner		= $this->banner;
		$bannerUrl	= isset( $banner ) ? $banner->getFileUrl() : null;

		return $bannerUrl;
	}

	/**
	 * @inheritdoc
	 */
	public function getVideo() {

		$fileTable	= CoreTables::getTableName( CoreTables::TABLE_FILE );

		return $this->hasOne( File::class, [ 'id' => 'videoId' ] )->from( "$fileTable as video" );
	}

	/**
	 * @inheritdoc
	 */
	public function getVideoUrl() {

		$video		= $this->video;
		$videoUrl	= isset( $video ) ? $video->getFileUrl() : null;

		return $videoUrl;
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
