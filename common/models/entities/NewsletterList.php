<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NewsletterList Entity
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $memberId
 * @property integer $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt 
 */
class NewsletterList extends CmgEntity {

	// Instance Methods --------------------------------------------

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

        return [
            [ [ 'newsletterId', 'memberId' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'newsletterId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'newsletterId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NEWSLETTER ),
			'memberId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MEMBER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER_LIST;
	}

	// NewsletterMember ------------------

	// Delete ----

	/**
	 * Delete the member.
	 */
	public static function deleteByNewsletterId( $newsletterId ) {

		self::deleteAll( 'newsletterId=:id', [ ':id' => $newsletterId ] );
	}

	/**
	 * Delete the member.
	 */
	public static function deleteByMemberId( $memberId ) {

		self::deleteAll( 'memberId=:id', [ ':id' => $memberId ] );
	}
}

?>