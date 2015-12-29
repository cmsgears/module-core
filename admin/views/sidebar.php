<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->cmgCore;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-identity" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-identity' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-user"></span></div>
			<div class="colf colf4x3">Identity</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-identity' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<?php if( $user->isPermitted( 'rbac' ) ) { ?>
					<li class='matrix <?php if( strcmp( $child, 'matrix' ) == 0 ) echo 'active';?>'><?= Html::a( "Access Matrix", ['/cmgcore/permission/matrix'] ) ?></li>
					<li class='role <?php if( strcmp( $child, 'role' ) == 0 ) echo 'active';?>'><?= Html::a( "Roles", ['/cmgcore/role/all'] ) ?></li>
					<li class='permission <?php if( strcmp( $child, 'permission' ) == 0 ) echo 'active';?>'><?= Html::a( "Permissions", ['/cmgcore/permission/all'] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( 'identity' ) ) { ?>
					<li class='admin <?php if( strcmp( $child, 'admin' ) == 0 ) echo 'active';?>'><?= Html::a( "Admins", ['/cmgcore/admin/all'] ) ?></li>
					<li class='user <?php if( strcmp( $child, 'user' ) == 0 ) echo 'active';?>'><?= Html::a( "Users", ['/cmgcore/user/all'] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-newsletter" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-newsletter' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-newsletter"></span></div>
			<div class="colf colf4x3">Newsletters</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-newsletter' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='newsletter <?php if( strcmp( $child, 'newsletter' ) == 0 ) echo 'active';?>'><?= Html::a( "Newsletters", ['/cmgcore/newsletter/all'] ) ?></li>
				<li class='member <?php if( strcmp( $child, 'member' ) == 0 ) echo 'active';?>'><?= Html::a( "Members", ['/cmgcore/newsletter/members'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-newsletter" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-core' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-settings"></span></div>
			<div class="colf colf4x3">Core</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-core' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='gallery <?php if( strcmp( $child, 'gallery' ) == 0 ) echo 'active';?>'><?= Html::a( "Galleries", [ '/cmgcore/gallery/all' ] ) ?></li>
				<?php if( Yii::$app->cmgCore->multiSite ) { ?>
					<li class='site <?php if( strcmp( $child, 'site' ) == 0 ) echo 'active';?>'><?= Html::a( "Sites", [ '/cmgcore/sites/all' ] ) ?></li>
				<?php } ?>
				<li class='country <?php if( strcmp( $child, 'country' ) == 0 ) echo 'active';?>'><?= Html::a( "Countries", [ '/cmgcore/country/all' ] ) ?></li>
				<li class='dropdown <?php if( strcmp( $child, 'dropdown' ) == 0 ) echo 'active';?>'><?= Html::a( "Dropdowns", [ '/cmgcore/dropdown/all' ] ) ?></li>
				<li class='checkbox-group <?php if( strcmp( $child, 'checkbox-group' ) == 0 ) echo 'active';?>'><?= Html::a( "Checkbox Groups", [ '/cmgcore/checkbox-group/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>