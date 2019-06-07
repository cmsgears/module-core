<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// Yii Imports
use Yii;
use yii\base\Component;
use yii\data\Sort;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IVisibility;

use cmsgears\core\common\services\interfaces\base\IActiveRecordService;

use cmsgears\core\common\data\ActiveDataProvider;

/**
 * ActiveRecordService defines several useful methods used for pagination and generating map
 * and list by specifying the columns.
 *
 * @since 1.0.0
 */
abstract class ActiveRecordService extends Component implements IActiveRecordService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT		= 10;
	const PAGE_LIMIT_MAX	= 50;

	// Public -----------------

	/**
	 * Page limit for data provider.
	 */
	public static $pageLimit	= self::PAGE_LIMIT;

	public static $maxPageLimit	= self::PAGE_LIMIT_MAX;

	/**
	 * ObjectData is configured as default model class since it supports arbitrary models.
	 */

	/**
	 * The model class used to call model static methods.
	 */
	public static $modelClass	= null;

	/**
	 * The service must specify whether it's corresponding model supports type for classification
	 * of the model.
	 */
	public static $typed		= false;

	/**
	 * Parent type is required to associate multiple mapper or resources to the corresponding model.
	 * It will be used by resources and mapper models to be stored in their parentType column. There
	 * is no relation between $typed and $parentType. The variable $typed is specific for service model
	 * whereas $parentType is required for mapper and resources.
	 */
	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ActiveRecordService -------------------

	public function getModelClass() {

		return static::$modelClass;
	}

	public function getModelObject( $config = [] ) {

		$modelClass	= static::$modelClass;

		return new $modelClass( $config );
	}

	public function getModelTable() {

		$modelClass	= static::$modelClass;

		return $modelClass::tableName();
	}

	public function isTyped() {

		return static::$typed;
	}

	public function getParentType() {

		return static::$parentType;
	}

	// Data Provider ------

	/**
	 * @return - DataProvider with applied configuration.
	 */
	public function getDataProvider( $config = [] ) {

		return static::findDataProvider( $config );
	}

	/**
	 * @return - DataProvider with applied configuration.
	 */
	public function getPage( $config = [] ) {

		return static::findPage( $config );
	}

	/**
	 * Returns data provider of publicly accessible models with applied configuration.
	 *
	 * @return \cmsgears\core\common\data\ActiveDataProvider
	 */
	public function getPublicPage( $config = [] ) {

		$config[ 'public' ]	= true;

		return $this->getPage( $config );
	}

	public function getPageForSearch( $config = [] ) {

		return static::findPageForSearch( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @return Entity by id.
	 */
	public function getById( $id ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findById( $id );
	}

	public function getByIds( $ids = [], $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		$config[ 'filters' ][]	= [ 'in', "$modelTable.id", $ids ];

		return static::searchModels( $config );
	}

	/**
	 * @return array - of models with given conditions.
	 */
	public function getModels( $config = [] ) {

		$advanced = isset( $config[ 'advanced' ] ) ? $config[ 'advanced' ] : false;

		if( $advanced ) {

			return static::searchModels( $config );
		}

		return static::findModels( $config );
	}

	/**
	 * A simple method to get random ids.
	 * It's not efficient for tables having large number of rows.
	 *
	 * TODO: We can make this method efficient by using random offset and limit instead of going for full table scan.
	 *       Avoid using count() to get total rows. Use Stats Table to get estimated count.
	 *
	 * Notes: Using offset is much slower as compared to start index with limit.
	 */
	public function getRandom( $config = [] ) {

		$offset			= $config[ 'offset' ] ?? 0;
		$limit			= $config[ 'limit' ] ?? 10;
		$conditions		= $config[ 'conditions' ] ?? null;

		// model class
		$modelClass		= static::$modelClass;

		// query generation
		$results		= [];

		$query			= $modelClass::find();

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		// Randomise -----------

		$query = $query->orderBy( 'RAND()' );

		// Offset --------------

		if( $offset > 0 ) {

			$query->offset( $offset );
		}

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		$results = $query->all();

		return $results;
	}

	// Read - Lists ----

	public function getList( $config = [] ) {

		return static::generateList( $config );
	}

	public function getIdList( $config = [] ) {

		return static::findIdList( $config );
	}

	public function getNameValueList( $config = [] ) {

		return static::findNameValueList( $config );
	}

	/**
	 * @return array of arrays having id and name for given configurations.
	 */
	public function getIdNameList( $config = [] ) {

		return static::findIdNameList( $config );
	}

	// Read - Maps -----

	public function getNameValueMap( $config = [] ) {

		return static::findNameValueMap( $config );
	}

	/**
	 * @return associated array of arrays having id and name for given configurations.
	 */
	public function getIdNameMap( $config = [] ) {

		return static::findIdNameMap( $config );
	}

	public function getObjectMap( $config = [] ) {

		return static::findObjectMap( $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$modelClass	= new static::$modelClass;

		$ignoreSite	= $config[ 'ignoreSite' ] ?? false;

		if( $modelClass::isMultiSite() && !$ignoreSite ) {

			$model->siteId	= $config[ 'siteId' ] ?? Yii::$app->core->siteId;
		}

		$model->save();

		if( $model->id > 0 ) {

			return $model;
		}
		// Handle cases where proper validation is not applied
		else if( YII_DEBUG ) {

			var_dump( $model->getErrors() );
		}

		return false;
	}

	public function createByParams( $params = [], $config = [] ) {

		$model	= new static::$modelClass;

		foreach( $params as $key => $value ) {

			$model->$key = $value;
		}

		return $this->create( $model, $config );
	}


	public function createMultiple( $models, $config = [] ) {

		$result = true;

		foreach( $models as $model ) {

			$created = $this->create( $model, $config );

			if( !$created ) {

				$result = false;
			}
		}

		return $result;
	}

	/**
	 * Add method can be used to register resources from admin.
	 *
	 * @param \cmsgears\core\common\models\base\Entity $model
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\Entity | boolean
	 */
	public function add( $model, $config = [] ) {

		return $this->create( $model, $config );
	}

	/**
	 * Register method can be used to register resources from frontend.
	 *
	 * @param \cmsgears\core\common\models\base\Entity $model
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\Entity | boolean
	 */
	public function register( $model, $config = [] ) {

		return $this->create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return self::updateSelective( $model, $config );
	}

	public function updateByParams( $params = [], $config = [] ) {

		// Implement in child classes if required in cases where we only have few of the key attributes, but no other information related to model.
	}

	public function updateAttributes( $model, $config = [] ) {

		$attributes	= $config[ 'attributes' ] ?? [];

		if( $model->updateAttributes( $attributes ) !== false ) {

			return $model;
		}

		return false;
	}

	public function updateAll( $model, $config = [] ) {

		$modelClass	= static::$modelClass;

		$attributes	= $config[ 'attributes' ] ?? [];
		$condition	= $config[ 'condition' ] ?? null;

		return $modelClass::updateAll( $attributes, $condition );
	}

	public function updateMultiple( $models, $config = [] ) {

		foreach( $models as $model ) {

			$this->update( $model, $config );
		}
	}

	public function updateByForm( $model, $form, $config = [] ) {

		$attributes		= $form->getArrayToStore();
		$classpath		= $model->getClasspath();

		foreach ( $attributes as $key => $value ) {

			if ( property_exists( $classpath, $key ) ) {

				$model->$key = $value;
			}
		}

		$this->update( $model, $config );
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		// Adapter Method - Implement in child classes
	}

	public function createOrUpdate( $model, $config = [] ) {

		if( empty( $model->id ) ) {

			return $this->create( $model, $config );
		}

		return $this->update( $model, $config );
	}

	public function linkModel( $model, $column, $link ) {

		if( empty( $link ) || empty( $link->id ) || $link->id == 0 ) {

			return;
		}

		$model->$column = $link->id;

		$this->update( $model, [
			'attributes' => [ $column ]
		]);
	}

	// Delete -------------

	/**
	 * Delete the model by deleting related resources and mapper.
	 *
	 * @param type $model
	 * @param type $config
	 * @return boolean
	 */
	public function delete( $model, $config = [] ) {

		$config[ 'notify' ] = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		$hard = isset( $config[ 'hard' ] ) ? $config[ 'hard' ] : true;

		if( isset( $model ) ) {

			// Permanent Delete
			if( $hard ) {

				return $model->delete();
			}
			// Soft Delete - Useful for models using status to mark model as deleted, but keep for historic purpose.
			else {

				$interfaces = class_implements( static::class );

				if( isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

					// Approval Trait
					return $this->softDeleteNotify( $model, $config );
				}
				else {

					return $this->softDelete( $model );
				}
			}
		}

		return false;
	}

	public function softDelete( $model ) {

		// Delete if not deleted yet
		if( !$model->status == IApproval::STATUS_DELETED ) {

			$model->status = IApproval::STATUS_DELETED;

			$model->update();

			return $model;
		}

		return false;
	}

	public function deleteMultiple( $models, $config = [] ) {

		if( isset( $models ) ){

			foreach( $models as $model ) {

				$this->delete( $model, $config );
			}
		}
	}

	public function deleteById( $id, $config = [] ) {

		$modelClass = static::$modelClass;

		return $modelClass::deleteById( $id );
	}

	// Bulk ---------------

	/**
	 * Default method for bulk actions.
	 *
	 * @param string $column
	 * @param string $action
	 * @param string $target
	 */
	public function applyBulkByTargetId( $column, $action, $target, $config = [] ) {

		foreach ( $target as $id ) {

			$model = $this->getById( $id );

			// Bulk Conditions
			if( isset( $model ) ) {

				$this->applyBulk( $model, $column, $action, $target, $config );
			}
		}
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		// adapter method for bulk actions
	}

	// Notifications ------

    /**
	 * Trigger Admin Notifications. It can also send direct notification where applicable.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 *
	 * @param array $config - key elements are template(template slug), data(template data),
	 * title(notification title), createdBy(creator id), parentId(parent model id), parentType,
	 * link, admin(flag), adminLink and users(to notify)
	 */
	public function notifyAdmin( $model, $config = [] ) {

		$config[ 'admin' ]	= true;
		$config[ 'direct' ]	= isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false;

		$this->sendNotification( $model, $config );
	}

    /**
	 * Trigger User Notifications. It can also send direct notification where applicable.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 *
	 * @param array $config - key elements are template(template slug), data(template data),
	 * title(notification title), createdBy(creator id), parentId(parent model id), parentType,
	 * link, admin(flag), adminLink and users(to notify)
	 */
    public function notifyUser( $model, $config = [] ) {

		$config[ 'admin' ]	= false;
		$config[ 'direct' ]	= isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false;

		$this->sendNotification( $model, $config );
	}

    /**
	 * Trigger only Direct Notifications specific to a model.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 *
	 * @param array $config - key elements are template(template slug), data(template data),
	 * title(notification title), createdBy(creator id), parentId(parent model id), parentType,
	 * link, admin(flag), adminLink and users(to notify)
	 */
    public function notifyModel( $model, $config = [] ) {

		$config[ 'admin' ]	= false;
		$config[ 'direct' ]	= true;

		$this->sendNotification( $model, $config );
	}

	/**
	 * Prepare the notification data and configuration and trigger it using the active
	 * event manager.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param array $config
	 * @return type
	 */
    protected function sendNotification( $model, $config = [] ) {

		$templateData	= $config[ 'data' ] ?? [];
		$templateConfig	= [];

		$templateData = ArrayHelper::merge( [ 'model' => $model, 'service' => $this ], $templateData );

		$templateConfig[ 'admin' ]	= $config[ 'admin' ] ?? false;
		$templateConfig[ 'direct' ]	= $config[ 'direct' ] ?? false;

		$templateConfig[ 'createdBy' ]	= $config[ 'createdBy' ] ?? null;
		$templateConfig[ 'parentId' ]	= $model->id;
		$templateConfig[ 'parentType' ]	= static::$parentType;
		$templateConfig[ 'link' ]		= $config[ 'link' ] ?? null;
		$templateConfig[ 'adminLink' ]	= $config[ 'adminLink' ] ?? null;

		$templateConfig[ 'title' ]	= isset( $config[ 'title' ] ) ? $config[ 'title' ] : null;
		$templateConfig[ 'users' ]	= $config[ 'users' ] ?? [];

		return Yii::$app->eventManager->triggerNotification( $config[ 'template' ], $templateData, $templateConfig );
	}

	// Cache --------------

	// Import/Export ------

	public function exportModels( $config = [] ) {

		// Apply Filters
		// Use query params to create custom filters

		return $this->searchModels( $config );
	}

	public function filterModels( $filters, $config = [] ) {

		// Apply Filters
		// Use $filters model to create custom filters

		return $this->searchModels( $config );
	}

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ActiveRecordService -------------------

	protected static function applyPublicFilters( $config ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// Public models
		$public		= isset( $config[ 'public' ] ) ? $config[ 'public' ] : false;
		$status		= isset( $config[ 'filterStatus' ] ) ? $config[ 'filterStatus' ] : true;
		$visibility = isset( $config[ 'filterVisibility' ] ) ? $config[ 'filterVisibility' ] : true;

		if( $public ) {

			$interfaces = class_implements( $modelClass );

			// Select only active and frozen models excluding new, blocked and terminated models.
			if( $status && isset( $interfaces[ 'cmsgears\core\common\models\interfaces\base\IApproval' ] ) ) {

				$config[ 'filters' ][] = [ 'in', "$modelTable.status", [ IApproval::STATUS_ACTIVE, IApproval::STATUS_FROJEN ] ];
			}

			// Select only publicly visible models
			if( $visibility && isset( $interfaces[ 'cmsgears\core\common\models\interfaces\base\IVisibility' ] ) ) {

				$config[ 'conditions' ][ "$modelTable.visibility" ]	= IVisibility::VISIBILITY_PUBLIC;
			}
		}

		return $config;
	}

	protected static function applySiteFilters( $config ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// Filter sites
		$ignoreSite			= $config[ 'ignoreSite' ] ?? false;
		$siteOnly			= $config[ 'siteOnly' ] ?? true;
		$excludeMain		= $config[ 'excludeMainSite' ] ?? false; // Exclude main site in multisite scenario
		$config[ 'siteId' ]	= $config[ 'siteId' ] ?? ( $modelClass::isMultiSite() ? Yii::$app->core->siteId : null );

		// Site specific models for multi-site applications
		if( $modelClass::isMultiSite() && !$ignoreSite ) {

			// Restrict to site only in case model supports multisite i.e. siteId column
			if( $siteOnly ) {

				$config[ 'conditions' ][ "$modelTable.siteId" ] = $config[ 'siteId' ];
			}

			// Exclude main site
			if( $excludeMain ) {

				$mainSiteId = Yii::$app->core->mainSiteId;

				$config[ 'conditions' ][] = "siteId!=$mainSiteId";
			}
		}

		return $config;
	}

	// Data Provider ------

	/**
	 * The method findDataProvider accept the entity class and various parameters to generate the active data provider.
	 * @param array $config
	 * @return ActiveDataProvider
	 */
	public static function findDataProvider( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// query generation
		$query			= $config[ 'query' ] ?? $modelClass::find();
		$limit			= $config[ 'limit' ] ?? self::$pageLimit; // Page Limit from config
		$page			= $config[ 'page' ] ?? 0; // Current Page from config
		$page			= Yii::$app->request->get( 'page' ) != null ? Yii::$app->request->get( 'page' ) - 1 : $page; // Current Page from params
		$pageLimit		= Yii::$app->request->get( 'per-page' ); // Page Limit from params
		$conditions		= $config[ 'conditions' ] ?? null;
		$filters		= $config[ 'filters' ] ?? null;
		$random			= $config[ 'random' ] ?? false; // Be careful in using random at database level for tables having high row count
		$softDelete		= $config[ 'softDelete' ] ?? false;

		// search and sort
		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchCol		= $config[ 'search-col' ] ?? [];
		$sort			= $config[ 'sort' ] ?? false;

		// report
		$reportCol = $config[ 'report-col' ] ?? [];

		// url generation
		$route = $config[ 'route' ] ?? null;

		// pagination
		$pagination = [];

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		$interfaces = class_implements( static::class );

		if( $softDelete || isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

			$softDelete = IApproval::STATUS_DELETED;

			$query->andWhere( "$modelTable.status!=$softDelete" );
		}

		// Random -------------

		if( $random ) {

			$query	= $query->orderBy( new Expression( 'rand()' ) );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( $searchParam );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && !empty( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery	= static::generateSearchQuery( $searchCol, $searchTerms );
			$query			= $query->andWhere( $searchQuery );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
		}

		// Reporting -----------

		$report	= Yii::$app->request->getQueryParam( 'report' );

		if( isset( $report ) && $report ) {

			$reportColumns = [];

			foreach( $reportCol as $key => $column ) {

				if( !is_string( $key ) ) {

					$key = $column;
				}

				$find	= Yii::$app->request->getQueryParam( "$key-find" );
				$flag	= Yii::$app->request->getQueryParam( "$key-flag" );
				$match	= Yii::$app->request->getQueryParam( "$key-match" );
				$start	= Yii::$app->request->getQueryParam( "$key-start" );
				$end	= Yii::$app->request->getQueryParam( "$key-end" );

				// String search
				if( isset( $find ) ) {

					$reportColumns[ $column ][ 'find' ] = $find;
				}

				// Flag
				if( isset( $flag ) ) {

					$reportColumns[ $column ][ 'flag' ] = $flag;
				}

				// Numeric
				if( isset( $match ) ) {

					$reportColumns[ $column ][ 'match' ] = $match;
				}

				// Numeric - Range - Start
				if( isset( $start ) ) {

					$reportColumns[ $column ][ 'start' ] = $start;
				}

				// Numeric - Range - End
				if( isset( $end ) ) {

					$reportColumns[ $column ][ 'end' ] = $end;
				}
			}

			foreach( $reportColumns as $key => $column ) {

				$find	= isset( $column[ 'find' ] ) ? $column[ 'find' ] : null;
				$flag	= isset( $column[ 'flag' ] ) ? $column[ 'flag' ] : null;
				$match	= isset( $column[ 'match' ] ) ? $column[ 'match' ] : null;
				$start	= isset( $column[ 'start' ] ) ? $column[ 'start' ] : null;
				$end	= isset( $column[ 'end' ] ) ? $column[ 'end' ] : null;

				// String search
				if( isset( $find ) ) {

					$query->andFilterWhere( [ 'like', $key, $find ] );
				}

				// Flag
				if( isset( $flag ) ) {

					$query->andWhere( "{$key}=:flag", [ ':flag' => $flag ] );
				}

				// Numeric
				if( isset( $match ) ) {

					$query->andWhere( "$key=:match", [ ':match' => $match ] );
				}

				// Numeric - Range - Start & End
				if( isset( $start ) && isset( $end ) ) {

					$query->andWhere( "$key BETWEEN :start AND :end", [ ':start' => $start, ':end' => $end ] );
				}
				// Numeric - Range - Start
				else if( isset( $start ) ) {

					$query->andWhere( "$key >= '$start'" );
				}
				// Numeric - Range - End
				else if( isset( $end ) ) {

					$query->andWhere( "$key <= '$end'" );
				}
			}
		}

		// Print to Debug -------

		/** Use only in case actual generate query is required. It can also be obtained from Yii's awesome
		 * debug bar in debug mode.
		 */
		if( isset( $config[ 'pquery' ] ) && $config[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Data Provider --------

		$pagination	= [ 'pageSize' => $limit ];

		if( isset( $page ) ) {

			$pagination[ 'page' ] = $page;
		}

		if( isset( $pageLimit ) && $pageLimit <= static::$maxPageLimit ) {

			$pagination[ 'pageSize' ] = $pageLimit;
		}

		if( isset( $route ) ) {

			$pagination[ 'route' ] = $route;
		}

		$dataProvider	= new ActiveDataProvider([
			'query' => $query,
			'sort' => $sort,
			'pagination' => $pagination
		]);

		return $dataProvider;
	}

	/**
	 * The method findPage provide data provider after generating appropriate query.
	 * It uses find or queryWithHasOne as default method to generate base query.
	 */
	public static function findPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		$sort = isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;

		// Default sort
		if( !$sort ) {

			$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

			$sort = new Sort([
				'attributes' => [
					'id' => [
						'asc' => [ "$modelTable.id" => SORT_ASC ],
						'desc' => [ "$modelTable.id" => SORT_DESC ],
						'default' => SORT_DESC,
						'label' => 'Id'
					]
				],
				'defaultOrder' => $defaultSort
			]);

			$config[ 'sort' ] = $sort;
		}

		// Default conditions
		$config[ 'conditions' ] = $config[ 'conditions' ] ?? [];

		// Default query
		if( !isset( $config[ 'query' ] ) ) {

			$modelClass	= static::$modelClass;
			$hasOne		= isset( $config[ 'hasOne' ] ) ? $config[ 'hasOne' ] : false;

			if( $hasOne ) {

				$config[ 'query' ] = $modelClass::queryWithHasOne();
			}
		}

		// Filters
		$config	= static::applyPublicFilters( $config );

		$config	= static::applySiteFilters( $config );

		// Default search column
		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ][] = "$modelTable.id";
		}

		return static::findDataProvider( $config );
	}

	/**
	 * Generate search query using tag and category tables. The search will be done in model, category and tag names.
	 */
	public static function findPageForSearch( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		$parentType	= $config[ 'parentType' ] ?? static::$parentType;

		// Search in
		$searchModel	 	= $config[ 'searchModel' ] ?? true; // Search in model name
		$searchCategory 	= $config[ 'searchCategory' ] ?? false; // Search in category name
		$searchTag		 	= $config[ 'searchTag' ] ?? false; // Search in tag name

		// DB Tables
		$mcategoryTable		= CoreTables::TABLE_MODEL_CATEGORY;
		$categoryTable		= CoreTables::TABLE_CATEGORY;
		$mtagTable			= CoreTables::TABLE_MODEL_TAG;
		$tagTable			= CoreTables::TABLE_TAG;

		// Sort
		$sortParam	= Yii::$app->request->get( 'sort' );
		$sortParam	= preg_replace( '/-/', '', $sortParam );

		// Keywords
		$searchParam	= isset( $config[ 'search-param' ] ) ? $config[ 'search-param' ] : 'keywords';
		$keywords		= Yii::$app->request->getQueryParam( $searchParam );

		// Search Query
		$query	= $config[ 'query' ] ?? $modelClass::find();
		$hasOne	= $config[ 'hasOne' ] ?? false;

		// Use model joins
		if( $hasOne ) {

			$query = $modelClass::queryWithHasOne();
		}

		// Tag
		if( $searchTag || isset( $config[ 'tag' ] ) || $sortParam === 'tag' ) {

			$query->leftJoin( $mtagTable, "$modelTable.id=$mtagTable.parentId AND $mtagTable.parentType='$parentType' AND $mtagTable.active=TRUE" )
				->leftJoin( $tagTable, "$mtagTable.modelId=$tagTable.id" );
		}

		if( isset( $config[ 'tag' ] ) ) {

			$query->andWhere( "$tagTable.id=" . $config[ 'tag' ]->id );
		}

		// Category
		if( $searchCategory || isset( $config[ 'category' ] ) || $sortParam === 'category' ) {

			$query->leftJoin( $mcategoryTable, "$modelTable.id=$mcategoryTable.parentId AND $mcategoryTable.parentType='$parentType' AND $mcategoryTable.active=TRUE" )
				->leftJoin( $categoryTable, "$mcategoryTable.modelId=$categoryTable.id" );
		}

		if( isset( $config[ 'category' ] ) ) {

			$query->andWhere( "$categoryTable.id=" . $config[ 'category' ]->id );
		}

		// Search
		if( isset( $keywords ) ) {

			if( $searchModel ) {

				$config[ 'search-col' ][] = "$modelTable.name";
			}

			if( $searchCategory ) {

				$config[ 'search-col' ][] = "$categoryTable.name";
			}

			if( $searchTag ) {

				$config[ 'search-col' ][] = "$tagTable.name";
			}
		}

		// Group by model id
		$query->groupBy( "$modelTable.id" );

		$config[ 'query' ] = $query;

		return static::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public static function findById( $id ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findById( $id );
	}

	/**
	 * Simple wrapper for findAll from Yii.
	 */
	public static function findModels( $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findAll( $config );
	}

	/**
	 * Advanced findModels having more options to search.
	 */
	public static function searchModels( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// Filters
		$config	= static::applyPublicFilters( $config );
		$config	= static::applySiteFilters( $config );

		// Generate Query
		$query			= $config[ 'query' ] ?? $modelClass::find();
		$offset			= $config[ 'offset' ] ?? 0;
		$limit			= $config[ 'limit' ] ?? self::PAGE_LIMIT;
		$conditions		= $config[ 'conditions' ] ?? null;
		$filters		= $config[ 'filters' ] ?? null;
		$sort			= $config[ 'sort' ] ?? [ 'id' => SORT_DESC ]; // Show latest first
		$public			= $config[ 'public' ] ?? false;

		// Result Columns
		$columns = $config[ 'columns' ] ?? [];

		// Array Result
		$array = $config[ 'array' ] ?? false;

		// Selective columns ---

		if( count( $columns ) > 0 ) {

			$query->select( $columns );
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		$interfaces = class_implements( static::class );

		if( isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

			$statusFilter = $modelClass::STATUS_TERMINATED;

			$query->andWhere( "$modelTable.status<$statusFilter" );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach( $filters as $filter ) {

				$query->andFilterWhere( $filter );
			}
		}

		// Offset --------------

		if( $offset > 0 ) {

			$query->offset( $offset );
		}

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		// Sort -----------------

		if( count( $sort ) > 0 ) {

			$query->orderBy( $sort );
		}

		// Print to Debug -------

		if( isset( $config[ 'pquery' ] ) && $config[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Models ---------------

		if( $array ) {

			$models = $query->asArray( $array )->all();
		}
		else {

			$models = $query->all();
		}

		return $models;
	}

	// Read - Lists ----

	public static function findList( $config = [] ) {

		return static::generateList( $config );
	}

	public static function findIdList( $config = [] ) {

		$config[ 'column' ] = 'id';

		return static::generateList( $config );
	}

	public static function findNameValueList( $config = [] ) {

		return static::generateNameValueList( $config );
	}

	public static function findIdNameList( $config = [] ) {

		$config[ 'nameColumn' ]		= $config[ 'nameColumn' ] ?? 'id';
		$config[ 'valueColumn' ]	= $config[ 'valueColumn' ] ?? 'name';
		$config[ 'nameAlias' ]		= 'id';
		$config[ 'valueAlias' ]		= 'name';

		return static::generateNameValueList( $config );
	}

	// Generate - Lists

	/**
	 * The method generateList returns an array of list for given column
	 * @param array $config
	 * @return array - array of values.
	 */
	public static function generateList( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// Query and Column
		$query	= new Query();
		$column	= $config[ 'column' ] ?? 'id';

		// Conditions, Filters and Sorting
		$conditions	= $config[ 'conditions' ] ?? null;
		$filters	= $config[ 'filters' ] ?? null;
		$order		= $config[ 'order' ] ?? null;

		// Conditions ----------

		$query->select( $column )->from( $modelTable );

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		$interfaces = class_implements( static::class );

		if( isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

			$statusFilter = $modelClass::STATUS_TERMINATED;

			$query->andWhere( "$modelTable.status<$statusFilter" );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
		}

		// Sorting -------------

		if( isset( $order ) ) {

			$query->orderBy( $order );
		}

		// Quering -------------

		// Get column
		$query->column();

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();
		$resultList	= [];

		// Result --------------

		foreach ( $list as $item ) {

			$resultList[] = $item[ $column ];
		}

		return $resultList;
	}

	/**
	 * The method generateNameValueList returns an array of associative arrays having name and value as keys for the defined columns.
	 * @param array $config
	 * @return array - associative array of arrays having name and value as keys.
	 */
	public static function generateNameValueList( $config = [] ) {

		$config = static::applySiteFilters( $config );

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// map columns
		$nameColumn		= $config[ 'nameColumn' ] ?? 'name';
		$valueColumn	= $config[ 'valueColumn' ] ?? 'value';

		// column alias
		$nameAlias		= $config[ 'nameAlias' ] ?? 'name';
		$valueAlias		= $config[ 'valueAlias' ] ?? 'value';

		// limit
		$limit			= $config[ 'limit' ] ?? 0;

		// Query
		$query = $config[ 'query' ] ?? new Query();

		// Conditions, Filters and Sorting
		$conditions	= $config[ 'conditions' ] ?? null;
		$filters	= $config[ 'filters' ] ?? null;
		$order		= $config[ 'order' ] ?? null;

		// additional data
		$prepend		= $config[ 'prepend' ] ?? [];
		$append			= $config[ 'append' ] ?? [];

		// Conditions ----------

		$query->select( [ "$nameColumn as $nameAlias", "$valueColumn as $valueAlias" ] )
			  ->from( $modelTable );

		if( isset( $conditions ) ) {

			foreach( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		$interfaces = class_implements( static::class );

		if( isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

			$statusFilter = $modelClass::STATUS_TERMINATED;

			$query->andWhere( "$modelTable.status<$statusFilter" );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach( $filters as $filter ) {

				$query = $query->andFilterWhere( $filter );
			}
		}

		// Sorting -------------

		if( isset( $order ) ) {

			$query->orderBy( $order );
		}

		// Limit ---------------

		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		// Quering -------------

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		// Result --------------

		// Prepend given list
		if( count( $prepend ) > 0 ) {

			$arrayList = ArrayHelper::merge( $prepend, $arrayList );
		}

		// Append given list
		if( count( $append ) > 0 ) {

			$arrayList = ArrayHelper::merge( $arrayList, $append );
		}

		return $arrayList;
	}

	// Read - Maps -----

	public static function findNameValueMap( $config = [] ) {

		return static::generateMap( $config );
	}

	public static function findIdNameMap( $config = [] ) {

		$config[ 'nameColumn' ]		= $config[ 'nameColumn' ] ?? 'id';
		$config[ 'valueColumn' ]	= $config[ 'valueColumn' ] ?? 'name';
		$config[ 'nameAlias' ]		= 'id';
		$config[ 'valueAlias' ]		= 'name';

		return static::generateMap( $config );
	}

	public static function findObjectMap( $config = [] ) {

		return static::generateObjectMap( $config );
	}

	// Generate - Maps -

	/**
	 * The method findMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param array $config
	 * @return array - associative array of arrays having name as key and value as value.
	 */
	public static function generateMap( $config = [] ) {

		$parentType = static::$parentType;

		// column alias
		$defaultValue	= preg_replace( '/-/', ' ', $parentType );
		$default		= $config[ 'default' ] ?? false;
		$defaultValue	= $config[ 'defaultValue' ] ?? ucfirst( $defaultValue );

		$nameAlias		= $config[ 'nameAlias' ] ?? 'name';
		$valueAlias		= $config[ 'valueAlias' ] ?? 'value';

		$arrayList		= static::generateNameValueList( $config );
		$map			= $default ? [ '0' => "Choose $defaultValue" ] : [];

		foreach( $arrayList as $item ) {

			$map[ $item[ $nameAlias ] ] = $item[ $valueAlias ];
		}

		return $map;
	}

	/**
	 * The method findObjectMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param array $config
	 */
	public static function generateObjectMap( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		// map columns
		$key	= $config[ 'key' ] ?? 'id';
		$value	= static::$modelClass;

		// query generation
		$query		= $config[ 'query' ] ?? $value::find();
		$limit		= $config[ 'limit' ] ?? self::PAGE_LIMIT;
		$conditions	= $config[ 'conditions' ] ?? null;
		$filters	= $config[ 'filters' ] ?? null;

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		$interfaces = class_implements( static::class );

		if( isset( $interfaces[ 'cmsgears\core\common\services\interfaces\base\IApproval' ] ) ) {

			$statusFilter = $modelClass::STATUS_TERMINATED;

			$query->andWhere( "$modelTable.status<$statusFilter" );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
		}

		// Quering -------------

		$objects = $query->all();

		// Result --------------

		$map = [];

		foreach( $objects as $object ) {

			$map[ $object->$key ] = $object;
		}

		return $map;
	}

	// Read - Others ---

	/**
	 * It generate search query for specified columns by parsing the comma separated search terms
	 */
	public static function generateSearchQuery( $columns, $searchTerms ) {

		$searchTerms	= preg_split( '/,/', $searchTerms );
		$searchQuery	= "";

		// Multiple columns
		if( is_array( $columns ) ) {

			foreach( $columns as $ckey => $column ) {

				$query	= null;

				foreach( $searchTerms as $skey => $term ) {

					if( $skey  == 0 ) {

						$query = " $column like '%$term%' ";
					}
					else {

						$query .= " OR $column like '%$term%' ";
					}
				}

				if( isset( $query ) ) {

					if( $ckey  == 0 ) {

						$searchQuery =	"( $query )";
					}
					else {

						$searchQuery .= " OR ( $query )";
					}
				}
			}
		}
		// Single column
		else {

			foreach( $searchTerms as $key => $value ) {

				if( $key  == 0 ) {

					$searchQuery .= " $columns like '%$value%' ";
				}
				else {

					$searchQuery .= " OR $columns like '%$value%' ";
				}
			}
		}

		return $searchQuery;
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public static function updateSelective( $model, $config = [] ) {

		$selective	= $config[ 'selective' ] ?? true;
		$validate	= $config[ 'validate' ] ?? true;

		if( Yii::$app->core->isUpdateSelective() && $selective ) {

			$attributes		= $config[ 'attributes' ] ?? [];

			$existingModel	= self::findById( $model->id );

			$existingModel->copyForUpdateFrom( $model, $attributes );

			$update = $existingModel->update( $validate );

			if( $existingModel->update( $validate ) !== false || count( $existingModel->getErrors() ) > 0 ) {

				return $existingModel;
			}
			// Handle cases where proper validation is not applied
			else if( YII_DEBUG ) {

				if( count( $existingModel->getErrors() ) > 0 ) {

					var_dump( $existingModel->getErrors() );
				}

				return false; // Return false on errors in debug mode
			}
		}
		else {

			$attributes	= $config[ 'attributes' ] ?? null;

			$update = $model->update( $validate, $attributes );

			if( $update ) {

				return $model;
			}
			// Handle cases where proper validation is not applied
			else if( YII_DEBUG ) {

				if( count( $model->getErrors() ) > 0 ) {

					var_dump( $model->getErrors() );
				}

				return false; // Return false on errors in debug mode
			}
		}

		return false;
	}

	// Delete -------------

}
