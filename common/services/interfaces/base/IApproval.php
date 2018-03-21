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
 * IApproval declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\ApprovalTrait]].
 *
 * @since 1.0.0
 */
interface IApproval {

	// Data Provider ------

	public function getPageByOwnerId( $ownerId, $config = [] );

	public function getPageByOwnerIdStatus( $ownerId, $status, $config = [] );

	public function getPageByAuthorityId( $id, $config = [] );

	public function getPageByAuthorityIdStatus( $id, $status, $config = [] );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function submit( $model, $public = true );

	public function confirm( $model, $public = true );

	public function approve( $model, $public = true );

	public function reject( $model, $message = null );

	public function freeze( $model, $message = null );

	public function block( $model, $message = null );

	public function terminate( $model, $message = null );

	public function setRejectMessage( $model, $message = null );

	public function setTerminateMessage( $model, $message = null );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
