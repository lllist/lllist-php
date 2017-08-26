# Lllist [![Build Status](https://api.travis-ci.org/lllist/lllist-php.svg?branch=master)](https://travis-ci.org/lllist/lllist-php) [![Latest Stable Version](https://poser.pugx.org/lllist/lllist-php/v/stable)](https://packagist.org/packages/lllist/lllist-php) [![Total Downloads](https://poser.pugx.org/lllist/lllist-php/downloads)](https://packagist.org/packages/lllist/lllist-php) [![Latest Unstable Version](https://poser.pugx.org/lllist/lllist-php/v/unstable)](https://packagist.org/packages/lllist/lllist-php) [![License](https://poser.pugx.org/lllist/lllist-php/license)](https://packagist.org/packages/lllist/lllist-php)

</center>

-------------------------------

Hate build conditional _lists, phrases, texts_? Me too. This library is made to avoid code boilerplate when building
lists, and also avoid things like: `Josecleiton likes: apple, bananas, grapes` 
or `Josecleiton likes: apple, grapes and also do null`, you could simply:

```php
lllist(', ', ' and ')
    ->items(['apple', 'bananas', 'grapes'])
```

Which will output `apple, bananas and grapes`

or

```php
lllist(', ', ' and ')
    ->items(['apple', 'bananas', 'grapes'])
    ->sep(' and also ')
    ->append(null)
```

Which will output `apple, bananas and grapes` too, because lllist ignores empty values and also trim separators.

If you give lllist an empty list it will return `null`, because it's the right thing to do™.

Example:

```php
lllist(', ', ' and ')
    ->items([])
    ->sep(' and also ')
    ->append(null)
```

Will output `null` :+1:.

Documentation will be released.


Made with `undefined` by [Wesley Schleumer](https://github.com/schleumer)