<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelCommentService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getCommentPageByParent( $parentId, $parentType, $config = [] );

	public function getReviewPageByParent( $parentId, $parentType, $config = [] );

	public function getPageByParentType( $parentType, $config = [] );

	public function getPageByBaseId( $baseId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByParentConfig( $parentId, $config = [] );

	public function getByParentTypeConfig( $parentType, $config = [] );

	public function getByBaseId( $baseId, $config = [] );

	public function isExistByEmail( $email );

	public function getByEmail( $email );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function attachMedia( $parent, $file, $mediaType, $type );

	// Update -------------

	public function updateSpamRequest( $model );

	public function updateDeleteRequest( $model );

	public function updateStatus( $model, $status );

	public function approve( $model );

	public function block( $model );

	public function markSpam( $model );

	public function markDelete( $model );

	// Delete -------------

}
