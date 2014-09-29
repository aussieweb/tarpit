# Tarpit
A Wordpress plugin that reduces comment spam with a smart [honeypot](http://en.wikipedia.org/wiki/Honeypot_(computing)) to capture bots.

It adds a field to your comments form that's hidden from users to visible to spambots. If this field is filled out, the comment is flagged as spam. Forked from [WP Comment Smart Honeypot](https://github.com/freak3dot/wp-smart-honeypot) by Ryan Johnston.

[Download Tarpit](https://github.com/cferdinandi/tarpit/archive/master.zip)

**In This Documentation**

1. [Getting Started](#getting-started)
2. [How to Contribute](#how-to-contribute)
3. [Limitations](#limitations)
4. [License](#license)
5. [Changelog](#changelog)



## Getting Started

Getting started with Tarpit is as simple as installing a plugin:

1. Upload the `tarpit` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.
3. Add your random key and configure your options under "Settings" in the Admin Dashboard.
4. Add the `.bzzz` class to your CSS with the following properties:

    ```css
    .bzzz {
        display: none;
        visibility: hidden;
    }
    ```

And that's it, you're done. Nice work!

You can change the class name and hidden field layout under "Settings" in the Admin Dashboard. You should avoid using words like "hide" or "hidden" in your hide class to make it harder for bots to identify.



## Limitations

Since this plugin rearranges form fields, it requires the [newer comment template](http://codex.wordpress.org/Function_Reference/comment_form). It also fails to capture trackback spam, which is different from comment spam.



## How to Contribute

In lieu of a formal style guide, take care to maintain the existing coding style. Don't forget to update the version number, the changelog (in the `readme.md` file), and when applicable, the documentation.



## License

Tarpit is licensed under the [GPLv2 License](https://wordpress.org/about/gpl/).



## Changelog

Tartpit uses [semantic versioning](http://semver.org/).

* v1.0.0 - September 26, 2014
	* Initial release.