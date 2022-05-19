<?php

namespace App\Controllers\APIControllers\Admin;

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
        $news = $this->News->orderBy('id', 'desc')->findAll(20);
        
        foreach($news as $index => $item){
            $news[$index]['cover_image'] = base_url($item['cover_image']);
        }

        $result = responseJson(200, false, 'news list', $news);
        return $this->respond($result);
    }
}