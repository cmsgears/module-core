<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\services\interfaces\entities\IPermissionService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

/**
 * The class PermissionService is base class to perform database activities for Permission Entity.
 */
class PermissionService extends \cmsgears\core\common\services\base\EntityService implements IPermissionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Permission';

	public static $modelTable	= CoreTables::TABLE_PERMISSION;

	public static $parentType	= CoreGlobal::TYPE_PERMISSION;

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

	// PermissionService ---------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'slug'
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

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'description' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
 	}

	/**
	 * @param BinderForm $binder
	 * @return boolean
	 */
	public function bindRoles( $binder ) {

		$permissionId	= $binder->binderId;
		$roles			= $binder->bindedData;

		// Clear all existing mappings
		RolePermission::deleteByPermissionId( $permissionId );

		// Create updated mappings
		if( isset( $roles ) && count( $roles ) > 0 ) {

			foreach ( $roles as $key => $value ) {

				if( isset( $value ) && $value > 0 ) {

					$toSave					= new RolePermission();
					$toSave->permissionId	= $permissionId;
					$toSave->roleId			= $value;

					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// PermissionService ---------------------

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
