<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\FileTrait;

/**
 * Gallery Entity - The primary class.
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Gallery extends NamedCmgEntity {

	use FileTrait;

	public $fileType	= CoreGlobal::FILE_TYPE_GALLERY;

	// Instance Methods --------------------------------------------

	// yii\base\Model ---------------------

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description'
		];
	}
	
	// Gallery ----------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------
	
	public static function tableName() {

		return CoreTables::TABLE_GALLERY;
	}

	// Gallery ----------------------------

}

?>