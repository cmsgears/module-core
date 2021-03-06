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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\services\interfaces\entities\IRoleService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * RoleService provide service methods of role model.
 *
 * @since 1.0.0
 */
class RoleService extends \cmsgears\core\common\services\base\EntityService implements IRoleService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\Role';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_ROLE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
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

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
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
	            'group' => [
	                'asc' => [ "$modelTable.group" => SORT_ASC ],
	                'desc' => [ "$modelTable.group" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Group'
	            ],
	            'admin' => [
	                'asc' => [ "$modelTable.adminUrl" => SORT_ASC ],
	                'desc' => [ "$modelTable.adminUrl" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Admin Url'
	            ],
	            'home' => [
	                'asc' => [ "$modelTable.homeUrl" => SORT_ASC ],
	                'desc' => [ "$modelTable.homeUrl" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Home Url'
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
	            ]
			],
			'defaultOrder' => $defaultSort
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'group': {

					$config[ 'conditions' ][ "$modelTable.group" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'desc' => "$modelTable.description"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'type' => "$modelTable.type",
			'desc' => "$modelTable.description"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	public function getIdNameListByTypeGroup( $type, $group = false, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;
		$config[ 'conditions' ][ 'group' ] 	= $group;

		return $this->getIdNameList( $config );
	}

	// Read - Maps -----

	public function getIdNameMapByRoleIds( $roleIds ) {

		return $this->getIdNameMap([
			'filters' => [ [ 'in', 'slug', $roleIds ] ],
			'prepend' => [ [ 'name' => '0', 'value' => 'Choose Role' ] ]
		]);
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'slug', 'icon', 'description', 'adminUrl', 'homeUrl'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'group'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * @param BinderForm $binder
	 * @return boolean
	 */
	public function bindPermissions( $binder ) {

		$roleId	= $binder->binderId;
		$binded	= $binder->binded;

		// Clear all existing mappings
		RolePermission::deleteByRoleId( $roleId );

		// Create updated mappings
		if( count( $binded ) > 0 ) {

			foreach( $binded as $id ) {

				$toSave	= new RolePermission();

				$toSave->roleId = $roleId;

				$toSave->permissionId = $id;

				$toSave->save();
			}
		}

		return true;
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

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
