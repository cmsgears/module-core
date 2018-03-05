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
 * The IOption declare the methods implemented by OptionTrait. It can be implemented
 * by entities, resources and models which need multiple options. The options will be
 * categorized using categories.
 *
 * @since 1.0.0
 */
interface IOption {

	/**
	 * Return all the option mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelOption[]
	 */
	public function getModelOptions();

	/**
	 * Return all the active option mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelOption[]
	 */
	public function getActiveModelOptions();

	/**
	 * Return the option mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelOption[]
	 */
	public function getModelOptionsByType( $type, $active = true );

	/**
	 * Return all the options associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Option[]
	 */
	public function getOptions();

	/**
	 * Return all the active options associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Option[]
	 */
	public function getActiveOptions();

	/**
	 * Return options associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Option[]
	 */
	public function getOptionsByType( $type, $active = true );

	// Category specific methods

	/**
	 * Generate and return the option id array using category id.
	 *
	 * @param integer $categoryId
	 * @param active $active
	 */
	public function getOptionIdListByCategoryId( $categoryId, $active = true );

	/**
	 * Generate and return the option name csv using category id.
	 *
	 * @param integer $categoryId
	 * @param active $active
	 */
	public function getOptionsCsvByCategoryId( $categoryId, $active = true );

	/**
	 * Generate and return the option array using category id.
	 *
	 * @param integer $categoryId
	 * @param active $active
	 */
	public function getOptionsByCategoryId( $categoryId, $active = true );
}
