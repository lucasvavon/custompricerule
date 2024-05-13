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
        $this->need_instance = 1;
        $this->bootstrap = true;

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
            && $this->registerHook('actionObjectProductUpdateAfter')
            && $this->registerHook('actionProductAttributeUpdate');
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
            `id_shop` INT UNSIGNED NOT NULL,
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
    public function displayHelpBox()
    {
        return $this->display(__FILE__, 'views/templates/admin/help-box.tpl');
    }

    /**
     * Builds the configuration form
     *
     * @return string HTML code
     */
    public function displayForm()
    {
        $this->context->smarty->assign(['groups' => $this->getGroups()]);
        $this->context->smarty->assign(['shops' => $this->getShops()]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
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

    public function hookActionObjectProductUpdateAfter($params)
    {
        // Retrieve the ID of product
        $productId = (int) $params['object']->id;
        $product = new Product($productId);
        $rules = $this->getCustomPriceRules();

        foreach ($rules as $rule) {
            $coef = (int) $rule['coef'] / 100;
            $amountToAdd = $product->wholesale_price * $coef;
            $newPrice = $product->wholesale_price + $amountToAdd;

            Db::getInstance()->update('specific_price', [
                'price' => (float) $newPrice,
                'from' => date('Y-m-d H:i:s'),
            ], 'id_product = ' . $productId . ' AND id_product_attribute = 0 AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
        }
    }

    public function hookActionProductAttributeUpdate($params)
    {
        // Retrieve the ID of product attribute
        $productAttributeId = (int) $params['id_product_attribute'];
        $combination = new Combination($productAttributeId);
        $wholesale_price = $combination->wholesale_price;
        $rules = $this->getCustomPriceRules();

        foreach ($rules as $rule) {
            $coef = (int) $rule['coef'] / 100;
            $amountToAdd = $wholesale_price * $coef;
            $newPrice = $wholesale_price + $amountToAdd;

            Db::getInstance()->update('specific_price', [
                'price' => (float) $newPrice,
                'from' => date('Y-m-d H:i:s'),
            ], 'id_product_attribute = ' . $productAttributeId . ' AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
        }
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

    public function getShops()
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'shop`');
    }

    public function getGroups()
    {
        $langId = Configuration::get('PS_LANG_DEFAULT');

        return Db::getInstance()->executeS('SELECT `id_group` as id_option, `name` FROM `' . _DB_PREFIX_ . 'group_lang` WHERE `id_lang` = ' . $langId);
    }

    public function getCustomPriceRules()
    {
        $langId = Configuration::get('PS_LANG_DEFAULT');

        return Db::getInstance()->executeS('SELECT cpr.*, gl.name as group_name,  s.name as shop_name
        FROM `' . _DB_PREFIX_ . 'custom_price_rule` cpr 
        JOIN `' . _DB_PREFIX_ . 'group_lang` gl ON cpr.id_group = gl.id_group
        JOIN `' . _DB_PREFIX_ . 'shop` s ON cpr.id_shop = s.id_shop
        WHERE gl.`id_lang` = ' . $langId);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
