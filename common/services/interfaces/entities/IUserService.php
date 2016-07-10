<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IUserService extends \cmsgears\core\common\services\interfaces\base\IApprovalService {

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

	public function getAttributeMapByType( $user, $type );

	// Create -------------

	public function register( $registerForm );

	// Update -------------

	public function updateModelAttributes( $user, $attributes );

    public function activate( $user, $resetForm, $activate = true );

	public function verify( $user, $token, $activate = true );

    public function forgotPassword( $user );

    public function resetPassword( $user, $resetForm, $activate = true );

    public function updateAvatar( $user, $avatar );

	// Delete -------------

}
