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
 * @property string $iso
 * @property string $name
 */
class Province extends \cmsgears\core\common\models\base\Entity {

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
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'countryId', 'code' ], 'unique', 'targetAttribute' => [ 'countryId', 'code' ] ],
            [ [ 'countryId', 'name' ], 'unique', 'targetAttribute' => [ 'countryId', 'name' ] ],
            [ 'countryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'code', 'iso' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
            [ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'code', 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'countryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
            'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
            'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME )
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    // Province ------------------------------

    /**
     * @return Country - parent country for province
     */
    public function getCountry() {

        return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
    }

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_PROVINCE;
    }

    // CMG parent classes --------------------

    // Province ------------------------------

    // Read - Query -----------

    public static function queryWithAll( $config = [] ) {

        $relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country' ];
        $config[ 'relations' ]	= $relations;

        return parent::queryWithAll( $config );
    }

    public static function queryWithCountry( $config = [] ) {

        $config[ 'relations' ]	= [ 'country' ];

        return parent::queryWithAll( $config );
    }

    // Read - Find ------------

    /**
     * @return array - by country id
     */
    public static function findByCountryId( $countryId ) {

        return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
    }

    // Create -----------------

    // Update -----------------

    // Delete -----------------
}
