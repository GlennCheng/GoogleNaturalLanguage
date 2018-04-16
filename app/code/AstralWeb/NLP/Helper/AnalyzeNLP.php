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
namespace AstralWeb\NLP\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Symfony\Component\Console\Application;
use Google\Cloud\Core\ClientTrait;
use Google\Cloud\Core\RetryDeciderTrait;

# Includes the autoloader for libraries installed with composer
class AnalyzeNLP extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $scopeConfig;
    protected $modulePath;
    protected $envPythonPath;
    protected $credentialJsonPath;
    protected $analyzeLibraryPath;

    /**
     * AnalyzeNLP constructor.
     */
    public function __construct()
    {
        //$jsonFile = $this->scopeConfig->getValue('google_nlp/nlp_config/nlp_credentials_file', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $object = ObjectManager::getInstance();
        $scopeConfig = $object->create(ScopeConfigInterface::class);

        $jsonFile = $scopeConfig->getValue('google_nlp/nlp_config/nlp_credentials_file', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->modulePath = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Module\Dir\Reader::class)
                ->getModuleDir('', 'AstralWeb_NLP');
        $this->envPythonPath = $this->modulePath.'/Library/env_NLP/bin/python3';
        $this->credentialJsonPath = $this->modulePath.'/Library/credentials/'.$jsonFile;
        $this->analyzeLibraryPath = $this->modulePath.'/Library/analyze.py';
    }


    /**
     * @param $type
     * @param $text
     * @param null $projectId
     * @return bool|mixed
     * @throws \Exception
     */
    public function analyze($type, $text, $projectId = null)
    {

        # Instantiates a client
        $language = new LanguageClient([
            'projectId' => $projectId,
            'keyFilePath' => $this->credentialJsonPath,
        ]);

        if ($text == null) {
            throw new \Exception(
                __('text is null.')
            );
            return false;
        }
        if ($type=='entities') {
            $result = $this->analyzeEntities($language, $text);
        } elseif ($type=='sentiment') {
            $result = $this->analyzeSentiment($language, $text);
        } elseif ($type=='syntax') {
            $result = $this->analyzeSyntax($language, $text);
        } else {
            throw new \Exception(
                __('NLP Analyze type is not exist.')
            );
            return false;
        }

        return $result;
    }


    /**
     * @param $language
     * @param $text
     * @param null $projectId
     * @return mixed
     */
    public function analyzeSentiment($language, $text)
    {
        // Call the analyzeSentiment function
        $annotation = $language->analyzeSentiment($text, ['language'=>'en']);
        // Print document and sentence sentiment information
        $result = $annotation->info();

        return $result;
    }


    /**
     * @param $language
     * @param $text
     * @param null $projectId
     * @return mixed
     */
    public function analyzeEntities($language, $text)
    {

        // Call the analyzeSentiment function
        $annotation = $language->analyzeEntities($text, ['language'=>'en']);
        // Print document and sentence sentiment information
        $result = $annotation->info();

        return $result;
    }


    /**
     * @param $language
     * @param $text
     * @param null $projectId
     * @return mixed
     */
    public function analyzeSyntax($language, $text)
    {

        // Call the analyzeSentiment function
        $annotation = $language->analyzeSyntax($text, ['language'=>'en']);
        // Print document and sentence sentiment information
        $result = $annotation->info();

        return $result;
    }
}
