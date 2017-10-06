<?php
namespace cmsgears\core\common\models\traits;

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
