<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * The IVisibility interface provide constants and methods specific to visibility attribute of a model.
 *
 * @since 1.0.0
 */
interface IVisibility {

	// Visibility
	const VISIBILITY_PRIVATE	=	 0; // Restricted to owner
	const VISIBILITY_SECURED	=  500; // Password protected
	const VISIBILITY_PROTECTED	= 1000;	// Restricted to logged in users
	const VISIBILITY_PUBLIC		= 1500;	// Open for all

	/**
	 * Return string representation of visibility.
	 *
	 * @return string
	 */
	public function getVisibilityStr();

	/**
	 * Check whether visibility is set to private.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVisibilityPrivate( $strict = true );

	/**
	 * Check whether visibility is set to secured.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVisibilitySecured( $strict = true );

	/**
	 * Check whether visibility is set to protected.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVisibilityProtected( $strict = true );

	/**
	 * Check whether visibility is set to public.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVisibilityPublic( $strict = true );

	/**
	 * Check whether model is visible. It defines the most generic visibility behavior.
	 * The model must implement the logic in case the generic visibility needs to be different.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVisible();

}
