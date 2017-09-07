<?php

namespace AppBundle\Game;

class Game
{
    const MAX_ATTEMPTS = 11;

    /**
     * @var string
     */
    private $word;

    /**
     * @var int
     */
    private $attempts;

    /**
     * @var array
     */
    private $triedLetters;

    /**
     * @var array
     */
    private $foundLetters;

    /**
     * @param $word
     * @param int   $attempts
     * @param array $triedLetters
     * @param array $foundLetters
     */
    public function __construct($word, $attempts = 0, array $triedLetters = [], array $foundLetters = [])
    {
        $this->word = strtolower($word);
        $this->attempts = (int) $attempts;
        $this->triedLetters = $triedLetters;
        $this->foundLetters = $foundLetters;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return [
            'word' => (string) $this->word,
            'attempts' => $this->attempts,
            'found_letters' => $this->foundLetters,
            'tried_letters' => $this->triedLetters,
        ];
    }

    /**
     * @return int
     */
    public function getRemainingAttempts()
    {
        return static::MAX_ATTEMPTS - $this->attempts;
    }

    /**
     * @param $letter
     *
     * @return bool
     */
    public function isLetterFound($letter)
    {
        return in_array(strtolower($letter), $this->foundLetters);
    }

    /**
     * @return bool
     */
    public function isHanged()
    {
        return static::MAX_ATTEMPTS === $this->attempts;
    }

    /**
     * @return bool
     */
    public function isOver()
    {
        return $this->isWon() || $this->isHanged();
    }

    /**
     * @return bool
     */
    public function isWon()
    {
        $diff = array_diff($this->getWordLetters(), $this->foundLetters);

        return 0 === count($diff);
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return array
     */
    public function getWordLetters()
    {
        return str_split($this->word);
    }

    /**
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @return array
     */
    public function getTriedLetters()
    {
        return $this->triedLetters;
    }

    /**
     * @return array
     */
    public function getFoundLetters()
    {
        return $this->foundLetters;
    }

    public function reset()
    {
        $this->attempts = 0;
        $this->triedLetters = array();
        $this->foundLetters = array();
    }

    /**
     * @param string $word
     *
     * @return bool
     */
    public function tryWord($word)
    {
        if ($word === $this->word) {
            $this->foundLetters = array_unique($this->getWordLetters());

            return true;
        }

        $this->attempts = self::MAX_ATTEMPTS;

        return false;
    }

    /**
     * @param $letter
     *
     * @return bool
     */
    public function tryLetter($letter)
    {
        $letter = strtolower($letter);

        if (0 === preg_match('/^[a-z]$/', $letter)) {
            throw new \InvalidArgumentException(sprintf('The letter "%s" is not a valid ASCII character matching [a-z].', $letter));
        }

        if (in_array($letter, $this->triedLetters)) {
            ++$this->attempts;

            return false;
        }

        if (false !== strpos($this->word, $letter)) {
            $this->foundLetters[] = $letter;
            $this->triedLetters[] = $letter;

            return true;
        }

        $this->triedLetters[] = $letter;
        ++$this->attempts;

        return false;
    }
}
