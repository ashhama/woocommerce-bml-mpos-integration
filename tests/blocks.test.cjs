const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.join(__dirname, '../assets/js/blocks.js'), 'utf8');
function run(settings, override) {
    const registrations = [];
    const window = {
        wc: {
            wcSettings: {getSetting: (key) => {
                assert.equal(key, 'woocommerce_bml_mpos_integration_data');
                return settings;
            }},
            wcBlocksRegistry: {registerPaymentMethod: method => registrations.push(method)}
        },
        wp: {
            element: {createElement: (type, props, ...children) => ({type, props, children})},
            htmlEntities: {decodeEntities: text => text.replace(/&amp;/g, '&')},
            i18n: {__: text => text}
        }
    };
    if (override) override(window);
    vm.runInNewContext(source, {window});
    return registrations;
}
const config = {isActive: true, title: 'BML &amp; Cards', description: '<script>alert(1)</script>', icon: 'https://example.test/bml.png', supports: ['products']};
const methods = run(config);
assert.equal(methods.length, 1);
const m = methods[0];
assert.equal(m.name, 'woocommerce_bml_mpos_integration');
assert.equal(m.paymentMethodId, m.name);
assert.equal(m.gatewayId, m.name);
assert.equal(m.canMakePayment(), true);
assert.equal(m.ariaLabel, 'BML & Cards');
assert.deepEqual(Array.from(m.supports.features), ['products']);
const content = m.content.type();
assert.equal(content.children[0], config.description);
assert.equal(content.props, null); // Text node, not dangerouslySetInnerHTML.
const icon = m.label.type().children[1];
assert.equal(icon.props.src, config.icon);
assert.equal(icon.props.style.maxWidth, '100%');
assert.equal(icon.props.style.height, 'auto');
assert.equal(run({...config, icon: ''})[0].label.type().children[1], null);
for (const isActive of [false, undefined, 'yes']) {
    assert.equal(run({...config, isActive})[0].canMakePayment(), false);
}
assert.equal(run({})[0].canMakePayment(), false);
assert.equal(run({})[0].ariaLabel, 'Credit Card');
assert.equal(run(config, w => {delete w.wc;}).length, 0);
assert.equal(run(config, w => {delete w.wp.element;}).length, 0);
assert.equal(run(config, w => {delete w.wc.wcBlocksRegistry;}).length, 0);
console.log('PASS: registration, gateway routing, availability, fallback label, text rendering, icon toggle/sizing, dependency guards.');
