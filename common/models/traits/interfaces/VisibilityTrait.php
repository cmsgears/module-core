<?php
namespace cmsgears\core\common\models\traits\interfaces;

// CMG Imports
use cmsgears\core\common\models\interfaces\IVisibility;

trait VisibilityTrait {

	public static $visibilityMap = [
		IVisibility::VISIBILITY_PRIVATE => 'Private',
		IVisibility::VISIBILITY_SECURED => 'Secured',
		IVisibility::VISIBILITY_PROTECTED => 'Protected',
		IVisibility::VISIBILITY_PUBLIC => 'Public'
	];

	public static $revVisibilityMap = [
		'Private' => IVisibility::VISIBILITY_PRIVATE,
		'Secured' => IVisibility::VISIBILITY_SECURED,
		'Protected' => IVisibility::VISIBILITY_PROTECTED,
		'Public' => IVisibility::VISIBILITY_PUBLIC
	];

	public static $urlRevVisibilityMap = [
		'private' => IVisibility::VISIBILITY_PRIVATE,
		'secured' => IVisibility::VISIBILITY_SECURED,
		'protected' => IVisibility::VISIBILITY_PROTECTED,
		'public' => IVisibility::VISIBILITY_PUBLIC
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

	public function isVisibilitySecured(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_SECURED;
		}

		return $this->visibility >= IVisibility::VISIBILITY_SECURED;
	}

	public function isVisibilityPublic(	 $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PUBLIC;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PUBLIC;
	}

	public function isVisibilityProtected(	$strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PROTECTED;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PROTECTED;
	}
}
