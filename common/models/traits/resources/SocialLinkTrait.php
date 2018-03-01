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

trait SocialLinkTrait {

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

	public function updateSocialLinks( $links ) {

		$map = [];

		if( isset( $links ) ) {

			foreach ( $links as $link ) {

				$map[ $link->sns ]	= $link;
			}
		}

		$this->updateDataMeta( CoreGlobal::DATA_SOCIAL_LINKS, $map );
	}

	public function updateSocialLink( $link ) {

		$map = $this->getSocialLinks();

		$map[ $link->sns ]	= $link;

		$this->updateSocialLinks( $map );
	}

	public function deleteSocialLink( $link ) {

		$map = $this->getSocialLinks();

		unset( $map[ $link->sns ] );

		$this->updateSocialLinks( $map );
	}
}
