<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use yii\helpers\ArrayHelper;

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

	public $value	= []; // Additional data required in some cases

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
			[ [ 'all', 'binded', 'value' ], 'safe' ],
			[ 'binderId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Binder --------------------------------

	public function mergeWithBinded( $merge, $csv = false ) {

		if( $csv && strlen( $csv ) > 1 ) {

			$merge = preg_split( '/,/',  $merge );
		}
		else {

			$merge = [];
		}

		$this->binded = ArrayHelper::merge( $this->binded, $merge );
	}

	public function getValueMap() {

		$count	= count( $this->binded );
		$map	= [];

		for( $i = 0; $i < $count; $i++ ) {

			$map[ $this->binded[ $i ] ] = $this->value[ $i ];
		}

		return $map;
	}

	public function mergeMapWithBinded( $map ) {

		foreach ( $map as $key => $value ) {

			if( !in_array( $key, $this->binded ) ) {

				$this->binded[] = $key;
				$this->value[]	= $value;
			}
		}
	}
}
