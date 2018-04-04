<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\services\interfaces\mappers\ISiteMemberService;
use cmsgears\core\common\services\interfaces\entities\IRoleService;

use cmsgears\core\common\services\base\MapperService;

/**
 * SiteMemberService provide service methods of site member mapper.
 *
 * @since 1.0.0
 */
class SiteMemberService extends  MapperService implements ISiteMemberService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\SiteMember';

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
	 * @return SiteMembers - for the given site
	 */
	public function getSiteMemberBySiteId( $siteId ) {

		$modelTable	= $this->getModelTable();

		$config = [];
		$config[ 'conditions' ][ "$modelTable.siteId" ]	= $siteId;

		return $this->getPage( $config );
	}

	/**
	 * @param integer $siteId
	 * @param integer $userId
	 * @return SiteMember - for the given site and user
	 */
	public function findBySiteIdUserId( $siteId, $userId ) {

		$modelClass	= $this->getModelClass();

		return $modelClass::findBySiteIdUserId( $siteId, $userId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $user, $config = [] ) {

		$siteMember = isset( $config[ 'siteMember' ] ) ? $config[ 'siteMember' ] : null;
		$roleId		= isset( $config[ 'roleId' ] ) ? $config[ 'roleId' ] : null;

		if( !isset( $siteMember ) ) {

			$siteMember	= $this->getModelObject();
		}

		if( isset( $roleId ) ) {

			$siteMember->roleId	= $roleId;
		}
		else {

			$role = $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );

			$siteMember->roleId	= $role->id;
		}

		$siteMember->siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
