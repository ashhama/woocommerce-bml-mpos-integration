# woocommerce-bml-mpos-integration
A 3rd party plugin to integrate woocommerce payments with the BML mPOS gateway (Note: The devs of this project are not in anyway related to Bank of Maldives)

## Getting Started

These instructions will get you a copy of the project up and running for development and testing purposes. To get started, download this project as a zip file. Project will be available in Wordpress plugin repository soon.

### Prerequisites

1. Wordpress running on local or host machine.
2. Woocommerce plugin installed in Wordpress
2. Credentials to your BML mPOS gateway (Bank of Maldives Connect) provided to you by Bank of Maldives.


### Installation


1. Download the plugin. (Downloading the whole repo from the download button above will work).
2. Go to plugins -> Add New in the sidebar of the wordpress Admin Page.

   ![Go to Add New Sub Section of Plugin Menu](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/add-new.JPG?raw=true)

3. Upload the downloaded plugin Zip File and Activate.

   ![Upload and activate](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/upload-plugin.JPG?raw=true)

## Deployment

1. Go to woocommerce settings in the sidebar of the wordpress Admin Page.

   ![Woocommerce settings](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/woocommerce-settings.JPG?raw=true)

2. Go to the 'Payments' tab that appears in the horizontal bar

   ![Woocommerce settings](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/payments.JPG?raw=true)

3. Turn on the new 'BML mPOS Payment' payment option that has now appeared. Then go to 'Manage'.

   ![Woocommerce payment settings](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/woocommerce-payments-settings.JPG?raw=true)

4. Enter the API login and key provided to you by BML. Customize anymore desired settings and then save.

   ![Woocommerce payment settings](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/bml-options.jpg?raw=true)


A payment method should now show in the checkout page of your store.

   ![Checkout Options](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/misc/assets/checkout-options.JPG?raw=true)


Transaction IDs generated for payment of your woocommerce order will also now be visible in the 'Orders' Pages

   ![Transaction ID in order](https://github.com/ashhama/woocommerce-bml-mpos-integration/blob/master/assets/images/order-transaction-id.JPG?raw=true)


## Notes

* This project and its developers are not in anyway related to Bank of Maldives.
* Having tested the plugin with numerous configurations and numerous scenarios and passed all tests, no developer can guarantee that their software is 100% free of bugs. We kindly request to update the developers if any bugs are observed.
* We cannot also take responsibility for any issues this plugin may cause within your deployed software. We also cannot take responsibility on any impact this plugin cause on your business. Please refer to our [LICENSE.md](LICENSE.md) files for more details.
* This plugin is only an intermediary between Woocommerce and BML Payment Gateway. Hence this is the scope of the project. Utilize the available woocommerce configurations and additional plugins to add and change features of your store. This includes customer side receipt printing, redirection to the defined success page after payment success and redirection to the defined cancel page after payment cancellation.


## Authors

* **Ashham Abdulla** - *Full time software dev, full time Budō* - Wanted to put this project out to give something back to the community. Specifically small businesses.

## License

This project is licensed under the GNU General Public License - see the [LICENSE.md](LICENSE.md) file for details.
