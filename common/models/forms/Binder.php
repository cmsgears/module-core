<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

// Yii Imports
use yii\helpers\ArrayHelper;

/**
 * It's useful to bind multiple models to a model using checkbox group.
 *
 * @property integer $binderId
 * @property array $all
 * @property array $binded
 * @property array $value
 *
 * @since 1.0.0
 */
class Binder extends BaseForm {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $binderId; // Binder to which data need to be binded

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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ 'binderId', 'required' ],
			[ [ 'all', 'binded', 'value' ], 'safe' ],
			// Other
			[ 'binderId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Binder --------------------------------

	/**
	 * Merge the given data either in csv format or as array.
	 *
	 * @param array|string $data
	 * @param boolean $csv
	 */
	public function mergeWithBinded( $data, $csv = false ) {

		$merge = [];

		if( $csv && strlen( $merge ) > 1 ) {

			$merge = preg_split( '/,/',  $data );
		}

		$this->binded = ArrayHelper::merge( $this->binded, $merge );
	}

	/**
	 * Generate and return an array having id as key and value.
	 *
	 * @return array
	 */
	public function getValueMap() {

		$count = count( $this->binded );

		$map = [];

		for( $i = 0; $i < $count; $i++ ) {

			$map[ $this->binded[ $i ] ] = $this->value[ $i ];
		}

		return $map;
	}

	/**
	 * Analyze and merge the key and value from given map with the binder.
	 *
	 * @param array $map
	 */
	public function mergeMapWithBinded( $map ) {

		foreach( $map as $key => $value ) {

			if( !in_array( $key, $this->binded ) ) {

				$this->binded[] = $key;
				$this->value[]	= $value;
			}
		}
	}

}
