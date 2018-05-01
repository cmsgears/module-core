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

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;
use cmsgears\core\common\services\traits\resources\ModelMetaTrait;
use cmsgears\core\common\services\traits\resources\SocialLinkTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * UserService provide service methods of user model.
 *
 * @since 1.0.0
 */
class UserService extends EntityService implements IUserService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\User';

	public static $parentType	= CoreGlobal::TYPE_USER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Traits ------------------------------------------------------

	use ApprovalTrait;
	use DataTrait;
	use ModelMetaTrait;
	use SocialLinkTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService	= $fileService;

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
			'defaultOrder' => [
				'id' => SORT_DESC
			]
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
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name", 'message' => "$modelTable.message", 'desc' => "$modelTable.description",
				'username' => "$modelTable.username", 'email' => "$modelTable.email",
				'mobile' => "$modelTable.mobile", 'phone' => "$modelTable.phone",
				'content' => "$modelTable.ontent"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'locale' => "$modelTable.localeId", 'gender' => "$modelTable.genderId",
			'name' => "$modelTable.name", 'status' => "$modelTable.status",
			'message' => "$modelTable.message", 'desc' => "$modelTable.description",
			'username' => "$modelTable.username", 'email' => "$modelTable.email",
			'mobile' => "$modelTable.mobile", 'phone' => "$modelTable.phone",
			'tzone' => "$modelTable.timeZone", 'content' => "$modelTable.ontent",
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByType( $type, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

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
	 * @param string $email
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

	// Read - Maps -----

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name", "$modelTable.email" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'query' ]->andWhere( "$modelTable.name LIKE :name", [ ':name' => "$name%" ] );

		return static::searchModels( $config );
	}

	/**
	 * @param string $roleSlug
	 * @return array
	 */
	public function getIdNameMapByRoleSlug( $roleSlug ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$roleTable			= Yii::$app->get( 'roleService' )->getModelTable();
		$siteTable			= Yii::$app->get( 'siteService' )->getModelTable();
		$siteMemberTable	= Yii::$app->get( 'siteMemberService' )->getModelTable();

		$users	= $modelClass::find()
					->leftJoin( $siteMemberTable, "$siteMemberTable.userId = $modelTable.id" )
					->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
					->leftJoin( $roleTable, "$roleTable.id = $siteMemberTable.roleId" )
					->where( "$roleTable.slug=:slug AND $siteTable.name=:name", [ ':slug' => $roleSlug, ':name' => Yii::$app->core->getSiteName() ] )->all();

		$usersMap = [];

		foreach( $users as $user ) {

			$usersMap[ $user->id ] = $user->getName();
		}

		return $usersMap;
	}

	// Read - Others ---

	// Create -------------

	/**
	 * The method create user.
	 * @param User $model
	 * @param array $config
	 * @return User
	 */
	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Set Attributes
		$model->registeredAt = DateUtil::getDateTime();

		if( !isset( $model->status ) ) {

			$model->status = User::STATUS_NEW;
		}

		// Generate Tokens
		$model->generateVerifyToken();
		$model->generateAuthKey();

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::create( $model, $config );
	}

	/**
	 * The method registers website users and set their status to new at start. It also generate verification token.
	 * @param RegisterForm $model
	 * @return User
	 */
	public function register( $model, $config = [] ) {

		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : User::STATUS_NEW;

		$user	= $this->getModelObject();
		$date	= DateUtil::getDateTime();

		$user->email		= $model->email;
		$user->username		= $model->username;
		$user->title		= $model->title;
		$user->firstName	= $model->firstName;
		$user->middleName	= $model->middleName;
		$user->lastName		= $model->lastName;
		$user->registeredAt	= $date;
		$user->status		= $status;

		$user->generatePassword( $model->password );
		$user->generateVerifyToken();
		$user->generateAuthKey();

		$user->save();

		return $user;
	}

	// Update -------------

	/**
	 * The method update user including avatar.
	 * @param User $model
	 * @param array $config
	 * @return User
	 */
	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'localeId', 'genderId', 'avatarId', 'bannerId', 'videoId', 'templateId',
			'email', 'username', 'title', 'firstName', 'middleName', 'lastName', 'message',
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

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * The method verify and confirm user by accepting valid token sent via mail. It also set user status to active.
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
	 * Activate User created from Admin Panel.
	 * @param User $user
	 * @param string $token
	 * @param ResetPasswordForm $resetForm
	 * @return boolean
	 */
	public function reset( $user, $token, $resetForm ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			$modelClass	= static::$modelClass;

			// Find existing user
			$userToUpdate = $modelClass::findById( $user->id );

			// Generate Password
			$userToUpdate->generatePassword( $resetForm->password );

			// User need admin approval
			if( Yii::$app->core->isUserApproval() ) {

				$userToUpdate->verify();
			}
			// Direct approval and activation
			else {

				$userToUpdate->status = User::STATUS_ACTIVE;
			}

			$userToUpdate->unsetResetToken();

			$userToUpdate->update();

			return true;
		}

		return false;
	}

	/**
	 * The method generate a new reset token which can be used later to update user password.
	 * @param User $user
	 * @return User
	 */
	public function forgotPassword( $user ) {

		$modelClass	= static::$modelClass;

		// Find existing user
		$userToUpdate = $modelClass::findById( $user->id );

		// Generate Token
		$userToUpdate->generateResetToken();

		// Update User
		$userToUpdate->update();

		return $userToUpdate;
	}

	/**
	 * The method generate a new secure password for the given password and unset the reset token. It also activate user.
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

		return $userToUpdate;
	}

	/**
	 * The method create user avatar if it does not exist or save existing avatar.
	 * @param User $model
	 * @param CmgFile $avatar
	 * @return User - updated User
	 */
	public function updateAvatar( $model, $avatar ) {

		// Save Avatar
		$this->fileService->saveImage( $avatar, [ 'model' => $model, 'attribute' => 'avatarId' ] );

		// Update Model
		return parent::update( $model, [
			'attributes' => [ 'avatarId' ]
		]);
	}

	// Log last activity ----

	public function logLastActivity() {

		$user = Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			$user->lastActivityAt = Date( 'Y-m-d h:i:s' );

			$this->update( $user, [ 'attributes' => [ 'lastActivityAt' ] ] );
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Files
		$this->fileService->deleteFiles( [ $model->avatar, $model->banner, $model->video ] );

		// Delete Notifications
		Yii::$app->eventManager->deleteNotificationsByUserId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'active': {

						$this->approve( $model );

						// TODO: Trigger activate email

						break;
					}
					case 'block': {

						$this->block( $model );

						// TODO: Trigger block email

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
