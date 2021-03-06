<?php

namespace Audiens\AdForm;

use Audiens\AdForm\Cache\CacheInterface;
use Audiens\AdForm\Provider\CategoryProvider;
use Audiens\AdForm\Provider\SegmentProvider;
use Audiens\AdForm\Provider\DataUsageProvider;
use Audiens\AdForm\Provider\DataProviderAudienceProvider;

/**
 * Class Adform
 *
 * @package Adform
 */
class Client
{
    /**
     * Version number of the Adform PHP SDK.
     *
     * @const string
     */
    const VERSION = '0.2.0';

    /**
     * URL for the AdForm API.
     *
     * @const string
     */
    const BASE_URL = 'https://dmp-api.adform.com';

    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var CategoryProvider
     */
    protected $categories;

    /**
     * @var SegmentProvider
     */
    protected $segments;

    /**
     * @var DataUsageProvider
     */
    protected $dataUsage;

    /**
     * @var DPAudienceProvider
     */
    protected $dataProviderAudience;

    /**
     * Constructor.
     *
     * @param string $username
     * @param string $password
     * @param CacheInterface $cache
     */
    public function __construct($username, $password, CacheInterface $cache = null)
    {
        $this->auth = new Authentication($username, $password);

        $this->httpClient = new HttpClient($this->auth);

        $this->cache = $cache;
    }

    /**
     * A proxy method for working with categories
     *
     * @return CategoryProvider
     */
    public function categories()
    {
        if (is_null($this->categories)) {
            $this->categories = new CategoryProvider($this->httpClient, $this->cache);
        }

        return $this->categories;
    }

    /**
     * A proxy method for working with segments
     *
     * @return SegmentProvider
     */
    public function segments()
    {
        if (is_null($this->segments)) {
            $this->segments = new SegmentProvider($this->httpClient, $this->cache);
        }

        return $this->segments;
    }

    /**
     * A proxy method for working with data usage reports
     *
     * @return DataUsageProvider
     */
    public function dataUsage()
    {
        if (is_null($this->dataUsage)) {
            $this->dataUsage = new DataUsageProvider($this->httpClient, $this->cache);
        }

        return $this->dataUsage;
    }

    /**
     * A proxy method for working with data provider audience reports
     *
     * @return DPAudienceProvider
     */
    public function dataProviderAudience()
    {
        if (is_null($this->dataProviderAudience)) {
            $this->dataProviderAudience = new DataProviderAudienceProvider($this->httpClient, $this->cache);
        }

        return $this->dataProviderAudience;
    }
}
