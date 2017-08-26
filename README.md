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


Made with :heart: by [Wesley Schleumer](https://github.com/schleumer)