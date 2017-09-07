<?php

namespace AppBundle\Game\Loader;

interface LoaderInterface
{
    /**
     * Loads a words list data source.
     *
     * @param string $dictionary The absolute path to a dictionary file
     *
     * @return array The list of loaded words
     */
    public function load($dictionary);
}
