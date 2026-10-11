<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$nav_links      = nav_menu_to_tree('Primary Menu');

$menu_locations = get_nav_menu_locations();
$primary_menu_id = $menu_locations['primary'];

if ($primary_menu_id) {
    $nav_btn_link = get_field(
        'nav_btn_link',
        'nav_menu_' . $primary_menu_id
    );
    $btn_link_title = $nav_btn_link['title'];
    $btn_link_url   = $nav_btn_link['url'];
    $btn_link_target = $nav_btn_link['target'];
} else {
    $btn_link_title = "Nav Button";
    $btn_link_url   = "/";
    $btn_link_target = "";
}

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="container-lg">
      <div class="row">
        <div class="col nav-col">
      
          <a class="navbar-brand" href="#">
            <img 
              class="nav-logo" 
              src="<?php echo get_stylesheet_directory_uri() . '/images/wondersee-labs-logo.png'; ?>" 
              alt="Wondersee Labs"
            >
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav my-2 my-lg-0" style="--bs-scroll-height: 100px;">
              <?php 
                foreach ($nav_links as $nav_link) { 
                    if ( isset($nav_link->children) ) { ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $nav_link->title; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($nav_link->children as $child) { ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo $child->url; ?>">
                                            <?php echo $child->title; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                

                            </ul>
                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $nav_link->url; ?>">
                                <?php echo $nav_link->title; ?>
                            </a>
                        </li>

              <?php }} ?>

              <li class="nav-item d-block d-lg-none">
                <a class="nav-link" href="<?php echo $btn_link_url; ?>">
                  <?php echo $btn_link_title; ?>
                </a>
              </li>

            </ul>

          </div>

          <div class="nav-btn-wrap">
              <a 
                href="<?php echo $btn_link_url; ?>" 
                role="button" class="btn-purple" 
                target="<?php echo $btn_link_target; ?>"
              >
                <?php echo $btn_link_title; ?>
              </a>
          </div>

        </div> <!-- .col -->
      </div> <!-- .row -->
    </div> <!-- .container-lg -->
  </div> <!-- .container-fluid -->
</nav>