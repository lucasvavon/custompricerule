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

return [
    'name' => 'AngarTheme',
    'display_name' => 'AngarTheme',
    'version' => '2.4.4',
    'theme_key' => 'dea02ceb7c0a4b9a1388c3819d0adda5',
    'author' => [
        'name' => 'AngarThemes',
        'email' => 'https://addons.prestashop.com/en/contact-us?id_product=39394',
        'url' => 'https://addons.prestashop.com/en/2_community-developer?contributor=147992',
    ],
    'meta' => [
        'compatibility' => [
            'from' => '1.7.0.0',
            'to' => null,
        ],
        'available_layouts' => [
            'layout-full-width' => [
                'name' => 'Full Width',
                'description' => 'No side columns, ideal for distraction-free pages such as product pages.',
            ],
            'layout-both-columns' => [
                'name' => 'Three Columns',
                'description' => 'One large central column and 2 side columns.',
            ],
            'layout-left-column' => [
                'name' => 'Two Columns, small left column',
                'description' => 'Two columns with a small left column',
            ],
            'layout-right-column' => [
                'name' => 'Two Columns, small right column',
                'description' => 'Two columns with a small right column',
            ],
        ],
    ],
    'assets' => [
        'css' => [
            'all' => [
                0 => [
                    'id' => 'bxslider',
                    'path' => 'assets/css/libs/jquery.bxslider.css',
                ],
                1 => [
                    'id' => 'font-awesome',
                    'path' => 'assets/css/font-awesome.css',
                ],
                2 => [
                    'id' => 'angartheme',
                    'path' => 'assets/css/angartheme.css',
                ],
                3 => [
                    'id' => 'homemodyficators',
                    'path' => 'assets/css/home_modyficators.css',
                ],
                4 => [
                    'id' => 'rwd',
                    'path' => 'assets/css/rwd.css',
                ],
                5 => [
                    'id' => 'black',
                    'path' => 'assets/css/black.css',
                ],
            ],
        ],
        'js' => [
            'all' => [
                0 => [
                    'id' => 'bxslider',
                    'path' => 'assets/js/libs/jquery.bxslider.min.js',
                ],
                1 => [
                    'id' => 'angartheme',
                    'path' => 'assets/js/angartheme.js',
                ],
            ],
        ],
    ],
    'global_settings' => [
        'configuration' => [
            'PS_IMAGE_QUALITY' => 'png',
            'PS_PRODUCTS_PER_PAGE' => 12,
            'BLOCK_CATEG_ROOT_CATEGORY' => 0,
            'BLOCKSOCIAL_FACEBOOK' => 'https://www.facebook.com/',
            'BLOCKSOCIAL_TWITTER' => 'https://twitter.com/',
            'BLOCKSOCIAL_YOUTUBE' => 'https://www.youtube.com/',
            'BLOCKSOCIAL_GOOGLE_PLUS' => 'https://plus.google.com/',
            'BLOCKSOCIAL_PINTEREST' => 'https://pinterest.com',
            'BLOCKSOCIAL_INSTAGRAM' => 'https://www.instagram.com/',
        ],
        'modules' => [
            'to_enable' => [
                0 => 'angarfastconfig',
                1 => 'angarbanners',
                2 => 'angarbestsellers',
                3 => 'angarcatproduct',
                4 => 'angarcmsdesc',
                5 => 'angarcmsinfo',
                6 => 'angarfacebook',
                7 => 'angarfeatured',
                8 => 'angarhomecat',
                9 => 'angarmanufacturer',
                10 => 'angarnewproducts',
                11 => 'angarparallax',
                12 => 'angarslider',
                13 => 'angarspecials',
                14 => 'angarcontact',
                15 => 'angarscrolltop',
                16 => 'angarthemeconfigurator',
                17 => 'productcomments',
                18 => 'ps_categoryproducts',
                19 => 'ps_linklist',
            ],
            'to_disable' => [
                0 => 'ps_imageslider',
                1 => 'ps_banner',
                2 => 'ps_featuredproducts',
                3 => 'ps_bestsellers',
                4 => 'ps_newproducts',
                5 => 'ps_specials',
                6 => 'ps_customtext',
                7 => 'gamification',
            ],
        ],
        'hooks' => [
            'custom_hooks' => [
                0 => [
                    'name' => 'angarCmsDesc',
                    'title' => 'angarCmsDesc',
                    'description' => 'Angar hook',
                ],
            ],
            'modules_to_hook' => [
                'displayBanner' => [
                    0 => 'angarbanners',
                ],
                'displayNav1' => [
                    0 => 'angarcontact',
                ],
                'displayNav2' => [
                    0 => 'ps_customersignin',
                    1 => 'ps_languageselector',
                    2 => 'ps_currencyselector',
                ],
                'displayTop' => [
                    0 => 'ps_shoppingcart',
                    1 => 'ps_searchbar',
                ],
                'displayNavFullWidth' => [
                    0 => 'ps_mainmenu',
                ],
                'displayTopColumn' => [
                    0 => 'angarslider',
                    1 => 'angarbanners',
                ],
                'displayLeftColumn' => [
                    0 => 'ps_categorytree',
                    1 => 'ps_linklist',
                    2 => 'ps_facetedsearch',
                    3 => 'ps_brandlist',
                    4 => 'ps_supplierlist',
                    5 => 'angarbestsellers',
                    6 => 'angarbanners',
                ],
                'displayRightColumn' => [
                    0 => 'angarbanners',
                ],
                'displayHomeTab' => [
                    0 => 'angarnewproducts',
                    1 => 'angarfeatured',
                    2 => 'angarspecials',
                ],
                'displayHomeTabContent' => [
                    0 => 'angarnewproducts',
                    1 => 'angarfeatured',
                    2 => 'angarspecials',
                ],
                'displayHome' => [
                    0 => 'angarbanners',
                ],
                'angarCmsDesc' => [
                    0 => 'angarcmsdesc',
                ],
                'angarParallax' => [
                    0 => 'angarparallax',
                ],
                'angarProductCat' => [
                    0 => 'angarcatproduct',
                ],
                'angarManufacturer' => [
                    0 => 'angarmanufacturer',
                ],
                'angarBannersBottom' => [
                    0 => 'angarbanners',
                ],
                'angarCmsBottom' => [
                    0 => 'angarcmsinfo',
                ],
                'angarFacebook' => [
                    0 => 'angarfacebook',
                ],
                'displayProductListReviews' => [
                    0 => 'productcomments',
                ],
                'displayCommentsExtra' => [
                    0 => 'productcomments',
                ],
                'displayProductTab' => [
                    0 => 'productcomments',
                ],
                'displayProductTabContent' => [
                    0 => 'productcomments',
                ],
                'displayFooter' => [
                    0 => 'ps_linklist',
                    1 => 'ps_customeraccountlinks',
                    2 => 'angarcontact',
                    3 => 'angarscrolltop',
                    4 => 'angarthemeconfigurator',
                ],
                'displayFooterAfter' => [
                    0 => 'ps_emailsubscription',
                    1 => 'ps_socialfollow',
                    2 => 'angarbanners',
                ],
                'displaySearch' => [
                    0 => 'ps_searchbar',
                ],
                'displayProductAdditionalInfo' => [
                    0 => 'ps_sharebuttons',
                    1 => 'productcomments',
                ],
                'displayReassurance' => [
                    0 => 'blockreassurance',
                ],
                'displayBeforeBodyClosingTag' => [
                    0 => 'angarslider',
                    1 => 'angarcatproduct',
                    2 => 'angarmanufacturer',
                    3 => 'statsdata',
                ],
                'displayFooterProduct' => [
                    0 => 'ps_categoryproducts',
                    1 => 'productcomments',
                ],
                'displayBackOfficeHeader' => [
                    0 => 'angarbanners',
                    1 => 'angarfastconfig',
                    2 => 'angarscrolltop',
                    3 => 'welcome',
                ],
            ],
        ],
        'image_types' => [
            'cart_default' => [
                'width' => 125,
                'height' => 125,
                'scope' => [
                    0 => 'products',
                ],
            ],
            'small_default' => [
                'width' => 98,
                'height' => 98,
                'scope' => [
                    0 => 'products',
                    1 => 'categories',
                ],
            ],
            'medium_default' => [
                'width' => 452,
                'height' => 452,
                'scope' => [
                    0 => 'products',
                ],
            ],
            'home_default' => [
                'width' => 259,
                'height' => 259,
                'scope' => [
                    0 => 'products',
                ],
            ],
            'large_default' => [
                'width' => 800,
                'height' => 800,
                'scope' => [
                    0 => 'products',
                ],
            ],
            'category_default' => [
                'width' => 200,
                'height' => 200,
                'scope' => [
                    0 => 'categories',
                ],
            ],
            'stores_default' => [
                'width' => 170,
                'height' => 115,
                'scope' => [
                    0 => 'stores',
                ],
            ],
            'manufacturer_default' => [
                'width' => 125,
                'height' => 125,
                'scope' => [
                    0 => 'manufacturers',
                ],
            ],
        ],
    ],
    'theme_settings' => [
        'default_layout' => 'layout-full-width',
        'layouts' => [
            'index' => 'layout-left-column',
            'category' => 'layout-left-column',
            'best-sales' => 'layout-left-column',
            'new-products' => 'layout-left-column',
            'prices-drop' => 'layout-left-column',
            'product' => 'layout-left-column',
            'contact' => 'layout-left-column',
            'manufacturer' => 'layout-left-column',
            'supplier' => 'layout-left-column',
            'search' => 'layout-left-column',
        ],
    ],
    'dependencies' => [
        'modules' => [
            0 => 'angarfastconfig',
            1 => 'angarbanners',
            2 => 'angarbestsellers',
            3 => 'angarcatproduct',
            4 => 'angarcmsdesc',
            5 => 'angarcmsinfo',
            6 => 'angarfacebook',
            7 => 'angarfeatured',
            8 => 'angarhomecat',
            9 => 'angarmanufacturer',
            10 => 'angarnewproducts',
            11 => 'angarparallax',
            12 => 'angarslider',
            13 => 'angarspecials',
            14 => 'angarcontact',
            15 => 'angarthemeconfigurator',
            16 => 'angarscrolltop',
            17 => 'productcomments',
            18 => 'ps_categoryproducts',
            19 => 'ps_brandlist',
            20 => 'ps_supplierlist',
        ],
    ],
];
