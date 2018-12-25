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
 * The ITag declare the methods implemented by TagTrait. It can be implemented
 * by entities, resources and models which need multiple tags.
 *
 * @since 1.0.0
 */
interface ITag {

	/**
	 * Return all the tag mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelTag[]
	 */
	public function getModelTags();

	/**
	 * Return all the active tag mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelTag[]
	 */
	public function getActiveModelTags();

	/**
	 * Return the tag mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelTag[]
	 */
	public function getModelTagsByType( $type, $active = true );

	/**
	 * Return all the tags associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Tag[]
	 */
	public function getTags();

	/**
	 * Return all the active tags associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Tag[]
	 */
	public function getActiveTags();

	/**
	 * Return tags associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Tag[]
	 */
	public function getTagsByType( $type, $active = true );

	/**
	 * Generate and return the array having tag id.
	 *
	 * @param boolean $active
	 * @return integer[]
	 */
	public function getTagIdList( $active = true );

	/**
	 * Generate and return the array having tag name.
	 *
	 * @param boolean $active
	 * @return string[]
	 */
	public function getTagNameList( $active = true );

	/**
	 * Generate and return the array having tag id and name.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getTagIdNameList( $active = true );

	/**
	 * Generate and return the map having tag id and name.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getTagIdNameMap( $active = true );

	/**
	 * Generate and return the map having tag slug and name.
	 *
	 * @param boolean $active
	 * @return array
	 */
	public function getTagSlugNameMap( $active = true );

	/**
	 * Find and return csv of tag name associated with parent.
	 *
	 * @param integer $limit
	 * @param boolean $active
	 * @return string
	 */
	public function getTagCsv( $limit = 0, $active = true );

	/**
	 * Find and return anchor tags of tag link associated with parent.
	 *
	 * @param string $baseUrl
	 * @param string $wrapper
	 * @param integer $limit
	 * @param boolean $active
	 * @return string
	 */
	public function getTagLinks( $baseUrl, $config = [] );

}
