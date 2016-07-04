<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * ModelAttribute Entity - A model can have only one attribute with the same name for particular type.
 *
 * @property long $id
 * @property long $modelId
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
abstract class ModelAttribute extends Attribute {

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
            [ [ 'modelId', 'name' ], 'required' ],
            [ [ 'id', 'value' ], 'safe' ],
            [ [ 'name', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ [ 'modelId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'modelId', 'name', 'type' ], 'unique', 'targetAttribute' => [ 'modelId', 'name', 'type' ] ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'type', 'valueType', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'valueType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelAttribute ------------------------

	abstract public function getParent();

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ModelAttribute ------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'parent' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithParent( $config = [] ) {

		$config[ 'relations' ]	= [ 'parent' ];

		return parent::queryWithAll( $config );
	}

    public static function queryByName( $modelId, $name ) {

		return static::find()->where( 'modelId=:pid AND name=:name', [ ':pid' => $modelId, ':name' => $name ] );
    }

    public static function queryByType( $modelId, $type ) {

		return static::find()->where( 'modelId=:pid AND type=:type', [ ':pid' => $modelId, ':type' => $type ] );
    }

    public static function queryByNameType( $modelId, $name, $type ) {

		return static::find()->where( 'modelId=:pid AND name=:name AND type=:type', [ ':pid' => $modelId, ':name' => $name, ':type' => $type ] );
    }

	// Read - Find ------------

	/**
	 * @param integer $modelId
	 * @param string $name
	 * @return array - ModelAttribute by name
	 */
	public static function findByName( $modelId, $name ) {

		return self::queryByName( $modelId, $name )->all();
	}

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @return array - ModelAttribute by type
	 */
	public static function findByType( $modelId, $type ) {

		return self::queryByType( $modelId, $type )->all();
	}

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @param string $name
	 * @return ModelAttribute - by type and name
	 */
	public static function findByNameType( $modelId, $name, $type ) {

		return self::queryByNameType( $modelId, $type, $name )->one();
	}

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether attribute exist by type and name
	 */
	public static function isExistByNameType( $modelId, $name, $type ) {

		$config = self::findByNameType( $modelId, $type, $name );

		return isset( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>