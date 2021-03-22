<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

/**
 * The interface IShared provide methods to identify shared models among frontend and backend.
 *
 * @since 1.0.0
 */
interface IShared {

	public function getSharedPage( $config = [] );

	public function getBackendSharedPage( $config = [] );

	public function getDirectPage( $config = [] );

	public function getBackendDirectPage( $config = [] );

	/*
	 * Returns the collection made by the user.
	 *
	 * @param integer $ownerId
	 */
	public function getSharedPageByOwnerId( $ownerId, $config = [] );

}
