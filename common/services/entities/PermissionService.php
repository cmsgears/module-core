<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\services\interfaces\entities\IPermissionService;

use cmsgears\core\common\services\traits\NameSlugTrait;

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

	use NameSlugTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

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

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'name', 'description' ]
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

?>