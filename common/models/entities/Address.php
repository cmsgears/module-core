<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Address extends ActiveRecord {

	// Instance methods --------------------------------------------------

	// db columns

	public function getId() {

		return $this->address_id;
	}

	public function unsetId() {

		unset( $this->address_id );
	}

	public function getCountryId() {

		return $this->address_country;
	}
	
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'country_id' => 'address_country' ] );
	}

	public function setCountryId( $id ) {

		$this->address_country = $id;
	}

	public function getProvinceId() {

		return $this->address_province;
	}
	
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'province_id' => 'address_province' ] );
	}

	public function setProvinceId( $id ) {

		$this->address_province = $id;
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

	public function getLine3() {

		return $this->address_line3;
	}

	public function setLine3( $line3 ) {

		$this->address_line3 = $line3;
	}

	public function getCity() {

		return $this->address_city;
	}

	public function setCity( $city ) {

		$this->address_city = $city;
	}

	public function getZip() {

		return $this->address_zip;
	}

	public function setZip( $zip ) {

		$zip				= strtoupper( $zip );
		$this->address_zip 	= $zip;
	}

	public function getFirstName() {

		return $this->address_firstname;
	}

	public function setFirstName( $name ) {

		$this->address_firstname = $name;
	}

	public function getLastName() {

		return $this->address_lastname;
	}

	public function setLastName( $name ) {

		$this->address_lastname = $name;
	}

	public function getPhone() {

		return $this->address_phone;
	}

	public function setPhone( $phone ) {

		$this->address_phone = $phone;
	}

	public function getEmail() {

		return $this->address_email;
	}

	public function setEmail( $email ) {

		$this->address_email = $email;
	}

	public function getFax() {

		return $this->address_fax;
	}

	public function setFax( $fax ) {

		$this->address_fax = $fax;
	}

	public function copyForUpdate( $addressToUpdate ) {

		$addressToUpdate->address_country	= $this->address_country;
		$addressToUpdate->address_province	= $this->address_province;
		$addressToUpdate->address_line_1	= $this->address_line_1;
		$addressToUpdate->address_line_2	= $this->address_line_2;
		$addressToUpdate->address_city		= $this->address_city;
		$addressToUpdate->address_zip		= $this->address_zip;
		$addressToUpdate->address_firstname	= $this->address_firstname;
		$addressToUpdate->address_lastname	= $this->address_lastname;
		$addressToUpdate->address_phone		= $this->address_phone;
		$addressToUpdate->address_email		= $this->address_email;
		$addressToUpdate->address_fax		= $this->address_fax;
	}

	public function rules() {
		
		return  [
			[ [ 'address_line1', 'address_city', 'address_province', 'address_zip' ], 'required' ],
			[ [ 'address_line2', 'address_firstname', 'address_lastname', 'address_phone', 'address_email', 'address_fax' ], 'safe' ],
			[ [ 'address_line1', 'address_line2' ], 'alphanumpun' ],
			[ 'address_city', 'alphanumspace' ],
			[ 'address_zip','alphanumhyphen'],
			[ [ 'address_country', 'address_province' ], 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => MessageUtil::getMessage( CoreGlobal::ERROR_SELECT ) ]
		];
	}

	public function attributeLabels() {

		return [
			'address_Country' => 'Country',
			'address_province' => 'Province',
			'address_line1' => 'Line 1',
			'address_line2' => 'Line 2',
			'address_city' => 'City',
			'address_zip' => 'Postal Code',
			'address_firstname' => 'First Name',
			'address_lastname' => 'Last Name',
			'address_phone' => 'Phone',
			'address_email' => 'Email',
			'address_fax' => 'Fax'
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