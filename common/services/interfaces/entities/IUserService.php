<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\base\IApprovalService;
use cmsgears\core\common\services\interfaces\base\IModelMetaService;

interface IUserService extends IApprovalService, IModelMetaService {

    // Data Provider ------

    public function getPageByRoleType( $roleType );

    public function getPageByAdmins();

    public function getPageByUsers();

    // Read ---------------

    // Read - Models ---

    public function getByAccessToken( $token );

    public function getByEmail( $email );

    public function isExistByEmail( $email );

    public function getByUsername( $username );

    public function isExistByUsername( $username );

    // Read - Lists ----

    // Read - Maps -----

    public function getIdNameMapByRoleSlug( $roleSlug );

    // Create -------------

    public function register( $registerForm );

    // Update -------------

    public function verify( $user, $token );

    public function activate( $user, $token, $resetForm );

    public function forgotPassword( $user );

    public function resetPassword( $user, $resetForm );

    public function updateAvatar( $user, $avatar );

    // Delete -------------

}
