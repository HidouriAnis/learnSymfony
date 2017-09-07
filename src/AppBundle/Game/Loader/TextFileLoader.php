<?php

namespace AppBundle\Game\Loader;

class TextFileLoader implements LoaderInterface
{

    /**
     * @inheritdoc
     */
    public function load($dictionary)
    {
        return array_map('trim', file($dictionary));
    }
}
