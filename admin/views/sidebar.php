<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
$siteId	= Yii::$app->core->siteId;
?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( 'identity' ) ) { ?>
	<div id="sidebar-identity" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-identity' ) == 0 ) echo 'active'; ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-user"></span></div>
			<div class="tab-title">Identity</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-identity' ) == 0 ) echo 'expanded visible'; ?>">
			<ul>
				<?php if( $user->isPermitted( 'rbac' ) ) { ?>
					<li class='matrix <?php if( strcmp( $child, 'matrix' ) == 0 ) echo 'active';?>'><?= Html::a( "Roles Matrix", ['/core/permission/matrix'] ) ?></li>
					<li class='role <?php if( strcmp( $child, 'role' ) == 0 ) echo 'active';?>'><?= Html::a( "Roles", ['/core/role/all'] ) ?></li>
					<li class='role-level <?php if( strcmp( $child, 'role-level' ) == 0 ) echo 'active';?>'><?= Html::a( "Role Hierarchy", ['/core/role/hierarchy'] ) ?></li>
					<li class='perm <?php if( strcmp( $child, 'perm' ) == 0 ) echo 'active';?>'><?= Html::a( "Permissions", ['/core/permission/all'] ) ?></li>
					<li class='perm-group <?php if( strcmp( $child, 'perm-group' ) == 0 ) echo 'active';?>'><?= Html::a( "Permissions Groups", ['/core/permission/groups'] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( 'identity' ) ) { ?>
					<li class='admin <?php if( strcmp( $child, 'admin' ) == 0 ) echo 'active';?>'><?= Html::a( "Admins", ['/core/admin/all'] ) ?></li>
					<li class='user <?php if( strcmp( $child, 'user' ) == 0 ) echo 'active';?>'><?= Html::a( "Users", ['/core/user/all'] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'core' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-core" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-core' ) == 0 ) echo 'active'; ?>">
		<span class="marker"></span>
		<div class="tab-header">
			<div class="tab-icon"><span class="cmti cmti-check-report"></span></div>
			<div class="tab-title">Core</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-core' ) == 0 ) echo 'expanded visible'; ?>">
			<ul>
				<li class='site <?php if( strcmp( $child, 'site' ) == 0 ) echo 'active';?>'><?= Html::a( 'Sites', [ '/core/sites/all' ] ) ?></li>
				<li class='theme <?php if( strcmp( $child, 'theme' ) == 0 ) echo 'active';?>'><?= Html::a( 'Themes', [ '/core/theme/all' ] ) ?></li>
				<li class='testimonials <?php if( strcmp( $child, 'testimonials' ) == 0 ) echo 'active';?>'><?= Html::a( 'Testimonials', [ "/core/testimonial/all?pid=$siteId" ] ) ?></li>
				<li class='gallery <?php if( strcmp( $child, 'gallery' ) == 0 ) echo 'active';?>'><?= Html::a( 'Galleries', [ '/core/gallery/all' ] ) ?></li>
				<li class='gallery-template <?php if( strcmp( $child, 'gallery-template' ) == 0 ) echo 'active';?>'><?= Html::a( 'Gallery Templates', [ '/core/gallery/template/all' ] ) ?></li>
				<li class='country <?php if( strcmp( $child, 'country' ) == 0 ) echo 'active';?>'><?= Html::a( 'Countries', [ '/core/country/all' ] ) ?></li>
				<li class='option-group <?php if( strcmp( $child, 'option-group' ) == 0 ) echo 'active';?>'><?= Html::a( 'Option Groups', [ '/core/optiongroup/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>