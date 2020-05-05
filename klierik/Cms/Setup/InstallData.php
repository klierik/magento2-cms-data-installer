<?php
/**
 * Copyright © github.com/klierik All rights reserved.
 * See LICENSE for license details.
 */

namespace klierik\Cms\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    const DEFAULT_STORE_ID = \Magento\Store\Model\Store::DEFAULT_STORE_ID;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    private $_blockFactory;

    /**
     * InstallData constructor
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory
    )
    {
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // initial
    }
}
