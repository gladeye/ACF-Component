<?php

use Kethatril\ACFComponent\StandardComponent;
use Kethatril\ACFComponent\Template;

Template::registerComponent(
    (new StandardComponent('tile_repeater', 'Tile Repeater'))
        ->addField('text', 'title', 'Title')
        ->addRepeaterField('tiles', 'Tiles', 'tile', 1, 3)
);



