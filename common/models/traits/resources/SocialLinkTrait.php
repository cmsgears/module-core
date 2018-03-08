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

use cmsgears\core\common\models\forms\SocialLink;

/**
 * SocialLinkTrait provide options to manage social links associated with a model using
 * [[$data]] attribute. The model must also use [[DataTrait]].
 */
trait SocialLinkTrait {

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

	// SocialLinkTrait -----------------------

	/**
	 * @inheritdoc
	 */
	public function getSocialLinks() {

		$links 	= $this->getDataMeta( CoreGlobal::DATA_SOCIAL_LINKS );
		$map	= [];

		if( isset( $links ) ) {

			foreach ( $links as $link ) {

				$socialLink					= new SocialLink( $link );
				$map[ $socialLink->sns ]	= $socialLink;
			}
		}

		return $map;
	}

	/**
	 * @inheritdoc
	 */
	public function updateSocialLinks( $links ) {

		$map = [];

		if( isset( $links ) ) {

			foreach ( $links as $link ) {

				$map[ $link->sns ]	= $link;
			}
		}

		$this->updateDataMeta( CoreGlobal::DATA_SOCIAL_LINKS, $map );
	}

	/**
	 * @inheritdoc
	 */
	public function updateSocialLink( $link ) {

		$map = $this->getSocialLinks();

		$map[ $link->sns ]	= $link;

		$this->updateSocialLinks( $map );
	}

	/**
	 * @inheritdoc
	 */
	public function deleteSocialLink( $link ) {

		$map = $this->getSocialLinks();

		unset( $map[ $link->sns ] );

		$this->updateSocialLinks( $map );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SocialLinkTrait -----------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
