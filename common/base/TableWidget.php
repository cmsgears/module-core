<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

/**
 * The class TableWidget can be used by widgets showing data tables.
 */
abstract class TableWidget extends GridWidget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template	= 'table';

	public $provider 	= false; // Model will be used instead of data provider

	// Widget Sections - Disabled by default

	public $sort 	= false;
	public $filter 	= false;
	public $report 	= false;
	public $option 	= false;
	public $header 	= false;
	public $footer 	= false;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

        if( !isset( $this->models ) ) {

            throw new InvalidConfigException( 'Models are required to generate table.' );
        }
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TableWidget ---------------------------

}
