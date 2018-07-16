<?php
/**
 * Created by PhpStorm.
 * User: glenn
 * Date: 2018/4/13
 * Time: 下午 3:01
 */
namespace AstralWeb\NLP\Controller\Review;

use AstralWeb\NLP\Helper\AnalyzeNLP;
use Magento\Framework\Controller\ResultFactory;
use Magento\Review\Model\Review;

class Post extends \Magento\Review\Controller\Product\Post
{

    protected $analyzeHelper;

    /**
     * Post constructor.
     * @param AnalyzeNLP $analyzeHelper
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Review\Model\RatingFactory $ratingFactory
     * @param \Magento\Catalog\Model\Design $catalogDesign
     * @param \Magento\Framework\Session\Generic $reviewSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        AnalyzeNLP $analyzeHelper,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Framework\Session\Generic $reviewSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->analyzeHelper = $analyzeHelper;
        parent::__construct(
            $context,
            $coreRegistry,
            $customerSession,
            $categoryRepository,
            $logger,
            $productRepository,
            $reviewFactory,
            $ratingFactory,
            $catalogDesign,
            $reviewSession,
            $storeManager,
            $formKeyValidator
        );
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $data = $this->reviewSession->getFormData(true);

        if ($data) {
            $rating = [];
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPostValue();
            $rating = $this->getRequest()->getParam('ratings', []);
        }

        if (($product = $this->initProduct()) && !empty($data)) {
            /** @var \Magento\Review\Model\Review $review */
            $review = $this->reviewFactory->create()->setData($data);
            $review->unsetData('review_id');

            $validate = $review->validate();
            if ($validate === true) {
                $analyze = $this->analyzeHelper->analyze('sentiment', $data['detail']);
                if ($analyze['documentSentiment']['score']<0) {
                    $this->messageManager->addError(__('平均情緒指數:'.$analyze['documentSentiment']['score'].' 你的評論包含太多負面情緒'));
                    foreach ($analyze['sentences'] as $k => $v){
                        $this->messageManager
                            ->addNoticeMessage('語句'.$k.': [ '.$v['text']['content'].' ], 情緒指數: '.$v['sentiment']['score']);
                    }
                } else {
                    try {
                        $review->setEntityId($review->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($product->getId())
                            ->setStatusId(Review::STATUS_PENDING)
                            ->setCustomerId($this->customerSession->getCustomerId())
                            ->setStoreId($this->storeManager->getStore()->getId())
                            ->setStores([$this->storeManager->getStore()->getId()])
                            ->save();

                        foreach ($rating as $ratingId => $optionId) {
                            $this->ratingFactory->create()
                                ->setRatingId($ratingId)
                                ->setReviewId($review->getId())
                                ->setCustomerId($this->customerSession->getCustomerId())
                                ->addOptionVote($optionId, $product->getId());
                        }

                        $review->aggregate();
                        $this->messageManager->addSuccess(__('You submitted your review for moderation.'));
                    } catch (\Exception $e) {
                        $this->reviewSession->setFormData($data);
                        $this->messageManager->addError(__('We can\'t post your review right now.'));
                    }
                    $this->messageManager->addSuccess(__('平均情緒指數:'.$analyze['documentSentiment']['score']));
                    foreach ($analyze['sentences'] as $k => $v){
                        $this->messageManager
                            ->addNoticeMessage('語句'.$k.': [ '.$v['text']['content'].' ], 情緒指數: '.$v['sentiment']['score']);
                    }
                }
            } else {
                $this->reviewSession->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                } else {
                    $this->messageManager->addError(__('We can\'t post your review right now.'));
                }
            }
        }
        $redirectUrl = $this->reviewSession->getRedirectUrl(true);
        $resultRedirect->setUrl($redirectUrl ?: $this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }

}