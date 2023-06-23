<?php
namespace BMLConnect\Crypt;

use BMLConnect\Model\Transaction;

class Signature
{
    /**
     * @var Transaction
     */
    private $transaction;


    /**
     * @var string
     */
    private $apiKey;

    /**
     * Signature constructor.
     * @param Transaction $transaction
     * @param string $apiKey
     */
    public function __construct(Transaction $transaction, $apiKey)
    {
        $this->transaction = $transaction;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function sign()
    {
        $str = 'amount='.$this->transaction->getAmount().
            '&currency='.$this->transaction->getCurrency().
            '&apiKey='.$this->apiKey;

        return sha1($str);
    }
}
