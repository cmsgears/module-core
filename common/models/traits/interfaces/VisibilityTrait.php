<?php
namespace cmsgears\core\common\models\traits\interfaces;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IVisibility;

trait VisibilityTrait {

    public static $visibilityMap = [
        IVisibility::VISIBILITY_PRIVATE => 'Private',
        IVisibility::VISIBILITY_PROTECTED => 'Protected',
        IVisibility::VISIBILITY_PUBLIC => 'Public'
    ];

    public function getVisibilityStr() {

        return self::$visibilityMap[ $this->visibility ];
    }

    public function isVisibilityPrivate(  $strict = true ) {

        if( $strict ) {

            return $this->visibility == IVisibility::VISIBILITY_PRIVATE;
        }

        return $this->visibility >= IVisibility::VISIBILITY_PRIVATE;
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
}
