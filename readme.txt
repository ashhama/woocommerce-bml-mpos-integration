=== BML mPOS Gateway Integration for WooCommerce ===
Contributors: ashhama
Tags: payment gateway, payment gateway for BML, Maldives, BML payment gateway, BML mPOS
Requires at least: 4.9
Tested up to: 5.5.1
Requires PHP: 5.6
Stable tag: 1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

3rd Party developed Plugin to connect Woocommerce with BML mPOS Gateway. (Note: This Plugin is developed by a 3rd party and the plugin and its developers are in no way related to Bank of Maldives)

== Description ==
A 3rd party plugin to integrate woocommerce payments with the BML mPOS gateway (Note: The devs of this project are not in anyway related to Bank of Maldives)

- Getting Started -

These instructions will get you a copy of the project up and running for development and testing purposes. 

- Prerequisites -

* Wordpress running on local or host machine.
* Woocommerce plugin installed in Wordpress
* Credentials to your BML mPOS gateway (Bank of Maldives Connect) provided to you by Bank of Maldives.

- Installation -

Install the plugin from the wordpress plugin repository or manually download and install the plugin from: https://github.com/ashhama/woocommerce-bml-mpos-integration    

- Deployment -

 1. Go to woocommerce settings in the sidebar of the wordpress Admin Page.

 2. Go to the \'Payments\' tab that appears in the horizontal bar

 3. Turn on the new \'BML mPOS Payment\' payment option that has now appeared. Then go to \'Manage\'.

 4. Enter the API login and key provided to you by BML. Customize anymore desired settings and then save.

* A payment method should now show in the checkout page of your store.

* Transaction IDs generated for payment of your woocommerce order will also now be visible in the \'Orders\' Pages

Notes

* This project and its developers are not in anyway related to Bank of Maldives.
* Having tested the plugin with numerous configurations and numerous scenarios and passed all tests, no developer can guarantee that their software is 100% free of bugs. We kindly request to update the developers if any bugs are observed.
*  We cannot also take responsibility for any issues this plugin may cause within your deployed software. We also cannot take responsibility on any impact this plugin cause on your business. Please refer to our LICENSE.md files for more details.
*  This plugin is only an intermediary between Woocommerce and BML Payment Gateway. Hence this is the scope of the project. Utilize the available woocommerce configurations and additional plugins to add and change features of your store. This includes customer side receipt printing, redirection to the defined success page after payment success and redirection to the defined cancel page after payment cancellation.

-Authors-

  * Ashham Abdulla - Full time software dev, full time Budō - Wanted to put this project out to give something back to the community. Specifically small businesses.

== Installation ==

 1. Go to woocommerce -> settings in the sidebar of the wordpress Admin Page.

 2. Go to the \'Payments\' tab that appears in the horizontal bar

 3.  Turn on the new \'BML mPOS Payment\' payment option that has now appeared. Then go to \'Manage\'.

4.   Enter the API login and API key provided to you by BML. Customize anymore desired settings and then save.

== Screenshots ==

1. This screenshot shows the payments tab in woocommerce after the plugin is installed

2. This screenshot shows the gateway settings page of the plugin

3. This screenshot shows the new credit card options added by the plugin to the checkout page of woocommerce



== Changelog ==
1.0.0 - Plugin Release after Beta Testing