# Osclass

### All you need to easily create your own classifieds website

[Osclass is for classifieds][osclass] what WordPress is for publishing. It's a free
and open script to create your advertisement or listings site. Best features: Plugins,
themes, multi-language, CAPTCHA, dashboard, SEO friendly.

![Preview of Osclass][preview]

## Project info

* [Official website][osclass]
* [Code repository][code]
* [Mailing list][mailing]
* IRC Channel [#osclass][irc]
* License: [Apache License V2.0][license]

## Develop

Clone the repository and the submodules.

```
$> git clone --recursive git@github.com:osclass/Osclass.git
```

## Installation

Go to [our site][installing] to get detailed information on installing Osclass.

## Heroku

If you are using _Heroku_, you will need to:

1. Set the `HEROKU_URL` environment variable to the root of your application (eg: `https://foo.herokuapp.com`)
2. Provision a MySQL database and then set one of `CLEARDB_DATABASE_URL`, `JAWSDB_DATABASE_URL` or `DATABASE_URL`
3. Set `OSCLASS_MULTISITE` (eg: `0`)
4. Push this code to heroku
5. Visit the app and follow the original web installation guide.

[osclass]: http://osclass.org/
[preview]: http://osclass.org/wp-content/uploads/2011/01/single_job_board-1024x729.png
[code]: https://github.com/osclass/Osclass
[mailing]: http://list.osclass.org/listinfo/osc-develop
[irc]: http://webchat.freenode.net/?channels=osclass
[license]: http://www.apache.org/licenses/LICENSE-2.0
[installing]: http://osclass.org/installing-osclass/
