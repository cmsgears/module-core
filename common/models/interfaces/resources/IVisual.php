<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\resources;

/**
 * The IVisual declare the methods specific to avatar, banner, video and texture.
 *
 * @since 1.0.0
 */
interface IVisual {

	/**
	 * Returns the avatar associated with model.
	 *
	 * @return File
	 */
	public function getAvatar();

	/**
	 * Returns the avatar url associated with model.
	 *
	 * @return string
	 */
	public function getAvatarUrl();

	/**
	 * Returns the banner associated with model.
	 *
	 * @return File
	 */
	public function getBanner();

	/**
	 * Returns the banner url associated with model.
	 *
	 * @return string
	 */
	public function getBannerUrl();

	/**
	 * Returns the video associated with model.
	 *
	 * @return File
	 */
	public function getVideo();

	/**
	 * Returns the video url associated with model.
	 *
	 * @return string
	 */
	public function getVideoUrl();

}
