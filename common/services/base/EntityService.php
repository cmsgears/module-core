<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;
use yii\data\Sort;
use yii\db\Expression;
use yii\db\Query;

use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\interfaces\IApproval;
use cmsgears\core\common\models\interfaces\IVisibility;

use cmsgears\core\common\services\interfaces\base\IEntityService;

use cmsgears\core\common\data\ActiveDataProvider;

/**
 * The class EntityService defines several useful methods used for pagination and generating map
 * and list by specifying the columns.
 */
abstract class EntityService extends \yii\base\Component implements IEntityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT			= 10;
	const PAGE_LIMIT_MAX		= 50;

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
	public static $modelClass	= '\cmsgears\core\common\models\entities\ObjectData';

	/**
	 * The model table used for advanced model operations.
	 */
	public static $modelTable	= CoreTables::TABLE_OBJECT_DATA;

	/**
	 * Parent type is required for entities having type column. It will be used by resources and
	 * mapper models to refer to typed parent entity.
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

	// EntityService -------------------------

	public function getModelClass() {

		return static::$modelClass;
	}

	public function getModelTable() {

		return static::$modelTable;
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
	 * @return - DataProvider of publicly accessible models with applied configuration.
	 */
	public function getPublicPage( $config = [] ) {

		$config[ 'public' ]	= true;

		return $this->getPage( $config );
	}

	/**
	 * @return - DataProvider or array of models with applied configuration.
	 */
	public function getPageForSimilar( $config = [] ) {

		$query				= $this->generateSimilarQuery( $config );
		$config[ 'query' ]	= $query;

		return $this->getPublicPage( $query );
	}

	public function getPageForSearch( $config = [] ) {

		return static::findPageForSearch( $config );
	}

	// Similar query considering category and tag for similarity
	protected function generateSimilarQuery( $config = [] ) {

		// DB Tables
		$modelClass			= static::$modelClass;
		$modelTable			= static::$modelTable;

		$parentType			= isset( $config[ 'parentType' ] ) ? $config[ 'parentType' ] : static::$parentType;
		$mcategoryTable		= CoreTables::TABLE_MODEL_CATEGORY;
		$categoryTable		= CoreTables::TABLE_CATEGORY;
		$mtagTable			= CoreTables::TABLE_MODEL_TAG;
		$tagTable			= CoreTables::TABLE_TAG;
		$filter				= null;

		// Search Query
		$query				= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$hasOne				= isset( $config[ 'hasOne' ] ) ? $config[ 'hasOne' ] : false;

		// Use model joins
		if( $hasOne ) {

			$query = $modelClass::queryWithHasOne();
		}

		// Tags
		if( isset( $config[ 'tags' ] ) && count( $config[ 'tags' ] ) > 0 ) {

			$query->leftJoin( $mtagTable, "$modelTable.id=$mtagTable.parentId AND $mtagTable.parentType='$parentType' AND $mtagTable.active=TRUE" )
				->leftJoin( $tagTable, "$mtagTable.modelId=$tagTable.id" );

			$filter	= "$tagTable.id in( " . join( ",", $config[ 'tags' ] ). ")";
		}

		// Categories
		if( isset( $config[ 'categories' ] ) && count( $config[ 'categories' ] ) > 0 ) {

			$query->leftJoin( "$mcategoryTable", "$modelTable.id=$mcategoryTable.parentId AND $mcategoryTable.parentType='$parentType' AND $mcategoryTable.active=TRUE" )
				->leftJoin( "$categoryTable", "$mcategoryTable.modelId=$categoryTable.id" );

			$filter	= "$categoryTable.id in( " . join( ",", $config[ 'categories' ] ). ")";
		}

		// Mixed
		if( isset( $config[ 'tags' ] ) && count( $config[ 'tags' ] ) > 0 && isset( $config[ 'categories' ] ) && count( $config[ 'categories' ] ) > 0 ) {

			$filter	= "( $tagTable.id in( " . join( ",", $config[ 'tags' ] ). ") OR $categoryTable.id in( " . join( ",", $config[ 'categories' ] ). ") )";
		}

		if( isset( $filter ) ) {

			$query->andWhere( $filter );
		}

		return $query;
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

		$modelTable		= static::$modelTable;

		$config[ 'filters' ][]	= [ 'in', "$modelTable.id", $ids ];

		return static::searchModels( $config );
	}

	public function getSimilar( $config = [] ) {

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 5;
		$query	= $this->generateSimilarQuery( $config );

		$query->limit( $limit );

        return $query->all();
	}

	/**
	 * @return array - of models with given conditions.
	 */
	public function getModels( $config = [] ) {

		$advanced	= isset( $config[ 'advanced' ] ) ? $config[ 'advanced' ] : false;

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
	 */
	public function getRandomObjects( $config = [] ) {

		$offset			= isset( $config[ 'offset' ] ) ? $config[ 'offset' ] : 0;
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;

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

	public function createByParams( $params = [], $config = [] ) {

		$model	= new static::$modelClass;

		foreach ( $params as $key => $value ) {

			$model->$key = $value;
		}

		return $this->create( $model, $config );
	}

	public function add( $model, $config = [] ) { } // Adapter

	public function register( $model, $config = [] ) { } // Adapter

	// Update -------------

	public function update( $model, $config = [] ) {

		$selective	= isset( $config[ 'selective' ] ) ? $config[ 'selective' ] : true;
		$validate	= isset( $config[ 'validate' ] ) ? $config[ 'validate' ] : true;

		if( Yii::$app->core->isUpdateSelective() && $selective ) {

			$attributes		= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [];

			$existingModel	= $this->getById( $model->id );

			$existingModel->copyForUpdateFrom( $model, $attributes );

			$update			= $existingModel->update( $validate );

			if( $update ) {

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

			$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : null;

			$update		= $model->update( $validate, $attributes );

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

	public function updateByParams( $params = [], $config = [] ) {

		// Implement in child classes if required in cases where we only have few of the key attributes, but no other information related to model.
	}

	public function updateAll( $model, $config = [] ) {

		$modelClass	= static::$modelClass;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [];
		$condition	= isset( $config[ 'condition' ] ) ? $config[ 'condition' ] : null;

		return $modelClass::updateAll( $attributes, $condition );
	}

	public function updateAttributes( $model, $config = [] ) {

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [];

		if( $model->updateAttributes( $attributes ) !== false ) {

			return $model;
		}

		return false;
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

	// Delete -------------

	public function delete( $model, $config = [] ) {

		if( isset( $model ) ) {

			if( Yii::$app->core->isUpdateSelective() ) {

				$model	= $this->getById( $model->id );
			}

			$model->delete();

			return true;
		}

		return false;
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// EntityService -------------------------

	protected static function applyPublicFilters( $config ) {

		// model class
		$modelClass	= static::$modelClass;
		$modelTable = static::$modelTable;

		// Public models
		$public		= isset( $config[ 'public' ] ) ? $config[ 'public' ] : false;

		if( $public ) {

			$interfaces		= class_implements( $modelClass );

			// Select only active and frozen models excluding new, blocked and terminated models.
			if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IApproval' ] ) ) {

				$config[ 'filters' ][]	= [ 'in', "$modelTable.status", [ IApproval::STATUS_ACTIVE, IApproval::STATUS_FROJEN ] ];
			}

			// Select only publicly visible models
			if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IVisibility' ] ) ) {

				$config[ 'conditions' ][ "$modelTable.visibility" ]	= IVisibility::VISIBILITY_PUBLIC;
			}
		}

		return $config;
	}

	protected static function applySiteFilters( $config ) {

		// model class
		$modelClass		= static::$modelClass;
		$modelTable 	= static::$modelTable;

		// Filter sites
		$siteOnly		= isset( $config[ 'siteOnly' ] ) ? $config[ 'siteOnly' ] : true;
		$excludeMain	= isset( $config[ 'excludeMainSite' ] ) ? $config[ 'excludeMainSite' ] : false; // Exclude main site in multisite scenario

		// Site specific models for multi-site applications
		if( $modelClass::$multiSite ) {

			// Restrict to site only in case model supports multisite i.e. siteId column
			if( $siteOnly ) {

				$config[ 'conditions' ][ "$modelTable.siteId" ] = Yii::$app->core->siteId;
			}

			// Exclude main site
			if( $excludeMain ) {

				$mainSiteId					= Yii::$app->core->mainSiteId;
				$config[ 'conditions' ][] 	= "siteId!=$mainSiteId";
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

		// model class
		$modelClass		= static::$modelClass;

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$page			= Yii::$app->request->get( 'page' ) != null ? Yii::$app->request->get( 'page' ) - 1 : null;
		$pageLimit		= Yii::$app->request->get( 'per-page' );
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$random			= isset( $config[ 'random' ] ) ? $config[ 'random' ] : false; // Be careful in using random at database level for tables having high row count

		// search and sort
		$searchParam	= isset( $config[ 'search-param' ] ) ? $config[ 'search-param' ] : 'keywords';
		$searchCol		= isset( $config[ 'search-col' ] ) ? $config[ 'search-col' ] : [];
		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;

		// report
		$reportCol		= isset( $config[ 'report-col' ] ) ? $config[ 'report-col' ] : [];

		// url generation
		$route			= isset( $config[ 'route' ] ) ? $config[ 'route' ] : null;

		$pagination		= [];

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

		// Random -------------

		if( $random ) {

			$query	= $query->orderBy( new Expression( 'rand()' ) );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( $searchParam );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && count( $searchCol ) > 0 ) {

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

			$reportColumns	= [];

			foreach ( $reportCol as $key => $column ) {

				if( !is_string( $key ) ) {

					$key = $column;
				}

				$find	= Yii::$app->request->getQueryParam( "$key-find" );
				$match	= Yii::$app->request->getQueryParam( "$key-match" );
				$start	= Yii::$app->request->getQueryParam( "$key-start" );
				$end	= Yii::$app->request->getQueryParam( "$key-end" );

				// String search
				if( isset( $find ) ) {

					$reportColumns[ $column ][ 'find' ] = $find;
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

			foreach ( $reportColumns as $key => $column ) {

				$find	= isset( $column[ 'find' ] ) ? $column[ 'find' ] : null;
				$match	= isset( $column[ 'match' ] ) ? $column[ 'match' ] : null;
				$start	= isset( $column[ 'start' ] ) ? $column[ 'start' ] : null;
				$end	= isset( $column[ 'end' ] ) ? $column[ 'end' ] : null;

				// String search
				if( isset( $find ) ) {

					$query->andFilterWhere( [ 'like', $key, $find ] );
				}

				// Numeric
				if( isset( $match ) ) {

					// TODO: Check for numerical and string matches
					$query->andWhere( "$key=:match", [ ':match' => $match ] );
				}

				// Numeric - Range - Start & End
				if( isset( $start ) && isset( $end ) ) {

					$query->andWhere( "$key BETWEEN ':start' AND ':end'", [ ':start' => $start, ':end' => $end ] );
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

		if( isset( $pageLimit ) && $pageLimit < static::$maxPageLimit ) {

			$pagination[ 'pageSize' ] = $pageLimit;
		}

		if( isset( $route ) ) {

			$pagination[ 'route' ]	= $route;
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

		$modelClass		= static::$modelClass;
		$modelTable 	= static::$modelTable;

		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;

		// Default sort
		if( !$sort ) {

			$sort = new Sort([
				'attributes' => [
					'id' => [
						'asc' => [ "$modelTable.id" => SORT_ASC ],
						'desc' => [ "$modelTable.id" => SORT_DESC ],
						'default' => SORT_DESC,
						'label' => 'Id'
					]
				]
			]);

			$config[ 'sort' ] = $sort;
		}

		// Default conditions
		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ] = [];
		}

		// Default query
		if( !isset( $config[ 'query' ] ) ) {

			$modelClass	= static::$modelClass;
			$hasOne		= isset( $config[ 'hasOne' ] ) ? $config[ 'hasOne' ] : false;

			if( $hasOne ) {

				$config[ 'query' ]	= $modelClass::queryWithHasOne();
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

		// Model
		$modelClass			= static::$modelClass;
		$modelTable			= static::$modelTable;
		$parentType			= isset( $config[ 'parentType' ] ) ? $config[ 'parentType' ] : static::$parentType;

		// Search in
		$searchModel	 	= isset( $config[ 'searchModel' ] ) ? $config[ 'searchModel' ] : true; // Search in model name
		$searchCategory 	= isset( $config[ 'searchCategory' ] ) ? $config[ 'searchCategory' ] : false; // Search in category name
		$searchTag		 	= isset( $config[ 'searchTag' ] ) ? $config[ 'searchTag' ] : false; // Search in tag name

		// DB Tables
		$mcategoryTable		= CoreTables::TABLE_MODEL_CATEGORY;
		$categoryTable		= CoreTables::TABLE_CATEGORY;
		$mtagTable			= CoreTables::TABLE_MODEL_TAG;
		$tagTable			= CoreTables::TABLE_TAG;

		// Sort
		$sortParam			= Yii::$app->request->get( 'sort' );
		$sortParam			= preg_replace( '/-/', '', $sortParam );

		// Keywords
		$searchParam		= isset( $config[ 'search-param' ] ) ? $config[ 'search-param' ] : 'keywords';
		$keywords			= Yii::$app->request->getQueryParam( $searchParam );

		// Search Query
		$query				= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$hasOne				= isset( $config[ 'hasOne' ] ) ? $config[ 'hasOne' ] : false;

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

		$config[ 'query' ]	= $query;

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

		// model class
		$modelClass		= static::$modelClass;

		// Filters
		$config			= static::applyPublicFilters( $config );

		$config			= static::applySiteFilters( $config );

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$offset			= isset( $config[ 'offset' ] ) ? $config[ 'offset' ] : 0;
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : [ 'id' => SORT_ASC ];
		$public			= isset( $config[ 'public' ] ) ? $config[ 'public' ] : false;

		// selected columns
		$columns		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [];

		// array result
		$array			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : false;

		// Selective columns ---

		if( count( $columns ) > 0 ) {

			$query->select( $columns );
		}

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

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
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

		$config[ 'nameColumn' ]		= 'id';
		$config[ 'valueColumn' ]	= 'name';
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

		// table name
		$tableName		= static::$modelTable;

		// query generation
		$query			= new Query();
		$column			= isset( $config[ 'column' ] ) ? $config[ 'column' ] : 'id';
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

		// sorting
		$order			= isset( $config[ 'order' ] ) ? $config[ 'order' ] : null;

		// Conditions ----------

		$query->select( $column )->from( $tableName );

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
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

		// table name
		$tableName		= static::$modelTable;

		// map columns
		$nameColumn		= isset( $config[ 'nameColumn' ] ) ? $config[ 'nameColumn' ] : 'name';
		$valueColumn	= isset( $config[ 'valueColumn' ] ) ? $config[ 'valueColumn' ] : 'value';

		// column alias
		$nameAlias		= isset( $config[ 'nameAlias' ] ) ? $config[ 'nameAlias' ] : 'name';
		$valueAlias		= isset( $config[ 'valueAlias' ] ) ? $config[ 'valueAlias' ] : 'value';

		// limit
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 0;

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : new Query();
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

		// additional data
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

		// Conditions ----------

		$query->select( [ "$nameColumn as $nameAlias", "$valueColumn as $valueAlias" ] )
			  ->from( $tableName );

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
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

		$config[ 'nameColumn' ]		= 'id';
		$config[ 'valueColumn' ]	= 'name';
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

		// column alias
		$nameAlias		= isset( $config[ 'nameAlias' ] ) ? $config[ 'nameAlias' ] : 'name';
		$valueAlias		= isset( $config[ 'valueAlias' ] ) ? $config[ 'valueAlias' ] : 'value';

		$arrayList		= static::generateNameValueList( $config );
		$map			= [];

		foreach ( $arrayList as $item ) {

			$map[ $item[ $nameAlias ] ] = $item[ $valueAlias ];
		}

		return $map;
	}

	/**
	 * The method findObjectMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param array $config
	 */
	public static function generateObjectMap( $config = [] ) {

		// map columns
		$key			= isset( $config[ 'key' ] ) ? $config[ 'key' ] : 'id';
		$value			= static::$modelClass;

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $value::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

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

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
		}

		// Quering -------------

		$objects	= $query->all();

		// Result --------------

		$map		= [];

		foreach ( $objects as $object ) {

			$map[ $object->$key ] = $object;
		}

		return $map;
	}

	// Read - Others ---

	/**
	 * It generate search query for specified columns by parsing the comma seperated search terms
	 */
	public static function generateSearchQuery( $columns, $searchTerms ) {

		$searchTerms	= preg_split( '/,/', $searchTerms );
		$searchQuery	= "";

		// Multiple columns
		if( is_array( $columns ) ) {

			foreach ( $columns as $ckey => $column ) {

				$query	= null;

				foreach ( $searchTerms as $skey => $term ) {

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

			foreach ( $searchTerms as $key => $value ) {

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

	// Delete -------------

}
