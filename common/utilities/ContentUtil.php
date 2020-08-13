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
 * ContentUtil generates the meta data for models. The generated data can be used
 * for SEO purpose.
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

			$seoData = $model->getDataPluginMeta( CoreGlobal::DATA_SEO );

			$summary	= isset( $seoData ) && !empty( $seoData->summary ) ? filter_var( $seoData->summary, FILTER_SANITIZE_STRING ) : null;
			$desc		= isset( $seoData ) && !empty( $seoData->description ) ? filter_var( $seoData->description, FILTER_SANITIZE_STRING ) : $model->description;
			$keywords	= isset( $seoData ) && !empty( $seoData->keywords ) ? filter_var( $seoData->keywords, FILTER_SANITIZE_STRING ) : null;
			$robot		= isset( $seoData ) && !empty( $seoData->robot ) ? filter_var( $seoData->robot, FILTER_SANITIZE_STRING ) : null;

			// Model
			$view->params[ 'model' ]	= $model;
			$view->params[ 'seo' ]		= $seoData;

			// SEO H1 - Summary
			$view->params[ 'summary' ] = !empty( $summary ) ? $summary : ( isset( $model->summary ) && !empty( $model->summary ) ? $model->summary : $model->description );

			// SEO Meta Tags - Description, Keywords, Robot Text
			$view->params[ 'desc' ]		= $desc;
			$view->params[ 'keywords' ]	= $keywords;
			$view->params[ 'robot' ]	= $robot;

			// SEO - Page Title
			$siteTitle	= $coreProperties->getSiteTitle();
			$seoName	= isset( $seoData ) && !empty( $seoData->name ) ? $seoData->name : $model->name;

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
