<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$fields = get_fields();

// roffy_pre($fields);

// Block classes
$block_class = 'hero-block bg-'.$fields['bg_type'];
if ($fields['comp_for_nav']) $block_class .= ' comp-nav'; // Compensate Nav
if (isset($fields['overlay']) && $fields['overlay']) $block_class .= ' overlay'; // Overlay
if ($fields['bg_type'] == 'color') {
    $block_class .= ' bg-color-'.$fields['bg_color']; // Solid background color
}
if ($fields['narrow_padding']) $block_class .= " narrow-padding";
if(is_front_page()) $block_class .= " front-page";


// Block Style
$block_style = '';
if ($fields['bg_type'] == "img") {
    $focal_x = $fields['img_w_focal']['img_focal_x'] !== '' ? $fields['img_w_focal']['img_focal_x'] : 50;
    $focal_y = $fields['img_w_focal']['img_focal_y'] !== '' ? $fields['img_w_focal']['img_focal_y'] : 50;
    
    $block_style .= 'style="background-image: url('.$fields['img_w_focal']['img']['url'].');';
    $block_style .= 'background-position: '.esc_attr( $focal_x ).'% '.esc_attr( $focal_y ).'%;"';
    
    $block_class .= " txt-".$fields['txt_color'];
}


?>


<div class="container-fluid <?php echo $block_class; ?>" <?php echo $block_style; ?>>

    <?php if ($fields['bg_type'] == "vid") { ?>
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="<?php echo $fields['bg_vid']['url']; ?>" type="video/mp4">
        </video>
    <?php } ?>

    <div class="container-lg hero-container">
        <div class="row w-100 hero-content-wrap">
            <div class="col-12 col-sm-10 <?php if(is_front_page()){echo 'col-md-9 col-lg-8 col-xl-7';}else{echo 'col-md-8 col-lg-7 col-xl-6';} ?> hero-header">
                <h1<?php if(!is_front_page()){echo " class='h2'";} ?>><?php echo $fields['header']; ?></h1>
            </div>
            <div class="col-11 col-sm-10 col-md-9 <?php if(is_front_page()){echo 'col-lg-7 col-xl-6';}else{echo 'col-lg-6 col-xl-5';} ?> hero-content">
                
                <?php echo $fields['hero_txt']; ?>

                <?php if (!empty($fields['btn_links'])) { ?>

                    <div class="btn-wrap">
                    
                        <?php
                            foreach($fields['btn_links'] as $link) {
                                if (empty($link['btn_link'])) {continue;}
                                ?>
                                    
                                    <a href="<?php echo $link['btn_link']['url']; ?>" role="button" class="btn-link">
                                        <?php echo $link['btn_link']['title']; ?>
                                    </a>

                                <?php
                            }
                        ?>

                    </div>
                
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>

<?php if ($fields['add_crest']) { ?>
    <div class="container-lg">
        <div class="row">
            <div class="col-10 overlay-crest"></div>
        </div>
    </div>
<?php } ?>