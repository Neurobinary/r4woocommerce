const Kkr4Module = (() => {
    const { useEffect, useCallback, useRef, createElement } = window.wp.element;

    function registerPaymentMethod(paymentMethod) {
        const name = `${paymentMethod.id}`
        if (!name.includes('kr4')) return
        const settings = window.wc.wcSettings.getSetting(`${name}_data`, null) || paymentMethod;
        console.log(paymentMethod.id, settings);
        const description = window.wp.htmlEntities.decodeEntities(window.wp.i18n.__(settings.description, 'woocommerce-kr4'));

        const label = createElement('div', {
            style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', width: '95%', flexWrap: 'wrap' }
        },
            createElement('span', { style: { width: 'auto' } }, window.wp.htmlEntities.decodeEntities(settings.title || window.wp.i18n.__('title', 'woocommerce-kr4'))),
            settings.icon ?
                createElement('img', {
                    src: settings.icon,
                    alt: settings.title || 'Payment Method Icon',
                    style: { display: 'flex', alignItems: 'center', justifyContent: 'center', marginLeft: '10px' }
                }) : null
        );

        const Kkr4ModuleComponent       = () => {
            const temporaryElement = document.createElement('div');
            temporaryElement.innerHTML = description;
            const plainText = temporaryElement.textContent || temporaryElement.innerText || '';
            return plainText;
        };
        const Block_Gateway = {
            name: name,
            label: label,
            content: createElement(Kkr4ModuleComponent, {
                onSelectPaymentMethod: () => {
                    const event = new CustomEvent('wc-blocks-internal.cart-update');
                    document.dispatchEvent(event);
                },
            }),
            edit: createElement(Kkr4ModuleComponent, null),
            canMakePayment: () => true,
            ariaLabel: settings.title || 'Payment Method',
            supports: {
                features: settings.supports || ['products'],
            },
        };
        window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);
    }

    return {
        init: () => {
            const paymentMethodData = window.wc.wcSettings.getSetting('paymentMethodData', {});
            Object.values(paymentMethodData).forEach(registerPaymentMethod);
        }
    };
})();

Kkr4Module.init();