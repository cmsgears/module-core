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
 * The IModelMeta declare the methods specific to model meta.
 *
 * @since 1.0.0
 */
interface IModelMeta {

	/**
	 * Return all the meta associated with parent model.
	 *
	 * @return \cmsgears\core\common\models\resources\ModelMeta[]
	 */
	public function getModelMetas();

	/**
	 * Return all the meta associated with parent model for given type.
	 *
	 * @param string $type
	 * @return \cmsgears\core\common\models\resources\ModelMeta[]
	 */
	public function getModelMetasByType( $type );

	/**
	 * Return the meta associated with parent model for given type and name.
	 *
	 * @param string $type
	 * @param string $name
	 * @return \cmsgears\core\common\models\resources\ModelMeta
	 */
	public function getModelMetaByTypeName( $type, $name );

	/**
	 * Generate and return the key value map of model meta.
	 *
	 * @return array
	 */
	public function getModelMetaNameValueMap();

	/**
	 * Generate and return the key value map of model meta using type.
	 *
	 * @return array
	 */
	public function getModelMetaNameValueMapByType( $type );

	/**
	 * Generate and return the meta map of model meta using type.
	 *
	 * @return array
	 */
	public function getModelMetaMapByType( $type );

	/**
	 * Update multiple meta value for given type.
	 *
	 * @param \cmsgears\core\common\models\resources\ModelMeta[] $metas
	 * @param string $type
	 * @return void
	 */
	public function updateModelMetas( $metas, $type = CoreGlobal::TYPE_CORE );
}
