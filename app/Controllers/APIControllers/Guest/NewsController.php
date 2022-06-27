<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\News;
use CodeIgniter\API\ResponseTrait;

class NewsController extends BaseController
{
    use ResponseTrait;

    private $News;

    public function __construct()
    {
        $this->News = new News();
    }

    public function news()
    {
        $news = $this->News->orderBy('NS_ID', 'desc')->findAll();
        
        foreach($news as $index => $item) {
            $news[$index]['NS_COVER_IMAGE'] = base_url($item['NS_COVER_IMAGE']);
        }

        $result = responseJson(200, false, ['msg' => 'news list'], $news);
        return $this->respond($result);
    }
}