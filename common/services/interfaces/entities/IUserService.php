<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IApproval;
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\resources\IData;
use cmsgears\core\common\services\interfaces\resources\IModelMeta;
use cmsgears\core\common\services\interfaces\resources\ISocialLink;

/**
 * IUserService provide service methods for user model.
 *
 * @since 1.0.0
 */
interface IUserService extends IEntityService, IApproval, IData, IModelMeta, ISocialLink {

	// Data Provider ------

	public function getPageByType( $type, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByAccessToken( $token );

	public function getByEmail( $email );

	public function isExistByEmail( $email );

	public function getByUsername( $username );

	public function isExistByUsername( $username );

	public function getBySlug( $slug );

	public function isExistBySlug( $slug );

	public function getByMobile( $mobile );

	public function isExistByMobile( $mobile );

	// Read - Lists ----

	public function getIdNameListByUsername( $username, $config = [] );

	// Read - Maps -----

	public function searchByName( $name, $config = [] );

	public function searchByNameType( $name, $type, $config = [] );

	public function getIdNameMapByRoleSlug( $roleSlug );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function verify( $user, $token );

	public function reset( $user, $token, $resetForm, $activate = false );

	public function forgotPassword( $user );

	public function resetPassword( $user, $resetForm );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	public function checkRoleChange( $model, $oldRoleId );

	// Cache --------------

	// Additional ---------

}
