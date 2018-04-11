import argparse

from google.cloud import language
import google.cloud
import six


def sentiment_text(text):
    """Detects sentiment in the text."""
    language_client = language.LanguageServiceClient()

    if isinstance(text, six.binary_type):
        text = text.decode('utf-8')

        # Instantiates a plain text document.
        document = language_client.document_from_text(text)

        # Detects sentiment in the document. You can also analyze HTML with:
        #   document.doc_type == language.Document.HTML
        sentiment = document.analyze_sentiment().sentiment

        print('Score: {}'.format(sentiment.score))
        print('Magnitude: {}'.format(sentiment.magnitude))


x = input('type something:')
sentiment_text(x)

print('done')