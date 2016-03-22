<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\validators\FilterValidator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NewsletterMember Entity
 *
 * @property long $id
 * @property string $name
 * @property string $email
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class NewsletterMember extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    /**
     * @return string representation of flag
     */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active ); 
    }

    // yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'email' ], 'required' ],
            [ [ 'id', 'name' ], 'safe' ],
            [ 'email', 'email' ],
            [ 'active', 'boolean' ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name', 'email' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
        ];
    }

    // NewsletterMember-------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_NEWSLETTER_MEMBER;
    }

    // NewsletterMember-------------------

    // Create -------------

    // Read ---------------

    /**
     * @param string $email
     * @return NewsletterMember - by email
     */
    public static function findByEmail( $email ) {

        return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
    }

    /**
     * @param string $email
     * @return NewsletterMember - by email
     */
    public static function isExistByEmail( $email ) {

        $member = self::findByEmail( $email );

        return isset( $member );
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete the member.
     */
    public static function deleteByEmail( $email ) {

        self::deleteAll( 'email=:email', [ ':email' => $email ] );
    }
}

?>