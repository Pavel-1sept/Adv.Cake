<?php
declare(strict_types=1);

namespace App\Service;

use Exception;
use LogicException;
use IntlChar;

class ReverseWords
{
    private const PUNCT_MARKS = '!?;:.,«»"';

    /**
     * Разворачивает слова внутри фразы
     *
     * @param string $phrase
     * @return string
     */
    public function reverseWordsInPhrase(string $phrase): string
    {
        if (preg_match('/[^(\w'.self::PUNCT_MARKS.'-`\s)]|\d/iu', $phrase)) {
            throw new Exception('Ошибка входных данных. Ожидалась строка из букв и знаков препинания '.self::PUNCT_MARKS);
        }

        try {
            $aComplexWords = \explode(' ', $phrase);
            $aReversedPhrase = [];

            foreach ($aComplexWords as $complexWord) {
                $array = [];
                $string = '';

                if (preg_match('/`/', $complexWord)) {
                    foreach (\explode('`', $complexWord) as $word) {
                        \array_push($array, $this->reverseWord($word));
                    }
                    $string = \implode('`', $array);

                } elseif (preg_match('/-/', $complexWord)) {
                    foreach (\explode('-', $complexWord) as $word) {
                        \array_push($array, $this->reverseWord($word));
                    }
                    $string = \implode('-', $array);

                } else {
                    $string = $this->reverseWord($complexWord);
                }
                \array_push($aReversedPhrase, $string);
            }

        } catch (\Throwable $th) {
            throw new LogicException($th->getMessage());
        }

        return \implode(' ', $aReversedPhrase);
    }

    /**
     * Разворачивает слово
     *
     * @param string $word
     * @return string
     */
    public function reverseWord(string $word): string
    {
        try {
            $aLetters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
            $aReversedWord = [];
            $aUpperCaseIndexes = [];
            $l = '';

            foreach ($aLetters as $key => $letter) {
                if (preg_match('/^['.self::PUNCT_MARKS.']/', $letter)) {
                    if (0 === $key && preg_match('/^['.self::PUNCT_MARKS.']/', $letter)) {
                        $l = $letter;
                    } else {
                        \array_push($aReversedWord, $letter);
                    }
                    continue;
                }

                if (IntlChar::isupper($letter)) {
                    \array_push($aUpperCaseIndexes, $key);
                    $letter = IntlChar::tolower($letter);
                }

                \array_unshift($aReversedWord, $letter);
            }

            foreach ($aUpperCaseIndexes as $index) {
                $aReversedWord[$index] = IntlChar::toupper($aReversedWord[$index]);
            }

            \array_unshift($aReversedWord, $l);

        } catch (\Throwable $th) {
            throw new LogicException($th->getMessage());
        }

        return \implode($aReversedWord);
    }
}
