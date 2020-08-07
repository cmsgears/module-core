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
 * The IGallery declare the methods implemented by GalleryTrait. It can be implemented
 * by entities, resources and models which need multiple galleries.
 *
 * @since 1.0.0
 */
interface IGallery {

	/**
	 * Return all the gallery mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelGallery]
	 */
	public function getModelGalleries();

	/**
	 * Return all the active gallery mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelGallery]
	 */
	public function getActiveModelGalleries();

	/**
	 * Return the gallery mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelGallery]
	 */
	public function getModelGalleriesByType( $type, $active = true );

	/**
	 * Return all the galleries associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Gallery[]
	 */
	public function getGalleries();

	/**
	 * Return all the active galleries associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Gallery[]
	 */
	public function getActiveGalleries();

	/**
	 * Return galleries associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Gallery[]
	 */
	public function getGalleriesByType( $type, $active = true );

	/**
	 * Return gallery associated with the parent for given code.
	 *
	 * @param string $code
	 * @return \cmsgears\core\common\models\resources\Gallery
	 */
	public function getGalleryByCode( $code );

	// Useful for models having single gallery mapped via $galleryId.

	/**
	 * Check whether gallery is assigned to the model using [[$galleryId]] attribute.
	 *
	 * @return boolean
	 */
	public function hasGallery();

	/**
	 * Return gallery associated with the model using [[$galleryId]] attribute.
	 *
	 * @return \cmsgears\core\common\models\resources\Gallery
	 */
	public function getGallery();

}
