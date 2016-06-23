<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * Locale Entity
 *
 * @property long $id
 * @property string $code
 * @property string $name
 */
class Locale extends \cmsgears\core\common\models\base\NamedEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    public function rules() {

        // model rules
        $rules = [
            [ [ 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'unique' ],
            [ 'code', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->smallText ],
            [ 'name', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
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
            'code' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
        ];
    }

    // Locale ----------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_LOCALE;
    }

    // Locale ----------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return Locale - by code
     */
    public static function findByCode( $code ) {

        return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
    }

    // Update -------------

    // Delete -------------
}

?>