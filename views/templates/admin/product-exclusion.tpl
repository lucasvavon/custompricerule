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
    <form action="" id="exclusionForm">
        <div class="panel" id="fieldset_0" style="position: relative;">

            <div class="loaderExclusion-container" style="display:none;">
                <div class="puik-spinner-loader puik-spinner-loader--lg puik-spinner-loader--secondary"
                    aria-live="polite" role="status">
                    <div class="puik-spinner-loader__spinner" aria-hidden="true"></div>
                </div>
            </div>

            <div class="panel-heading">
                <i class="icon-ban"></i> {l s='Product exclusion' d='Modules.Sellingpricerule.Admin'}
            </div>
            <div style="display: grid;">
                <div class="form-group" style="display: flex;">
                    <label for="groupShop" style="text-align: end;" class="control-label col-lg-2 required">{l
                        s='Product'
                        d='Modules.Sellingpricerule.Admin'}</label>
                    <div class="col-lg-8">
                        <select id="productsInput" class="form-control" name="products[]" multiple="multiple">
                            <option>{l s='Choose a product' d='Modules.Sellingpricerule.Admin'}</option>
                            {foreach from=$products item=product}
                            <option value="{$product.id_product}">{$product.name} {if
                                $product.reference}({$product.reference}){/if}</option>
                            {/foreach}
                        </select>

                    </div>
                    <button type="submit" class="btn btn-primary">{l s='Exclude'
                        d='Modules.Sellingpricerule.Admin'}</button>
                </div>
            </div>
            <hr>
            <div class="form-wrapper">

                <table class="table">
                    <thead>
                        <tr>
                            <th>{l s='ID' d='Modules.Sellingpricerule.Admin'}</th>
                            <th>{l s='Name' d='Modules.Sellingpricerule.Admin'}</th>
                            <th>{l s='Reference' d='Modules.Sellingpricerule.Admin'}</th>
                            <th>{l s='Date' d='Modules.Sellingpricerule.Admin'}</th>
                            <th>{l s='Actions' d='Modules.Sellingpricerule.Admin'}</th>
                        </tr>
                    </thead>
                    {if $exclusions}
                    <tbody style="text-align: center;">
                        {foreach from=$exclusions item=exclusion}
                        <tr>
                            <td style="padding: 0.5rem;">{$exclusion.id_product}</td>
                            <td style="padding: 0.5rem;">{$exclusion.product_name}</td>
                            <td style="padding: 0.5rem;">{$exclusion.product_reference}</td>
                            <td style="padding: 0.5rem;">{$exclusion.date_add|date_format:"%d/%m/%Y"}</td>
                            <td style="padding: 0.5rem;">
                                <a href="#" class="toolbar-button" onclick="deleteExclusion({$exclusion.id_exclusion})">
                                    <i class="material-icons">delete</i>
                                </a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    {else}
                    <tbody style="text-align: center;">
                        <tr>
                            <td style="padding: 0.5rem;" colspan="6">{l s='No exclusion defined'
                                d='Modules.Sellingpricerule.Admin'}</td>
                        </tr>
                    </tbody>
                    {/if}
                </table>

            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $('#productsInput').select2(
                {
                    placeholder: "{l s='Search a product' d='Modules.Sellingpricerule.Admin'}"
                }
            );
        });
    </script>