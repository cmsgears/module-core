<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * Country Entity
 *
 * @property long $id
 * @property string $code
 * @property string $codeNum
 * @property string $name
 */
class Country extends \cmsgears\core\common\models\base\NamedEntity {

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
            [ [ 'name', 'code' ], 'required' ],
            [ 'id', 'safe' ],
            [ [ 'code', 'codeNum' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->smallText ],
            [ 'name', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
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
            'code' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE ),
            'codeNum' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE_NUM ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Country -------------------------------

    /**
     * @return array - list of Province having all the provinces belonging to this country
     */
    public function getProvinces() {

        return $this->hasMany( Province::className(), [ 'countryId' => 'id' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_COUNTRY;
    }

	// CMG parent classes --------------------

	// Country -------------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Country by code
     */
    public static function findByCode( $code ) {

        return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>