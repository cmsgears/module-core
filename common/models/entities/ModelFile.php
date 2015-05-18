<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelFile Entity
 *
 * @property int $parentId
 * @property string $parentType
 * @property int $fileId
 * @property short $order 
 */
class ModelFile extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return CmgFile - associated file
	 */
	public function getFile() {

    	return $this->hasOne( CmgFile::className(), [ 'id' => 'fileId' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'fileId' ], 'required' ],
            [ 'order', 'safe' ],
            [ [ 'parentId', 'fileId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'fileId' => 'File',
			'order' => 'File Order'
		];
	}

	// ModelFile -------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_FILE;
	}

	// ModelFile -------------------------
	
	// Read ----

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @return array - ModelFile by parent id and type
	 */
	public static function findByParentIdType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] )->all();
	}
	
	// Delete ----

	/**
	 * Delete all the entries associated with the parent.
	 */
	public static function deleteByParentIdType( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] );
	}

	/**
	 * Delete all entries related to a file
	 */
	public static function deleteByFileId( $fileId ) {

		self::deleteAll( 'fileId=:id', [ ':id' => $fileId ] );
	}
}

?>