<?php

namespace Qandidate\AuthorBundle;

use Sculpin\Contrib\ProxySourceCollection\ProxySourceItem;

class Author extends ProxySourceItem
{
    public function name()
    {
        return $this->data()->get('name');
    }

    public function nickname()
    {
        return $this->data()->get('nickname');
    }

    public function posts()
    {
        return $this->data()->get('posts');
    }

    public function gravatar()
    {
        return $this->data()->get('gravatar');
    }
}
