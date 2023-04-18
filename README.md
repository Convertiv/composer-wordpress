# ComposerWordPress Plugin

This is a Composer plugin that copies packages (in our case WordPress plugins, mu-plugins and themes) from a specified source path to a specified destination path, as defined in the `composer.json` file.

## Installation

You can install this plugin by doing the following:

## 1. Setup in the composer.json file of your project
Add the following configuration to your `composer.json` file:

```json
{
   "extra": {
        "wordpress-install-dir": "public/",
        "composer-custom-install-path-src": "bin/composer_wordpress_vendor/wp-content",
        "composer-custom-install-path-dest": "public/wp-content/",
        "installer-paths": {
            "bin/composer_wordpress_vendor/wp-content/plugins/{$name}/": [
                "type:wordpress-plugin"
            ],
            "bin/composer_wordpress_vendor/wp-content/mu-plugins/{$name}/": [
                "type:wordpress-muplugin"
            ],
            "bin/composer_wordpress_vendor/wp-content/themes/{$name}/": [
                "type:wordpress-theme"
            ]
        }
    }
}

```
The wordpress-install-dir key defines the path to the WordPress installation directory which is installed using John Bloch's WordPress package.

the installer-paths key defines the paths to the WordPress packages to be copied. We're copying the WordPress packages to the bin/composer_wordpress_vendor/wp-content directory.

The composer-custom-install-path-src key defines the path to the source directory containing WordPress packages to be copied, and the composer-custom-install-path-dest key defines the path to the destination directory where the packages will be copied.

The plugin will automatically run during the post-install-cmd and post-update-cmd events, and will copy the WordPress packages from the source path to the destination path.

## 2. Run the following command to install the plugin:
```
composer require convertiv/composer-wordpress
```
Composer installer will ask whether you trust the plugin:

```
Do you trust "convertiv/composer-wordpress" to execute code and wish to enable it now? (writes "allow-plugins" to composer.json) [y,n,d,?]
```
Type `y` to allow the plugin to be used.

After this setup is done, the plugin will be used to copy WordPress packages from the source path to the destination path during `composer install` and `composer update`.

## 4. Add the folder path used in composer-custom-install-path-src to your .gitignore file

If you used bin/composer_wordpress_vendor/wp-content as the path, add the following to your .gitignore file:

```
bin/composer_wordpress_vendor
```

License
This plugin is licensed under the MIT License. For more information, see the [LICENCE](LICENCE) file.