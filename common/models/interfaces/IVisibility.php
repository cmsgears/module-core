<?php
namespace cmsgears\core\common\models\interfaces;

interface IVisibility {

	// Visibility
    const VISIBILITY_PUBLIC     =    0; // Open for all
    const VISIBILITY_PROTECTED	= 1000;	// Restricted to logged in users
    const VISIBILITY_PRIVATE	= 1500;	// Restricted to owner

	public function isVisibilityPublic(  $strict = true );

	public function isVisibilityProtected( $strict = true );

	public function isVisibilityPrivate( $strict = true );
}

?>