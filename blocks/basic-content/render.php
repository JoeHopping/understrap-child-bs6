<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$fields = get_fields();
$i = 1;

// roffy_pre($fields);

// Advanced - Add Classes
$classes = '';
if (!empty($block['className'])) {
    $classes = ' ' . $block['className'];
}

// Column Classes
if ( count($fields['column']) == 1 ) {
    $col_class = "col-12 col-sm-10 col-md-10 col-lg-8 ";
} else {
    $col_class  = "col-12 col-md-10 col-lg-5 col-xl-5 mb-3";
    $col2_class = "col-12 col-md-10 col-lg-5 col-xl-5 offset-lg-1";
}

?>

<div class="container-fluid basic-content-block basic-content-block-<?php echo $fields['block_color']; ?> <?php echo $fields['use_sm_p'] ? 'use_sm_p' : ''; echo $classes; ?> ">
    <div class="container-lg">
        <?php if (!empty($fields['header'])) { ?>
            <div class="row basic-content-block-header">
                <div class="col-11 <?php if(!$fields['header_width_full']){ ?>col-sm-8 col-lg-7<?php } else { ?> col-sm-10 <?php } ?>">
                    <h2 class="h3"><?php echo $fields['header']; ?></h2>            
                </div>
            </div>
        <?php } ?>
        <div class="row basic-content-block-content">
            <?php foreach ($fields['column'] as $col) { ?>
                <div class="basic-content-block-content-col <?php if($i==1){echo $col_class;}else{echo $col2_class;} ?>">
                    <?php echo $col['column_content']; ?>
                </div>             
            <?php $i++; } ?>
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