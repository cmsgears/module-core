<?php
namespace cmsgears\core\common\services\interfaces\resources;

interface IModelCommentService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getCommentPageByParent( $parentId, $parentType, $config = [] );

	public function getReviewPageByParent( $parentId, $parentType, $config = [] );

	public function getPageByParentType( $parentType, $config = [] );

	public function getPageByBaseId( $baseId, $config = [] );

	public function getPageForApproved( $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByUser( $parentId, $parentType );

	public function isExistByUser( $parentId, $parentType );

	public function getByParentConfig( $parentId, $config = [] );

	public function getByParentTypeConfig( $parentType, $config = [] );

	public function getByBaseId( $baseId, $config = [] );

	public function isExistByEmail( $email );

	public function getByEmail( $email );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function attachMedia( $model, $file, $mediaType, $parentType );

	// Update -------------

	public function updateStatus( $model, $status );

	public function approve( $model );

	public function block( $model );

	public function markSpam( $model );

	public function markTrash( $model );

	public function updateSpamRequest( $model );

	public function updateDeleteRequest( $model );

	// Delete -------------

}
