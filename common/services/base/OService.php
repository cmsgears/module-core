<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;

use yii\db\Query;
use yii\data\ActiveDataProvider;

use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

/**
 * The class Service defines several useful methods used for pagination and generating map and list by specifying the columns.
 */
abstract class OService extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT	= 10;

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

	public static $parentType	= null;

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// OService --------------------------

	// Data Provider ------

	public function getDataProvider( $config ) {

		$modelClass	= static::$modelClass;

		return self::findDataProvider( $modelClass, $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getById( $id ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findById( $id );
	}

    // Read - Lists ----

	public function getIdNameList( $config = [] ) {

		return self::generateIdNameList( 'id', 'name', static::$modelTable, $config );
	}

    // Read - Maps -----

	public function getIdNameMap( $config = [] ) {

		return self::generateMap( 'id', 'name', static::$modelTable, $config );
	}

	// Read - Others ---
/*
	// Create ----------------

 	public function create( $model, $config = [] ) {

		$model->save();

		return $model;
 	}

	// Update ----------------

	public function update( $model, $config = [] ) {

        $existingModel  = $this->getById( $model->id );

		$existingModel->copyForUpdateFrom( $model, [ 'name' ] );

		$existingModel->update();

		return $existingModel;
 	}

	public function updateMultiple( $models, $config = [] ) {

		foreach( $models as $model ) {

			$this->update( $model );
		}
	}

	public function updateByForm( $form, $config = [] ) {

		$existingModel  = $this->getById( $model->id );
		$attributes 	= $form->getArrayToStore();

		foreach ( $attributes as $key => $value ) {

			$existingModel->$key	= $value;
		}

		$this->update( $existingModel );
 	}

	// Delete -------------

	public function delete( $model ) {

		$existingModel  = $this->getById( $model->id );

		$existingModel->delete();
	}
*/
	// Static Methods ----------------------------------------------

	// < parent class > ------------------

	// <Service> -------------------------

	// Data Provider ------

	/**
	 * The method findDataProvider accept the entity class and various parameters to generate the active data provider.
	 * @param array $config
	 * @return ActiveDataProvider
	 */
	public static function findDataProvider( $modelClass, $config ) {

		// params to generate query
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$searchParam	= isset( $config[ 'search-param' ] ) ? $config[ 'search-param' ] : 'keywords';
		$searchCol		= isset( $config[ 'search-col' ] ) ? $config[ 'search-col' ] : null;
		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;
		$route			= isset( $config[ 'route' ] ) ? $config[ 'route' ] : null;

		$pagination	= array();

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->andWhere( $conditions );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( $searchParam );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery 	= self::generateSearchQuery( $searchCol, $searchTerms );
			$query 			= $query->andWhere( $searchQuery );
		}

		// Additional Filters --

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
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

	public static function findPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable = static::$modelTable;

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

		if( !isset( $config[ 'query' ] ) ) {

			$modelClass 		= static::$modelClass;

			$config[ 'query' ] 	= $modelClass::queryWithAll();
		}

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::findDataProvider( $modelClass, $config );
	}

	/**
	 * Generate search query using content, tag and category tables.
	 */
	public static function findPageForSearch( $config = [] ) {

		$modelClass			= static::$modelClass;
		$modelTable 		= static::$modelTable;
		$parentType			= static::$parentType;

		// DB Tables
		$mcategoryTable		= CoreTables::TABLE_MODEL_CATEGORY;
		$categoryTable		= CoreTables::TABLE_CATEGORY;
		$mtagTable			= CoreTables::TABLE_MODEL_TAG;
		$tagTable			= CoreTables::TABLE_TAG;

		// Search Query
		$query			 	= $modelClass::queryWithAll();

		// Tag
		if( isset( $search ) || isset( $config[ 'tag' ] ) ) {

			$query->leftJoin( $mtagTable, "$modelTable.id=$mtagTable.parentId AND $mtagTable.parentType='$parentType' AND $mtagTable.active=TRUE" )
				->leftJoin( $tagTable, "$mtagTable.tagId=$tagTable.id" );
		}

		if( isset( $config[ 'tag' ] ) ) {

			$query->andWhere( "$tagTable.id=" . $config[ 'tag' ]->id );
		}

		// Category
		if( isset( $search ) || isset( $config[ 'category' ] ) ) {

			$query->leftJoin( "$mcategoryTable", "$modelTable.id=$mcategoryTable.parentId AND $mcategoryTable.parentType='$parentType' AND $mcategoryTable.active=TRUE" )
				->leftJoin( "$categoryTable", "$mcategoryTable.categoryId=$categoryTable.id" );
		}

		if( isset( $config[ 'category' ] ) ) {

			$query->andWhere( "$categoryTable.id=" . $config[ 'category' ]->id );
		}

		// Search
		$search 	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $search ) ) {

			$config[ 'search-col' ]	= [ "$categoryTable.name", "$tagTable.name" ];
		}

		if( isset( $search ) || isset( $config[ 'category' ] ) ) {

			$query->groupBy( "$modelTable.id" );
		}

		$config[ 'query' ]	= $query;

		return static::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public static function findById( $id ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findById( $id );
	}

	public static function findModels( $config = [] ) {

		return $modelClass::findAll( $config );
	}

    // Read - Lists ----

	public static function findIdNameList( $config = [] ) {

		return self::generateIdNameList( 'id', 'name', static::$modelTable, $config );
	}

	/**
	 * The method generateList returns an array of list for given column
	 * @param string $column
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function generateList( $column, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$order			= isset( $config[ 'order' ] ) ? $config[ 'order' ] : null;

		$query			= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $column )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $column )
				  ->from( $tableName );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		if( isset( $order ) ) {

			$query->orderBy( $order );
		}

		// Get column
		$query->column();

		// Create command
		$command 	= $query->createCommand();

		// Execute the command
		$list 		= $command->queryAll();
		$resultList	= [];

		foreach ( $list as $item ) {

			$resultList[] = $item[ $column ];
		}

		return $resultList;
	}

	/**
	 * The method generateNameValueList returns an array of associative arrays having name and value as keys for the defined columns.
	 * @param string $nameColumn
	 * @param string $valueColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function generateNameValueList( $nameColumn, $valueColumn, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

		$query 			= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
				  ->from( $tableName );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

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

	/**
	 * The method generateIdNameList returns an array of associative arrays having id and name as keys for the defined columns.
	 * @param string $idColumn
	 * @param string $nameColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function generateIdNameList( $idColumn, $nameColumn, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
				  ->from( $tableName );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

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

	public static function findIdNameMap( $config = [] ) {

		return self::generateMap( 'id', 'name', static::$modelTable, $config );
	}

	/**
	 * The method findMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param string $keyColumn
	 * @param string $valueColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function generateMap( $keyColumn, $valueColumn, $tableName, $config = [] ) {

		$arrayList  = self::generateNameValueList( $keyColumn, $valueColumn, $tableName, $config );
		$map		= [];

		foreach ( $arrayList as $item ) {

			$map[ $item['name'] ] = $item['value'];
		}

		return $map;
	}

	/**
	 * The method findObjectMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param string $keyColumn
	 * @param object $entity
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function generateObjectMap( $keyColumn, $entity, $config = [] ) {

		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $entity::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$map			= [];

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->andWhere( $conditions );
		}

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		$objects	= $query->all();

		foreach ( $objects as $object ) {

			$map[ $object->$keyColumn ] = $object;
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

						$searchQuery = 	"( $query )";
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

	// Create -------------

	// Update -------------

	// Delete -------------
}

?>