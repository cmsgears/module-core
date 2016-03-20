<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models which required high level of security while editing or updating by site users.
 */
interface IOwner {

	// The method checks ownership for given user. In case user is not provided, current logged in user can be considered depending on model implementation. 
	public function isOwner( $user = null );
}

?>