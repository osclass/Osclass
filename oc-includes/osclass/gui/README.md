# Bender

## Dependencies

* [Node.js](http://nodejs.org/)
* [Sass](http://sass-lang.com/)

Once you have the dependencies installed, run the following command:

```
$> npm install
```

## Structure

There are two template files:

* `index.php.tpl`
* `sass/colors.scss.tpl`

If you want to generate them again, you just have to execute `grunt template:blue`.

## Watch changes

You can compile sass files automatically every time it's changed by executing the following command:

```
$> grunt watch:blue
```

## Build

```
$> grunt dist
```

It generates a `.zip` file per color scheme: blue (default), red, black and purple. The `index.php` and `colors.scss` are generated from the variables in `themes.json`. The screenshots are taken from `screenshot/{color scheme}/screenshot.png`.

![Bender](http://pool.theinfosphere.org/images/1/14/Bender_promo_2.jpg)