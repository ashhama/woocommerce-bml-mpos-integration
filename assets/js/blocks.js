(function () {
    'use strict';

    var wc = window.wc;
    var wp = window.wp;
    if (!wc || !wc.wcBlocksRegistry || !wc.wcSettings || !wp || !wp.element || !wp.htmlEntities || !wp.i18n) {
        return;
    }
    var name = 'woocommerce_bml_mpos_integration';
    var settings = wc.wcSettings.getSetting(name + '_data', {});
    var createElement = wp.element.createElement;
    var decode = wp.htmlEntities.decodeEntities;
    var title = decode(settings.title || wp.i18n.__('Credit Card', 'woocommerce_bml_mpos_integration'));

    function Label() {
        return createElement('span', {
            style: { display: 'inline-flex', alignItems: 'center', flexWrap: 'wrap', gap: '8px 14px', maxWidth: '100%' }
        }, createElement('span', null, title), settings.icon ? createElement('img', {
            src: settings.icon,
            alt: wp.i18n.__('BML Payment Gateway and accepted cards', 'woocommerce_bml_mpos_integration'),
            style: { width: '320px', maxWidth: '100%', height: 'auto', objectFit: 'contain' }
        }) : null);
    }

    function Content() {
        // Render plain text through React, never inject merchant-supplied HTML.
        return createElement('span', null, decode(settings.description || ''));
    }

    wc.wcBlocksRegistry.registerPaymentMethod({
        name: name,
        paymentMethodId: name,
        gatewayId: name,
        label: createElement(Label),
        content: createElement(Content),
        edit: createElement(Content),
        ariaLabel: title,
        canMakePayment: function () { return settings.isActive === true; },
        supports: { features: settings.supports || ['products'] }
    });
}());
