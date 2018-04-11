<?php
/**
 * Created by PhpStorm.
 * User: glenn
 * Date: 2018/4/11
 * Time: 下午 2:18
 */

namespace AstralWeb\NLP\Model\Config\Backend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\Config\Backend\File;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir\Reader;

class AllowFileType extends File
{

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleDirReader;

    /**
     * AllowFileType constructor.
     * @param Reader $moduleDirReader
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param File\RequestData\RequestDataInterface $requestData
     * @param Filesystem $filesystem
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Reader $moduleDirReader,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        File\RequestData\RequestDataInterface $requestData,
        Filesystem $filesystem,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->moduleDirReader = $moduleDirReader;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $uploaderFactory,
            $requestData,
            $filesystem,
            $resource,
            $resourceCollection,
            $data
        );
    }


    /**
     * @return string
     */
    protected function _getUploadDir()
    {
        $modulePath = $this->moduleDirReader->getModuleDir('', 'AstralWeb_NLP') . '/Library/credentials/';
        return $this->_mediaDirectory->getRelativePath($modulePath);
    }

    /**
     * @return array
     */
    public function _getAllowedExtensions()
    {
        return ['json',];
    }

}