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
 * The IObject declare the methods implemented by ObjectTrait. It can be implemented
 * by entities, resources and models which need multiple objects.
 *
 * @since 1.0.0
 */
interface IObject {

	/**
	 * Return all the object mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelObject[]
	 */
	public function getModelObjects();

	/**
	 * Return all the active object mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelObject[]
	 */
	public function getActiveModelObjects();

	/**
	 * Return the object mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelObject[]
	 */
	public function getModelObjectsByType( $type, $active = true );

	/**
	 * Return all the objects associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\entities\ObjectData[]
	 */
	public function getObjects();

	/**
	 * Return all the active objects associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\entities\ObjectData[]
	 */
	public function getActiveObjects();

	/**
	 * Return objects associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\entities\ObjectData[]
	 */
	public function getObjectsByType( $type, $active = true );
}
