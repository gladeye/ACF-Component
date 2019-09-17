<?php

use Kethatril\ACFComponent\StandardComponent;
use Kethatril\ACFComponent\Template;

Template::registerComponent(
    (new StandardComponent('tile', 'Tile'))
        ->addField('text', 'title', 'Title')
        ->addField('wysiwyg', 'content', 'Content')
        ->addField('image', 'image', 'Image')
        ->addField('link', 'link', 'Link')
);



