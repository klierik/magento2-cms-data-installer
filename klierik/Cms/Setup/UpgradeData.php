<?php
/**
 * Copyright © github.com/klierik All rights reserved.
 * See LICENSE for license details.
 */

namespace klierik\Cms\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use klierik\Cms\Model\SetupData\CmsBlockData;
use klierik\Cms\Model\SetupData\CmsPageData;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    const DEFAULT_STORE_ID = \Magento\Store\Model\Store::DEFAULT_STORE_ID;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    private $_blockFactory;

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    private $_blockRepository;

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    private $_pageFactory;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    private $_pageRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var CmsBlockData
     */
    private $cmsBlockData;

    /**
     * @var CmsPageData
     */
    private $cmsPageData;

    /**
     * UpgradeData constructor.
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param CmsBlockData $setupCmsBlockData
     * @param CmsPageData $setupCmsPageData
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository,
        \Psr\Log\LoggerInterface $logger,
        CmsBlockData $setupCmsBlockData,
        CmsPageData $setupCmsPageData
    )
    {
        $this->_blockFactory = $blockFactory;
        $this->_blockRepository = $blockRepository;
        $this->_pageFactory = $pageFactory;
        $this->_pageRepository = $pageRepository;
        $this->logger = $logger;
        $this->cmsBlockData = $setupCmsBlockData;
        $this->cmsPageData = $setupCmsPageData;
    }

    /**
     * Remove CMS Block
     *
     * @param string $blockId
     * @return void
     * @throws \Exception
     */
    private function removeCmsBlock(string $blockId)
    {
        try {
            $this->_blockRepository->deleteById($blockId);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            $this->logger->critical($exception->getMessage());
        }
    }

    /**
     * Remove CMS Page
     *
     * @param string $pageId
     * @return void
     * @throws \Exception
     */
    private function removeCmsPage(string $pageId)
    {
        try {
            $this->_pageRepository->deleteById($pageId);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }

    /**
     * Install or Update CMS Block
     *
     * @param array $cmsBlocks
     */
    private function installUpdateCmsBlock($cmsBlocks)
    {

        foreach ($cmsBlocks as $cmsBlockData) {
            $cmsBlock = $this->_blockFactory->create()->setStoreId(self::DEFAULT_STORE_ID)->load($cmsBlockData['identifier'], 'identifier');

            if (!$cmsBlock->getId()) {
                $this->_blockFactory->create()->setData($cmsBlockData)->save();
            } else {
                $this->backupCmsBlock($cmsBlock);

                isset($cmsBlock['title']) ? $cmsBlock->setTitle($cmsBlockData['title'])->save(): '';
                isset($cmsBlock['content']) ? $cmsBlock->setContent($cmsBlockData['content'])->save(): '';
                isset($cmsBlock['is_active']) ? $cmsBlock->setIsActive($cmsBlockData['is_active'])->save(): '';
            }
        }

    }

    /**
     * Backup present CMS Block
     *
     * @param array $cmsBlock
     */
    private function backupCmsBlock($cmsBlock)
    {
        $cmsBlockBackup = [
            'title'      => $cmsBlock->getTitle() . ' — Backup ' . date("m:h d.m.Y"),
            'identifier' => $cmsBlock->getIdentifier() . '_' . uniqid(),
            'content'    => $cmsBlock->getContent(),
            'is_active'  => 0,
            'stores'     => $cmsBlock->getStores()
        ];

        $this->_blockFactory->create()->setData($cmsBlockBackup)->save();
    }

    /**
     * Install or Update CMS Page
     *
     * @param array $cmsPages
     */
    private function installUpdateCmsPage($cmsPages)
    {

        foreach ($cmsPages as $cmsPageData) {
            $cmsPage = $this->_pageFactory->create()->setStoreId([self::DEFAULT_STORE_ID])->load($cmsPageData['identifier'], 'identifier');

            if (!$cmsPage->getId()) {
                $this->_pageFactory->create()->setData($cmsPageData)->save();
            } else {
                $this->backupCmsPage($cmsPage);

                isset($cmsPageData['title']) ? $cmsPage->setTitle($cmsPageData['title'])->save() : '';
                isset($cmsPageData['page_layout']) ? $cmsPage->setPageLayout($cmsPageData['page_layout'])->save() : '';
                isset($cmsPageData['meta_keywords']) ? $cmsPage->setMetaKeywords($cmsPageData['meta_keywords'])->save(): '';
                isset($cmsPageData['meta_description']) ? $cmsPage->setMetaDescription($cmsPageData['meta_description'])->save(): '';
                isset($cmsPageData['content_heading']) ? $cmsPage->setContentHeading($cmsPageData['content_heading'])->save() : '';
                isset($cmsPageData['content']) ? $cmsPage->setContent($cmsPageData['content'])->save() : '';
                isset($cmsPageData['sort_order']) ? $cmsPage->setSortOrder($cmsPageData['sort_order'])->save() : '';
                isset($cmsPageData['layout_update_xml']) ? $cmsPage->setLayoutUpdateXml($cmsPageData['layout_update_xml'])->save() : '';
                isset($cmsPageData['custom_theme']) ? $cmsPage->setCustomTheme($cmsPageData['custom_theme'])->save() : '';
                isset($cmsPageData['custom_root_template']) ? $cmsPage->setCustomRootTemplate($cmsPageData['custom_root_template'])->save() : '';
                isset($cmsPageData['custom_layout_update_xml']) ? $cmsPage->setCustomLayoutUpdateXml($cmsPageData['custom_layout_update_xml'])->save() : '';
                isset($cmsPageData['custom_theme_from']) ? $cmsPage->setCustomThemeFrom($cmsPageData['custom_theme_from'])->save() : '';
                isset($cmsPageData['custom_theme_to']) ? $cmsPage->setCustomThemeTo($cmsPageData['custom_theme_to'])->save() : '';
            }
        }

    }

    /**
     * Backup present CMS Page
     *
     * @param array $cmsPage
     */
    private function backupCmsPage($cmsPage)
    {

        $cmsPageBackup = [
            'title'                    => $cmsPage->getTitle() . ' — Backup ' . date("m:h d.m.Y"),
            'page_layout'              => $cmsPage->getPageLayout(),
            'meta_keywords'            => $cmsPage->getMetaKeywords(),
            'meta_description'         => $cmsPage->getMetaDescription(),
            'identifier'               => $cmsPage->getIdentifier() . '_' . uniqid(),
            'content_heading'          => $cmsPage->getContentHeading(),
            'content'                  => $cmsPage->getContent(),
            'is_active'                => 0,
            'stores'                   => $cmsPage->getStores(),
            'sort_order'               => $cmsPage->getSortOrder(),
            'layout_update_xml'        => $cmsPage->getLayoutUpdateXml(),
            'custom_theme'             => $cmsPage->getCustomTheme(),
            'custom_root_template'     => $cmsPage->getCustomRootTemplate(),
            'custom_layout_update_xml' => $cmsPage->getCustomLayoutUpdateXml(),
            'custom_theme_from'        => $cmsPage->getCustomThemeFrom(),
            'custom_theme_to'          => $cmsPage->getCustomThemeTo()
        ];

        $this->_pageFactory->create()->setData($cmsPageBackup)->save();
    }

    /**
     * Upgrade data for the module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();


        /**
         * Install or Update CMS Block
         */
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

        /**
         * Install/Update CMS Block via SetupData Module
         */
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

        /**
         * Install or Update Multiple CMS Blocks via SetupData Module
         */
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

        /**
         * Install or Update CMS Page
         */
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

        /**
         * Install or Update CMS Page
         */
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

        /**
         * Install or Update Multiple CMS Pages via SetupData Module
         */
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

        $setup->endSetup();
    }
}
