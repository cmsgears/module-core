<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\ApprovalTrait;
use cmsgears\core\common\services\traits\DataTrait;
use cmsgears\core\common\services\traits\ModelMetaTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class UserService is base class to perform database activities for User Entity.
 */
class UserService extends \cmsgears\core\common\services\base\EntityService implements IUserService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\User';

	public static $modelTable	= CoreTables::TABLE_USER;

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
		$modelTable	= static::$modelTable;

		$siteTable			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'gender' => [
					'asc' => [ "$modelTable.genderId" => SORT_ASC ],
					'desc' => [ "$modelTable.genderId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Gender'
				],
				'role' => [
					'asc' => [ "$siteMemberTable.roleId" => SORT_ASC ],
					'desc' => [ "$siteMemberTable.roleId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Role'
				],
				'name' => [
					'asc' => [ "$modelTable.firstName" => SORT_ASC, "$modelTable.lastName" => SORT_ASC ],
					'desc' => [ "$modelTable.firstName" => SORT_DESC, "$modelTable.lastName" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'username' => [
					'asc' => [ "$modelTable.username" => SORT_ASC ],
					'desc' => [ "$modelTable.username" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Username'
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
				'dob' => [
					'asc' => [ "$modelTable.dob" => SORT_ASC ],
					'desc' => [ "$modelTable.dob" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Birth Date'
				],
				'phone' => [
					'asc' => [ "$modelTable.phone" => SORT_ASC ],
					'desc' => [ "$modelTable.phone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Phone'
				],
	            'cdate' => [
	                'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ],
	            'ldate' => [
	                'asc' => [ "$modelTable.lastLoginAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.lastLoginAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Last Login'
	            ]
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		$config[ 'conditions' ][ "$siteTable.slug" ] = Yii::$app->core->getSiteSlug();

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "concat($modelTable.firstName,$modelTable.lastName)", 'username' => "$modelTable.username",
				'email' => "$modelTable.email", 'phone' => "$modelTable.phone"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "concat($modelTable.firstName,$modelTable.lastName)", 'username' => "$modelTable.username",
			'email' => "$modelTable.email", 'phone' => "$modelTable.phone"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByRoleType( $roleType ) {

		$roleTable = CoreTables::TABLE_ROLE;

		return $this->getPage( [ 'conditions' => [ "$roleTable.type" => $roleType ], 'query' => User::queryWithSiteMembers() ] );
	}

	public function getPageByAdmins() {

		return $this->getPageByPermissionName( CoreGlobal::PERM_ADMIN );
	}

	public function getPageByUsers() {

		return $this->getPageByPermissionName( CoreGlobal::PERM_USER );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @param string $token
	 * @return User
	 */
	public function getByAccessToken( $token ) {

		return User::findByAccessToken( $token );
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public function getByEmail( $email ) {

		return User::findByEmail( $email );
	}

	/**
	 * @param string $email
	 * @return boolean
	 */
	public function isExistByEmail( $email ) {

		$user = User::findByEmail( $email );

		return isset( $user );
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public function getByUsername( $username ) {

		return User::findByUsername( $username );
	}

	/**
	 * @param string $username
	 * @return boolean
	 */
	public function isExistByUsername( $username ) {

		$user = User::findByUsername( $username );

		return isset( $user );
	}

	// Read - Lists ----

	// Read - Maps -----

	public function searchByName( $name, $config = [] ) {

		$modelClass					= static::$modelClass;
		$modelTable					= static::$modelTable;

		$config[ 'query' ]			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "concat($modelTable.firstName, ' ', $modelTable.lastName) AS name" ];
		$config[ 'array' ]			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

		$config[ 'query' ]->andWhere( "concat($modelTable.firstName, $modelTable.lastName) like '$name%'" );

		return static::searchModels( $config );
	}

	/**
	 * @param string $roleSlug
	 * @return array
	 */
	public function getIdNameMapByRoleSlug( $roleSlug ) {

		$roleTable			= CoreTables::TABLE_ROLE;
		$userTable			= CoreTables::TABLE_USER;
		$siteTable			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		$users				= User::find()
								->leftJoin( $siteMemberTable, "$siteMemberTable.userId = $userTable.id" )
								->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
								->leftJoin( $roleTable, "$roleTable.id = $siteMemberTable.roleId" )
								->where( "$roleTable.slug=:slug AND $siteTable.name=:name", [ ':slug' => $roleSlug, ':name' => Yii::$app->core->getSiteName() ] )->all();
		$usersMap			= [];

		foreach ( $users as $user ) {

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

		// Set Attributes
		$model->registeredAt	= DateUtil::getDateTime();
		$model->status			= User::STATUS_NEW;

		// Generate Tokens
		$model->generateVerifyToken();
		$model->generateAuthKey();

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		// Create User
		$model->save();

		return $model;
	}

	/**
	 * The method registers website users and set their status to new at start. It also generate verification token.
	 * @param RegisterForm $model
	 * @return User
	 */
	public function register( $model, $config = [] ) {

		$status	= isset( $config[ 'status' ] ) ? $config[ 'status' ] : User::STATUS_NEW;

		$user	= new User();
		$date	= DateUtil::getDateTime();

		$user->email		= $model->email;
		$user->username		= $model->username;
		$user->firstName	= $model->firstName;
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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'avatarId', 'genderId', 'email', 'username', 'firstName', 'lastName', 'status', 'phone', 'avatarUrl', 'websiteUrl' ];
		$avatar		= isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

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

			// Find existing user
			$userToUpdate	= User::findById( $user->id );

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
	public function activate( $user, $token, $resetForm ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			// Find existing user
			$userToUpdate	= User::findById( $user->id );

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

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

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

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

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

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Files
		$this->fileService->deleteFiles( [ $model->avatar ] );

		// Delete Notifications
		Yii::$app->eventManager->deleteNotifications( $model->id, static::$parentType, true );

		// Delete model
		return parent::delete( $model, $config );
	}

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
