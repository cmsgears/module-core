<?php
namespace cmsgears\core\common\services\interfaces\base;

interface IResourceService extends IEntityService {

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    public function getByParent( $parentId, $parentType );

    public function getByParentId( $parentId );

    public function getByParentType( $parentType );

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    // Delete -------------

    public function deleteByParent( $parentId, $parentType );
}
