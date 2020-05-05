# Magento 2 — CMS data installer
This module allow to install/update/backup/remove CMS Block or Page

# Installation
* Download [master.zip](https://github.com/klierik/magento2-cms-data-installer/archive/master.zip) archive
* Unpack zip archive
* Copy `klierik` folder to `magento/app/code/`
* Update module vendor name `klierik` with your specific name if needed

# How to install CMS data
* Open `app/code/klierik/Cms/etc/module.xml` and increase module version
* Update `app/code/klierik/Cms/Setup/UpgradeData.php` code with in increased version. Take a look examples below. 
* Run `php bin/magento setup:upgrade --keep-generated` and install content data

# Backup
Script will backup any CMS Block or CMS Page automatically to new block/page with same ID: `id_%hash%`

# Examples
Take a look at [UpgradeData.php](https://github.com/klierik/magento2-cms-data-installer/blob/master/klierik/Cms/Setup/UpgradeData.php) for more examples

### Install or Update CMS Block
```
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $footer_links_example = <<<HTML
<h3>Customer Service</h3>
<ul>
    <li><a href="/contact/">Contact Us</a></li>
    <li><a href="/returns/">Returns</a></li>
    <li><a href="/shipping/">Shipping</a></li>
    <li><a href="/delivery/">Shipping</a></li>
    <li><a href="/faq/">FAQ</a></li>
</ul>
HTML;

            $cmsBlocks = [
                [
                    'title' => 'Footer Links :: Example',
                    'identifier' => 'footer_links_example',
                    'content' => $footer_links_example,
                    'is_active' => 1,
                    'stores' => self::DEFAULT_STORE_ID
                ]
            ];

            $this->installUpdateCmsBlock($cmsBlocks);

        }
```

### Install/Update CMS Block via SetupData Module
```
        if (version_compare($context->getVersion(), '1.0.2') < 0) {

            $cmsBlocks = [
                [
                    'title' => 'Footer Links :: Example 2',
                    'identifier' => 'footer_links_example_2',
                    'content' => $this->cmsBlockData->getLoremLipsumData(),
                    'is_active' => 1,
                    'stores' => self::DEFAULT_STORE_ID
                ]
            ];

            $this->installUpdateCmsBlock($cmsBlocks);

        }
```

### Install or Update Multiple CMS Blocks via SetupData Module
```
        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            $cmsBlocks = [
                [
                    'title' => 'Footer Links :: Example 3',
                    'identifier' => 'footer_links_example_3',
                    'content' => $this->cmsBlockData->getLoremLipsumData(),
                    'is_active' => 1,
                    'stores' => self::DEFAULT_STORE_ID
                ],
                [
                    'title' => 'Footer Links :: Example 4',
                    'identifier' => 'footer_links_example_4',
                    'content' => $this->cmsBlockData->getLoremLipsumData(),
                    'is_active' => 1,
                    'stores' => self::DEFAULT_STORE_ID
                ],
                [
                    'title' => 'Footer Links :: Example 5',
                    'identifier' => 'footer_links_example_5',
                    'content' => $this->cmsBlockData->getLoremLipsumData(),
                    'is_active' => 1,
                    'stores' => self::DEFAULT_STORE_ID
                ]
            ];

            $this->installUpdateCmsBlock($cmsBlocks);
            $this->removeCmsBlock('footer_links_example_5');

        }
```

### Install or Update CMS Page
```
        if (version_compare($context->getVersion(), '1.0.4') < 0) {

            $cmsPageGettingStartedContent = <<<HTML
<div class="block block-getting-started">
    <div class="block-header">Getting Started</div>
    <div class="block-content">
        <p>Quisque ut dolor gravida, placerat libero vel, euismod. Quid securi etiam tamquam eu fugiat nulla pariatur. Ut enim ad minim veniam, quis nostrud exercitation. Qui ipsorum lingua Celtae, nostra Galli appellantur.</p>
        <p>Salutantibus vitae elit libero, a pharetra augue. Tityre, tu patulae recubans sub tegmine fagi dolor. Contra legem facit qui id facit quod lex prohibet. Etiam habebis sem dicantur magna mollis euismod. Ab illo tempore, ab est sed immemorabili. Quis aute iure reprehenderit in voluptate velit esse.</p>
        <h3>At nos hinc posthac, sitientis piros Afros.</h3>
        <ul>
            <li>Unam incolunt Belgae, aliam Aquitani, tertiam.</li>
            <li>Inmensae subtilitatis, obscuris et malesuada fames.</li>
            <li>Curabitur blandit tempus ardua ridiculus sed magna.</li>
        </ul>
        <h3>Hi omnes lingua, institutis, legibus inter se differunt.</h3>
        <p>Phasellus laoreet lorem vel dolor tempus vehicula.</p>
    </div>
</div>
HTML;

            $cmsPageGettingStartedLayoutXmlUpdate = <<<HTML
<body>
    <referenceBlock name="page.main.title" display="false"/>
</body>
HTML;

            $cmsPages = [
                [
                    'title'            => 'Getting Started',
                    'page_layout'      => '1column',
                    'meta_keywords'    => '',
                    'meta_description' => '',
                    'identifier'       => 'getting-started',
                    'content_heading'  => '',
                    'content'          => $cmsPageGettingStartedContent,
                    'is_active'        => 1,
                    'stores'           => [self::DEFAULT_STORE_ID],
                    'sort_order'       => 0,
                    'layout_update_xml'=> $cmsPageGettingStartedLayoutXmlUpdate
                ]
            ];

            $this->installUpdateCmsPage($cmsPages);
        }
```

### Install or Update CMS Page
```
        if (version_compare($context->getVersion(), '1.0.5') < 0) {

            $cmsPages = [
                [
                    'title'            => 'Delivery',
                    'page_layout'      => '1column',
                    'meta_keywords'    => '',
                    'meta_description' => '',
                    'identifier'       => 'delivery',
                    'content_heading'  => 'Delivery',
                    'content'          => $this->cmsPageData->getDeliveryData(),
                    'is_active'        => 1,
                    'stores'           => [self::DEFAULT_STORE_ID],
                    'sort_order'       => 0
                ]
            ];

            $this->installUpdateCmsPage($cmsPages);
        }
```

### Install or Update Multiple CMS Pages via SetupData Module
```
        if (version_compare($context->getVersion(), '1.0.6') < 0) {

            $cmsPages = [
                [
                    'title'            => 'Our Story',
                    'page_layout'      => '1column',
                    'meta_keywords'    => '',
                    'meta_description' => '',
                    'identifier'       => 'our-story',
                    'content_heading'  => 'Our Story',
                    'content'          => $this->cmsPageData->getLoremLipsumData(),
                    'is_active'        => 1,
                    'stores'           => [self::DEFAULT_STORE_ID],
                    'sort_order'       => 0
                ],
                [
                    'title'            => 'Frequently Asked Questions',
                    'page_layout'      => '1column',
                    'meta_keywords'    => '',
                    'meta_description' => '',
                    'identifier'       => 'faq',
                    'content_heading'  => 'Frequently Asked Questions',
                    'content'          => $this->cmsPageData->getLoremLipsumData(),
                    'is_active'        => 1,
                    'stores'           => [self::DEFAULT_STORE_ID],
                    'sort_order'       => 0
                ],
                [
                    'title'            => 'Gift Cards',
                    'page_layout'      => '1column',
                    'meta_keywords'    => '',
                    'meta_description' => '',
                    'identifier'       => 'gift-cards',
                    'content_heading'  => 'Gift Cards',
                    'content'          => $this->cmsPageData->getLoremLipsumData(),
                    'is_active'        => 1,
                    'stores'           => [self::DEFAULT_STORE_ID],
                    'sort_order'       => 0
                ]

            ];

            $this->installUpdateCmsPage($cmsPages);
            $this->removeCmsPage('gift-cards');
        }
```

# Debugging
In some cases need to re-install some content data localy. You can decrease module setup version `-1` in database and run `php bin/magento setup:upgrade --keep-generated` again. Script will install content data to database again.

You can update database via any MySQL client (like phpMyAdmin or SequelPro) or via SQL:
```
UPDATE `setup_module` SET `schema_version` = '1.0.5', `data_version` = '1.0.5' WHERE `module` = 'klierik_Cms';
```
