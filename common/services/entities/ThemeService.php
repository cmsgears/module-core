<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Theme;

use cmsgears\core\common\services\interfaces\entities\IThemeService;

use cmsgears\core\common\services\traits\NameTrait;
use cmsgears\core\common\services\traits\SlugTrait;

/**
 * The class ThemeService is base class to perform database activities for Theme Entity.
 */
class ThemeService extends \cmsgears\core\common\services\base\EntityService implements IThemeService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Theme';

	public static $modelTable	= CoreTables::TABLE_THEME;

	public static $parentType	= CoreGlobal::TYPE_THEME;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTrait;
	use SlugTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ThemeService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
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
	                'label' => "Slug"
	            ],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Type"
	            ],
	            'default' => [
	                'asc' => [ "$modelTable.default" => SORT_ASC ],
	                'desc' => [ "$modelTable.default" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Default"
	            ],
	            'renderer' => [
	                'asc' => [ "$modelTable.renderer" => SORT_ASC ],
	                'desc' => [ "$modelTable.renderer" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Renderer"
	            ],
	            'base' => [
	                'asc' => [ "$modelTable.basePath" => SORT_ASC ],
	                'desc' => [ "$modelTable.basePath" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => "Base Path"
	            ]
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

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) && $status === 'default' ) {

			$config[ 'conditions' ][ "$modelTable.default" ]	= true;
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content",
			'default' => "$modelTable.default", 'renderer' => "$modelTable.renderer"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Uncheck default for all other themes
		if( $model->default ) {

			Theme::updateAll( [ 'default' => false ], '`default`=1' );
		}

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'description', 'default', 'basePath', 'renderer' ];

		// Uncheck default for all other themes
		if( $model->default ) {

			Theme::updateAll( [ 'default' => false ], '`default`=1' );
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * Make the default theme available for all sites in case no theme is selected.
	 *
	 * @param type $model
	 * @param type $config
	 * @return type
	 */
	public function makeDefault( Theme $model, $config = [] ) {

		$type = CoreGlobal::TYPE_SITE;

		if( $model->type !== $type ) {

			return false;
		}

		// Disable All
		Theme::updateAll( [ 'default' => false ], "`default`=1 AND `type`='$type'" );

		// Make Default
		$model->default = true;

		// Update
		return parent::update( $model, [
			'attributes' => [ 'default' ]
		]);
	}

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

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ThemeService --------------------------

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
