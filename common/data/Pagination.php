<?php
namespace cmsgears\core\common\data;

// Yii Imports
use Yii;
use yii\web\Request;

class Pagination extends \yii\data\Pagination {

	public $excludeParams	= [];

    public function createUrl( $page, $pageSize = null, $absolute = false ) {

		$page		= (int) $page;
		$pageSize	= (int) $pageSize;

		if( ( $params = $this->params ) === null ) {

			$request	= Yii::$app->getRequest();
            $params		= $request instanceof Request ? $request->getQueryParams() : [];
        }

		if( $page > 0 || $page == 0 && $this->forcePageParam ) {

			$params[ $this->pageParam ] = $page + 1;
        }
		else {

			unset( $params[ $this->pageParam ] );
        }

        if( $pageSize <= 0 ) {

            $pageSize = $this->getPageSize();
        }

        if( $pageSize != $this->defaultPageSize ) {

			$params[ $this->pageSizeParam ] = $pageSize;
        }
		else {

			unset( $params[ $this->pageSizeParam ] );
        }

		$params[0]	= $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
		$urlManager	= $this->urlManager === null ? Yii::$app->getUrlManager() : $this->urlManager;

		foreach ( $this->excludeParams as $param ) {

			unset( $params[ $param ] );
		}

		if( $absolute ) {

			return $urlManager->createAbsoluteUrl( $params );
		}
		else {

			return $urlManager->createUrl( $params );
        }
    }
}
