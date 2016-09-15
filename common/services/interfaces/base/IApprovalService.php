<?php
namespace cmsgears\core\common\services\interfaces\base;

interface IApprovalService extends IEntityService {

    // Data Provider ------

    public function getPageByOwnerId( $ownerId, $config = [] );

    public function getPageByOwnerIdStatus( $ownerId, $status, $config = [] );

    public function getPageByAuthorityId( $id, $config = [] );

    public function getPageByAuthorityIdStatus( $id, $status, $config = [] );

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    public function updateStatus( $model, $status );

    public function confirm( $model, $public = true );

    public function approve( $model, $public = true );

    public function setRejectMessage( $model, $message = null );

    public function reject( $model, $message = null );

    public function freeze( $model, $message = null );

    public function block( $model, $message = null );

    // Delete -------------

}
