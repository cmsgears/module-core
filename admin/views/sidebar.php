<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
$siteId	= Yii::$app->core->siteId;
?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_IDENTITY ) ) { ?>
	<div id="sidebar-identity" class="collapsible-tab has-children <?= $parent === 'sidebar-identity' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-user"></span></div>
			<div class="tab-title">Identity</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-identity' ? 'expanded visible' : null ?>">
			<ul>
				<?php if( $user->isPermitted( CoreGlobal::PERM_RBAC ) ) { ?>
					<li class='matrix <?= $child === 'matrix' ? 'active' : null ?>'><?= Html::a( "Roles Matrix", ['/core/permission/matrix'] ) ?></li>
					<li class='role <?= $child === 'role' ? 'active' : null ?>'><?= Html::a( "Roles", ['/core/role/all'] ) ?></li>
					<li class='role-level <?= $child === 'role-level' ? 'active' : null ?>'><?= Html::a( "Role Hierarchy", ['/core/role/hierarchy'] ) ?></li>
					<li class='perm <?= $child === 'perm' ? 'active' : null ?>'><?= Html::a( "Permissions", ['/core/permission/all'] ) ?></li>
					<li class='perm-group <?= $child === 'perm-group' ? 'active' : null ?>'><?= Html::a( "Permissions Groups", ['/core/permission/groups'] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( CoreGlobal::PERM_IDENTITY ) ) { ?>
					<li class='admin <?= $child === 'admin' ? 'active' : null ?>'><?= Html::a( "Admins", ['/core/admin/all'] ) ?></li>
					<li class='user <?= $child === 'user' ? 'active' : null ?>'><?= Html::a( "Users", ['/core/user/all'] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-core" class="collapsible-tab has-children <?= $parent === 'sidebar-core' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-check-report"></span></div>
			<div class="tab-title">Core</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-core' ? 'expanded visible' : null ?>">
			<ul>
				<li class='site <?= $child === 'site' ? 'active' : null ?>'><?= Html::a( 'Sites', [ '/core/sites/all' ] ) ?></li>
				<li class='theme <?= $child === 'theme' ? 'active' : null ?>'><?= Html::a( 'Themes', [ '/core/theme/all' ] ) ?></li>
				<li class='testimonials <?= $child === 'testimonials' ? 'active' : null ?>'><?= Html::a( 'Testimonials', [ "/core/testimonial/all?pid=$siteId" ] ) ?></li>
				<li class='country <?= $child === 'country' ? 'active' : null ?>'><?= Html::a( 'Countries', [ '/core/country/all' ] ) ?></li>
				<li class='option-group <?= $child === 'option-group' ? 'active' : null ?>'><?= Html::a( 'Option Groups', [ '/core/optiongroup/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_GALLERY_ADMIN ) ) { ?>
	<div id="sidebar-gallery" class="collapsible-tab has-children <?= $parent === 'sidebar-gallery' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-design-tools"></span></div>
			<div class="tab-title">Galleries</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-gallery' ? 'expanded visible' : null ?>">
			<ul>
				<li class='gallery <?= $child === 'gallery' ? 'active' : null ?>'><?= Html::a( 'Galleries', [ '/core/gallery/all' ] ) ?></li>
				<li class='template <?= $child === 'template' ? 'active' : null ?>'><?= Html::a( 'Templates', [ '/core/gallery/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_FILE_ADMIN ) ) { ?>
	<div id="sidebar-file" class="collapsible-tab has-children <?= $parent === 'sidebar-file' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-document"></span></div>
			<div class="tab-title">Files</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-file' ? 'expanded visible' : null ?>">
			<ul>
				<li class='file <?= $child === 'file' ? 'active' : null ?>'><?= Html::a( 'Files', [ '/core/file/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>