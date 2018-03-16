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
 * The IModelMapper interface provide methods specific to mapping parent model to model.
 *
 * @since 1.0.0
 */
interface IModelMapper {

	/**
	 * Check parent using given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return boolean
	 */
	public function isParentValid( $parentId, $parentType );

	/**
	 * Check whether mapping is active using given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return boolean
	 */
	public function isMappingActive( $parentId, $parentType );
}