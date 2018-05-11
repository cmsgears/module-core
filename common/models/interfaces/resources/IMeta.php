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
 * The IMeta declare the methods specific to meta table.
 *
 * @since 1.0.0
 */
interface IMeta {

	/**
	 * Return all the meta associated with model.
	 *
	 * @return \cmsgears\core\common\models\base\Meta[]
	 */
	public function getMetas();

	/**
	 * Return all the active meta associated with model.
	 *
	 * @return \cmsgears\core\common\models\base\Meta[]
	 */
	public function getActiveMetas();

	/**
	 * Return all the meta associated with model for given type.
	 *
	 * @param string $type
	 * @return \cmsgears\core\common\models\base\Meta[]
	 */
	public function getMetasByType( $type );

	/**
	 * Return the meta associated with model for given type and name.
	 *
	 * @param string $type
	 * @param string $name
	 * @return \cmsgears\core\common\models\base\Meta
	 */
	public function getMetaByTypeName( $type, $name );

	/**
	 * Generate and return the key value map of meta.
	 *
	 * @return array
	 */
	public function getMetaNameValueMap();

	/**
	 * Generate and return the key value map of meta using type.
	 *
	 * @return array
	 */
	public function getMetaNameValueMapByType( $type );

	/**
	 * Generate and return the meta map of meta using type.
	 *
	 * @return array
	 */
	public function getMetaMapByType( $type );

	/**
	 * Update multiple meta value for given type.
	 *
	 * @param \cmsgears\core\common\models\base\Meta[] $metas
	 * @param string $type
	 * @return void
	 */
	public function updateMetas( $metas, $type = CoreGlobal::TYPE_CORE );

}
