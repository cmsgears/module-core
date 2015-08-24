<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NewsletterMember Entity
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property integer $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class NewsletterMember extends CmgEntity {

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

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'email' ], 'required' ],
            [ [ 'id', 'name', 'active' ], 'safe' ],
            [ 'email', 'email' ],
            [ 'active', 'number', 'integerOnly' => true ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

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

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER_MEMBER;
	}

	// NewsletterMember ------------------
	
	// Read ----

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

	// Delete ----
	
	/**
	 * Delete the member.
	 */
	public static function deleteByEmail( $email ) {

		self::deleteAll( 'email=:email', [ ':email' => $email ] );
	}
}

?>