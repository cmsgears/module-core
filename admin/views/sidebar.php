<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$core	= Yii::$app->core;
$user	= $core->getUser();
$siteId	= Yii::$app->core->siteId;

$siteRootUrl = Yii::$app->core->getSiteRootUrl();
?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_IDENTITY ) ) { ?>
	<div id="sidebar-rbac" class="collapsible-tab has-children <?= $parent === 'sidebar-rbac' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-user"></span></div>
			<div class="tab-title">RBAC</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-rbac' ? 'expanded visible' : null ?>">
			<ul>
				<?php if( $user->isPermitted( CoreGlobal::PERM_RBAC ) ) { ?>
					<li class='role <?= $child === 'role' ? 'active' : null ?>'><?= Html::a( "Roles", [ "$siteRootUrl/core/role/all" ] ) ?></li>
					<li class='perm <?= $child === 'perm' ? 'active' : null ?>'><?= Html::a( "Permissions", [ "$siteRootUrl/core/permission/all" ] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( CoreGlobal::PERM_IDENTITY ) ) { ?>
					<li class='user <?= $child === 'user' ? 'active' : null ?>'><?= Html::a( "Users", [ "$siteRootUrl/core/user/all" ] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-core" class="collapsible-tab has-children <?= $parent === 'sidebar-core' ? 'active' : null ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-core"></span></div>
			<div class="tab-title">Core</div>
		</div>
		<div class="tab-content clear <?= $parent === 'sidebar-core' ? 'expanded visible' : null ?>">
			<ul>
				<li class='site <?= $child === 'site' ? 'active' : null ?>'><?= Html::a( 'Sites', [ "$siteRootUrl/core/sites/all" ] ) ?></li>
				<li class='theme <?= $child === 'theme' ? 'active' : null ?>'><?= Html::a( 'Themes', [ "$siteRootUrl/core/theme/all" ] ) ?></li>
				<li class='testimonials <?= $child === 'testimonials' ? 'active' : null ?>'><?= Html::a( 'Testimonials', [ "$siteRootUrl/core/testimonial/all?pid=$siteId" ] ) ?></li>
				<li class='feedbacks <?= $child === 'feedbacks' ? 'active' : null ?>'><?= Html::a( 'Feedbacks', [ "$siteRootUrl/core/feedback/all?pid=$siteId" ] ) ?></li>
				<li class='country <?= $child === 'country' ? 'active' : null ?>'><?= Html::a( 'Countries', [ "$siteRootUrl/core/country/all" ] ) ?></li>
				<li class='option-group <?= $child === 'option-group' ? 'active' : null ?>'><?= Html::a( 'Option Groups', [ "$siteRootUrl/core/option-group/all" ] ) ?></li>
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
				<li class='sfile <?= $child === 'sfile' ? 'active' : null ?>'><?= Html::a( 'Shared Files', [ "$siteRootUrl/core/file/shared/all" ] ) ?></li>
				<li class='dfile <?= $child === 'dfile' ? 'active' : null ?>'><?= Html::a( 'Direct Files', [ "$siteRootUrl/core/file/direct/all" ] ) ?></li>
				<?php if( $user->isPermitted( CoreGlobal::PERM_GALLERY_ADMIN ) ) { ?>
					<li class='gallery <?= $child === 'gallery' ? 'active' : null ?>'><?= Html::a( 'Galleries', [ "$siteRootUrl/core/gallery/all" ] ) ?></li>
					<li class='gallery-template <?= $child === 'gallery-template' ? 'active' : null ?>'><?= Html::a( 'Gallery Templates', [ "$siteRootUrl/core/gallery/template/all" ] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>
