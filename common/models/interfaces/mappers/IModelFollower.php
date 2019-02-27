<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The IModelFollower declare the methods implemented by FollowerTrait. It can be implemented
 * by entities, resources and models which need followers.
 *
 * @since 1.0.0
 */
interface IModelFollower extends IFollower {

	/**
	 * Return all the file mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelFollower[]
	 */
	public function getModelFollowers();

	/**
	 * Return all the active file mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelFollower[]
	 */
	public function getActiveModelFollowers();

	/**
	 * Return the file mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelFollower[]
	 */
	public function getModelFollowersByType( $type, $active = true );

}
