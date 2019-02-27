<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * The interface IFeatured provide methods to mark a model as featured or pinned.
 *
 * @since 1.0.0
 */
interface IFeatured {

	/**
	 * Returns string representation of pinned flag.
	 *
	 * @return string
	 */
	public function getPinnedStr();

	/**
	 * Returns string representation of featured flag.
	 *
	 * @return string
	 */
	public function getFeaturedStr();

}
