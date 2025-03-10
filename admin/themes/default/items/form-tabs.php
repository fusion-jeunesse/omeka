<?php
$tabs = array();
foreach ($elementSets as $key => $elementSet) {
    $tabName = $elementSet->name;

    switch ($tabName) {
        case ElementSet::ITEM_TYPE_NAME:
            // Output buffer this form instead of displaying it right away.
            ob_start();
            include 'item-type-form.php';
            $tabs[$tabName] = ob_get_contents();
            ob_end_clean();
            break;

        default:
            $tabContent  = '<p class="element-set-description" id="';
            $tabContent .= html_escape(text_to_id($elementSet->name) . '-description') . '">';
            $tabContent .= url_to_link(__($elementSet->description)) . '</p>' . "\n\n";
            $tabContent .= element_set_form($item, $elementSet->name);
            $tabs[$tabName] = $tabContent;
            break;
    }
}


ob_start();
require 'files-form.php';
$tabs['Files'] = ob_get_contents();
ob_clean();
#if(is_allowed($item, 'tag')) { # can't assert
if(is_allowed('Items', 'tags')) {
    require 'tag-form.php';
    $tabs['Tags'] = ob_get_contents();
}
ob_end_clean();

$tabs = apply_filters('admin_items_form_tabs', $tabs, array('item' => $item));
?>

<!-- Create the sections for the various element sets -->

<ul id="section-nav" class="navigation tabs">
    <?php foreach ($tabs as $tabName => $tabContent): ?>
        <?php if (!empty($tabContent)): // Don't display tabs with no content. '?>
            <li><a href="#<?php echo html_escape(text_to_id($tabName) . '-metadata'); ?>"><?php echo html_escape(__($tabName)); ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
