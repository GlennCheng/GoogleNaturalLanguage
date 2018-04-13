Google Cloud Natural Language Module 
===============================================================================

This module is created for Magento2, contains Cloud Natural Language API. The `Google Cloud Natural Language API`_ provides natural language understanding technologies to developers, including sentiment analysis, entity recognition, and syntax analysis. This API is part of the larger Cloud Machine Learning API.

.. _Google Cloud Natural Language API: http://cloud.google.com/natural-language

Setup
-------------------------------------------------------------------------------

Authentication
++++++++++++++

This module requires you to have authentication setup,

Please follow these steps to creating a service account

#. Go to the `Create service account key page`_ in the GCP Console.
.. _Create service account key page: https://console.cloud.google.com/apis/credentials/serviceaccountkey

#. From the Service account drop-down list, select New service account.

#. Input a name into the Service account name field.

#. From the Role drop-down list, select Project > Owner.

# Click Create. A JSON file that contains your key downloads to your computer.

Or refer to the
`Authentication Getting Started Guide`_ for instructions on setting up
credentials for applications.

.. _Authentication Getting Started Guide:
    https://cloud.google.com/docs/authentication/getting-started

Finally
#. `Enable the Cloud Natural Language API`_.
.. _Enable the Cloud Natural Language API: https://console.cloud.google.com/flows/enableapi?apiid=language.googleapis.com


Setup application credential
++++++++++++++
#. Go to **Magendo2 admin page**>**STORES**>**Configuration**>**GOOGLE NATURAL LANGUAGE API**
#. Upload your downloaded JSON file. 


Dependencies
++++++++++++++++++++

This module include the minimum python environment and dependencies of this module.


Use
-------------------------------------------------------------------------------
Use the helper form AstralWeb\NLP\Helper
.. code-block:: php
    
    use AstralWeb\NLP\Helper\AnalyzeNLP;
    
In your Class
.. code-block:: php
    
    protected $helperAnalyze;
    
    public function __construct(
        AnalyzeNLP $helperAnalyze,
    ) {
        $this->helperAnalyze = $helperAnalyze;
    }
    
    yourfuunction()
    {
        //...your code
        $result = $this->helperAnalyze->analyze($type, $text);
    }


Example and Test
-------------------------------------------------------------------------------

You can do testing and get results dump in 
YourHost/nlp/Language/Index?type=[TYPE]&text=[TEXT]

eg.
YourHost/nlp/Language/Index?type=sentiment&text=AstralWeb is awesome!! (this module is create by Glenn)
.. code-block:: json

    {
      "documentSentiment": {
        "magnitude": 1.1,
        "score": 0.5
      },
      "language": "en",
      "sentences": [
        {
          "text": {
            "content": "AstralWeb is awesome!!",
            "beginOffset": 0
          },
          "sentiment": {
            "magnitude": 0.9,
            "score": 0.9
          }
        },
        {
          "text": {
            "content": "(this module is create by Glenn)",
            "beginOffset": 23
          },
          "sentiment": {
            "magnitude": 0.2,
            "score": 0.2
          }
        }
      ]
    }
    
    
    