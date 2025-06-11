<?php

// Item Props
$props = $node->props;

$content = '';
if (!empty($props['name'])) {
    $content .= $props['name'] . ' ';
}
if (!empty($props['hex_value'])) {
    $content .= $props['hex_value'] . ' ';
}

echo $content;

?>
