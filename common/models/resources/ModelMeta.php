<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\ResourceTrait;

/**
 * ModelMeta Entity
 *
 * @property long $id
 * @property long $parentId
 * @property string $parentType
 * @property string $type
 * @property string $valueType
 * @property string $name
 * @property string $label
 * @property string $value
 */
class ModelMeta extends \cmsgears\core\common\models\base\Meta {

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

	use ResourceTrait;

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
			[ [ 'parentId', 'parentType', 'name' ], 'required' ],
			[ [ 'id', 'value' ], 'safe' ],
			// Unique
			[ [ 'parentId', 'parentType', 'name', 'type' ], 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'name', 'type' ] ],
			// Text Limit
			[ [ 'parentType', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			// Other
			[ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// trim if required
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'label', 'value', 'valueType', 'type' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'valueType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelMeta -----------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_META;
	}

	// CMG parent classes --------------------

	// ModelMeta -----------------------------

	// Read - Query -----------

	public static function queryByName( $parentId, $parentType, $name ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] );
	}

	public static function queryByType( $parentId, $parentType, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] );
	}

	public static function queryByNameType( $parentId, $parentType, $name, $type ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type AND name=:name', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type, ':name' => $name ] );
	}

	// Read - Find ------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @return ModelMeta - by name
	 */
	public static function findByName( $parentId, $parentType, $name ) {

		return self::queryByName( $parentId, $parentType, $name )->all();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return array - ModelMeta by type
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::queryByType( $parentId, $parentType, $type )->all();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return ModelMeta - by type and name
	 */
	public static function findByNameType( $parentId, $parentType, $name, $type ) {

		return self::queryByNameType( $parentId, $parentType, $name, $type )->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether meta exist by type and name
	 */
	public static function isExistByNameType( $parentId, $parentType, $name, $type ) {

		$config = self::queryByNameType( $parentId, $parentType, $name, $type )->one();

		return isset( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
