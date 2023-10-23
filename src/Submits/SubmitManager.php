<?php

namespace BytePlatform\Seo\Submits;

class SubmitManager
{

    public function index(string $engine, string $apiKey, array $urls, $host): bool
    {
        $ch = curl_init("https://{$engine}/indexnow");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['host' => $host, 'key' => $apiKey, 'urlList' => $urls]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['content-type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $code >= 200 && $code < 300;
    }
    /**
     * Engines to inform
     * @var array
     */
    protected $engines =
    [
        'https://www.google.com',
        'https://www.bing.com',
        'https://webmaster.yandex.com'
    ];

    /**
     * Initialize new sitemap bing
     *
     * @param array $append
     */
    public function __construct(array $append = [])
    {
        if (empty($append) === false) {
            $this->engines = array_unique(array_merge($this->engines, $append));
        }
    }

    /**
     * Send sitemap url to registred engines
     *
     * @param  string $sitemapUrl
     * @return void
     */
    public function send(string $sitemapUrl): void
    {
        foreach ($this->engines as $engine) {
            $this->inform($engine, $sitemapUrl);
        }
    }

    /**
     * Inform search engine
     *
     * @param  string $engine
     * @param  string $url
     * @return void
     */
    public function inform(string $engine, string $url): void
    {
        $req = curl_init("{$engine}/ping?sitemap={$url}");
        curl_setopt($req, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($req);
        curl_close($req);
    }
}
