# Theme Partials

Please make sure to break down your larger theme files into smaller partial components.

Your partials should be broken down into respective folders, with individual files named `name-of-partial.php`

You should pull all partial assets with:

```
<?php include(get_template_directory() . '/partials/template-name/name-of-partial.php'); ?>
```

Or use a really handy utility method that looks much nicer and requires less typing :thumbsup::

```
<?php include(get_partial_path('template-name/name-of-partial')); ?>
```

Or if you're really fancy, you can use dot notation (which just looks nicer, doesn't it?):

```
<?php include(get_partial_path('template-name.name-of-partial')); ?>
```

If for some reason your partial has a different extension or you have a different partial directory, pass in those parameters:

```
<?php include(get_partial_path('name-of-partial', 'html', 'alternative-partials-directory/or-with-sub-directory')); ?>
```

If you need a module-like area for repeated code throughout your site, use a `global` folder.
