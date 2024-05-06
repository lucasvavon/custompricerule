<form action="" id="priceRuleForm" style="position: relative;">
    <div class="loaderAdd-container" style="display:none;">
        <div class="puik-spinner-loader puik-spinner-loader--lg puik-spinner-loader--secondary" aria-live="polite"
            role="status">
            <div class="puik-spinner-loader__spinner" aria-hidden="true"></div>
        </div>
    </div>
    <div class="puik-card puik-card--default" style="margin-bottom: 20px;">
        <h3>{l s='Add a rule' mod='custompricerule'}</h3>
        <div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">{l s='Group' mod='custompricerule'}</label>
                <select class="form-control" id="groupSelect" name="group_id">
                    {foreach from=$groups item=group}
                    <option value="{$group.id_option}">{$group.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="input1">{l s='Coefficient (in %)' mod='custompricerule'}</label>
                <input class="form-control form-control-lg" type="text" id="coefficient" name="coefficient" required>
            </div>
        </div>
        <button type="submit" class="puik-button puik-button--primary puik-button--md" style="width: max-content;">{l
            s='Apply rule' mod='custompricerule'}</button>
    </div>
</form>