<?php
use Kethatril\ACFComponent\Template;

(new Template('home', 'Home', [
    'param' => 'page_template',
    'operator' => '==',
    'value' => 'home'
], [], true, 'home'))
    ->addComponent('home-hero', 'home_hero', 'Hero')
    ->addComponent('home-introduction', 'home_introduction', 'Introduction')
    ->addComponent('featured-stores', 'featured_stores', 'Featured Stores', 'inline', ['extraClasses' => 'blue'])
    ->addComponent('product-slider', 'product_slider', 'Product Slider')
    ->addComponent('content-tiles', 'content_tiles', 'Content Tiles')
    ->addComponent('merchant-cta', 'merchant_cta', 'Merchant Call to Action')
    ->apply();
