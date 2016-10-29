<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\data\Sort;

use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IApproval;
use cmsgears\core\common\models\interfaces\IVisibility;
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\interfaces\base\IEntityService;

/**
 * The class EntityService defines several useful methods used for pagination and generating map and list by specifying the columns.
 */
abstract class EntityService extends \yii\base\Component implements IEntityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT	= 10;

	// Public -----------------

	/**
	 * Page limit for data provider.
	 */
	public static $pageLimit	= self::PAGE_LIMIT;

	/**
	 * The model class used to call model static methods.
	 */
	public static $modelClass	= '\cmsgears\core\common\models\entities\ObjectData';

	/**
	 * The model table used for advanced model operations.
	 */
	public static $modelTable	= CoreTables::TABLE_OBJECT_DATA;

	/**
	 * Parent type is required for entities having type column.
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

		$modelClass	= static::$modelClass;

		return static::findDataProvider( $config );
	}

	public function getPage( $config = [] ) {

		return static::findPage( $config );
	}

	public function getPublicPage( $config = [] ) {

		$modelTable	= static::$modelTable;

		$modelClass	= static::$modelClass;
		$interfaces = class_implements( $modelClass );

		if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IApproval' ] ) ) {

			$config[ 'filters' ][]	= [ 'in', "$modelTable.status", [ IApproval::STATUS_ACTIVE, IApproval::STATUS_FROJEN ] ];
		}

		if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IVisibility' ] ) ) {

			$config[ 'conditions' ][ "$modelTable.visibility" ]	= IVisibility::VISIBILITY_PUBLIC;
		}

		return $this->getPage( $config );
	}

	public function getPageForChildSites( $config = [] ) {

		$config[ 'filters' ][]	= [ 'not in', 'siteId', [ Yii::$app->core->mainSiteId ] ];
		$config[ 'multiSite' ]	= false;

		return $this->getPublicPage( $config );
	}

	public function getPageForSimilar( $config = [] ) {

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
		$config[ 'query' ]	= $query;

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

		return $this->getPublicPage( $config );
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

		$modelTable		= static::$modelTable;

		$config[ 'filters' ][]	= [ 'in', "$modelTable.id", $ids ];

		return static::searchModels( $config );
	}

	/**
	 * @return array - of models with given conditions.
	 */
	public function getModels( $config = [] ) {

		return static::findModels( $config );
	}

	/**
	 * A simple method to get random ids
	 */
	public function getRandomObjects( $config = [] ) {

		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 10;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;

		// model class
		$modelClass		= static::$modelClass;

		// query generation
		$results		= [];

		if( isset( $conditions ) ) {

			$results = $modelClass::find()->where( $conditions )->orderBy( 'RAND()' )->limit( $limit )->all();
		}
		else {

			$results = $modelClass::find()->orderBy( 'RAND()' )->limit( $limit )->all();
		}

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

		return $model->id > 0 ? $model : false;
	}

	public function createMultiple( $models, $config = [] ) {

		foreach( $models as $model ) {

			$this->create( $model, $config );
		}
	}

	public function createByParams( $params = [], $config = [] ) {

		$model	= new static::$modelClass;

		foreach ( $params as $key => $value ) {

			$model->$key	= $value;
		}

		return $this->create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$selective	= isset( $config[ 'selective' ] ) ? $config[ 'selective' ] : true;
		$validate	= isset( $config[ 'validate' ] ) ? $config[ 'validate' ] : true;

		if( Yii::$app->core->isUpdateSelective() && $selective ) {

			$attributes		= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [];

			$existingModel	= $this->getById( $model->id );

			$existingModel->copyForUpdateFrom( $model, $attributes );

			if( $existingModel->update( $validate ) !== false ) {

				return $existingModel;
			}
		}
		else {

			$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : null;

			if( $model->update( $validate, $attributes ) !== false ) {

				return $model;
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

				$model->$key	= $value;
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

	// Data Provider ------

	/**
	 * The method findDataProvider accept the entity class and various parameters to generate the active data provider.
	 * @param array $config
	 * @return ActiveDataProvider
	 */
	public static function findDataProvider( $config = [] ) {

		// TODO: Search in multiple columns.

		// model class
		$modelClass		= static::$modelClass;

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$random			= isset( $config[ 'random' ] ) ? $config[ 'random' ] : false;

		// search and sort
		$searchParam	= isset( $config[ 'search-param' ] ) ? $config[ 'search-param' ] : 'keywords';
		$searchCol		= isset( $config[ 'search-col' ] ) ? $config[ 'search-col' ] : null;
		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;

		// url generation
		$route			= isset( $config[ 'route' ] ) ? $config[ 'route' ] : null;

		$pagination	= array();

		// Conditions ----------

		if( isset( $conditions ) ) {

			$query	= $query->andWhere( $conditions );
		}

		// Random -------------

		if( $random ) {

			$query	= $query->orderBy( new Expression( 'rand()' ) );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( $searchParam );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

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

		// Print to Debug -------

		if( isset( $config[ 'pquery' ] ) && $config[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Data Provider --------

		$pagination	= [ 'pageSize' => $limit ];

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
	 * The method findPage provide data provider after generating appropriate query. It uses queryWithHasOne as default method to generate base query.
	 */
	public static function findPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = static::$modelTable;

		$sort		= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;
		$multiSite	= isset( $config[ 'multiSite' ] ) ? $config[ 'multiSite' ] : true;

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

		// Restrict to site
		if( $modelClass::$multiSite && $multiSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ] = Yii::$app->core->siteId;

			// Get data from all sites irrespective of current site.
			if( isset( $config[ 'ignoreMultiSite' ] ) && $config[ 'ignoreMultiSite' ] ) {

				unset( $config[ 'conditions' ][ "$modelTable.siteId" ] );
			}
		}

		// Default query
		if( !isset( $config[ 'query' ] ) ) {

			$modelClass			= static::$modelClass;

			$config[ 'query' ]	= $modelClass::queryWithHasOne();
		}

		// Default search column
		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = "$modelTable.name";
		}

		return static::findDataProvider( $config );
	}

	/**
	 * Generate search query using tag and category tables.
	 */
	public static function findPageForSearch( $config = [] ) {

		// Model
		$modelClass			= static::$modelClass;
		$modelTable			= static::$modelTable;
		$parentType			= isset( $config[ 'parentType' ] ) ? $config[ 'parentType' ] : static::$parentType;

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
		$query				= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::queryWithAll();

		// Public Page
		$public				= isset( $config[ 'public' ] ) ? $config[ 'public' ] : false;

		if( $public ) {

			$interfaces		= class_implements( $modelClass );

			if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IApproval' ] ) ) {

				$config[ 'filters' ][]	= [ 'in', "$modelTable.status", [ IApproval::STATUS_ACTIVE, IApproval::STATUS_FROJEN ] ];
			}

			if( isset( $interfaces[ 'cmsgears\core\common\models\interfaces\IVisibility' ] ) ) {

				$config[ 'conditions' ][ "$modelTable.visibility" ]	= IVisibility::VISIBILITY_PUBLIC;
			}
		}

		// Tag
		if( isset( $keywords ) || isset( $config[ 'tag' ] ) || strcmp( $sortParam, 'tag' ) == 0 ) {

			$query->leftJoin( $mtagTable, "$modelTable.id=$mtagTable.parentId AND $mtagTable.parentType='$parentType' AND $mtagTable.active=TRUE" )
				->leftJoin( $tagTable, "$mtagTable.modelId=$tagTable.id" );
		}

		if( isset( $config[ 'tag' ] ) ) {

			$query->andWhere( "$tagTable.id=" . $config[ 'tag' ]->id );
		}

		// Category
		if( isset( $keywords ) || isset( $config[ 'category' ] ) || strcmp( $sortParam, 'category' ) == 0 ) {

			$query->leftJoin( $mcategoryTable, "$modelTable.id=$mcategoryTable.parentId AND $mcategoryTable.parentType='$parentType' AND $mcategoryTable.active=TRUE" )
				->leftJoin( $categoryTable, "$mcategoryTable.modelId=$categoryTable.id" );
		}

		if( isset( $config[ 'category' ] ) ) {

			$category			= isset( $config[ 'category' ] ) ? $config[ 'category' ] : null;
			$categoryService	= Yii::$app->factory->get( 'categoryService' );

			if( !isset( $category ) ) {

				$category	= $categoryService->getBySlug( $sortParam, true );
			}

			$cids	= $categoryService->getChildIdList( $category );
			$cids	= join( ',', $cids );

			$query->andWhere( "$categoryTable.id in ($cids)" );
		}

		// Search
		if( isset( $keywords ) ) {

			$config[ 'search-col' ][] = "$modelTable.name";
			$config[ 'search-col' ][] = "$categoryTable.name";
			$config[ 'search-col' ][] = "$tagTable.name";
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
	 * Advanced of searchModels having more options to search.
	 */
	public static function searchModels( $config = [] ) {

		// model class
		$modelClass		= static::$modelClass;

		// query generation
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

		// selected columns
		$columns		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ 'id' ];

		// array result
		$array			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : false;

		// Selective columns ---

		if( count( $columns ) > 0 ) {

			$query->select( $columns );
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			$query	= $query->andWhere( $conditions );
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

		// Print to Debug -------

		if( isset( $config[ 'pquery' ] ) && $config[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Models ---------------

		$models = $query->asArray( $array )->all();

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

		if( isset( $conditions ) ) {

			$query->select( $column )->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $column )->from( $tableName );
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

		// query generation
		$query			= new Query();
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

		// additional data
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

		// Conditions ----------

		if( isset( $conditions ) ) {

			$query->select( [ "$nameColumn as $nameAlias", "$valueColumn as $valueAlias" ] )
				  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( [ "$nameColumn as $nameAlias", "$valueColumn as $valueAlias" ] )
				  ->from( $tableName );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
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

			$query	= $query->andWhere( $conditions );
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
