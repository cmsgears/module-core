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
use cmsgears\core\common\services\interfaces\cache\IGridCacheable;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * ITemplateService provide service methods for template model.
 *
 * @since 1.0.0
 */
interface ITemplateService extends IEntityService, IData, IGridCacheable, IMultiSite, INameType, ISlugType {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getGlobalBySlugType( $slug, $type, $config = [] );

	public function getByThemeSlugType( $slug, $type, $config = [] );

	public function getActiveByType( $type );

	// Read - Lists ----

	// Read - Maps -----

	public function getFrontendIdNameMapByType( $type, $config = [] );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggleActive( $model, $config = [] );

	public function toggleFileRender( $model, $config = [] );

	public function toggleGroupLayout( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
