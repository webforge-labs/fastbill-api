<?php

namespace FastBill\Api;

use FastBill\Model\Article;
use FastBill\Model\Subscription;
use Guzzle\HTTP\Client as GuzzleClient;

class FastBillAutomaticClient extends AbstractFastBillClient
{
    public function __construct(GuzzleClient $guzzleClient, Array $options)
    {
        $guzzleClient->setBaseUrl("https://automatic.fastbill.com/");
        parent::__construct($guzzleClient, $options);
    }

    /**
     * @return FastBill\Model\Subscription
     */
    public function createSubscription(Subscription $subscription)
    {
        $requestBody = array(
            'SERVICE' => 'subscription.create',
            'DATA' => $subscription->serializeJSONXML()
        );

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'STATUS is not equal to success';

                return isset($response->STATUS) && $response->STATUS === 'success';
            }
        );

        $subscription->setSubscriptionId($jsonResponse->RESPONSE->SUBSCRIPTION_ID);

        return $subscription;
    }

    /**
     * @return FastBill\Model\Subscription
     */
    public function updateSubscription(Subscription $subscription)
    {
        $requestBody = array(
            'SERVICE' => 'subscription.update',
            'DATA' => $subscription->serializeJSONXML()
        );

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'STATUS is not equal to success';

                return isset($response->STATUS) && $response->STATUS === 'success';
            }
        );

        return $subscription;
    }

    /**
     * @return FastBill\Model\Subscription
     */
    public function cancelSubscription(Subscription $subscription)
    {
        $requestBody = array(
            'SERVICE' => 'subscription.cancel',
            'DATA' => $subscription->serializeJSONXML()
        );

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'STATUS is not equal to success';

                return isset($response->STATUS) && $response->STATUS === 'success';
            }
        );

        $subscription->setCancellationDate($jsonResponse->RESPONSE->CANCELLATION_DATE);

        return $subscription;
    }

    /**
     * @return FastBill\Model\Subscription
     */
    public function reactivateSubscription(Subscription $subscription)
    {
        $requestBody = array(
            'SERVICE' => 'subscription.reactivate',
            'DATA' => $subscription->serializeJSONXML()
        );

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'STATUS is not equal to success';

                return isset($response->STATUS) && $response->STATUS === 'success';
            }
        );

        return $subscription;
    }

    public function getSubscriptions(Array $filters = array())
    {
        $requestBody = (object) array(
            'SERVICE' => 'subscription.get'
        );

        $this->filtersToXml($filters, $requestBody);

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'key SUBSCRIPTIONS is not set';

                return isset($response->SUBSCRIPTIONS);
            }
        );

        $subscriptions = array();
        foreach ($jsonResponse->RESPONSE->SUBSCRIPTIONS as $xmlSubscription) {
            $subscriptions[] = Subscription::fromObject($xmlSubscription);
        }

        return $subscriptions;
    }

    public function getArticles(Array $filters = array())
    {
        $requestBody = (object) array(
            'SERVICE' => 'article.get'
        );

        $this->filtersToXml($filters, $requestBody);

        $jsonResponse = $this->validateResponse(
            $this->dispatchRequest(
                $this->createRequest('POST', '/', $requestBody)
            ),
            function ($response, &$msg) {
                $msg = 'key ARTICLES is not set';

                return isset($response->ARTICLES);
            }
        );

        $articles = array();
        foreach ($jsonResponse->RESPONSE->ARTICLES as $xmlSubscription) {
            $articles[] = Article::fromObject($xmlSubscription);
        }

        return $articles;
    }
}
