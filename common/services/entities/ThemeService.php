<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
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

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
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
