<?php

use Kethatril\ACFComponent\StandardComponent;
use Kethatril\ACFComponent\Template;

Template::registerComponent(
    (new StandardComponent('call_to_action', 'Call to Action'))
    ->addField('text', 'title', 'Title')
    ->addField('image', 'image', 'Image')
    ->addField('link', 'link', 'Link')
);



