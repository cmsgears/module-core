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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\services\interfaces\mappers\ISiteMemberService;
use cmsgears\core\common\services\interfaces\entities\IRoleService;
use cmsgears\core\common\services\interfaces\entities\ISiteService;
use cmsgears\core\common\services\interfaces\entities\IUserService;

use cmsgears\core\common\services\traits\base\FeaturedTrait;

/**
 * SiteMemberService provide service methods of site member mapper.
 *
 * @since 1.0.0
 */
class SiteMemberService extends \cmsgears\core\common\services\base\MapperService implements ISiteMemberService {

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

	private $siteService;
	private $roleService;
	private $userService;

	// Traits ------------------------------------------------------

	use FeaturedTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( ISiteService $siteService, IRoleService $roleService, IUserService $userService, $config = [] ) {

		$this->siteService = $siteService;
		$this->roleService = $roleService;
		$this->userService = $userService;

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

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$siteTable = $this->siteService->getModelTable();
		$roleTable = $this->roleService->getModelTable();
		$userTable = $this->userService->getModelTable();

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
				'popular' => [
					'asc' => [ "$modelTable.popular" => SORT_ASC ],
					'desc' => [ "$modelTable.popular" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Popular'
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
				case 'popular': {

					$config[ 'conditions' ][ "$modelTable.popular" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$userTable.name",
			'site' => "$siteTable.name",
			'role' => "$roleTable.name",
			'content' => "$userTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$userTable.name",
			'site' => "$siteTable.name",
			'role' => "$roleTable.name",
			'content' => "$userTable.content",
			'order' => "$modelTable.order",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured",
			'popular' => "$modelTable.popular"
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

		$user = Yii::$app->core->getUser();

		if( empty( $model->userId ) && isset( $user ) ) {

			$model->userId = $user->id;
		}

		if( empty( $model->roleId ) || $model->roleId <= 0 ) {

			$role = $this->roleService->getBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );

			$model->roleId = $role->id;
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

		$user = Yii::$app->core->getUser();

		if( !isset( $userId ) && isset( $user ) ) {

			$userId = $user->id;
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

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'roleId' ];

		if( $admin ) {

			$attributes = ArrayHelper::merge( $attributes, [ 'pinned', 'featured', 'popular' ] );
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public static function deleteBySiteId( $siteId ) {

		$modelClass = static::$modelClass;

		return $modelClass::deleteBySiteId( $siteId );
	}

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
