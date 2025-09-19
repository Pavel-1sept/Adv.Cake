<?php
declare(strict_types=1);

namespace Tests;

use App\Service\ReverseWords;
use Exception;
use LogicException;
use PHPUnit\Framework\TestCase;

class ReverseWordsTest extends TestCase
{
    private ReverseWords $reverseWords;

    protected function setUp(): void
    {
        $this->reverseWords = new ReverseWords();
    }

    /**
     * Test reverseWord method with basic cases
     */
    public function testReverseWordBasic(): void
    {
        $this->assertEquals('olleh', $this->reverseWords->reverseWord('hello'));
        $this->assertEquals('dlrow', $this->reverseWords->reverseWord('world'));
        $this->assertEquals('a', $this->reverseWords->reverseWord('a'));
        $this->assertEquals('', $this->reverseWords->reverseWord(''));
    }

    /**
     * Test reverseWord method with uppercase letters
     */
    public function testReverseWordWithUppercase(): void
    {
        $this->assertEquals('Olleh', $this->reverseWords->reverseWord('Hello'));
        $this->assertEquals('DLROW', $this->reverseWords->reverseWord('WORLD'));
        $this->assertEquals('OlleH', $this->reverseWords->reverseWord('HellO'));
        $this->assertEquals('A', $this->reverseWords->reverseWord('A'));
    }

    /**
     * Test reverseWord method with punctuation marks
     */
    public function testReverseWordWithPunctuation(): void
    {
        $this->assertEquals('!olleh', $this->reverseWords->reverseWord('!hello'));
        $this->assertEquals('olleh!', $this->reverseWords->reverseWord('hello!'));
        $this->assertEquals('?dlrow', $this->reverseWords->reverseWord('?world'));
        $this->assertEquals('dlrow?', $this->reverseWords->reverseWord('world?'));
        $this->assertEquals('!Olleh!', $this->reverseWords->reverseWord('!Hello!'));
    }

    /**
     * Test reverseWord method with multiple punctuation marks
     */
    public function testReverseWordWithMultiplePunctuation(): void
    {
        $this->assertEquals('?olleh!', $this->reverseWords->reverseWord('?hello!'));
        $this->assertEquals('olleh!?', $this->reverseWords->reverseWord('hello!?'));
        $this->assertEquals('!Olleh!', $this->reverseWords->reverseWord('!Hello!'));
    }

    /**
     * Test reverseWord method with special punctuation marks
     */
    public function testReverseWordWithSpecialPunctuation(): void
    {
        $this->assertEquals('«olleh»', $this->reverseWords->reverseWord('«hello»'));
        $this->assertEquals('"dlrow"', $this->reverseWords->reverseWord('"world"'));
        $this->assertEquals(';olleh:', $this->reverseWords->reverseWord(';hello:'));
        $this->assertEquals('.dlrow,', $this->reverseWords->reverseWord('.world,'));
    }

    /**
     * Test reverseWordsInPhrase method with basic cases
     */
    public function testReverseWordsInPhraseBasic(): void
    {
        $this->assertEquals('olleh dlrow', $this->reverseWords->reverseWordsInPhrase('hello world'));
        $this->assertEquals('a b c', $this->reverseWords->reverseWordsInPhrase('a b c'));
        $this->assertEquals('olleh', $this->reverseWords->reverseWordsInPhrase('hello'));

        $this->expectException(Exception::class);
        $this->reverseWords->reverseWordsInPhrase('');
    }

    /**
     * Test reverseWordsInPhrase method with uppercase letters
     */
    public function testReverseWordsInPhraseWithUppercase(): void
    {
        $this->assertEquals('Olleh Dlrow', $this->reverseWords->reverseWordsInPhrase('Hello World'));
        $this->assertEquals('OLLEH DLROW', $this->reverseWords->reverseWordsInPhrase('HELLO WORLD'));
        $this->assertEquals('OlleH DlroW', $this->reverseWords->reverseWordsInPhrase('HellO WorlD'));
    }

    /**
     * Test reverseWordsInPhrase method with punctuation marks
     */
    public function testReverseWordsInPhraseWithPunctuation(): void
    {
        $this->assertEquals('!olleh dlrow!', $this->reverseWords->reverseWordsInPhrase('!hello world!'));
        $this->assertEquals('olleh, dlrow!', $this->reverseWords->reverseWordsInPhrase('hello, world!'));
        $this->assertEquals('?Olleh Dlrow?', $this->reverseWords->reverseWordsInPhrase('?Hello World?'));
    }

    /**
     * Test reverseWordsInPhrase method with complex punctuation
     */
    public function testReverseWordsInPhraseWithComplexPunctuation(): void
    {
        $this->assertEquals('«Olleh» "Dlrow"!', $this->reverseWords->reverseWordsInPhrase('«Hello» "World"!'));
        $this->assertEquals(';Olleh: Dlrow,', $this->reverseWords->reverseWordsInPhrase(';Hello: World,'));
        $this->assertEquals('.Olleh Dlrow?', $this->reverseWords->reverseWordsInPhrase('.Hello World?'));
    }

    /**
     * Test reverseWordsInPhrase method with hyphenated words
     */
    public function testReverseWordsInPhraseWithHyphens(): void
    {
        $this->assertEquals('olleh-dlrow', $this->reverseWords->reverseWordsInPhrase('hello-world'));
        $this->assertEquals('Olleh-Dlrow', $this->reverseWords->reverseWordsInPhrase('Hello-World'));
        $this->assertEquals('olleh-dlrow-tset', $this->reverseWords->reverseWordsInPhrase('hello-world-test'));
    }

