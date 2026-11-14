/**
 * Copyright © Lyra Network and contributors.
 * This file is part of Izipay plugin for WooCommerce. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @author    Geoffrey Crofte, Alsacréations (https://www.alsacreations.fr/)
 * @copyright Lyra Network and contributors
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL v2)
 */

/**
 * External dependencies.
 */
import { registerPaymentMethod } from '@woocommerce/blocks-registry';

/**
 * Internal dependencies.
 */
import { getMicuentawebServerData } from './micuentaweb-utils';

const PAYMENT_METHOD_NAME = 'micuentawebother_lyranetwork';
const micuentaweb_prefix = 'micuentawebother_';
const styles = 
    {
        divWidth: {
            width: '95%'
        },
        imgFloat: {
            float: 'right'
        }
    };

var micuentaweb_data = getMicuentawebServerData(PAYMENT_METHOD_NAME);

if (micuentaweb_data?.sub_methods) {
    (micuentaweb_data?.sub_methods).forEach((name) => {
        let method = micuentaweb_prefix + name.toLowerCase();
        let data = getMicuentawebServerData(method);

        let Content = () => {
            return (data?.description);
        };

        let Label = () => {
            return (
                <div style={ styles.divWidth }>
                    <span>{ data?.title}</span>
                    <img
                        style={ styles.imgFloat }
                        src={ data?.logo_url }
                        alt={ data?.title }
                    />
                </div>
            );
        };

        registerPaymentMethod({
            name: method,
            label: <Label />,
            ariaLabel: 'Micuentaweb payment method',
            canMakePayment: () => true,
            content: <Content />,
            edit: <Content />,
            supports: {
                features: data?.supports ?? [],
            },
        });
    })
}
