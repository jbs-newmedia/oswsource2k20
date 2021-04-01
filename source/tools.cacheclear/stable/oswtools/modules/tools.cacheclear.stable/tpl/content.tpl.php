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

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'changelog.tpl.php'; ?>

<?php else: ?>

	<a href="javascript:osWTools_selectAll('#oswtools_cacheclear')" class="btn btn-secondary">Select all</a>
	<a href="javascript:osWTools_selectNone('#oswtools_cacheclear')" class="btn btn-secondary">Select none</a>
	<a href="javascript:osWTools_selectInvert('#oswtools_cacheclear')" class="btn btn-secondary">Invert selection</a>

	<br/><br/>

	<p>Please select your directories you want to clear and confirm your input by pressing the button "Clear selected directories from cache".</p>

	<hr/>


	<?php echo $osW_Form->startForm('oswtools_cacheclear_form', 'current', '', ['input_addid'=>true]);?>

		<table id="oswtools_cacheclear" class="table table-striped table-bordered">
			<thead>
			<tr>
				<th style="width:3rem;" class="text-center">Clear</th>
				<th>Directory</th>
			</tr>
			</thead>
			<tbody>
			<?php if(count($Tool->getCacheList())>0):?>
				<?php $i=0;foreach ($Tool->getCacheList() as $dir):$i++;?>
					<tr>
						<td class="text-center"><input type="checkbox" name="dir[<?php echo $dir?>]" value="1"/></td>
						<td><?php echo $dir?></td>
					</tr>
				<?php endforeach?>
			<?php endif?>
			</tbody>
		</table>


		<?php if(count($Tool->getCacheList())>0):?>

			<hr/>

			<a href="javascript:$('#oswtools_cacheclear_form').submit()" class="btn btn-primary d-block">Clear selected directories from cache</a>

			<?php echo $osW_Form->drawHiddenField('doaction', 'doclear');?>
		<?php endif?>

	<?php echo $osW_Form->endForm();?>


<?php endif ?>