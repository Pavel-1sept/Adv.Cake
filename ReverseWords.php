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
        if (\preg_match('/[^(\w'.self::PUNCT_MARKS.'-`\s)]|\d/iu', $phrase)) {
            throw new Exception('Ошибка входных данных. Ожидалась строка из букв и знаков препинания '.self::PUNCT_MARKS);
        }

        try {
            $aComplexWords = \explode(' ', $phrase);
            $aReversedPhrase = [];

            foreach ($aComplexWords as $complexWord) {
                $pattern = '';

                // есть ли внутри слова разделитель - апостроф или дефис
                if (\preg_match('/`/', $complexWord)) {
                    $pattern = '`';
                } elseif ((\preg_match('/-/', $complexWord))) {
                    $pattern = '-';
                }

                $array = [];
                $string = '';

                // разворачиваем слово (или слова, если в слове есть разделители)
                if ('' !== $pattern) {
                    foreach (\explode($pattern, $complexWord) as $word) {
                        \array_push($array, $this->reverseWord($word));
                    }
                    $string = \implode($pattern, $array);
                } else {
                    $string = $this->reverseWord($complexWord);
                }

                // добавляем слово в итоговую фразу
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
            $aLetters = \preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
            $aReversedWord = [];
            $aUpperCaseIndexes = [];
            $l = '';

            // перебираем буквы в слове
            foreach ($aLetters as $key => $letter) {
                // обработка встреченных знаков препинания
                if (\preg_match('/^['.self::PUNCT_MARKS.']/', $letter)) {
                    // если есть знак препинания в начале слова, запоминаем его
                    if (0 === $key && \preg_match('/^['.self::PUNCT_MARKS.']/', $letter)) {
                        $l = $letter;
                    } else {
                        \array_push($aReversedWord, $letter);
                    }
                    continue;
                }

                // если буква прописная, запоминаем место и делаем ее строчной
                if (IntlChar::isupper($letter)) {
                    \array_push($aUpperCaseIndexes, $key);
                    $letter = IntlChar::tolower($letter);
                }

                // добавляем букву в итоговый массив слова
                \array_unshift($aReversedWord, $letter);
            }

            // добавляем знак препинания, который был в начале слова
            '' !== $l ? \array_unshift($aReversedWord, $l) : null;

            // делаем прописными буквы там, где они были прописные
            foreach ($aUpperCaseIndexes as $index) {
                $aReversedWord[$index] = IntlChar::toupper($aReversedWord[$index]);
            }

        } catch (\Throwable $th) {
            throw new LogicException($th->getMessage());
        }

        return \implode($aReversedWord);
    }
}
