<?php
namespace cmsgears\core\api\controllers\api;

use Yii;

trait RestTrait {

	public function behaviors() {
		
		return array_merge(parent::behaviors(), [

			// For cross-domain AJAX request
			'corsFilter'  => [
				'class' => \yii\filters\Cors::className(),
				'cors'  => [
					// restrict access to domains:
					'Origin'                           => static::allowedDomains(),
					//'Access-Control-Request-Headers'    => ['Content-Type', 'accessToken', 'site'  ],
					'Access-Control-Request-Headers'    => ['*'  ],
					'Access-Control-Request-Method'    => ['POST', 'GET', 'OPTIONS'],
					'Access-Control-Allow-Credentials' => null,
					'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
					'Content-Type'						=> 'application/json;chareset=utf-8'
				],
			]

		]);
	}
	
	public function setSite( ) {

		$slug = Yii::$app->request->headers->get('site') ?? 'main';

		$siteService	= Yii::$app->factory->get( 'siteService' );
		$site			=  $siteService->getBySlug( $slug );

		if( isset( $site ) ) {

			// Configure Site
			Yii::$app->core->site		= $site;
			Yii::$app->core->siteId		= $site->id;
			Yii::$app->core->siteSlug	= $site->slug;
		}
	}
}

?>