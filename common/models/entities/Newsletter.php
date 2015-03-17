<?php
namespace cmsgears\core\common\models\entities;

use cmsgears\core\common\utilities\MessageUtil;

class Newsletter extends NamedActiveRecord {

	// Instance Methods --------------------------------------------

	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}	

	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'id', 'description', 'content' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'content' => ''
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER;
	}

	// Newsletter

	public static function findById( $id ) {

		return Newsletter::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Newsletter::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>