    /**
     * Test reverseWordsInPhrase method with apostrophes
     */
    public function testReverseWordsInPhraseWithApostrophes(): void
    {
        $this->assertEquals('olleh`dlrow', $this->reverseWords->reverseWordsInPhrase('hello`world'));
        $this->assertEquals('Olleh`Dlrow', $this->reverseWords->reverseWordsInPhrase('Hello`World'));
        $this->assertEquals('olleh`dlrow`tset', $this->reverseWords->reverseWordsInPhrase('hello`world`test'));
    }

    /**
     * Test reverseWordsInPhrase method with mixed separators
     */
    public function testReverseWordsInPhraseWithMixedSeparators(): void
    {
        $this->assertEquals('olleh-dlrow tset`tset', $this->reverseWords->reverseWordsInPhrase('hello-world test`test'));
        $this->assertEquals('Olleh-Dlrow Tset`Tset', $this->reverseWords->reverseWordsInPhrase('Hello-World Test`Test'));

        $this->expectException(Exception::class);
        $this->reverseWords->reverseWordsInPhrase('hello-world`test');
    }

    /**
     * Test reverseWordsInPhrase method with complex phrases
     */
    public function testReverseWordsInPhraseComplex(): void
    {
        $this->assertEquals('!Olleh, dlrow?', $this->reverseWords->reverseWordsInPhrase('!Hello, world?'));
        $this->assertEquals('«Olleh-dlrow» "tset"!', $this->reverseWords->reverseWordsInPhrase('«Hello-world» "test"!'));
        $this->assertEquals(';Olleh`dlrow: tset,', $this->reverseWords->reverseWordsInPhrase(';Hello`world: test,'));
    }

    /**
     * Test reverseWordsInPhrase method with invalid input containing numbers
     */
    public function testReverseWordsInPhraseWithNumbersThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->reverseWords->reverseWordsInPhrase('hello123world');
    }

    /**
     * Test reverseWordsInPhrase method with valid input containing spaces and punctuation
     */
    public function testReverseWordsInPhraseWithValidSpacesAndPunctuation(): void
    {
        $this->assertEquals('olleh dlrow', $this->reverseWords->reverseWordsInPhrase('hello world'));
        $this->assertEquals('olleh, dlrow!', $this->reverseWords->reverseWordsInPhrase('hello, world!'));
        $this->assertEquals('olleh dlrow tset', $this->reverseWords->reverseWordsInPhrase('hello world test'));
    }

    /**
     * Test reverseWordsInPhrase method with valid input containing backticks and hyphens
     */
    public function testReverseWordsInPhraseWithValidBackticksAndHyphens(): void
    {
        $this->assertEquals('olleh-dlrow', $this->reverseWords->reverseWordsInPhrase('hello-world'));
        $this->assertEquals('olleh`dlrow', $this->reverseWords->reverseWordsInPhrase('hello`world'));

        $this->expectException(Exception::class);
        $this->reverseWords->reverseWordsInPhrase('hello-world`test');
    }

    /**
     * Test reverseWord method with empty string
     */
    public function testReverseWordEmptyString(): void
    {
        $this->assertEquals('', $this->reverseWords->reverseWord(''));
    }

    /**
     * Test reverseWordsInPhrase method with single word
     */
    public function testReverseWordsInPhraseSingleWord(): void
    {
        $this->assertEquals('olleh', $this->reverseWords->reverseWordsInPhrase('hello'));
        $this->assertEquals('Olleh', $this->reverseWords->reverseWordsInPhrase('Hello'));
        $this->assertEquals('!Olleh!', $this->reverseWords->reverseWordsInPhrase('!Hello!'));
    }

    /**
     * Test reverseWordsInPhrase method with multiple spaces
     */
    public function testReverseWordsInPhraseMultipleSpaces(): void
    {
        $this->assertEquals('olleh  dlrow', $this->reverseWords->reverseWordsInPhrase('hello  world'));
        $this->assertEquals('olleh   dlrow   tset', $this->reverseWords->reverseWordsInPhrase('hello   world   test'));
    }

    /**
     * Test reverseWord method with only punctuation
     */
    public function testReverseWordOnlyPunctuation(): void
    {
        $this->assertEquals('!', $this->reverseWords->reverseWord('!'));
        $this->assertEquals('!?', $this->reverseWords->reverseWord('!?'));
        $this->assertEquals('«»', $this->reverseWords->reverseWord('«»'));
    }

    /**
     * Test reverseWordsInPhrase method with only punctuation
     */
    public function testReverseWordsInPhraseOnlyPunctuation(): void
    {
        $this->expectException(Exception::class);
        $this->reverseWords->reverseWordsInPhrase('! ? ;');
    }

    /**
     * Test reverseWord method with Unicode characters
     */
    public function testReverseWordWithUnicode(): void
    {
        $this->assertEquals('привет', $this->reverseWords->reverseWord('тевирп'));
        $this->assertEquals('Привет', $this->reverseWords->reverseWord('Тевирп'));
        $this->assertEquals('ПРИВЕТ', $this->reverseWords->reverseWord('ТЕВИРП'));
    }

    /**
     * Test reverseWordsInPhrase method with Unicode characters
     */
    public function testReverseWordsInPhraseWithUnicode(): void
    {
        $this->assertEquals('привет мир', $this->reverseWords->reverseWordsInPhrase('тевирп рим'));
        $this->assertEquals('Привет Мир!', $this->reverseWords->reverseWordsInPhrase('Тевирп Рим!'));
    }
}
