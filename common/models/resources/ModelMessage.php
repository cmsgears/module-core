<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Locale;

use cmsgears\core\common\models\traits\ResourceTrait;

/**
 * ModelMessage Entity
 *
 * @property long $id
 * @property long $localeId
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $value
 */
class ModelMessage extends \cmsgears\core\common\models\base\Resource {

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
            [ [ 'localeId', 'parentId', 'parentType', 'name' ], 'required' ],
            [ [ 'id', 'value' ], 'safe' ],
            [ [ 'localeId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType', 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	/**
	 * Validates to ensure that only one message exist with one name for a particular locale.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one message exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingMessage = self::findByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId );

			if( isset( $existingMessage ) && $existingMessage->id != $this->id ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// ModelMessage --------------------------

	/**
	 * @return Locale - parent locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// CMG parent classes --------------------

	// ModelMessage --------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'locale' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithLocale( $config = [] ) {

		$config[ 'relations' ]	= [ 'locale' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return ModelMessage - by name and locale id
	 */
	public static function findByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name AND localeId=:lid' )
							->addParams( [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name, ':lid' => $localeId ] )
							->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return boolean - check whether message exist by name and locale id
	 */
	public static function isExistByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return isset( self::findByNameLocaleId( $parentId, $parentType, $name, $localeId ) );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all entries related to a locale
	 */
	public static function deleteByLocaleId( $localeId ) {

		self::deleteAll( 'localeId=:id', [ ':id' => $localeId ] );
	}
}

?>