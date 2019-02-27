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
 * The IHierarchy declare the methods specific to manage hierarchical models.
 *
 * @since 1.0.0
 */
interface IHierarchy {

	/**
	 * Return the immediate parent model.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getParent();

	/**
	 * Return the immediate parent models.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getParents();

	/**
	 * Return the immediate child models.
	 *
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getChildren();

	/**
	 * Return the list of child id.
	 *
	 * @return array
	 */
	public function getChildrenIdList();
}
