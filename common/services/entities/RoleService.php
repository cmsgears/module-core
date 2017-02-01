<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\services\interfaces\entities\IRoleService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

/**
 * The class RoleService is base class to perform database activities for Role Entity.
 */
class RoleService extends \cmsgears\core\common\services\base\EntityService implements IRoleService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Role';

	public static $modelTable	= CoreTables::TABLE_ROLE;

	public static $parentType	= CoreGlobal::TYPE_ROLE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RoleService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ 'slug' => SORT_ASC ],
					'desc' => ['slug' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdNameMapByRoles( $roles ) {

		return $this->getIdNameMap( [ 'filters' => [ [ 'in', 'slug', $roles ] ], 'prepend' => [ [ 'name' => '0', 'value' => 'Choose Role' ] ] ] );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'description', 'homeUrl' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * @param BinderForm $binder
	 * @return boolean
	 */
	public function bindPermissions( $binder ) {

		$roleId			= $binder->binderId;
		$permissions	= $binder->bindedData;

		// Clear all existing mappings
		RolePermission::deleteByRoleId( $roleId );

		// Create updated mappings
		if( isset( $permissions ) && count( $permissions ) > 0 ) {

			foreach ( $permissions as $key => $value ) {

				if( isset( $value ) ) {

					$toSave					= new RolePermission();
					$toSave->roleId			= $roleId;
					$toSave->permissionId	= $value;

					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// RoleService ---------------------------

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
