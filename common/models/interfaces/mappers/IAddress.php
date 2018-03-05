<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The IAddress declare the methods implemented by AddressTrait. It can be implemented
 * by entities, resources and models which need multiple addresses.
 *
 * @since 1.0.0
 */
interface IAddress {

	/**
	 * Return all the address mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelAddress[]
	 */
	public function getModelAddresses();

	/**
	 * Return all the active address mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelAddress[]
	 */
	public function getActiveModelAddresses();

	/**
	 * Return the address mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelAddress[]
	 */
	public function getModelAddressesByType( $type, $active = true );

	/**
	 * Return all the address associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Address[]
	 */
	public function getAddresses();

	/**
	 * Return all the active address associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Address[]
	 */
	public function getActiveAddresses();

	/**
	 * Return addresses associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Address[]
	 */
	public function getAddressesByType( $type, $active = true );

	// Some useful methods in case model allows only one address for specific address type.

	/**
	 * Return the address associated with the parent for default type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getDefaultAddress();

	/**
	 * Return the address associated with the parent for primary type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getPrimaryAddress();

	/**
	 * Return the address associated with the parent for residential type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getResidentialAddress();

	/**
	 * Return the address associated with the parent for shipping type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getShippingAddress();

	/**
	 * Return the address associated with the parent for billing type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getBillingAddress();

	/**
	 * Return the address associated with the parent for office type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getOfficeAddress();

	/**
	 * Return the address associated with the parent for mailing type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getMailingAddress();

	/**
	 * Return the address associated with the parent for branch type.
	 *
	 * @return \cmsgears\core\common\models\resources\Address
	 */
	public function getBranchAddress();
}
