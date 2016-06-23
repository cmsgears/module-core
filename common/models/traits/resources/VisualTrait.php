<?php
namespace cmsgears\core\common\models\traits\resources;

use cmsgears\core\common\models\resources\File;

/**
 * VisualTrait can be used to assist models supporting avatar, banner, texture and video. It might be possible that few models support only one or two among these four.
 * CodeGenUtil got few utility methods to provide placeholder graphics in case a model does not have Avatar or Banner.
 */
trait VisualTrait {

	public function getAvatar() {

		return $this->hasOne( File::className(), [ 'id' => 'avatarId' ] );
	}

	public function getAvatarUrl() {

		$banner			= $this->avatar;
		$bannerUrl		= isset( $banner ) ? $banner->getFileUrl() : null;

		return $bannerUrl;
	}

	public function getBanner() {

		return $this->hasOne( File::className(), [ 'id' => 'bannerId' ] );
	}

	public function getBannerUrl() {

		$banner			= $this->banner;
		$bannerUrl		= isset( $banner ) ? $banner->getFileUrl() : null;

		return $bannerUrl;
	}

	public function getTexture() {

		return $this->hasOne( File::className(), [ 'id' => 'textureId' ] );
	}

	public function getTextureUrl() {

		$texture		= $this->texture;
		$textureUrl		= isset( $texture ) ? $texture->getFileUrl() : null;

		return $textureUrl;
	}

	public function getVideo() {

		return $this->hasOne( File::className(), [ 'id' => 'videoId' ] );
	}

	public function getVideoUrl() {

		$video			= $this->video;
		$videoUrl		= isset( $video ) ? $video->getFileUrl() : null;

		return $videoUrl;
	}
}

?>