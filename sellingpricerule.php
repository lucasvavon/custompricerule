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

class Sellingpricerule extends Module
{
    private $langId;

    public function __construct()
    {
        $this->name = 'sellingpricerule';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'Piment Bleu';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->langId = Configuration::get('PS_LANG_DEFAULT');

        parent::__construct();

        $this->displayName = $this->trans('Selling price rule based on purchase price', [], 'Modules.Sellingpricerule.Admin');
        $this->description = $this->trans('This module lets you define sales prices calculated from the purchase price for a group of customers. This is ideal if you wish to sell to reseller customers by simply applying a percentage to the purchase price.', [], 'Modules.Sellingpricerule.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Sellingpricerule.Admin');

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
            'CREATE TABLE IF NOT EXISTS  `' . _DB_PREFIX_ . 'selling_price_rule` (
            `id_price_rule` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_shop` INT UNSIGNED NOT NULL,
			`id_group` INT UNSIGNED NOT NULL,
			`coef` int(3) NOT NULL,
			`date_add` datetime,
			PRIMARY KEY (`id_price_rule`))
			ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8'
        ) && Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS  `' . _DB_PREFIX_ . 'selling_price_rule_exclusion` (
            `id_exclusion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_product` INT UNSIGNED NOT NULL,
			`date_add` datetime,
			PRIMARY KEY (`id_exclusion`))
			ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8'
        );
    }

    public function uninstallDb()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'selling_price_rule;') && Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'selling_price_rule_exclusion;');
    }

    /**
     * This method handles the module's configuration page
     *
     * @return string The page's HTML content
     */
    public function getContent()
    {
        // display any message, then the form
        return $this->displayHelpBox() . $this->displayForm() . $this->displayRulesList() . $this->displayProductExclusion();
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
        $this->context->smarty->assign(['rules' => $this->getSellingPriceRules()]);

        return $this->display(__FILE__, 'views/templates/admin/price_rule-list.tpl');
    }

    /**
     * Builds the configuration form
     *
     * @return string HTML code
     */
    public function displayProductExclusion()
    {
        $AdminProductLinkComponents = parse_url($this->context->link->getAdminLink('AdminProducts'));
        parse_str($AdminProductLinkComponents['query'], $params);
        $this->context->smarty->assign(['token' => $params['_token']]);
        $this->context->smarty->assign(['hasExclusions' => $this->hasExclusions()]);
        $this->context->smarty->assign(['products' => $this->getProducts()]);
        $this->context->smarty->assign(['exclusions' => $this->getExclusions()]);

        return $this->display(__FILE__, 'views/templates/admin/product-exclusion.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            $this->context->controller->addCSS('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
            $this->context->controller->addJS('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        }
        $token = Tools::getAdminTokenLite('AdminSellingPriceRule');
        // define js value to use in ajax url
        Media::addJsDef(
            [
                'token' => $token,
            ]
        );
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        // Retrieve the ID of product
        $productId = (int) $params['object']->id;
        $product = new Product($productId);
        $wholesalePrice = (float) $product->wholesale_price;
        $rules = $this->getSellingPriceRules();
        if ($wholesalePrice > 0 && !$product->hasAttributes() && !$this->productExcluded($productId)) {
            foreach ($rules as $rule) {
                $coef = (float) $rule['coef'] / 100;
                $amountToAdd = $wholesalePrice * $coef;
                $newPrice = $wholesalePrice + $amountToAdd;

                $count = Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'specific_price WHERE id_product = ' . $productId . ' AND id_product_attribute = 0 AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
                $hasRule = (int) $count > 0 ? true : false;
                if ($hasRule) {
                    Db::getInstance()->update('specific_price', [
                        'price' => (float) $newPrice,
                        'from' => date('Y-m-d H:i:s'),
                    ], 'id_product = ' . $productId . ' AND id_product_attribute = 0 AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
                } else {
                    $this->addSpecificPrice($productId, $rule['id_shop'], $rule['id_group'], $newPrice);
                }
            }
        }
    }

    public function hookActionProductAttributeUpdate($params)
    {
        // Retrieve the ID of product attribute
        $productAttributeId = (int) $params['id_product_attribute'];
        $productId = (int) Db::getInstance()->getValue('SELECT id_product FROM ' . _DB_PREFIX_ . 'product_attribute WHERE id_product_attribute = ' . $productAttributeId);
        $combination = new Combination($productAttributeId);
        $wholesalePrice = (float) $combination->wholesale_price;
        $rules = $this->getSellingPriceRules();

        if ($wholesalePrice > 0 && !$this->productExcluded($productId)) {
            foreach ($rules as $rule) {
                $coef = (float) ((int) $rule['coef'] / 100);
                $amountToAdd = $wholesalePrice * $coef;
                $newPrice = $wholesalePrice + $amountToAdd;

                $count = Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'specific_price WHERE id_product_attribute = ' . $productAttributeId . ' AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
                $hasRule = (int) $count > 0 ? true : false;
                if ($hasRule) {
                    Db::getInstance()->update('specific_price', [
                        'price' => (float) $newPrice,
                        'from' => date('Y-m-d H:i:s'),
                    ], 'id_product_attribute = ' . $productAttributeId . ' AND id_group = ' . (int) $rule['id_group'] . ' AND id_shop = ' . (int) $rule['id_shop']);
                } else {
                    $this->addSpecificPrice($productId, $rule['id_shop'], $rule['id_group'], $newPrice, $productAttributeId);
                }
            }
        }
    }

    public function getShops()
    {
        return Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'shop');
    }

    public function getGroups()
    {
        return Db::getInstance()->executeS('SELECT gl.id_group as id_option, gl.name FROM ' . _DB_PREFIX_ . 'group_lang gl LEFT JOIN ' . _DB_PREFIX_ . 'selling_price_rule spr ON gl.id_group = spr.id_group WHERE id_lang = ' . $this->langId . ' AND spr.id_group IS NULL;');
    }

    public function getProducts()
    {
        return Db::getInstance()->executeS('SELECT p.id_product, pl.name, p.reference FROM ' . _DB_PREFIX_ . 'product p
        LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON p.id_product = pl.id_product
        LEFT JOIN ' . _DB_PREFIX_ . 'selling_price_rule_exclusion spre ON p.id_product = spre.id_product 
        WHERE pl.id_lang = ' . $this->langId . ' AND spre.id_product IS NULL
        ORDER BY p.id_product DESC;');
    }

    public function getExclusions()
    {
        return Db::getInstance()->executeS('SELECT spre.*, pl.name as product_name, p.reference as product_reference FROM ' . _DB_PREFIX_ . 'selling_price_rule_exclusion spre 
        LEFT JOIN ' . _DB_PREFIX_ . 'product p ON spre.id_product = p.id_product
        LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON spre.id_product = pl.id_product
        ORDER BY spre.id_exclusion DESC;');
    }

    public function getSellingPriceRules()
    {
        return Db::getInstance()->executeS('SELECT cpr.*, gl.name as group_name,  s.name as shop_name
        FROM ' . _DB_PREFIX_ . 'selling_price_rule cpr 
        LEFT JOIN ' . _DB_PREFIX_ . 'group_lang gl ON cpr.id_group = gl.id_group
        LEFT JOIN ' . _DB_PREFIX_ . 'shop s ON cpr.id_shop = s.id_shop
        WHERE gl.id_lang = ' . $this->langId);
    }

    public function addSpecificPrice($productId, $shopId, $groupId, $newPrice, $attributeId = 0)
    {
        PrestaShopLogger::addLog("$productId with attri $attributeId : nv prix $newPrice !");

        $specificPrice = new SpecificPrice();
        $specificPrice->id_product = (int) $productId;
        $specificPrice->id_product_attribute = (int) $attributeId;
        $specificPrice->reduction = 0;
        $specificPrice->reduction_type = 'amount';
        $specificPrice->reduction_tax = 1;
        $specificPrice->id_shop = (int) $shopId;
        $specificPrice->id_cart = 0;
        $specificPrice->id_currency = 0;
        $specificPrice->id_country = 0;
        $specificPrice->id_group = (int) $groupId;
        $specificPrice->id_customer = 0;
        $specificPrice->price = $newPrice;
        $specificPrice->from_quantity = 1;
        $specificPrice->from = date('Y-m-d H:i:s');
        $specificPrice->to = '0000-00-00 00:00:00';

        return $specificPrice->save();
    }

    public function productExcluded($productId): bool
    {
        $count = Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'selling_price_rule_exclusion WHERE id_product = ' . (int) $productId);

        return $count > 0;
    }

    public function hasExclusions(): bool
    {
        $count = Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'selling_price_rule_exclusion');

        return $count > 0;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
