<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * Province Entity
 *
 * @property long $id
 * @property long $countryId
 * @property string $code
 * @property string $codeNum
 * @property string $name
 */
class Province extends \cmsgears\core\common\models\base\CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'countryId', 'code' ], 'unique', 'targetAttribute' => [ 'countryId', 'code' ] ],
            [ 'countryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'code', 'codeNum' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->smallText ],
            [ 'name', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'name', 'alphanumpun' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name', 'code' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'countryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
            'code' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE ),
            'codeNum' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE_NUM ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
        ];
    }

    // Province --------------------------

    /**
     * Validates whether a province existing with the same name for same country.
     */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCountryId( $this->name, $this->countryId ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates whether a province existing with the same name for same country.
     */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingProvince = self::findByNameCountryId( $this->name, $this->countryId );

            if( isset( $existingProvince ) && $this->id != $existingProvince->id ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * @return Country - parent country for province
     */
    public function getCountry() {

        return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_PROVINCE;
    }

    // Province --------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return array - by country id
     */
    public static function findByCountryId( $countryId ) {

        return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
    }

    /**
     * @return Province - by name and country id
     */
    public static function findByNameCountryId( $name, $countryId ) {

        return self::find()->where( 'countryId=:id AND name=:name', [ ':id' => $countryId, ':name' => $name ] )->one();
    }

    /**
     * @return Province - check whether a province exist by the provided name and country id
     */
    public static function isExistByNameCountryId( $name, $countryId ) {

        $province = self::findByNameCountryId( $name, $countryId );

        return isset( $province );
    }

    /**
     * @return Province - by code and country id
     */
    public static function findByCodeCountryId( $code, $countryId ) {

        return self::find()->where( 'countryId=:id AND code=:code', [ ':id' => $countryId, ':code' => $code ] )->one();
    }

    // Update -------------

    // Delete -------------
}

?>