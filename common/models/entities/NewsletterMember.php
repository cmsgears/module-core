<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports

/**
 * NewsletterMember Entity
 *
 * @property integer $id
 * @property string $email
 */
class NewsletterMember extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'email' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ 'email', 'email' ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL )
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
	 * @param string $id
	 * @return NewsletterMember - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

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