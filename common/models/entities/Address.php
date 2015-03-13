<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\utilities\MessageUtil;

class Address extends ActiveRecord {

	// Instance methods --------------------------------------------------

	// db columns

	public function getId() {

		return $this->address_id;
	}

	public function unsetId() {

		unset( $this->address_id );
	}

	public function getLine1() {

		return $this->address_line1;
	}

	public function setLine1( $line1 ) {

		$this->address_line1 = $line1;
	}

	public function getLine2() {

		return $this->address_line2;
	}

	public function setLine2( $line2 ) {

		$this->address_line2 = $line2;
	}
	
	public function getCity() {

		return $this->address_city;
	}

	public function setCity( $city ) {

		$this->address_city = $city;
	}
	
	public function getProvinceId() {

		return $this->address_province;
	}
	
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'province_id' => 'address_province' ] );
	}

	public function setProvinceId( $provinceid ) {

		$this->address_province = $provinceid;
	}

	public function getZip() {

		return $this->address_zip;
	}

	public function setZip( $zip ) {

		$zip				= strtoupper( $zip );
		$this->address_zip 	= $zip;
	}

	public function copyForUpdate( $addressToUpdate ) {

		$addressToUpdate->address_line_1	= $this->address_line_1;
		$addressToUpdate->address_line_2	= $this->address_line_2;
		$addressToUpdate->address_city		= $this->address_city;
		$addressToUpdate->address_province	= $this->address_province;
		$addressToUpdate->address_zip		= $this->address_zip;
	}

	public function rules() {
		
		return  [
			//Section A1 Ordinary Address
			[ [ 'address_line1', 'address_city', 'address_province', 'address_zip' ], 'required' ],
			[ 'address_line2', 'safe' ],
			[ [ 'address_line1', 'address_line2' ], 'alphanumpun' ],
			[ 'address_city', 'alphanumspace' ],
			[ 'address_zip','alphanumhyphen'],
			[ 'address_province', 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => MessageUtil::getMessage( CoreGlobal::ERROR_SELECT ) ]
		];
	}

	public function attributeLabels() {

		return [
			'address_line1' => 'Line 1',
			'address_line2' => 'Line 2',
			'address_city' => 'City',
			'address_province' => 'Province',
			'address_zip' => 'Postal Code'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_ADDRESS;
	}

	public static function findById( $id ) {

		return self::find()->where( 'address_id=:id', [ ':id' => $id ] )->one();
	}
}

?>