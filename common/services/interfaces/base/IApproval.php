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

	public function getCountsByOwnerId( $ownerId, $config = [] );

	public function getCountsByAuthorityId( $id, $config = [] );

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function submit( $model, $notify = true, $config = [] );

	public function reject( $model, $notify = true, $config = [] );

	public function reSubmit( $model, $notify = true, $config = [] );

	public function confirm( $model, $notify = true, $config = [] );

	public function approve( $model, $notify = true, $config = [] );

	public function activate( $model, $notify = true, $config = [] );

	public function freeze( $model, $notify = true, $config = [] );

	public function upliftFreeze( $model, $notify = true, $config = [] );

	public function block( $model, $notify = true, $config = [] );

	public function upliftBlock( $model, $notify = true, $config = [] );

	public function terminate( $model, $notify = true, $config = [] );

	public function softDelete( $model, $notify = true, $config = [] );

	public function toggleFrojen( $model, $notify = true, $config = [] );

	public function toggleBlock( $model, $notify = true, $config = [] );

	public function getRejectMessage( $model );

	public function setRejectMessage( $model, $message = null );

	public function getTerminateMessage( $model );

	public function setTerminateMessage( $model, $message = null );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
