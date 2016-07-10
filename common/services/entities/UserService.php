<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\services\interfaces\entities\IUserService;
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\resources\IModelAttributeService;

use cmsgears\core\common\services\traits\ApprovalTrait;

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
	private $modelAttributeService;

	// Traits ------------------------------------------------------

	use ApprovalTrait;

	// Constructor and Initialisation ------------------------------

    public function __construct( IFileService $fileService, IModelAttributeService $modelAttributeService, $config = [] ) {

		$this->fileService				= $fileService;
		$this->modelAttributeService	= $modelAttributeService;

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

		$siteTable 			= CoreTables::TABLE_SITE;
		$siteMemberTable 	= CoreTables::TABLE_SITE_MEMBER;

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'firstName' => SORT_ASC, 'lastName' => SORT_ASC ],
	                'desc' => [ 'firstName' => SORT_DESC, 'lastName' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'username' => [
	                'asc' => [ 'username' => SORT_ASC ],
	                'desc' => ['username' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'username',
	            ],
	            'role' => [
	                'asc' => [ "$siteMemberTable.roleId" => SORT_ASC ],
	                'desc' => [ "$siteMemberTable.roleId" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'role',
	            ],
	            'status' => [
	                'asc' => [ 'status' => SORT_ASC ],
	                'desc' => ['status' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'status',
	            ],
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);

		$config[ 'conditions' ][ "$siteTable.slug" ] 	= Yii::$app->core->getSiteSlug();

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'email';
		}

		return parent::findPage( $config );
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

	/**
	 * @param string $roleSlug
	 * @return array
	 */
	public function getIdNameMapByRoleSlug( $roleSlug ) {

		$roleTable			= CoreTables::TABLE_ROLE;
		$userTable			= CoreTables::TABLE_USER;
		$siteTable			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		$users 				= User::find()
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

	public function getAttributeMapByType( $user, $type ) {

		return $this->modelAttributeService->getObjectMapByType( $user->id, CoreGlobal::TYPE_USER, $type );
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
		$model->registeredAt 	= DateUtil::getDateTime();
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
	 * @param RegisterForm $registerForm
	 * @return User
	 */
	public function register( $registerForm ) {

		$user 	= new User();
		$date	= DateUtil::getDateTime();

		$user->email 		= $registerForm->email;
		$user->username 	= $registerForm->username;
		$user->firstName	= $registerForm->firstName;
		$user->lastName		= $registerForm->lastName;
		$user->registeredAt	= $date;
		$user->status		= User::STATUS_NEW;

		$user->generatePassword( $registerForm->password );
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

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;

		// Save Files
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar ] );

		return parent::update( $model, [
			'attributes' => [ 'avatarId', 'genderId', 'email', 'username', 'firstName', 'lastName', 'status', 'phone', 'avatarUrl', 'websiteUrl' ]
		]);
	}

	public function updateModelAttributes( $user, $attributes ) {

		foreach ( $attributes as $attribute ) {

			$this->modelAttributeService->update( $attribute );
		}

		return true;
	}

    /**
     * Activate User created from Admin Panel.
     * @param User $user
     * @param ResetPasswordForm $resetForm
     * @param boolean $activate
     * @return boolean
     */
    public function activate( $user, $resetForm, $activate = true ) {

        // Find existing user
        $userToUpdate   = User::findById( $user->id );

        // Generate Password
        $userToUpdate->generatePassword( $resetForm->password );

        // Activate User
        if( $activate ) {

            $userToUpdate->status = User::STATUS_ACTIVE;
        }

        $userToUpdate->unsetResetToken();

        $userToUpdate->update();

        return true;
    }

	/**
	 * The method verify and confirm user by accepting valid token sent via mail. It also set user status to active.
	 * @param User $user
	 * @param string $token
	 * @param boolean $activate
	 * @return boolean
	 */
	public function verify( $user, $token, $activate = true ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			// Find existing user
			$userToUpdate	= User::findById( $user->id );

			// Activate User
			if( $activate ) {

				$userToUpdate->status = User::STATUS_ACTIVE;
			}
			else {

				$userToUpdate->status = User::STATUS_CONFIRMED;
			}

			$userToUpdate->unsetVerifyToken();

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
        $userToUpdate   = User::findById( $user->id );

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
    public function resetPassword( $user, $resetForm, $activate = true ) {

        // Find existing user
        $userToUpdate   = User::findById( $user->id );

        // Generate Password
        $userToUpdate->generatePassword( $resetForm->password );
        $userToUpdate->unsetResetToken();

        // Activate User
        if( $userToUpdate->isNew() && $activate ) {

            $userToUpdate->status = User::STATUS_ACTIVE;
        }

        // Update User
        $userToUpdate->update();

        return $userToUpdate;
    }

    /**
     * The method create user avatar if it does not exist or save existing avatar.
     * @param User $user
     * @param CmgFile $avatar
     * @return User - updated User
     */
    public function updateAvatar( $user, $avatar ) {

        // Find existing user
        $userToUpdate   = User::findById( $user->id );

        // Save Avatar
        $this->fileService->saveImage( $avatar, [ 'model' => $userToUpdate, 'attribute' => 'avatarId' ] );

        // Update User
        $userToUpdate->update();

        // Return updated User
        return $userToUpdate;
    }

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		$this->fileService->deleteFiles( [ $model->avatar ] );

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
