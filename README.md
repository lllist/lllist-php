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

Documentation will be released.