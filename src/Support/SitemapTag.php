<?php

namespace BytePlatform\Seo\Support;

class SitemapTag extends LinkTag
{
    public string $rel = 'sitemap';

    public string $type = 'application/xml';

    public string $title = 'Sitemap';

    public function __construct(
        public string $href
    ) {
    }
}