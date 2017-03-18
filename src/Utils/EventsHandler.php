<?php

namespace Simplon\Core\Utils;

/**
 * Class EventsHandler
 * @package Simplon\Core\Utils
 */
class EventsHandler
{
    /**
     * @var array
     */
    protected $subscriptions = [];
    /**
     * @var array
     */
    protected $pulls = [];

    /**
     * @param string $event
     * @param \Closure $callback
     *
     * @return EventsHandler
     */
    public function addSubscription(string $event, \Closure $callback): self
    {
        if (empty($this->subscriptions[$event]))
        {
            $this->subscriptions[$event] = [];
        }

        $this->subscriptions[$event][] = $callback;

        return $this;
    }

    /**
     * @param string $event
     * @param \Closure $callback
     *
     * @return EventsHandler
     */
    public function removeSubscription(string $event, \Closure $callback): self
    {
        if (isset($this->subscriptions[$event]))
        {
            $index = array_search($callback, $this->subscriptions[$event], true);

            if (false !== $index)
            {
                unset($this->subscriptions[$event][$index]);
            }
        }

        return $this;
    }

    /**
     * @param string $event
     *
     * @return EventsHandler
     */
    public function removeAllSubscriptions(string $event = null): self
    {
        if ($event !== null)
        {
            unset($this->subscriptions[$event]);
        }
        else
        {
            $this->subscriptions = [];
        }

        return $this;
    }

    /**
     * @param string $event
     *
     * @return array
     */
    public function getSubscriptions(string $event): array
    {
        return isset($this->subscriptions[$event]) ? $this->subscriptions[$event] : [];
    }

    /**
     * @param string $event
     * @param array $params
     *
     * @return EventsHandler
     */
    public function listen(string $event, array $params = []): self
    {
        foreach ($this->getSubscriptions($event) as $push)
        {
            call_user_func_array($push, $params);
        }

        return $this;
    }

    /**
     * @param string $event
     * @param \Closure $callback
     *
     * @return EventsHandler
     */
    public function addOffer(string $event, \Closure $callback): self
    {
        $this->pulls[$event] = $callback;

        return $this;
    }

    /**
     * @param string $event
     * @param array $params
     *
     * @return mixed|null
     */
    public function request(string $event, array $params = [])
    {
        if (isset($this->pulls[$event]))
        {
            return call_user_func_array($this->pulls[$event], $params);
        }

        return null;
    }
}