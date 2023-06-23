<?php
namespace BMLConnect\Model;

class Transaction
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $isPreAuthorization;

    /**
     * @var
     */
    private $provider;

    /**
     * @var
     */
    private $redirectUrl;

    /**
     * @var
     */
    private $localId;

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getisPreAuthorization(): string
    {
        return $this->isPreAuthorization;
    }

    /**
     * @param string $isPreAuthorization
     */
    public function setIsPreAuthorization(string $isPreAuthorization)
    {
        $this->isPreAuthorization = $isPreAuthorization;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getLocalId()
    {
        return $this->localId;
    }

    /**
     * @param mixed $localId
     */
    public function setLocalId($localId)
    {
        $this->localId = $localId;
    }

    /**
     * @param array $json
     * @return $this
     */
    public function fromArray(array $json)
    {
        if (array_key_exists('amount', $json) && array_key_exists('currency', $json)) {
            $this->amount = $json['amount'];
            $this->currency = $json['currency'];
        } else {
            throw new \InvalidArgumentException('amount and currency are required to sign a transaction');
        }

        if (array_key_exists('isPreAuthorization', $json)) {
            $this->isPreAuthorization = $json['isPreAuthorization'];
        }

        if (array_key_exists('redirectUrl', $json)) {
            $this->redirectUrl = $json['redirectUrl'];
        }

        if (array_key_exists('provider', $json)) {
            $this->provider = $json['provider'];
        }

        if (array_key_exists('localId', $json)) {
            $this->localId = $json['localId'];
        }

        return $this;

    }
}
