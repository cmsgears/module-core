<?php
namespace cmsgears\core\common\services\interfaces\base;

interface IMapperService extends IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    public function getAllByModelId( $modelId );

	public function getByModelId( $parentId, $parentType, $modelId );

	public function getByParent( $parentId, $parentType );

	public function getByParentId( $parentId );

	public function getByParentType( $parentType );

	// Models having active column

	public function getActiveByParent( $parentId, $parentType );

	public function getActiveByParentId( $parentId );

	public function getActiveByParentType( $parentType );

    public function getActiveByModelIdParentType( $modelId, $parentType );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Models having active column

	public function activate( $model );

	public function deActivate( $model );

	// Delete -------------

	public function deleteByParent( $parentId, $parentType );

	public function deleteByModelId( $modelId );
}
