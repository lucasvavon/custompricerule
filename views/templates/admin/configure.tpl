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
    <form action="" id="priceRuleForm">
        <div class="panel" id="fieldset_0" style="position: relative;">

            <div class="loaderAdd-container" style="display:none;">
                <div class="puik-spinner-loader puik-spinner-loader--lg puik-spinner-loader--secondary"
                    aria-live="polite" role="status">
                    <div class="puik-spinner-loader__spinner" aria-hidden="true"></div>
                </div>
            </div>

            <div class="panel-heading">
                <i class="icon-plus-sign"></i> {l s='Add a rule' d='Modules.Sellingpricerule.Admin'}
            </div>

            {if $groups}
            <div style="display: grid;">
                <div class="form-group 1">
                    <label for="groupShop" style="text-align: end;" class="control-label col-lg-4 required">{l s='Shop'
                        d='Modules.Sellingpricerule.Admin'}</label>
                    <div class="col-lg-8">
                        <select class="form-control fixed-width-xl" id="groupShop" name="groupShop">
                            {foreach from=$shops item=shop}
                            <option value="{$shop.id_shop}">{$shop.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group 2">
                    <label class="control-label col-lg-4 required" style="text-align: end;" for="groupSelect">{l
                        s='Group'
                        d='Modules.Sellingpricerule.Admin'}</label>
                    <div class="col-lg-8">
                        <select class="form-control fixed-width-xl" id="groupSelect" name="groupSelect">
                            {foreach from=$groups item=group}
                            <option value="{$group.id_option}">{$group.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group 3">
                    <label class="control-label col-lg-4 required" style="text-align: end;" for="coefficient">{l
                        s='Coefficient (in %)'
                        d='Modules.Sellingpricerule.Admin'}</label>
                    <div class="col-lg-8">
                        <input class="form-control  fixed-width-xs" type="text" id="coefficient" name="coefficient"
                            required>
                    </div>
                </div>
            </div>
            {else}
            <div>
                {l s='No groups configurable' d='Modules.Sellingpricerule.Admin'}
            </div>
            {/if}
            <div class="panel-footer">
                <button type="submit" class="btn btn-default pull-right" {if !$groups}disabled{/if}>
                    <i class="process-icon-save"></i> {l s='Apply rule' d='Modules.Sellingpricerule.Admin'}
                </button>
            </div>
        </div>
    </form>
    