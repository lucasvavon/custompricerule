<?php
/**
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
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class Custompricerule extends Module
{
    public function __construct()
    {
        $this->name = 'custompricerule';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'Piment Bleu';
        $this->need_instance = 0;

        $this->bootstrap = false;

        parent::__construct();

        $this->displayName = $this->trans('Custom Price Rule', [], 'Modules.Custompricerule.Admin');
        $this->description = $this->trans('Custom Price Rule Description', [], 'Modules.Custompricerule.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Custompricerule.Admin');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install()
            && $this->installDb()
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('actionObjectProductUpdateAfter');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    public function installDb()
    {
        return Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS  `' . _DB_PREFIX_ . 'custom_price_rule` (
            `id_price_rule` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_group` INT UNSIGNED NOT NULL,
			`coef` int(3) NOT NULL,
			`date_add` datetime,
			PRIMARY KEY (`id_price_rule`))
			ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8'
        );
    }

    public function uninstallDb()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'custom_price_rule;');
    }

    /**
     * This method handles the module's configuration page
     *
     * @return string The page's HTML content
     */
    public function getContent()
    {
        // display any message, then the form
        return $this->displayHelpBox() . $this->displayForm() . $this->displayRulesList();
    }

    /**
     * Builds the configuration form
     *
     * @return string HTML code
     */
    public function displayRulesList()
    {
        $rules = $this->getCustomPriceRules();
        $this->context->smarty->assign(['rules' => $rules]);

        return $this->display(__FILE__, 'views/templates/admin/price_rule-list.tpl');
    }

    /**
     * Builds the configuration form
     *
     * @return string HTML code
     */
    public function displayForm()
    {
        $this->context->smarty->assign(['groups' => $this->getGroups()]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    /**
     * Builds the configuration form
     *
     * @return string HTML code
     */
    public function displayHelpBox()
    {
        return $this->display(__FILE__, 'views/templates/admin/help-box.tpl');
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        // Retrieve the ID of product
        $productId = (int) $params['object']->id;

        PrestaShopLogger::addLog("modification : $productId", 2);
        // new product object
        /* $product = new Product($productId);

        if (!Validate::isLoadedObject($product)) {
            die('Product does not exist');
        }

        $newPrice = $product->wholesale_price * 1.25;

        if ($newPrice) {
            // Set specific price details
            $specific_price = new SpecificPrice();
            $specific_price->id_product = $productId;
            $specific_price->id_customer = 0;
            $specific_price->id_shop = 0; // 0 if you want this to apply to all shops
            $specific_price->id_currency = 0; // 0 for all currencies
            $specific_price->id_country = 0; // 0 for all countries
            $specific_price->id_group = 8; // 0 for all groups
            $specific_price->price = $newPrice; // Set a new price or use -1 to not change the product price
            $specific_price->from_quantity = 1;  // The minimum quantity needed to apply the discount
            $specific_price->reduction = 0; // 20% reduction. For amount reduction, use a value here and set reduction_type to 'amount'
            $specific_price->reduction_type = 'amount'; // 'amount' for a fixed amount reduction
            $specific_price->from = '0000-00-00 00:00:00'; // Starting date of the specific price
            $specific_price->to = '0000-00-00 00:00:00'; // Ending date of the specific price

            // Save the new specific price
            return $specific_price->add();
        } */
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
        $token = Tools::getAdminTokenLite('AdminCustomPriceRule');
        // define js value to use in ajax url
        Media::addJsDef(
            [
                'token' => $token,
            ]
        );
    }

    public function updatepriceRuleValue()
    {
        Db::getInstance()->executeS();
    }

    public function getProductsWithpriceRuleValue()
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'specific_price` WHERE `id_group` = 8');
    }

    public function getGroups()
    {
        $langId = Configuration::get('PS_LANG_DEFAULT');

        return Db::getInstance()->executeS('SELECT `id_group` as id_option, `name` FROM `' . _DB_PREFIX_ . 'group_lang` WHERE `id_lang` = ' . $langId);
    }

    public function getCustomPriceRules()
    {
        $langId = Configuration::get('PS_LANG_DEFAULT');

        return Db::getInstance()->executeS('SELECT cpr.*, gl.name as group_name
        FROM `' . _DB_PREFIX_ . 'custom_price_rule` cpr 
        JOIN `' . _DB_PREFIX_ . 'group_lang` gl ON cpr.id_group = gl.id_group
        WHERE gl.`id_lang` = ' . $langId);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
