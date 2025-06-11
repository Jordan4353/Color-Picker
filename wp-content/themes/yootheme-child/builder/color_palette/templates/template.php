<?php

// Element Props
$props = $node->props;

// Layout and Theme classes
$layout_class = 'uk-grid-small uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m'; // Default: grid
$item_wrapper_class = ''; // For grid items, no extra wrapper needed per item by default. For list, it's <li>.
$main_container_attributes = ['uk-grid' => true]; // Default for grid

if ($props['layout'] === 'list') {
    $layout_class = 'uk-list uk-list-striped'; // Classes for list view
    $item_wrapper_class = 'yoo-color-palette-list-item'; // Add a class for potential <li> styling if needed, or direct styling
    $main_container_attributes = []; // No uk-grid for list
} elseif ($props['layout'] === 'carousel') {
    $layout_class = 'uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m';
    $item_wrapper_class = ''; // Slider items are usually direct children (divs)
    $main_container_attributes = [
        'uk-slider' => 'finite: false',
    ];
}

$theme_class = $props['theme'] === 'dark' ? 'uk-light uk-background-secondary' : 'uk-background-default';

$el = $this->el('div', [
    'class' => [
        'yoo-color-palette',
        $theme_class,
        'uk-padding-small',
    ],
    'uk-margin' => true,
]);

// Separate children by type (though not strictly needed for rendering order, can be useful for conditional logic like carousel nav)
$heading_items = [];
$color_items = [];
if (isset($children) && count($children)) {
    foreach ($children as $child) {
        if ($child->type === 'color_group_heading_item') {
            $heading_items[] = $child;
        } elseif ($child->type === 'color_palette_item') {
            $color_items[] = $child;
        }
    }
}

?>
<?= $el($props, $attrs) ?>

    <?php
    // Items will be rendered in the order they are added in the YOOtheme Pro builder.
    // color_group_heading_item's template.php is responsible for its H4 tag.
    // color_palette_item's template.php is responsible for its card display.
    // This main template orchestrates the layout (grid, list, carousel).
    ?>

    <?php if (isset($children) && count($children)) : ?>

        <?php if ($props['layout'] === 'carousel') : ?>
            <div <?= $this->attrs($main_container_attributes) ?>>
                <div class="<?= $layout_class ?>">
                    <?php foreach ($children as $child) : // Render all children in carousel slides ?>
                        <div><?= $builder->render($child, ['element' => $props]) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($color_items) > 1) : // Show nav only if there are multiple color items to slide through ?>
                    <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slider-item="previous"></a>
                    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slider-item="next"></a>
                <?php endif; ?>
            </div>
        <?php else : // Grid or List Layout ?>
            <div class="<?= $layout_class ?>" <?= $this->attrs($main_container_attributes) ?>>
                <?php foreach ($children as $child) : ?>
                    <?php if ($props['layout'] === 'list' && $child->type === 'color_palette_item') : ?>
                        <li class="<?= $item_wrapper_class ?>">
                            <?= $builder->render($child, ['element' => $props]) ?>
                        </li>
                    <?php elseif ($child->type === 'color_group_heading_item') : ?>
                        <?php // Heading items in grid/list are rendered directly. Their template defines their tag (e.g., <h4>)
                              // They will flow naturally in grid or list context. List context might need specific styling for headings.
                        ?>
                        <?= $builder->render($child, ['element' => $props]) ?>
                    <?php elseif ($child->type === 'color_palette_item') : // Grid items ?>
                        <div class="<?= $item_wrapper_class ?>">
                             <?= $builder->render($child, ['element' => $props]) ?>
                        </div>
                    <?php else: // Fallback for other types if any (should not happen with current setup) ?>
                        <?= $builder->render($child, ['element' => $props]) ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <p class="uk-text-center">No content (headings or colors) defined yet.</p>
    <?php endif; ?>

<?= $el->end() ?>
