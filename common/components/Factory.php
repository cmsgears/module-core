<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\components;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\base\Component;

/**
 * The Core Factory component initialise the services available in Core Module.
 *
 * @since 1.0.0
 */
class Factory extends Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();
		$this->registerSystemServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
		$this->registerMapperAliases();
		$this->registerEntityAliases();
		$this->registerSystemAliases();
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IAddressService', 'cmsgears\core\common\services\resources\AddressService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ILocationService', 'cmsgears\core\common\services\resources\LocationService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ICategoryService', 'cmsgears\core\common\services\resources\CategoryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFileService', 'cmsgears\core\common\services\resources\FileService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFormFieldService', 'cmsgears\core\common\services\resources\FormFieldService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IFormService', 'cmsgears\core\common\services\resources\FormService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IGalleryService', 'cmsgears\core\common\services\resources\GalleryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelCommentService', 'cmsgears\core\common\services\resources\ModelCommentService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelHierarchyService', 'cmsgears\core\common\services\resources\ModelHierarchyService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelMessageService', 'cmsgears\core\common\services\resources\ModelMessageService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelAnalyticsService', 'cmsgears\core\common\services\resources\ModelAnalyticsService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IModelMetaService', 'cmsgears\core\common\services\resources\ModelMetaService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IObjectMetaService', 'cmsgears\core\common\services\resources\ObjectMetaService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IOptionService', 'cmsgears\core\common\services\resources\OptionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ISiteAccessService', 'cmsgears\core\common\services\resources\SiteAccessService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ISiteMetaService', 'cmsgears\core\common\services\resources\SiteMetaService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\IStatsService', 'cmsgears\core\common\services\resources\StatsService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\resources\ITagService', 'cmsgears\core\common\services\resources\TagService' );
	}

	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelAddressService', 'cmsgears\core\common\services\mappers\ModelAddressService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelLocationService', 'cmsgears\core\common\services\mappers\ModelLocationService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelCategoryService', 'cmsgears\core\common\services\mappers\ModelCategoryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelFileService', 'cmsgears\core\common\services\mappers\ModelFileService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelFormService', 'cmsgears\core\common\services\mappers\ModelFormService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelGalleryService', 'cmsgears\core\common\services\mappers\ModelGalleryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelObjectService', 'cmsgears\core\common\services\mappers\ModelObjectService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelOptionService', 'cmsgears\core\common\services\mappers\ModelOptionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\IModelTagService', 'cmsgears\core\common\services\mappers\ModelTagService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\mappers\ISiteMemberService', 'cmsgears\core\common\services\mappers\SiteMemberService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ILocaleService', 'cmsgears\core\common\services\entities\LocaleService' );

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ICountryService', 'cmsgears\core\common\services\entities\CountryService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IProvinceService', 'cmsgears\core\common\services\entities\ProvinceService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IRegionService', 'cmsgears\core\common\services\entities\RegionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ICityService', 'cmsgears\core\common\services\entities\CityService' );

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ISiteService', 'cmsgears\core\common\services\entities\SiteService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IThemeService', 'cmsgears\core\common\services\entities\ThemeService' );

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\ITemplateService', 'cmsgears\core\common\services\entities\TemplateService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IObjectService', 'cmsgears\core\common\services\entities\ObjectDataService' );

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IPermissionService', 'cmsgears\core\common\services\entities\PermissionService' );
		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IRoleService', 'cmsgears\core\common\services\entities\RoleService' );

		$factory->set( 'cmsgears\core\common\services\interfaces\entities\IUserService', 'cmsgears\core\common\services\entities\UserService' );
	}

	public function registerSystemServices() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( '<interface path>', '<classpath>' );
	}

	/**
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'addressService', 'cmsgears\core\common\services\resources\AddressService' );
		$factory->set( 'locationService', 'cmsgears\core\common\services\resources\LocationService' );
		$factory->set( 'categoryService', 'cmsgears\core\common\services\resources\CategoryService' );
		$factory->set( 'fileService', 'cmsgears\core\common\services\resources\FileService' );
		$factory->set( 'formFieldService', 'cmsgears\core\common\services\resources\FormFieldService' );
		$factory->set( 'formService', 'cmsgears\core\common\services\resources\FormService' );
		$factory->set( 'galleryService', 'cmsgears\core\common\services\resources\GalleryService' );
		$factory->set( 'modelCommentService', 'cmsgears\core\common\services\resources\ModelCommentService' );
		$factory->set( 'modelHierarchyService', 'cmsgears\core\common\services\resources\ModelHierarchyService' );
		$factory->set( 'modelMessageService', 'cmsgears\core\common\services\resources\ModelMessageService' );
		$factory->set( 'modelAnalyticsService', 'cmsgears\core\common\services\resources\ModelAnalyticsService' );
		$factory->set( 'modelMetaService', 'cmsgears\core\common\services\resources\ModelMetaService' );
		$factory->set( 'objectMetaService', 'cmsgears\core\common\services\resources\ObjectMetaService' );
		$factory->set( 'optionService', 'cmsgears\core\common\services\resources\OptionService' );
		$factory->set( 'siteAccessService', 'cmsgears\core\common\services\resources\SiteAccessService' );
		$factory->set( 'siteMetaService', 'cmsgears\core\common\services\resources\SiteMetaService' );
		$factory->set( 'statsService', 'cmsgears\core\common\services\resources\StatsService' );
		$factory->set( 'tagService', 'cmsgears\core\common\services\resources\TagService' );
	}

	/**
	 * Registers mapper aliases.
	 */
	public function registerMapperAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'modelAddressService', 'cmsgears\core\common\services\mappers\ModelAddressService' );
		$factory->set( 'modelLocationService', 'cmsgears\core\common\services\mappers\ModelLocationService' );
		$factory->set( 'modelCategoryService', 'cmsgears\core\common\services\mappers\ModelCategoryService' );
		$factory->set( 'modelFileService', 'cmsgears\core\common\services\mappers\ModelFileService' );
		$factory->set( 'modelFormService', 'cmsgears\core\common\services\mappers\ModelFormService' );
		$factory->set( 'modelGalleryService', 'cmsgears\core\common\services\mappers\ModelGalleryService' );
		$factory->set( 'modelObjectService', 'cmsgears\core\common\services\mappers\ModelObjectService' );
		$factory->set( 'modelOptionService', 'cmsgears\core\common\services\mappers\ModelOptionService' );
		$factory->set( 'modelTagService', 'cmsgears\core\common\services\mappers\ModelTagService' );
		$factory->set( 'siteMemberService', 'cmsgears\core\common\services\mappers\SiteMemberService' );
	}

	/**
	 * Registers entity aliases.
	 */
	public function registerEntityAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'localeService', 'cmsgears\core\common\services\entities\LocaleService' );

		$factory->set( 'countryService', 'cmsgears\core\common\services\entities\CountryService' );
		$factory->set( 'provinceService', 'cmsgears\core\common\services\entities\ProvinceService' );
		$factory->set( 'regionService', 'cmsgears\core\common\services\entities\RegionService' );
		$factory->set( 'cityService', 'cmsgears\core\common\services\entities\CityService' );

		$factory->set( 'siteService', 'cmsgears\core\common\services\entities\SiteService' );
		$factory->set( 'themeService', 'cmsgears\core\common\services\entities\ThemeService' );

		$factory->set( 'templateService', 'cmsgears\core\common\services\entities\TemplateService' );
		$factory->set( 'objectService', 'cmsgears\core\common\services\entities\ObjectDataService' );

		$factory->set( 'permissionService', 'cmsgears\core\common\services\entities\PermissionService' );
		$factory->set( 'roleService', 'cmsgears\core\common\services\entities\RoleService' );

		$factory->set( 'userService', 'cmsgears\core\common\services\entities\UserService' );
	}

	/**
	 * Registers system aliases.
	 */
	public function registerSystemAliases() {

		$factory = Yii::$app->factory->getContainer();

		//$factory->set( '<name>', '<classpath>' );
	}

}
