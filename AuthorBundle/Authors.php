<?php

namespace Qandidate\AuthorBundle;

use Sculpin\Contrib\ProxySourceCollection\ProxySourceCollection;

class Authors extends ProxySourceCollection
{
    public function init()
    {
        uasort($this->items, function ($a, $b) {
            return strcmp($a->name(), $b->name());
        });

        parent::init();
    }
}
