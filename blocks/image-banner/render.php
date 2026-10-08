<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$fields = get_fields();

$focus_x = $fields['img_w_focal']['img_focal_x'] !== '' ? $fields['img_w_focal']['img_focal_x'] : 50;
$focus_y = $fields['img_w_focal']['img_focal_y'] !== '' ? $fields['img_w_focal']['img_focal_y'] : 50;

// roffy_pre($fields);

?>

<div class="container-fluid image-banner-block">
    <img 
        src="<?php echo esc_url($fields['img_w_focal']['img']['url']); ?>" 
        alt="<?php echo esc_attr($fields['img_w_focal']['img']['alt']); ?>"
        style="object-position: <?php echo esc_attr( $focal_x ); ?>% <?php echo esc_attr( $focal_y ); ?>%;"
    >
</div>