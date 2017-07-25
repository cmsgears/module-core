<?php
namespace cmsgears\core\common\services\interfaces\mappers;

interface IModelTagService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createFromArray( $parentId, $parentType, $tags );

	public function createFromCsv( $parentId, $parentType, $tags );

	public function createFromJson( $parentId, $parentType, $tags );

	// Update -------------

	public function bindTags( $parentId, $parentType, $config = [] );

	// Delete -------------

	public function deleteByTagSlug( $parentId, $parentType, $tagSlug, $delete = false );
}
