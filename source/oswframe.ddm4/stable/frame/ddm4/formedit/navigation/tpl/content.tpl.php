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

$links=$this->getEditElementOption($element, 'data');
$links_anz=0;
if (is_array($links)) {
	$links_anz=count($links);
}

?>

<?php if ($links_anz>0): ?>
	<tr class="table_ddm_row table_ddm_row_navigation ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">
		<td class="table_ddm_col" colspan="2">
			<ul class="table_ddm_list table_ddm_list_horizontal">
				<?php $i=0;
				foreach ($links as $link_id=>$__link):$i++; ?><?php
					if (isset($__link['navigation_id'])) {
						$link_id=$__link['navigation_id'];
					}
					?>
					<li>
						<a<?php if ($this->getParameter('ddm_navigation_id')==$link_id): ?> class="active"<?php endif ?><?php echo(((isset($__link['target'])))?' target="'.$__link['target'].'"':''); ?> href="<?php echo osW_Template::getInstance()->buildhrefLink(((($__link['module']))?$__link['module']:$this->getDirectModule()), 'ddm_navigation_id='.$link_id.((($__link['parameter']))?'&'.$__link['parameter']:'')) ?>"><?php echo((($__link['text']))?osWFrame\Core\HTML::outputString($__link['text']):'undefined') ?></a>
					</li>
				<?php endforeach ?>
			</ul>
		</td>
	</tr>
<?php endif ?>