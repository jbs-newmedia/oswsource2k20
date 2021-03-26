<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?><!DOCTYPE html>
<html lang="<?php echo \osWFrame\Core\Language::getCurrentLanguage('short') ?>">
<head>
	<?php echo $this->getHead(); ?>
</head>
<body id="page-top">
<?php echo $this->getBody(); ?>

<body class="bg-light vh-100" style="background-attachment: fixed !important;">

<nav class="navbar navbar-expand-md navbar-light bg-white mb-4 fixed-top shadow border-bottom border-secondary" style="border-width:10px !important">
	<div class="container<?php if($Tool->getFluidNavigation()===true):?>-fluid<?php endif?>">
		<a class="navbar-brand d-flex align-items-center" href="<?php echo $this->buildhrefLink('default', 'action=""')?>">
			<div class="navbar-brand-icon">
				<img src="resources/img/oswtools-logo.svg" style="height: 36px;"/>
			</div>
			<div class="navbar-brand-text mx-3 text-secondary"><strong>osW</strong>Tools:<?php echo $Tool->getStringValue('name')?></div>
		</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#PageNavigation" aria-controls="PageNavigation" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="PageNavigation">
			<ul class="navbar-nav ml-auto">
<?php foreach ($Tool->getNavigation() as $element_name => $element_details):?>
<?php if($element_details['links']==[]):?>
				<li class="nav-item<?php if($element_details['active']===true):?> active<?php endif?>">
					<a class="nav-link" href="<?php echo $this->buildhrefLink('current', 'action='.$element_details['action'])?>"><?php if(isset($element_details['icon'])):?><i class="<?php echo $element_details['icon'];?>"></i> <?php endif?><?php echo $element_details['title'];?></a>
				</li>
<?php else:?>
				<li class="nav-item dropdown<?php if($element_details['active']===true):?> active<?php endif?>">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown_<?php echo $element_name?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if(isset($element_details['icon'])):?><i class="<?php echo $element_details['icon'];?>"></i> <?php endif?><?php echo $element_details['title'];?></a>
					<div class="dropdown-menu<?php if($Tool->getFluidNavigation()===true):?> dropdown-menu-right<?php endif?>" aria-labelledby="dropdown_<?php echo $element_name?>">
<?php foreach ($element_details['links'] as $element_link_name => $element_link_details):?>
						<a class="dropdown-item<?php if($element_link_details['active']===true):?> active<?php endif?>" href="<?php echo $this->buildhrefLink('current', 'action='.$element_link_details['action'])?>"><?php if(isset($element_link_details['icon'])):?><i class="<?php echo $element_link_details['icon'];?>"></i> <?php endif?><?php echo $element_link_details['title'];?></a>
<?php endforeach;?>
					</div>
				</li>
<?php endif?>
<?php endforeach;?>
			</ul>
		</div>
	</div>
</nav>

<main role="main" class="container-fluid " style="padding-top:6rem; padding-bottom:6rem;">

	<div class="container<?php if($Tool->getFluidContent()===true):?>-fluid<?php endif?> card shadow"<?php if($Tool->getVH()===true):?> style="height: calc(100vh - 12rem)!important"<?php endif?>>
		<div class="card-body"><?php echo $content ?></div>
	</div>

</main>

<footer class="navbar navbar-expand navbar-light bg-white mt-4 fixed-bottom shadow border-top border-secondary" style="border-width:3px !important">
	<div class="container p-2">
		<div class="mr-auto text-secondary d-none d-md-block"><strong>osW</strong>Tools:<?php echo $Tool->getStringValue('name')?></div>
		<div class="ml-auto text-secondary"><strong>Author:</strong> <?php echo $Tool->getStringValue('author')?> - <strong>Copyright:</strong> <?php if($Tool->getStringValue('link')!=''):?><a href="<?php echo $Tool->getStringValue('link')?>" target="_blank"><?php endif?><?php echo $Tool->getStringValue('copyright')?><?php if($Tool->getStringValue('link')!=''):?></a><?php endif?></div>
	</div>
</footer>

<a class="scroll-to-top rounded" href="#page-top"> <i class="fas fa-angle-up"></i> </a>

</body>
</html>