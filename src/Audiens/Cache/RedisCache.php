<?php

namespace Audiens\AdForm\Cache;

use Audiens\AdForm\Exception\RedisException;
use Predis;

class RedisCache extends BaseCache implements CacheInterface
{
    /**
     * @var Predis\Client
     */
    private $client;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param        $config
     * @param int    $ttl
     * @param string $prefix
     *
     * @throws RedisException
     */
    public function __construct($config, $ttl = 3600, $prefix = "adform_")
    {
        try {
            $this->client = new Predis\Client($config);
        } catch (Predis\Response\ServerException $e) {
            throw RedisException::connect($e->getMessage());
        }

        $this->ttl = $ttl;

        $this->prefix = $prefix;
    }

    /**
     * @param string $uri
     * @param string $query
     * @param string $data
     *
     * @return bool
     */
    public function put($uri, $query, $data)
    {
        $hash = $this->getHash($uri, $query);

        return (bool) $this->client->set($hash, json_encode($data), "ex", $this->ttl);
    }

    /**
     * @param string $uri
     * @param string $query
     *
     * @return mixed
     */
    public function get($uri, $query)
    {
        $hash = $this->getHash($uri, $query);

        $data = json_decode($this->client->get($hash));

        if (!empty($data)) {
            return $data;
        }

        return false;
    }

    /**
     * @param $uri
     * @param $query
     *
     * @return bool
     */
    public function delete($uri, $query)
    {
        $hash = $this->getHash($uri, $query);

        return (bool) $this->client->del($hash);
    }
}
