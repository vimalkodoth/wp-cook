# php Cookbook

[![Cookbook Version](https://img.shields.io/cookbook/v/php.svg)](https://supermarket.chef.io/cookbooks/php)
[![Build Status](https://img.shields.io/circleci/project/github/sous-chefs/php/master.svg)](https://circleci.com/gh/sous-chefs/php)
[![OpenCollective](https://opencollective.com/sous-chefs/backers/badge.svg)](#backers)
[![OpenCollective](https://opencollective.com/sous-chefs/sponsors/badge.svg)](#sponsors)
[![License](https://img.shields.io/badge/License-Apache%202.0-green.svg)](https://opensource.org/licenses/Apache-2.0)

It installs and configures PHP and the PEAR package management system. Also includes resources for managing PEAR (and PECL) packages, PECL channels, and PHP-FPM pools.

## Maintainers

This cookbook is maintained by the Sous Chefs. The Sous Chefs are a community of Chef cookbook maintainers working together to maintain important cookbooks. If you’d like to know more please visit [sous-chefs.org](https://sous-chefs.org/) or come chat with us on the Chef Community Slack in [#sous-chefs](https://chefcommunity.slack.com/messages/C2V7B88SF).

## Requirements

### Platforms

- Debian, Ubuntu
- CentOS, Red Hat, Oracle, Scientific, Amazon Linux
- Fedora

### Chef

- Chef 14+

## Attributes

- `node['php']['install_method']` = method to install php with, default `package`.
- `node['php']['directives']` = Hash of directives and values to append to `php.ini`, default `{}`.
- `node['php']['pear_setup']` = Boolean value to determine whether to set up pear repositories. Default: `true`
- `node['php']['pear_channels']` = List of external pear channels to add if `node['php']['pear_setup']` is true. Default: `['pear.php.net', 'pecl.php.net']`

The file also contains the following attribute types:

- platform specific locations and settings.
- source installation settings

## Resources

This cookbook includes resources for managing:

- PEAR channels
- PEAR/PECL packages

### `php_pear_channel`

[PEAR Channels](http://pear.php.net/manual/en/guide.users.commandline.channels.php) are alternative sources for PEAR packages. This resource provides and easy way to manage these channels.

#### Actions

- `:discover`: Initialize a channel from its server.
- `:add`: Add a channel to the channel list, usually only used to add private channels. Public channels are usually added using the `:discover` action
- `:update`: Update an existing channel
- `:remove`: Remove a channel from the List

#### Properties

- `channel_name`: name attribute. The name of the channel to discover
- `channel_xml`: the channel.xml file of the channel you are adding
- `binary`: pear binary, default: pear

#### Examples

```ruby
# discover the horde channel
php_pear_channel "pear.horde.org" do
  action :discover
end

# download xml then add the symfony channel
remote_file "#{Chef::Config[:file_cache_path]}/symfony-channel.xml" do
  source 'http://pear.symfony-project.com/channel.xml'
  mode '0644'
end
php_pear_channel 'symfony' do
  channel_xml "#{Chef::Config[:file_cache_path]}/symfony-channel.xml"
  action :add
end

# update the main pear channel
php_pear_channel 'pear.php.net' do
  action :update
end

# update the main pecl channel
php_pear_channel 'pecl.php.net' do
  action :update
end
```

### `php_pear`

[PEAR](http://pear.php.net/) is a framework and distribution system for reusable PHP components. [PECL](http://pecl.php.net/) is a repository for PHP Extensions. PECL contains C extensions for compiling into PHP. As C programs, PECL extensions run more efficiently than PEAR packages. PEARs and PECLs use the same packaging and distribution system. As such this resource is clever enough to abstract away the small differences and can be used for managing either. This resource also creates the proper module .ini file for each PECL extension at the correct location for each supported platform.

#### Actions

- `:install`: Install a pear package - if version is provided, install that specific version
- `:upgrade`: Upgrade a pear package - if version is provided, upgrade to that specific version
- `:remove`: Remove a pear package
- `:reinstall`: Force install of the package even if the same version is already installed. Note: This will converge on every Chef run and is probably not what you want.
- `:purge`: An alias for remove as the two behave the same in pear

#### Properties

- `package_name`: name attribute. The name of the pear package to install
- `version`: the version of the pear package to install/upgrade. If no version is given latest is assumed.
- `channel`:
- `options`: Add additional options to the underlying pear package command
- `directives`: extra extension directives (settings) for a pecl. on most platforms these usually get rendered into the extension's .ini file
- `zend_extensions`: extension filenames which should be loaded with zend_extension.
- `preferred_state`: PEAR by default installs stable packages only, this allows you to install pear packages in a devel, alpha or beta state
- `binary`: The pear binary to use, by default pear, can be overridden if the binary is not called pear, e.g. pear7

#### Examples

```ruby
# upgrade a pear
php_pear 'XML_RPC' do
  action :upgrade
end

# install a specific version
php_pear 'XML_RPC' do
  version '1.5.4'
  action :install
end

# install the mongodb pecl
php_pear 'Install mongo but use a different resource name' do
  package_name 'mongo'
  action :install
end

# install the xdebug pecl
php_pear 'xdebug' do
  # Specify that xdebug.so must be loaded as a zend extension
  zend_extensions ['xdebug.so']
  action :install
end

# install apc pecl with directives
php_pear 'apc' do
  action :install
  directives(shm_size: 128, enable_cli: 1)
end

# install using the pear-7 binary
php_pear 'apc' do
  action :install
  binary 'pear7'
end

# install sync using the pecl binary
php_pear 'sync' do
  version '1.1.1'
  binary 'pecl'
end

# install sync using the pecl channel
php_pear 'sync' do
  version '1.1.1'
  channel 'pecl.php.net'
end

# install the beta version of Horde_Url
# from the horde channel
hc = php_pear_channel 'pear.horde.org' do
  action :discover
end

php_pear 'Horde_Url' do
  preferred_state 'beta'
  channel hc.channel_name
  action :install
end

# install the YAML pear from the symfony project
sc = php_pear_channel 'pear.symfony-project.com' do
  action :discover
end

php_pear 'YAML' do
  channel sc.channel_name
  action :install
end
```

### `php_fpm_pool`

Installs the `php-fpm` package appropriate for your distro (if using packages) and configures a FPM pool for you. Currently only supported in Debian-family operating systems and CentOS 7 (or at least tested with such, YMMV if you are using source).

Please consider FPM functionally pre-release, and test it thoroughly in your environment before using it in production

More info: <http://php.net/manual/en/install.fpm.php>

#### Actions

- `:install`: Installs the FPM pool (default).
- `:uninstall`: Removes the FPM pool.

#### Attribute Parameters

- `pool_name`: name attribute. The name of the FPM pool.
- `listen`: The listen address. Default: `/var/run/php5-fpm.sock`
- `user`: The user to run the FPM under. Default should be the webserver user for your distro.
- `group`: The group to run the FPM under. Default should be the webserver group for your distro.
- `process_manager`: Process manager to use - see <http://php.net/manual/en/install.fpm.configuration.php>. Default: `dynamic`
- `max_children`: Max children to scale to. Default: 5
- `start_servers`: Number of servers to start the pool with. Default: 2
- `min_spare_servers`: Minimum number of servers to have as spares. Default: 1
- `max_spare_servers`: Maximum number of servers to have as spares. Default: 3
- `chdir`: The startup working directory of the pool. Default: `/`
- `additional_config`: Additional parameters in JSON. Default: {}

#### Examples

```ruby
# Install a FPM pool named "default"
php_fpm_pool 'default' do
  action :install
end
```

## Recipes

### default

Include the default recipe in a run list, to get `php`. By default `php` is installed from packages but this can be changed by using the `install_method` attribute.

### package

This recipe installs PHP from packages.

### source

This recipe installs PHP from source.

## Usage

Simply include the `php` recipe where ever you would like php installed. To install from source override the `node['php']['install_method']` attribute with in a role or wrapper cookbook:

### Role example

```ruby
name 'php'
description 'Install php from source'
override_attributes(
  'php' => {
    'install_method' => 'source',
  }
)
run_list(
  'recipe[php]'
)
```

## Contributors

This project exists thanks to all the people who [contribute.](https://opencollective.com/sous-chefs/contributors.svg?width=890&button=false)

### Backers

Thank you to all our backers!

![https://opencollective.com/sous-chefs#backers](https://opencollective.com/sous-chefs/backers.svg?width=600&avatarHeight=40)

### Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website.

![https://opencollective.com/sous-chefs/sponsor/0/website](https://opencollective.com/sous-chefs/sponsor/0/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/1/website](https://opencollective.com/sous-chefs/sponsor/1/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/2/website](https://opencollective.com/sous-chefs/sponsor/2/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/3/website](https://opencollective.com/sous-chefs/sponsor/3/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/4/website](https://opencollective.com/sous-chefs/sponsor/4/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/5/website](https://opencollective.com/sous-chefs/sponsor/5/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/6/website](https://opencollective.com/sous-chefs/sponsor/6/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/7/website](https://opencollective.com/sous-chefs/sponsor/7/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/8/website](https://opencollective.com/sous-chefs/sponsor/8/avatar.svg?avatarHeight=100)
![https://opencollective.com/sous-chefs/sponsor/9/website](https://opencollective.com/sous-chefs/sponsor/9/avatar.svg?avatarHeight=100)
