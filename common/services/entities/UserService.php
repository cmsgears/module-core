<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\resources\IUserMetaService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\resources\MetaTrait;
use cmsgears\core\common\services\traits\resources\SocialLinkTrait;
use cmsgears\core\common\services\traits\resources\VisualTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * UserService provide service methods of user model.
 *
 * @since 1.0.0
 */
class UserService extends \cmsgears\core\common\services\base\EntityService implements IUserService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\User';

	public static $parentType = CoreGlobal::TYPE_USER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;
	private $metaService;

	// Traits ------------------------------------------------------

	use ApprovalTrait;
	use DataTrait;
	use GridCacheTrait;
	use MetaTrait;
	use SocialLinkTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, IUserMetaService $metaService, $config = [] ) {

		$this->fileService	= $fileService;
		$this->metaService	= $metaService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$siteTable	= Yii::$app->factory->get( 'siteService' )->getModelTable();
		$roleTable	= Yii::$app->factory->get( 'roleService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'locale' => [
					'asc' => [ "$modelTable.localeId" => SORT_ASC ],
					'desc' => [ "$modelTable.localeId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Locale'
				],
				'gender' => [
					'asc' => [ "$modelTable.genderId" => SORT_ASC ],
					'desc' => [ "$modelTable.genderId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Gender'
				],
				'marital' => [
					'asc' => [ "$modelTable.maritalId" => SORT_ASC ],
					'desc' => [ "$modelTable.maritalId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Marital Status'
				],
				'role' => [
					'asc' => [ "$roleTable.name" => SORT_ASC ],
					'desc' => [ "$roleTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Role'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'email' => [
					'asc' => [ "$modelTable.email" => SORT_ASC ],
					'desc' => [ "$modelTable.email" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Email'
				],
				'username' => [
					'asc' => [ "$modelTable.username" => SORT_ASC ],
					'desc' => [ "$modelTable.username" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Username'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'icon' => [
					'asc' => [ "$modelTable.icon" => SORT_ASC ],
					'desc' => [ "$modelTable.icon" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Icon'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'dob' => [
					'asc' => [ "$modelTable.dob" => SORT_ASC ],
					'desc' => [ "$modelTable.dob" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Birth Date'
				],
				'mobile' => [
					'asc' => [ "$modelTable.mobile" => SORT_ASC ],
					'desc' => [ "$modelTable.mobile" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Mobile'
				],
				'phone' => [
					'asc' => [ "$modelTable.phone" => SORT_ASC ],
					'desc' => [ "$modelTable.phone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Phone'
				],
				'tzone' => [
					'asc' => [ "$modelTable.timeZone" => SORT_ASC ],
					'desc' => [ "$modelTable.timeZone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Time Zone'
				],
	            'rdate' => [
	                'asc' => [ "$modelTable.registeredAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.registeredAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Registered At'
	            ],
	            'ldate' => [
	                'asc' => [ "$modelTable.lastLoginAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.lastLoginAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Last Login'
	            ],
	            'adate' => [
	                'asc' => [ "$modelTable.lastActivityAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.lastActivityAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Last Activity'
	            ]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'query' ] = $modelClass::queryWithSiteMembers();
		}

		$config[ 'conditions' ][ "$siteTable.slug" ] = Yii::$app->core->getSiteSlug();

		// Filters ----------

		// Filter - Status
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'message' => "$modelTable.message",
			'desc' => "$modelTable.description",
			'username' => "$modelTable.username",
			'email' => "$modelTable.email",
			'mobile' => "$modelTable.mobile",
			'phone' => "$modelTable.phone",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'locale' => "$modelTable.localeId",
			'gender' => "$modelTable.genderId",
			'marital' => "$modelTable.maritalId",
			'name' => "$modelTable.name",
			'status' => "$modelTable.status",
			'role' => "$roleTable.id",
			'message' => "$modelTable.message",
			'desc' => "$modelTable.description",
			'username' => "$modelTable.username",
			'email' => "$modelTable.email",
			'mobile' => "$modelTable.mobile",
			'phone' => "$modelTable.phone",
			'tzone' => "$modelTable.timeZone",
			'content' => "$modelTable.content"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByType( $type, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		return $this->getPage( $config );
	}

	public function getPageByRoleId( $roleId, $config = [] ) {

		$siteMemberTable = Yii::$app->factory->get( 'siteMemberService' )->getModelTable();

		$config[ 'conditions' ][ "$siteMemberTable.roleId" ] = $roleId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @param string $token
	 * @return User
	 */
	public function getByAccessToken( $token ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByAccessToken( $token );
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public function getByEmail( $email ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByEmail( $email );
	}

	/**
	 * @param string $email
	 * @return boolean
	 */
	public function isExistByEmail( $email ) {

		$modelClass	= static::$modelClass;

		$user = $modelClass::findByEmail( $email );

		return isset( $user );
	}

	/**
	 * @param string $username
	 * @return User
	 */
	public function getByUsername( $username ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByUsername( $username );
	}

	/**
	 * @param string $username
	 * @return boolean
	 */
	public function isExistByUsername( $username ) {

		$modelClass	= static::$modelClass;

		$user = $modelClass::findByUsername( $username );

		return isset( $user );
	}

	/**
	 * @param string $slug
	 * @return User
	 */
	public function getBySlug( $slug ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findBySlug( $slug );
	}

	/**
	 * @param string $slug
	 * @return boolean
	 */
	public function isExistBySlug( $slug ) {

		$modelClass	= static::$modelClass;

		$user = $modelClass::findBySlug( $slug );

		return isset( $user );
	}

	/**
	 * @param string $mobile
	 * @return User
	 */
	public function getByMobile( $mobile ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByMobile( $mobile );
	}

	/**
	 * @param string $mobile
	 * @return boolean
	 */
	public function isExistByMobile( $mobile ) {

		$modelClass	= static::$modelClass;

		$user = $modelClass::findByMobile( $mobile );

		return isset( $user );
	}

	// Read - Lists ----

	public function getIdNameListByUsername( $username, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();

		$config[ 'nameColumn' ]		= "$modelTable.id";
		$config[ 'valueColumn' ]	= "concat($modelTable.username, ', ', $modelTable.email)";

		$config[ 'query' ]->andWhere( "$modelTable.username LIKE :uname", [ ':uname' => "$username%" ] );

		return static::findIdNameList( $config );
	}

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name", "$modelTable.username", "$modelTable.email", "$modelTable.mobile" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'query' ]->andWhere( "$modelTable.name LIKE :name", [ ':name' => "$name%" ] );

		return static::searchModels( $config );
	}

	public function searchByNameType( $name, $type, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name", "$modelTable.username", "$modelTable.email", "$modelTable.mobile" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'query' ]->andWhere( "$modelTable.name LIKE :name AND type=:type", [ ':name' => "$name%", ':type' => $type ] );

		return static::searchModels( $config );
	}

	// Read - Maps -----

	/**
	 * @param string $roleSlug
	 * @return array
	 */
	public function getIdNameMapByRoleSlug( $roleSlug, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$roleTable			= Yii::$app->get( 'roleService' )->getModelTable();
		$siteMemberTable	= Yii::$app->get( 'siteMemberService' )->getModelTable();

		$query = $modelClass::find()
			->leftJoin( $siteMemberTable, "$siteMemberTable.userId = $modelTable.id" )
			->leftJoin( $roleTable, "$roleTable.id = $siteMemberTable.roleId" )
			->where( "$roleTable.slug=:slug", [ ':slug' => $roleSlug ] );

		if( !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			$query->andWhere( "$siteMemberTable.siteId=:siteId", [ ':siteId' => $siteId ] );
		}

		$users = $query->all();

		$usersMap = [];

		foreach( $users as $user ) {

			$usersMap[ $user->id ] = $user->getName();
		}

		return $usersMap;
	}

	// Read - Others ---

	// Create -------------

	/**
	 * Create the user and associate avatar, banner or video.
	 *
	 * @param User $model
	 * @param array $config
	 * @return User
	 */
	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		$modelClass	= static::$modelClass;

		// Set Attributes
		$model->registeredAt = DateUtil::getDateTime();

		// Default Status - New
		$model->status = $model->status ?? $modelClass::STATUS_NEW;

		// Default Slug - Username
		$model->slug = $model->slug ?? $model->username;

		// Generate Tokens
		$model->generateVerifyToken();
		$model->generateAuthKey();
		$model->generateOtp();

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::create( $model, $config );
	}

	/**
	 * Register User - It register the user and set status to new. It also generate
	 * verification token.
	 *
	 * @param RegisterForm $model
	 * @return User
	 */
	public function register( $model, $config = [] ) {

		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : User::STATUS_NEW;
		$user	= isset( $config[ 'user' ] ) ? $config[ 'user' ] : $this->getModelObject();

		$date = DateUtil::getDateTime();

		$user->email		= $model->email;
		$user->username		= $model->username;
		$user->slug			= empty( $model->slug ) ? $model->username : $model->slug;
		$user->title		= $model->title;
		$user->firstName	= $model->firstName;
		$user->middleName	= $model->middleName;
		$user->lastName		= $model->lastName;
		$user->mobile		= $model->mobile;
		$user->phone		= $model->phone;
		$user->dob			= $model->dob;
		$user->registeredAt	= $date;
		$user->status		= $status;
		$user->type			= $model->type;

		$user->generatePassword( $model->password );
		$user->generateVerifyToken();
		$user->generateAuthKey();
		$user->generateOtp();

		$user->save();

		return $user;
	}

	// Update -------------

	/**
	 * Update the user and associate/update avatar, banner or video.
	 *
	 * @param User $model
	 * @param array $config
	 * @return User
	 */
	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'localeId', 'genderId', 'maritalId', 'avatarId', 'bannerId', 'videoId', 'templateId',
			'email', 'username', 'slug', 'title', 'firstName', 'middleName', 'lastName', 'message',
			'description', 'dob', 'mobile', 'phone', 'timeZone', 'avatarUrl', 'websiteUrl', 'content'
		];

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		if( $admin ) {

			$attributes[] = 'status';
		}

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->id ];

			$config[ 'data' ][ 'message' ] = 'User status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	/**
	 * Confirm User - The method verify and confirm user by accepting valid token
	 * sent via mail. It also set user status to active.
	 *
	 * @param User $user
	 * @param string $token
	 * @return boolean
	 */
	public function verify( $user, $token ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			$modelClass	= static::$modelClass;

			// Find existing user
			$userToUpdate = $modelClass::findById( $user->id );

			// User need admin approval
			if( Yii::$app->core->isUserApproval() ) {

				// Mark user as verified for further action from admin
				$userToUpdate->verify();
			}
			// Direct approval and activation
			else {

				$userToUpdate->status = User::STATUS_ACTIVE;
			}

			$userToUpdate->unsetVerifyToken();

			$userToUpdate->update();

			return true;
		}

		return false;
	}

	/**
	 * Activate User / Reset Password - Activates the User created from Admin Panel or
	 * reset password.
	 *
	 * @param User $user
	 * @param string $token
	 * @param ResetPasswordForm $resetForm
	 * @param boolean $activate - It will be true if user is invited by admin for activation.
	 * @return boolean
	 */
	public function reset( $user, $token, $resetForm, $activate = false ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			$modelClass	= static::$modelClass;

			// Find existing user
			$userToUpdate = $modelClass::findById( $user->id );

			// Generate Password
			$userToUpdate->generatePassword( $resetForm->password );

			// User need admin approval for activation
			if( !$activate && Yii::$app->core->isUserApproval() ) {

				$userToUpdate->verify();
			}
			// Direct approval and activation
			else {

				$userToUpdate->status = User::STATUS_ACTIVE;
			}

			$userToUpdate->unsetResetToken();

			$userToUpdate->unsetVerifyToken();

			$userToUpdate->update();

			return true;
		}

		return false;
	}

	/**
	 * The method generate a new reset token which can be used later to update user password.
	 *
	 * @param User $user
	 * @return User
	 */
	public function forgotPassword( $user ) {

		$modelClass	= static::$modelClass;

		// Find existing user
		$userToUpdate = $modelClass::findById( $user->id );

		// Generate Token
		$userToUpdate->generateResetToken();
		$userToUpdate->generateOtp();

		// Update User
		$userToUpdate->update();

		return $userToUpdate;
	}

	/**
	 * The method generate a new secure password for the given password and unset the
	 * reset token. It also activate user.
	 *
	 * @param User $user
	 * @param ResetPasswordForm $resetForm
	 * @param boolean $activate
	 * @return User
	 */
	public function resetPassword( $user, $resetForm ) {

		$modelClass	= static::$modelClass;

		// Find existing user
		$userToUpdate = $modelClass::findById( $user->id );

		// Generate Password
		$userToUpdate->generatePassword( $resetForm->password );
		$userToUpdate->unsetResetToken();

		// User need admin approval
		if( Yii::$app->core->isUserApproval() ) {

			$userToUpdate->verify();
		}
		// Direct approval and activation
		else {

			$userToUpdate->status = User::STATUS_ACTIVE;
		}

		// Update User
		$userToUpdate->update();

		if( $userToUpdate->update() !== false ) {

			return $userToUpdate;
		}

		return false;
	}

	// Log last activity ----

	public function logLastActivity() {

		$user = Yii::$app->core->getUser();

		if( isset( $user ) ) {

			$user->lastActivityAt = Date( 'Y-m-d h:i:s' );

			$this->update( $user, [ 'attributes' => [ 'lastActivityAt' ] ] );
		}
	}

	// Delete -------------

	/**
	 * The project must extend this class to delete project specific resources associated
	 * with the user.
	 *
	 * @param \cmsgears\core\common\models\entities\User $model
	 * @param array $config
	 * @return boolean
	 */
	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete Meta
				$this->metaService->deleteByModelId( $model->id );

				// Delete Files
				$this->fileService->deleteMultiple( [ $model->avatar, $model->banner, $model->video ] );

				// Delete Model Files
				$this->fileService->deleteFiles( $model->files );

				// Delete Option Mappings
				Yii::$app->factory->get( 'modelOptionService' )->deleteByParent( $model->id, static::$parentType );

				// Delete Notifications
				Yii::$app->eventManager->deleteNotificationsByUserId( $model->id );

				// Commit
				$transaction->commit();
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$direct = isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false; // Trigger direct notifications
		$users	= isset( $config[ 'users' ] ) ? $config[ 'users' ] : [ $model->id ]; // Trigger user notifications

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'activate': {

						$this->activate( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'freeze': {

						$this->freeze( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'block': {

						$this->block( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'terminate': {

						$this->terminate( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	public function checkRoleChange( $model, $oldRoleId ) {

		if( $model->activeSiteMember->roleId !== intval( $oldRoleId ) ) {

			$roleService = Yii::$app->factory->get( 'roleService' );

			$oldRole = $roleService->getById( $oldRoleId )->name;
			$newRole = $roleService->getById( $model->activeSiteMember->roleId )->name;

			// Trigger Role Change Notification
			$this->notifyUser( $model, [
				'template' => CoreGlobal::TPL_NOTIFY_USER_ROLE,
				'users' => [ $model->id ], 'data' => [ 'oldRole' => $oldRole, 'newRole' => $newRole ]
			]);
		}
	}

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// UserService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
