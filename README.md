# Reverse Words

A PHP class for reversing words in phrases while preserving punctuation, case, and handling complex word structures.

## Features

- Reverses individual words while preserving case
- Handles punctuation marks correctly
- Supports hyphenated words and apostrophes
- Maintains original punctuation placement
- Supports Unicode characters
- Input validation with meaningful error messages

## Installation

```bash
composer install
```

## Running Tests

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## Usage

```php
use App\Service\ReverseWords;

$reverseWords = new ReverseWords();

// Basic word reversal
echo $reverseWords->reverseWord('Hello'); // Output: Olleh

// Phrase reversal
echo $reverseWords->reverseWordsInPhrase('Hello World!'); // Output: Olleh Dlrow!

// Complex words with separators
echo $reverseWords->reverseWordsInPhrase('Hello-world Test`test'); // Output: Olleh-dlrow Tset`tset

// With punctuation
echo $reverseWords->reverseWordsInPhrase('«Hello» "World"!'); // Output: «Olleh» "Dlrow"!
```

## Supported Punctuation

The class supports the following punctuation marks:
- `!?;:.,«»"`

## Error Handling

The class throws exceptions for invalid input:
- `Exception` for input containing numbers or unsupported characters
- `LogicException` for internal processing errors

## Test Coverage

The test suite includes comprehensive coverage for:
- Basic word and phrase reversal
- Case preservation
- Punctuation handling
- Complex word structures (hyphens, apostrophes)
- Unicode character support
- Error conditions
- Edge cases
