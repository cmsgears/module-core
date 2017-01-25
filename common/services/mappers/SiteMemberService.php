<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;
use \cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\services\interfaces\mappers\ISiteMemberService;
use cmsgears\core\common\services\interfaces\entities\IRoleService;

/**
 * The class SiteMemberService is base class to perform database activities for SiteMember Entity.
 */
class SiteMemberService extends \cmsgears\core\common\services\base\EntityService implements ISiteMemberService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\SiteMember';

	public static $modelTable	= CoreTables::TABLE_SITE_MEMBER;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $roleService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IRoleService $roleService, $config = [] ) {

		$this->roleService	= $roleService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteMemberService ---------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * @param integer $siteId
	 * @param integer $userId
	 * @return SiteMember - for the given site and user
	 */
	public function findBySiteIdUserId( $siteId, $userId ) {

		return SiteMember::findBySiteIdUserId( $siteId, $userId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $user, $config = [] ) {

		$siteMember = isset( $config[ 'siteMember' ] ) ? $config[ 'siteMember' ] : null;
		$roleId		= isset( $config[ 'roleId' ] ) ? $config[ 'roleId' ] : null;

		if( !isset( $siteMember ) ) {

			$siteMember	= new SiteMember();
		}

		if( isset( $roleId ) ) {

			$siteMember->roleId	= $roleId;
		}
		else {

			$role				= $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );
			$siteMember->roleId	= $role->id;
		}

		$siteMember->siteId = Yii::$app->core->siteId;
		$siteMember->userId	= $user->id;

		return parent::create( $siteMember, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'roleId' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SiteMemberService ---------------------

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
