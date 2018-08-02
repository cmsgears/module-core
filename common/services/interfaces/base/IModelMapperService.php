<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

/**
 * The base model mapper service interface declares the methods available with all the model mapper services.
 *
 * @since 1.0.0
 */
interface IModelMapperService extends IMapperService {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType );

	// Read ---------------

	// Read - Models ---

	/**
	 * Find and return the mappings for given parent id. It's useful in cases where only
	 * single parent type is allowed.
	 *
	 * @param integer $parentId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByParentId( $parentId );

	/**
	 * Find and return the mappings for given parent type.
	 *
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByParentType( $parentType );

	/**
	 * Find and return the mappings for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByParent( $parentId, $parentType );

	/**
	 * Find and return the mappings for given model id.
	 *
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByModelId( $modelId );

	/**
	 * Find and return the mappings for given parent id, parent type and model id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByParentModelId( $parentId, $parentType, $modelId );

	/**
	 * Find and return the first mapping for given parent id, parent type and model id. It's
	 * useful for the cases where only one mapping is allowed for a parent and model.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getFirstByParentModelId( $parentId, $parentType, $modelId );

	/**
	 * Find and return the mappings for given model id and parent type.
	 *
	 * @param integer $modelId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByModelIdParentType( $modelId, $parentType );

	/**
	 * Find and return the mappings for given parent id, parent type, model id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @param integer $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getByParentModelIdType( $parentId, $parentType, $modelId, $type );

	/**
	 * Find and return the mapping for given parent id, parent type, model id and type. It's
	 * useful for the cases where only one mapping is allowed for a parent, model and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @param integer $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getFirstByParentModelIdType( $parentId, $parentType, $modelId, $type );

	/**
	 * Find and return the active mappings for given parent id. It's useful in cases where only
	 * single parent type is allowed. It must be used carefully.
	 *
	 * @param integer $parentId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByParentId( $parentId );

	/**
	 * Find and return the active mappings for given parent type.
	 *
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByParentType( $parentType );

	/**
	 * Find and return the active mappings for given parent id and parent type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByParent( $parentId, $parentType );

	/**
	 * Find and return the active mappings for model id.
	 *
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByModelId( $modelId );

	/**
	 * Find and return the active mappings for parent id, parent type and model id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByParentModelId( $parentId, $parentType, $modelId );

	/**
	 * Find and return the active mappings for parent id, parent type and model id. It's
	 * useful for the cases where only one active mapping is allowed for a parent and model.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getFirstActiveByParentModelId( $parentId, $parentType, $modelId );

	/**
	 * Find and return the active mappings for given mapped model id and parent type.
	 *
	 * @param integer $modelId
	 * @param string $parentType
	 * @return \cmsgears\core\common\models\base\ActiveRecord[]
	 */
	public function getActiveByModelIdParentType( $modelId, $parentType );

	/**
	 * Find and return the active mappings for given parent id, parent type, model id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @param integer $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );

	/**
	 * Find and return the active mapping for given parent id, parent type, model id and type. It's
	 * useful for the cases where only one mapping is allowed for a parent, model and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $modelId
	 * @param integer $type
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function getFirstActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function activate( $model );

	public function activateByModelId( $parentId, $parentType, $modelId, $type = null );

	public function disable( $model );

	public function disableByParent( $parentId, $parentType, $delete = false );

	public function disableByModelId( $parentId, $parentType, $modelId, $delete = false );

	// Delete -------------

	public function deleteByParent( $parentId, $parentType );

	public function deleteByModelId( $modelId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
