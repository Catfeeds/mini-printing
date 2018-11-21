<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;

class AboutController extends Controller
{
    public function show()
    {
        $about = About::first();
        $about->about_us = str_replace("\r\n","<br>",$about->about_us);
        $about->about_us = str_replace("\n","<br>",$about->about_us);
        $about->about_us = str_replace(" ","&nbsp;",$about->about_us);
        return api()->item($about, AboutResource::class);
    }
}
