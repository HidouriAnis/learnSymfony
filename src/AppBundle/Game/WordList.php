<?php

namespace AppBundle\Game;

use AppBundle\Game\Loader\LoaderInterface;

class WordList implements DictionaryLoaderInterface, WordListInterface
{
    private $words;
    private $loaders;

    public function __construct()
    {
        $this->words = [];
        $this->loaders = [];
    }

    /**
     * @param string          $type
     * @param LoaderInterface $loader
     */
    public function addLoader($type, LoaderInterface $loader)
    {
        $this->loaders[strtolower($type)] = $loader;
        dump($type);
        dump(get_class($loader));
    }

    /**
     * @param array $dictionaries
     */
    public function loadDictionaries(array $dictionaries)
    {
        foreach ($dictionaries as $dictionary) {
            $this->loadDictionary($dictionary);
        }
    }

    /**
     * @param $path
     */
    private function loadDictionary($path)
    {
        $loader = $this->findLoader(pathinfo($path, PATHINFO_EXTENSION));

        $words = $loader->load($path);
        foreach ($words as $word) {
            $this->addWord($word);
        }
    }

    /**
     * @param $type
     *
     * @return LoaderInterface
     */
    private function findLoader($type)
    {
        $type = strtolower($type);
        if (!isset($this->loaders[$type])) {
            throw new \RuntimeException(sprintf('There is no loader able to load a %s dictionary.', $type));
        }

        return $this->loaders[$type];
    }

    /**
     * @inheritdoc
     */
    public function getRandomWord($length)
    {
        if (!isset($this->words[$length])) {
            throw new \InvalidArgumentException(sprintf('There is no word of length %u.', $length));
        }

        $key = array_rand($this->words[$length]);

        return $this->words[$length][$key];
    }

    /**
     * @inheritdoc
     */
    public function addWord($word)
    {
        $length = strlen($word);

        if (!isset($this->words[$length])) {
            $this->words[$length] = [];
        }

        if (!in_array($word, $this->words[$length])) {
            $this->words[$length][] = $word;
        }
    }
}
