Hate build conditional _lists, phrases, texts_? Me too. This library is made to avoid code boilerplate when building
lists, and also avoid things like: `Josecleiton likes: apple, bananas, grapes` 
or `Josecleiton likes: apple, grapes and also do null`, you could simply:

```php
lllist(', ')
    ->items(['apple', 'bananas', 'grapes'])
```

or

```php
lllist(', ')
    ->items(['apple', 'bananas', 'grapes'])
    ->append(' and also ')
    ->append(null)
```

Documentation will be released.