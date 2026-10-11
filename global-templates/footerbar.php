<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$footer_links = nav_menu_to_tree('Footer Links');
$footer_legal_links = nav_menu_to_tree('Footer Legal Links');


?>

<div class="container-fluid">
    <div class="container-lg">
        <div class="row pb-4">
            <div class="col-12 col-lg-5">

                <a class="footer-brand" href="#">
                    <img 
                    class="footer-logo" 
                    src="<?php echo get_stylesheet_directory_uri() . '/images/wondersee-labs-logo.png'; ?>" 
                    alt="Wondersee Labs"
                    >
                </a>

            </div>
            <div class="col-12 col-lg-7">

                <footer>
                    <nav aria-label="Footer Navigation">
                        <ul>
                            <?php foreach ($footer_links as $footer_link) { ?>
                                <li><a href="<?php echo $footer_link->url; ?>">
                                    <?php echo $footer_link->title; ?>
                                </a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </footer>

            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-5 copyright-wrap">

                Copyright <?php echo date('Y'); ?>. All rights reserved.

            </div>
            <div class="col-12 col-lg-7 legal-links-wrap">

                <nav aria-label="Footer Legal Links">
                    <ul>
                        <?php foreach ($footer_legal_links as $footer_legal_link) { ?>
                            <li><a href="<?php echo $footer_legal_link->url; ?>">
                                <?php echo $footer_legal_link->title; ?> &#62;
                            </a></li>
                        <?php } ?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>