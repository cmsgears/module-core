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

	public function initByNameType( $modelId, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT, $label = null, $icon = null );

	// Read - Lists ----

	// Read - Maps -----

	public function getNameValueMapByModelId( $modelId, $config = [] );

	public function getNameValueMapByType( $modelId, $type, $config = [] );

	public function getIdMetaMapByModelId( $modelId, $config = [] );

	public function getIdMetaMapByType( $modelId, $type, $config = [] );

	public function getNameMetaMapByType( $modelId, $type, $config = [] );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function creatOrUpdateByParent( $parent, $metas, $config = [] );

	public function toggleActive( $model );

	public function toggleValue( $model );

	public function disableByType( $parent, $type );

	// Delete -------------

	public function deleteByModelId( $modelId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
