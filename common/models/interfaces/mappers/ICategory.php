<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The ICategory declare the methods implemented by CategoryTrait. It can be implemented
 * by entities, resources and models which need multiple categories.
 *
 * @since 1.0.0
 */
interface ICategory {

	/**
	 * Return all the category mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelCategory[]
	 */
	public function getModelCategories();

	/**
	 * Return all the active category mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelCategory[]
	 */
	public function getActiveModelCategories();

	/**
	 * Return the category mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelCategory[]
	 */
	public function getModelCategoriesByType( $type, $active = true );

	/**
	 * Return all the categories associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Category[]
	 */
	public function getCategories();

	/**
	 * Return all the active categories associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Category[]
	 */
	public function getActiveCategories();

	/**
	 * Return categories associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Category[]
	 */
	public function getCategoriesByType( $type, $active = true );

	/**
	 * Find and return list of category id associated with parent.
	 *
	 * @param boolean $active
	 * @return integer[]
	 */
	public function getCategoryIdList( $active = true );

	/**
	 * Find and return list of category id associated with parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return integer[]
	 */
	public function getCategoryIdListByType( $type, $active = true );

	/**
	 * Find and return list of category name associated with parent.
	 *
	 * @param boolean $active
	 * @param boolean $l1
	 * @return string[]
	 */
	public function getCategoryNameList( $active = true, $l1 = false );

	/**
	 * Find and return list of category name associated with parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @param boolean $l1
	 * @return string[]
	 */
	public function getCategoryNameListByType( $type, $active = true, $l1 = false );

	/**
	 * Find and return list of category id and name associated with parent.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getCategoryIdNameList( $active = true );

	/**
	 * Find and return map of category id and name associated with parent.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getCategoryIdNameMap( $active = true );

	/**
	 * Find and return map of category slug and name associated with parent.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getCategorySlugNameMap( $active = true );

	/**
	 * Find and return csv of category name associated with parent.
	 *
	 * @param integer $limit
	 * @param boolean $active
	 * @param boolean $l1
	 * @return string
	 */
	public function getCategoryCsv( $limit = 0, $active = true, $l1 = false );

	/**
	 * Find and return anchor tags of category link associated with parent.
	 *
	 * @param string $baseUrl
	 * @param string $wrapper
	 * @param integer $limit
	 * @param boolean $active
	 * @param boolean $l1
	 * @return string
	 */
	public function getCategoryLinks( $baseUrl, $wrapper = 'li', $limit = 0, $active = true, $l1 = false );
}
