<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * SiteAttribute Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
class SiteAttribute extends \cmsgears\core\common\models\base\Attribute {

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
            [ [ 'siteId', 'name' ], 'required' ],
            [ [ 'id', 'value' ], 'safe' ],
            [ [ 'name', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'label', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'siteId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
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
			'siteId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'valueType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE )
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

            if( self::isExistByTypeName( $this->siteId, $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one attribute exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingConfig = self::findByTypeName( $this->siteId, $this->type, $this->name );

			if( isset( $existingConfig ) && $existingConfig->id != $this->id ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// SiteAttribute -------------------------

	public function getParent() {

		return $this->hasOne( Content::className(), [ 'id' => 'siteId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_SITE_ATTRIBUTE;
	}

	// CMG parent classes --------------------

	// SiteAttribute -------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * @param integer $siteId
	 * @param string $type
	 * @return array - SiteAttribute by type
	 */
	public static function findByType( $siteId, $type ) {

		return self::find()->where( 'siteId=:pid AND type=:type', [ ':pid' => $siteId, ':type' => $type ] )->all();
	}

	/**
	 * @param integer $siteId
	 * @param string $name
	 * @return SiteAttribute - by name
	 */
	public static function findByName( $siteId, $name ) {

		return self::find()->where( 'siteId=:pid AND name=:name', [ ':pid' => $siteId, ':name' => $name ] )->all();
	}

	/**
	 * @param integer $siteId
	 * @param string $type
	 * @param string $name
	 * @return SiteAttribute - by type and name
	 */
	public static function findByTypeName( $siteId, $type, $name ) {

		return self::find()->where( 'siteId=:pid AND type=:type AND name=:name', [ ':pid' => $siteId, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @param integer $siteId
	 * @param string $type
	 * @param string $name
	 * @return boolean - Check whether attribute exist by type and name
	 */
	public static function isExistByTypeName( $siteId, $type, $name ) {

		$config = self::findByTypeName( $siteId, $type, $name );

		return isset( $config );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>