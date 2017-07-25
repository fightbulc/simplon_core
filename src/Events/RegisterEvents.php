<?php

namespace Simplon\Core\Events;

use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\Exceptions\ServerException;
use Simplon\Helper\Data\InstanceData;
use Simplon\Helper\Instances;

/**
 * @package Simplon\Core\Events
 */
class RegisterEvents
{
    /**
     * @param RegistryInterface[] $components
     *
     * @throws ServerException
     */
    public function __construct(array $components)
    {
        foreach ($components as $component)
        {
            $this->register($component);
        }
    }

    /**
     * @param RegistryInterface $register
     *
     * @throws ServerException
     */
    private function register(RegistryInterface $register): void
    {
        if ($register->getEvents())
        {
            // add subscriptions

            if (empty($register->getEvents()->getSubscriptions()) === false)
            {
                foreach ($register->getEvents()->getSubscriptions() as $event => $callback)
                {
                    $this->getEvents()->addSubscription($event, $callback);
                }
            }

            // add offers

            if (empty($register->getEvents()->getOffers()) === false)
            {
                foreach ($register->getEvents()->getOffers() as $event => $callback)
                {
                    $this->getEvents()->addOffer($event, $callback);
                }
            }
        }
    }

    /**
     * @return Events
     */
    private function getEvents(): Events
    {
        return Instances::cache(
            InstanceData::create(Events::class)
        );
    }
}