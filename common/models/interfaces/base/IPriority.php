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
 * The IPriority interface provide constants and methods specific to priority attribute of a model.
 *
 * @since 1.0.0
 */
interface IPriority {

	const PRIORITY_DEFAULT	=	 0;
	const PRIORITY_LOW		=  500;
	const PRIORITY_MEDIUM	= 1000;
	const PRIORITY_HIGH		= 1500;

	/**
	 * Return string representation of priority.
	 *
	 * @return string
	 */
	public function getPriorityStr();

	/**
	 * Check whether priority is set to default.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isPriorityDefault( $strict = true );

	/**
	 * Check whether priority is set to low.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isPriorityLow(	$strict = true );

	/**
	 * Check whether priority is set to medium.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isPriorityMedium( $strict = true );

	/**
	 * Check whether priority is set to high.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isPriorityHigh( $strict = true );
}
