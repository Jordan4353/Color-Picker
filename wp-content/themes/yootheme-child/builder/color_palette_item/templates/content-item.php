<?php

// Item Props
$props = $node->props;

$content = '';
if (!empty($props['title'])) {
    $content .= $props['title'] . ' ';
}
if (!empty($props['hex_value'])) {
    $content .= $props['hex_value'] . ' ';
}

echo $content;

?>
