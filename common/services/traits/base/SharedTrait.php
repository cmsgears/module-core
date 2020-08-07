<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

/**
 * SharedTrait can be used to share the models among backend and frontend.
 *
 * @property boolean $backend
 * @property boolean $frontend
 * @property boolean $shared
 *
 * @since 1.0.0
 */
trait SharedTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// SharedTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function getSharedPage( $config = [] ) {

		$config[ 'conditions' ][ 'shared' ] = true;

		return $this->getPage( $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getBackendSharedPage( $config = [] ) {

		$config[ 'conditions' ][ 'backend' ]	= true;
		$config[ 'conditions' ][ 'frontend' ]	= false;
		$config[ 'conditions' ][ 'shared' ]		= true;

		return $this->getPage( $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getDirectPage( $config = [] ) {

		$config[ 'conditions' ][ 'shared' ] = false;

		return $this->getPage( $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getBackendDirectPage( $config = [] ) {

		$config[ 'conditions' ][ 'backend' ]	= true;
		$config[ 'conditions' ][ 'frontend' ]	= false;
		$config[ 'conditions' ][ 'shared' ]		= false;

		return $this->getPage( $config );
	}

	/**
	 * @inheritdoc
	 */
	public function getSharedPageByOwnerId( $ownerId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.shared" ]		= true;
		$config[ 'conditions' ][ "$modelTable.createdBy" ]	= $ownerId;

		return $this->getPageByOwnerId( $ownerId, $config );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SharedTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
