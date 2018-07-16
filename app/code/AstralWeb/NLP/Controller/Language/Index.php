<?php
/**
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace AstralWeb\NLP\Controller\Language;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Symfony\Component\Console\Application;
use Google\Cloud\Core\ClientTrait;
use Google\Cloud\Core\RetryDeciderTrait;
use AstralWeb\NLP\Helper\AnalyzeNLP;

# Includes the autoloader for libraries installed with composer
class Index extends Action
{



    protected $resultPageFactory;

    protected $helperAnalyze;


    /**
     * Index constructor.
     * @param AnalyzeNLP $helperAnalyze
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param array $config
     */
    public function __construct(
        AnalyzeNLP $helperAnalyze,
        Context $context,
        PageFactory $resultPageFactory,
        array $config = []
    ) {

        $this->helperAnalyze = $helperAnalyze;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Exception
     */
    public function execute()
    {

        $type = $this->getRequest()->getParam('type');
        var_dump($type);

        $text = $this->getRequest()->getParam('text');

        var_dump($text);
        $result = $this->helperAnalyze->analyze($type, $text);
        var_dump(($result));


        //return $this->resultPageFactory->create();
    }
}
