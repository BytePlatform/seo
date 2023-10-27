<?php

namespace BytePlatform\Seo\Tags\TwitterCard;

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\Support\RenderableCollection;
use BytePlatform\Seo\Support\TwitterCardTag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SummaryLargeImage extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): static
    {
        $collection = new static();

        if ( $SEOData->imageMeta ) {
            if ( $SEOData->imageMeta->width < 300 ) {
                return $collection;
            }

            if ( $SEOData->imageMeta->height < 157 ) {
                return $collection;
            }

            if ( $SEOData->imageMeta->width > 4096 ) {
                return $collection;
            }

            if ( $SEOData->imageMeta->height > 4096 ) {
                return $collection;
            }
        }

        $collection->push(new TwitterCardTag('card', 'summary_large_image'));

        if ( $SEOData->image ) {
            $collection->push(new TwitterCardTag('image', $SEOData->image));

            if ( $SEOData->imageMeta ) {
                $collection
                    ->when($SEOData->imageMeta?->width, fn (self $collection): self => $collection->push(new TwitterCardTag('image:width', $SEOData->imageMeta->width)))
                    ->when($SEOData->imageMeta?->height, fn (self $collection): self => $collection->push(new TwitterCardTag('image:height', $SEOData->imageMeta->height)));
            }
        }

        return $collection;
    }
}