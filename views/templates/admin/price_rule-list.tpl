{**
* Copyright since 2007 PrestaShop SA and Contributors
* PrestaShop is an International Registered Trademark & Property of PrestaShop SA
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.md.
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author PrestaShop SA and Contributors <contact@prestashop.com>
    * @copyright Since 2007 PrestaShop SA and Contributors
    * @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
    *}
    <div class="panel" id="fieldset_1" style="position: relative;">
        <div class="loaderDelete-container" style="display:none;">
            <div class="puik-spinner-loader puik-spinner-loader--lg puik-spinner-loader--secondary" aria-live="polite"
                role="status">
                <div class="puik-spinner-loader__spinner" aria-hidden="true"></div>
            </div>
        </div>

        <div class="panel-heading">
            <i class="icon-list-alt"></i> {l s='Rules list' d='Modules.Sellingpricerule.Admin'}
        </div>
        <div class="form-wrapper">

            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='ID' d='Modules.Sellingpricerule.Admin'}</th>
                        <th>{l s='Shop' d='Modules.Sellingpricerule.Admin'}</th>
                        <th>{l s='Group' d='Modules.Sellingpricerule.Admin'}</th>
                        <th>{l s='Coefficient (in %)' d='Modules.Sellingpricerule.Admin'}</th>
                        <th>{l s='Date' d='Modules.Sellingpricerule.Admin'}</th>
                        <th>{l s='Actions' d='Modules.Sellingpricerule.Admin'}</th>
                    </tr>
                </thead>
                {if $rules}
                <tbody style="text-align: center;">
                    {foreach from=$rules item=rule}
                    <tr>
                        <td style="padding: 0.5rem;">{$rule.id_price_rule}</td>
                        <td style="padding: 0.5rem;">{$rule.shop_name}</td>
                        <td style="padding: 0.5rem;">{$rule.group_name}</td>
                        <td style="padding: 0.5rem;">{$rule.coef}</td>
                        <td style="padding: 0.5rem;">{$rule.date_add|date_format:"%d/%m/%Y"}</td>
                        <td style="padding: 0.5rem;">
                            <a href="#" class="toolbar-button" onclick="deletePriceRule({$rule.id_price_rule}, {$rule.id_shop}, {$rule.id_group})">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
                {else}
                <tbody style="text-align: center;">
                    <tr>
                        <td style="padding: 0.5rem;" colspan="6">{l s='No rules defined'
                            d='Modules.Sellingpricerule.Admin'}</td>
                    </tr>
                </tbody>
                {/if}
            </table>
        </div>
    </div>