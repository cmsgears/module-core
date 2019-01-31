<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ContentTrait can be used to add content feature to relevant models.
 */
trait ContentTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// ContentTrait --------------------------

	public function getSmallContent( $ellipsis = true ) {

		$content = $this->content;

		if( strlen( $content ) > CoreGlobal::TEXT_SMALL ) {

			$content = substr( $content, 0, CoreGlobal::TEXT_SMALL );

			return $ellipsis ? "$content ..." : $content;
		}

		return $content;
	}

	public function getMediumContent( $ellipsis = true ) {

		$content = $this->content;

		if( strlen( $content ) > CoreGlobal::TEXT_MEDIUM ) {

			$content = substr( $content, 0, CoreGlobal::TEXT_MEDIUM );

			return $ellipsis ? "$content ..." : $content;
		}

		return $content;
	}

	public function getLargeContent( $ellipsis = true ) {

		$content = $this->content;

		if( strlen( $content ) > CoreGlobal::TEXT_LARGE ) {

			$content = substr( $content, 0, CoreGlobal::TEXT_LARGE );

			return $ellipsis ? "$content ..." : $content;
		}

		return $content;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ContentTrait --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
