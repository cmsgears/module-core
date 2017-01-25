<?php
namespace cmsgears\core\common\models\interfaces;

interface IVisibility {

	// Visibility
	const VISIBILITY_PRIVATE	=	 0; // Restricted to owner
	const VISIBILITY_PROTECTED	= 1000;	// Restricted to logged in users
	const VISIBILITY_PUBLIC		= 1500;	// Open for all

	public function isVisibilityPrivate( $strict = true );

	public function isVisibilityPublic(	 $strict = true );

	public function isVisibilityProtected( $strict = true );
}
