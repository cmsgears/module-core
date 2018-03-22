<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\base\IMultiSite;
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * ITemplateService provide service methods for template model.
 *
 * @since 1.0.0
 */
interface ITemplateService extends IEntityService, IData, IMultiSite, INameType, ISlugType {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getActiveByType( $type );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function switchFileRender( $model, $config = [] );

	public function switchGroupLayout( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
