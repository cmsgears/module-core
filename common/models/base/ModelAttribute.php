<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * ModelAttribute Entity
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
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'modelId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
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

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->modelId, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->coreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->modelId, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id ) {

				$this->addError( $attribute, Yii::$app->coreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

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

	// Read - Find ------------

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @return array - ModelAttribute by type
	 */
	public static function findByType( $modelId, $type ) {

		return self::find()->where( 'modelId=:pid AND type=:type', [ ':pid' => $modelId, ':type' => $type ] )->all();
	}

	/**
	 * @param integer $modelId
	 * @param string $name
	 * @return ModelAttribute - by name
	 */
	public static function findByName( $modelId, $name ) {

		return self::find()->where( 'modelId=:pid AND name=:name', [ ':pid' => $modelId, ':name' => $name ] )->all();
	}

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @param string $name
	 * @return ModelAttribute - by type and name
	 */
	public static function findByTypeName( $modelId, $type, $name ) {

		return self::find()->where( 'modelId=:pid AND type=:type AND name=:name', [ ':pid' => $modelId, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $modelId
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether attribute exist by type and name
	 */
	public static function isExistByTypeName( $modelId, $type, $name ) {

		$config = self::findByTypeName( $modelId, $type, $name );

		return isset( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>