<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Address extends CmgEntity {

	// Instance methods --------------------------------------------------
	
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'id' => 'provinceId' ] );
	}

	public function rules() {

		return  [
			[ [ 'line1', 'city', 'provinceId', 'zip' ], 'required' ],
			[ [ 'firstname', 'lastname', 'phone', 'email', 'fax' ], 'safe' ],
			[ [ 'line1', 'line2', 'line3' ], 'alphanumpun' ],
			[ 'city', 'alphanumspace' ],
			[ 'zip','alphanumhyphen'],
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => MessageUtil::getMessage( CoreGlobal::ERROR_SELECT ) ]
		];
	}

	public function attributeLabels() {

		return [
			'countryId' => 'Country',
			'provinceId' => 'Province',
			'line1' => 'Line 1',
			'line2' => 'Line 2',
			'line3' => 'Line 3',
			'city' => 'City',
			'zip' => 'Postal Code',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'phone' => 'Phone',
			'email' => 'Email',
			'fax' => 'Fax'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_ADDRESS;
	}

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>