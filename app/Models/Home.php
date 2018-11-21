<?php

namespace App\Models;

use App\Http\Resources\BannerResource;
use App\Http\Resources\ItemRecommendResource;
use App\Http\Resources\NavigationResource;

class Home
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBanner()
    {
        $banners = Banner::open()->orderBy('order', 'desc')->get();
        return BannerResource::collection($banners);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNavigation()
    {
        $navigations = Navigation::open()->orderBy('order', 'desc')->get();
        return NavigationResource::collection($navigations);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRecommend()
    {
        $recommends = ItemRecommend::orderBy('created_at', 'desc')->limit(10)->get();
        $recommends->load('item.covers');
        return ItemRecommendResource::collection($recommends);
    }
}
