<?php
namespace cmsgears\core\common\models\entities;

/**
 * Tag Entity
 *
 * @property integer $id
 * @property string $name
 */
class Tag extends NamedCmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'name' => 'Name'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_TAG;
	}

	// Tag -------------------------------

}

?>