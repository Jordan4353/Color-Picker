<?php

// Element Props
$props = $node->props;

$content = '';

if (isset($children) && count($children)) {
    foreach ($children as $child) {
        if (!empty($child->props['name'])) {
            $content .= $child->props['name'] . ' ';
        }
        if (!empty($child->props['hex_value'])) {
            $content .= $child->props['hex_value'] . ' ';
        }
    }
}

echo $content;

?>
