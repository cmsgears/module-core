<?php
namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface ITemplateService extends INameTypeService, ISlugTypeService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getActiveByType( $type );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function switchFileRender( $model, $config = [] );

	public function switchGroupLayout( $model, $config = [] );

	// Delete -------------

}
