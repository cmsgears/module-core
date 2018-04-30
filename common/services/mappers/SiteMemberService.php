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
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\services\interfaces\mappers\ISiteMemberService;
use cmsgears\core\common\services\interfaces\entities\IRoleService;

use cmsgears\core\common\services\base\MapperService;

/**
 * SiteMemberService provide service methods of site member mapper.
 *
 * @since 1.0.0
 */
class SiteMemberService extends MapperService implements ISiteMemberService {

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

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$siteTable = Yii::$app->factory->get( 'siteService' )->getModelTable();
		$roleTable = Yii::$app->factory->get( 'roleService' )->getModelTable();
		$userTable = Yii::$app->factory->get( 'userService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'site' => [
	                'asc' => [ "$siteTable.name" => SORT_ASC ],
	                'desc' => [ "$siteTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Template'
	            ],
				'role' => [
					'asc' => [ "$roleTable.name" => SORT_ASC ],
					'desc' => [ "$roleTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
				'name' => [
					'asc' => [ "$userTable.name" => SORT_ASC ],
					'desc' => [ "$userTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'email' => [
					'asc' => [ "$userTable.email" => SORT_ASC ],
					'desc' => [ "$userTable.email" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.updatedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.updatedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
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

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'visibility' => "$modelTable.visibility",
			'order' => "$modelTable.order",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageBySiteId( $siteId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.siteId" ]	= $siteId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @param integer $siteId
	 * @param integer $userId
	 * @return SiteMember - for the given site and user
	 */
	public function getBySiteIdUserId( $siteId, $userId ) {

		$modelClass	= $this->getModelClass();

		return $modelClass::findBySiteIdUserId( $siteId, $userId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( empty( $model->userId ) ) {

			$model->userId = Yii::$app->user->identity->id;
		}

		if( empty( $model->roleId ) || $model->roleId <= 0 ) {

			$role = $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );

			$model->roleId	= $role->id;
		}

		if( empty( $model->siteId ) ) {

			$model->siteId = Yii::$app->core->siteId;
		}

		return parent::create( $model, $config );
	}

	public function createByParams( $params = [], $config = [] ) {

		$userId = isset( $params[ 'userId' ] ) ? $params[ 'userId' ] : null;
		$roleId = isset( $params[ 'roleId' ] ) ? $params[ 'roleId' ] : null;
		$siteId = isset( $params[ 'siteId' ] ) ? $params[ 'siteId' ] : null;

		if( !isset( $userId ) && isset( Yii::$app->user->identity ) ) {

			$userId = Yii::$app->user->identity->id;
		}

		if( !isset( $roleId ) ) {

			$role = $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );

			$roleId	= $role->id;
		}

		if( !isset( $siteId ) ) {

			$siteId = Yii::$app->core->siteId;
		}

		$params[ 'userId' ] = $userId;
		$params[ 'roleId' ] = $roleId;
		$params[ 'siteId' ] = $siteId;

		return parent::createByParams( $params, $config );
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
