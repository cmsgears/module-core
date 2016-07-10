<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\mappers\ModelForm;

use cmsgears\core\common\services\interfaces\resources\IFormService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

class FormService extends \cmsgears\core\common\services\base\EntityService implements IFormService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Form';

	public static $modelTable	= CoreTables::TABLE_FORM;

	public static $parentType	= CoreGlobal::TYPE_FORM;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FormService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'updatedAt' => SORT_ASC ],
	                'desc' => ['updatedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ],
	        'defaultOrder' => [
	        	'cdate' => SORT_DESC
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'templateId', 'name', 'description', 'successMessage', 'captcha', 'visibility', 'active', 'userMail', 'adminMail', 'htmlOptions', 'data' ]
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		ModelForm::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// FormService ---------------------------

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
