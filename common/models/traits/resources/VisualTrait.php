<?php
namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\File;

/**
 * VisualTrait can be used to assist models supporting avatar, banner, texture or video.
 * It might be possible that few models support only one or two among these four.
 * CodeGenUtil provides utility methods to generate placeholder graphics in case a model
 * does not have Avatar or Banner.
 */
trait VisualTrait {

	public function getAvatar() {

		$fileTable	= CoreTables::TABLE_FILE;

		return $this->hasOne( File::className(), [ 'id' => 'avatarId' ] )->from( "$fileTable as avatar" );
	}

	public function getAvatarUrl() {

		$avatar			= $this->avatar;
		$avatarUrl		= isset( $avatar ) ? $avatar->getFileUrl() : null;

		return $avatarUrl;
	}

	public function getBanner() {

		$fileTable	= CoreTables::TABLE_FILE;

		return $this->hasOne( File::className(), [ 'id' => 'bannerId' ] )->from( "$fileTable as banner" );
	}

	public function getBannerUrl() {

		$banner			= $this->banner;
		$bannerUrl		= isset( $banner ) ? $banner->getFileUrl() : null;

		return $bannerUrl;
	}

	public function getTexture() {

		$fileTable	= CoreTables::TABLE_FILE;

		return $this->hasOne( File::className(), [ 'id' => 'textureId' ] )->from( "$fileTable as texture" );
	}

	public function getTextureUrl() {

		$texture		= $this->texture;
		$textureUrl		= isset( $texture ) ? $texture->getFileUrl() : null;

		return $textureUrl;
	}

	public function getVideo() {

		$fileTable	= CoreTables::TABLE_FILE;

		return $this->hasOne( File::className(), [ 'id' => 'videoId' ] )->from( "$fileTable as video" );
	}

	public function getVideoUrl() {

		$video			= $this->video;
		$videoUrl		= isset( $video ) ? $video->getFileUrl() : null;

		return $videoUrl;
	}
}
