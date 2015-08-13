<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NewsletterList Entity
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $memberId
 */
class NewsletterList extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'newsletterId', 'memberId' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'newsletterId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'newsletterId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NEWSLETTER ),
			'memberId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MEMBER )
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