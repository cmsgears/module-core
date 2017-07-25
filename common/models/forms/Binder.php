<?php
namespace cmsgears\core\common\models\forms;

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

	public $all		= []; // All data

	public $binded	= []; // Data to be active and submitted by user

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
			[ [ 'all', 'binded' ], 'safe' ],
			[ 'binderId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Binder --------------------------------

}
