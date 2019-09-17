<?php
namespace Kethatril\ACFComponent;

interface Component {
    public function getFields();
    public function getGroup();
    public function getName();
    public function getTitle();
}