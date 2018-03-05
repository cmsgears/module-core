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
 * The IFile declare the methods implemented by FileTrait. It can be implemented
 * by entities, resources and models which need multiple files.
 *
 * @since 1.0.0
 */
interface IFile {

	/**
	 * Return all the file mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelFile[]
	 */
	public function getModelFiles();

	/**
	 * Return all the active file mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelFile[]
	 */
	public function getActiveModelFiles();

	/**
	 * Return the file mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelFile[]
	 */
	public function getModelFilesByType( $type, $active = true );

	/**
	 * Return all the files associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\File[]
	 */
	public function getFiles();

	/**
	 * Return all the active files associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\File[]
	 */
	public function getActiveFiles();

	/**
	 * Return files associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\File[]
	 */
	public function getFilesByType( $type, $active = true );

	/**
	 * Return file associated with the parent for given title. It's useful only in cases
	 * where unique title is allowed.
	 *
	 * @param string $title
	 * @return \cmsgears\core\common\models\resources\File
	 */
	public function getFileByTitle( $title );
}
