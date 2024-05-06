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
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}
<form action="" id="priceRuleForm" style="position: relative;">
    <div class="loaderAdd-container" style="display:none;">
        <div class="puik-spinner-loader puik-spinner-loader--lg puik-spinner-loader--secondary" aria-live="polite"
            role="status">
            <div class="puik-spinner-loader__spinner" aria-hidden="true"></div>
        </div>
    </div>
    <div class="puik-card puik-card--default" style="margin-bottom: 20px;">
        <h3 class="puik-h3">{l s='Add a rule' d='Modules.Custompricerule.Admin'}</h3>
        <div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">{l s='Group' d='Modules.Custompricerule.Admin'}</label>
                <select class="form-control" id="groupSelect" name="group_id">
                    {foreach from=$groups item=group}
                    <option value="{$group.id_option}">{$group.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="input1">{l s='Coefficient (in %)' d='Modules.Custompricerule.Admin'}</label>
                <input class="form-control form-control-lg" type="text" id="coefficient" name="coefficient" required>
            </div>
        </div>
        <div style="text-align: right;">
            <button type="submit" class="puik-button puik-button--primary puik-button--md"
                style="width: max-content;">{l
                s='Apply rule' d='Modules.Custompricerule.Admin'}</button>
        </div>
    </div>
</form>