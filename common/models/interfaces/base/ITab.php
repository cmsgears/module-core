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
 * The ITab interface provide constants and methods specific to maintaining tab status
 * of models which need approval process and data will be collected using multiple tabs.
 *
 * @since 1.0.0
 */
interface ITab {

	/**
	 * Return the tab using the given status.
	 *
	 * @param type $status
	 */
	public function getTab( $status = null );

	/**
	 * Return the status of tab corresponding to current action.
	 *
	 * @return string
	 */
	public function getTabStatus();

	/**
	 * Return the next status of tab corresponding to current action.
	 *
	 * @param string $status
	 * @return string
	 */
	public function getNextStatus( $status = null );

	/**
	 * Return the previous tab corresponding to current action.
	 *
	 * @return string
	 */
	public function getPreviousTab();

	/**
	 * Return the next tab corresponding to current action.
	 *
	 * @return string
	 */
	public function getNextTab();
}
