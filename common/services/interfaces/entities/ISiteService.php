<?php
namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports


use cmsgears\core\common\services\interfaces\base\INameService;
use cmsgears\core\common\services\interfaces\base\ISlugService;

interface ISiteService extends INameService, ISlugService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getIdMetaMapBySlug( $slug );

	public function getMetaMapBySlugType( $slug, $type );

	public function getMetaNameValueMapBySlugType( $slug, $type );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
