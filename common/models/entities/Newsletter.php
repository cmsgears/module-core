<?php
namespace cmsgears\core\common\models\entities;

/**
 * Newsletter Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property longtext $content
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 */
class Newsletter extends NamedCmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return User
	 */
	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}	

	/**
	 * @return User
	 */
	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'content' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'content' => 'Content'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER;
	}

	// Newsletter ------------------------

}

?>