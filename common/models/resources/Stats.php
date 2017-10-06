<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

/**
 * Stats Entity
 *
 * @property long $id
 * @property string $table
 * @property long $rows
 */
class Stats extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

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

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'table', 'rows' ], 'required' ],
			[ 'id', 'safe' ],
			// Text Limit
			[ 'table', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Others
			[ 'rows' => 'number', 'integerOnly' => true ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'table' => 'table',
			'rows' => 'rows'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Stats ---------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_STATS;
	}

	// CMG parent classes --------------------

	// Stats ---------------------------------

	// Read - Query -----------

	// Read - Find ------------

	public static function getRowCount( $table, $type = null ) {

		// Row count for model type
		if( isset( $type ) ) {

		}
		// Row count for model
		else {

			$query = self::find()->where( '`table`=:table AND type IS NULL', [ ':table' => $table ] )->one();

			if( isset( $query ) ) {

				return $query->rows();
			}
		}

		return 0;
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
