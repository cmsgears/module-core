<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

use cmsgears\core\common\models\base\Meta;

/**
 * The base meta service interface declares the methods available with all the meta services.
 *
 * @since 1.0.0
 */
interface IMetaService extends IResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByModelId( $modelId );

	public function getByName( $modelId, $name );

	public function getFirstByName( $modelId, $name );

	public function getByType( $modelId, $type );

	public function getByNameType( $modelId, $type, $name );

	public function initByNameType( $modelId, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT, $label = null );

	// Read - Lists ----

	// Read - Maps -----

	public function getNameValueMapByModelId( $modelId );

	public function getNameValueMapByType( $modelId, $type );

	public function getIdMetaMapByModelId( $modelId );

	public function getIdMetaMapByType( $modelId, $type );

	public function getNameMetaMapByType( $modelId, $type );

	// Read - Others ---

	// Create -------------

	/**
	 * It creates or update the $metas for given $parent.
	 * It also disable existing metas before updating in case type is provided.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $parent
	 * @param array $metas
	 * @param array $config
	 */
	public function creatOrUpdateByParent( $parent, $metas, $config = [] );

	// Update -------------

	public function toggle( $model );

	public function disableByType( $parent, $type );

	// Delete -------------

	public function deleteByModelId( $modelId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
