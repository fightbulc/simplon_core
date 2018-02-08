<?php

namespace Simplon\Core\Events;

use Simplon\Core\Utils\Exceptions\ServerException;

class Events
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
     * @param callable $callback
     *
     * @return Events
     */
    public function addSubscription(string $event, callable $callback): self
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
     * @param callable $callback
     *
     * @return Events
     */
    public function removeSubscription(string $event, callable $callback): self
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
     * @return Events
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
     * @return Events
     */
    public function trigger(string $event, array $params = []): self
    {
        foreach ($this->getSubscriptions($event) as $push)
        {
            call_user_func_array($push, $params);
        }

        return $this;
    }

    /**
     * @param string $event
     * @param callable $callback
     *
     * @return Events
     */
    public function addOffer(string $event, callable $callback): self
    {
        if (!empty($this->pulls[$event]))
        {
            throw (new ServerException())->internalError([
                'reason'     => 'offer event name exists already',
                'event_name' => $event,
            ]);
        }

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