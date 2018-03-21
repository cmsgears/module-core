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
 * ISlugTypeService declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\SlugTypeTrait]].
 *
 * @since 1.0.0
 */
interface ISlugTypeService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getBySlug( $slug, $config = [] );

	public function getFirstBySlug( $slug, $config = [] );

	public function getBySlugType( $slug, $type, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	public function getSlugModelMap();

	public function getSlugModelMapByType( $type );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
