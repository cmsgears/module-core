<?php
namespace cmsgears\core\common\models\traits\interfaces;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IVisibility;

trait VisibilityTrait {

    public static $visibilityMap = [
        IVisibility::VISIBILITY_PUBLIC => 'Public',
        IVisibility::VISIBILITY_PROTECTED => 'Protected',
        IVisibility::VISIBILITY_PRIVATE => 'Private'
    ];

	public function getVisibilityStr() {

		return self::$visibilityMap[ $this->visibility ];
	}

	public function isVisibilityPublic(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PUBLIC;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PUBLIC;
	}

	public function isVisibilityProtected(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PROTECTED;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PROTECTED;
	}

	public function isVisibilityPrivate(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PRIVATE;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PRIVATE;
	}
}

?>