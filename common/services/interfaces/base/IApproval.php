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

	public function getByStatus( $status, $config = [] );

	public function getActive( $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getApprovalNotificationMap();

	public function getStatusCountByUserId( $userId, $config = [] );

	public function getStatusCountByAuthorityId( $userId, $config = [] );

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function accept( $model, $config = [] );

	public function invite( $model, $config = [] );

	public function acceptInvite( $model, $config = [] );

	public function submit( $model, $config = [] );

	public function reject( $model, $config = [] );

	public function reSubmit( $model, $config = [] );

	public function confirm( $model, $config = [] );

	public function approve( $model, $config = [] );

	public function activate( $model, $config = [] );

	public function freeze( $model, $config = [] );

	public function upliftFreeze( $model, $config = [] );

	public function block( $model, $config = [] );

	public function upliftBlock( $model, $config = [] );

	public function terminate( $model, $config = [] );

	public function checkStatusChange( $model, $oldStatus, $config = [] );

	public function softDeleteNotify( $model, $config = [] );

	public function toggleFrojen( $model, $config = [] );

	public function toggleBlock( $model, $config = [] );

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
