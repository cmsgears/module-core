<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;

/**
 * ContentUtil generates the meta data for models. The generated data
 * can be used for SEO purpose.
 *
 * @since 1.0.0
 */
class ContentUtil {

	/**
	 * Generates the meta data of Model using SEO Data.
	 *
	 * @param \yii\web\View $view The current view being rendered by controller.
	 * @param array $config
	 * @return array having model meta data.
	 */
	public static function initModel( $view, $config = [] ) {

		$model = isset( $view->params[ 'model' ] ) ? $view->params[ 'model' ] : self::findModel( $config );

		if( isset( $model ) ) {

			$coreProperties = CoreProperties::getInstance();
			$seoData		= $model->getDataPluginMeta( CoreGlobal::DATA_SEO );

			// Model
			$view->params[ 'model' ]	= $model;
			$view->params[ 'seo' ]		= $seoData;

			// SEO H1 - Page Summary
			$view->params[ 'summary' ]	= isset( $seoData ) && !empty( $seoData->summary ) ? $seoData->summary : ( isset( $model->summary ) && !empty( $model->summary ) ? $model->summary : $model->description );

			// SEO Meta Tags - Description, Keywords, Robot Text
			$view->params[ 'desc' ]		= isset( $seoData ) && !empty( $seoData->description ) ? $seoData->description : $model->description;
			$view->params[ 'keywords' ]	= isset( $seoData ) && !empty( $seoData->keywords ) ? $seoData->keywords : null;
			$view->params[ 'robot' ]	= isset( $seoData ) && !empty( $seoData->robot ) ? $seoData->robot : null;

			// SEO - Page Title
			$siteTitle		= $coreProperties->getSiteTitle();
			$seoName		= isset( $seoData ) && !empty( $seoData->name ) ? $seoData->name : $model->name;

			$view->title = "$seoName | $siteTitle";
		}
	}

	public static function findModel( $config ) {

		if( empty( $config[ 'service' ] ) ) {

			return;
		}

		$service	= Yii::$app->factory->get( $config[ 'service' ] );
		$typed		= isset( $config[ 'typed' ] ) ? $config[ 'typed' ] : true;
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : $service->getParentType();
		$slug		= Yii::$app->request->queryParams[ 'slug' ];

		if( $typed ) {

			return $service->getBySlugType( $slug, $type );
		}
		else {

			return $service->getBySlug( $slug );
		}
	}

}
