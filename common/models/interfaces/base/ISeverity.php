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
 * The ISeverity interface provide constants and methods specific to severity attribute of a model.
 *
 * @since 1.0.0
 */
interface ISeverity {

	const SEVERITY_DEFAULT	=	 0;
	const SEVERITY_LOW		=  500;
	const SEVERITY_MEDIUM	= 1000;
	const SEVERITY_HIGH		= 1500;

	/**
	 * Return string representation of severity.
	 *
	 * @return string
	 */
	public function getSeverityStr();

	/**
	 * Check whether severity is set to default.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isSeverityDefault( $strict = true );

	/**
	 * Check whether severity is set to low.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isSeverityLow( $strict = true );

	/**
	 * Check whether severity is set to medium.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isSeverityMedium( $strict = true );

	/**
	 * Check whether severity is set to high.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isSeverityHigh( $strict = true );
}
