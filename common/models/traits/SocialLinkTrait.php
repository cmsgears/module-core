<?php
namespace cmsgears\core\common\models\traits;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\SocialLink;

trait SocialLinkTrait {

	public function getSocialLinks() {

		$links 			= $this->getDataMeta( CoreGlobal::DATA_SOCIAL_LINKS );
		$linkObjects	= [];

		if( isset( $links ) ) {

			foreach ( $links as $link ) {

				$linkObjects[]	= new SocialLink( $link );
			}
		}

		return $linkObjects;
	}
}
