<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * It's useful to bind multiple models to a model using checkbox group.
 */
class Binder extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $binderId; // Binder to which binded data need to be binded

	public $allData 	= []; // All data

	public $bindedData 	= []; // Data to be active and submitted by user

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

        return [
            [ [ 'binderId' ], 'required' ],
            [ [ 'allData', 'bindedData' ], 'safe' ],
            [ 'binderId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Binder --------------------------------

}

?>