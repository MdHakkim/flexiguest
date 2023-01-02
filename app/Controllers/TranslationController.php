<?php

namespace App\Controllers;

use App\Libraries\CurlRequestLibrary;
use CodeIgniter\API\ResponseTrait;

class TranslationController extends BaseController
{

    use ResponseTrait;

    private $CurlRequestLibrary;

    public function __construct()
    {
        $this->CurlRequestLibrary = new CurlRequestLibrary();
    }

    public function translateWords($words, $target)
    {
        $word_keys = array_values($words);

        $data['method'] = 'POST';
        $data['url'] = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAH6IGPjtfVk-9UaWowS5NQRHBUsEo9I-s';
        $data['body'] = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'q' => $word_keys,
                'source' => 'en',
                'target' => $target,
                'format' => 'text'
            ]
        ];

        $response = $this->CurlRequestLibrary->makeRequest($data);
        $response =  json_decode(json_encode($response), true);
        $translated_words = $response['data']['translations'];

        $i = 0;
        foreach ($words as $index => $word) {
            if (!empty($translated_words[$i]))
                $words[$index] = $translated_words[$i++]['translatedText'];
        }

        return $words;
    }

    public function translate()
    {
        $target = $this->request->getGet('target') ?? 'en';
        $words = json_decode(json_encode($this->request->getVar()), true);

        $file_name = "assets/language/translations/$target.json";

        if (file_exists($file_name)) {
            $json = file_get_contents($file_name);
            $translated_words = json_decode($json, true);

            $new_words = [];
            foreach ($words as $i => $word) {

                $present_flag = false;
                foreach ($translated_words as $j => $translated_word) {
                    if ($i == $j) {
                        $present_flag = true;
                        break;
                    }
                }

                if (!$present_flag)
                    $new_words[$i] = $word;
            }

            if (!empty($new_words)) {
                $translated_words = array_merge($translated_words, $this->translateWords($new_words, $target));
                file_put_contents($file_name, json_encode($translated_words));
            }
        } else {
            $translated_words = $this->translateWords($words, $target);
            file_put_contents($file_name, json_encode($translated_words));
        }

        return $this->respond(responseJson(200, false, ['msg' => 'translated file.'], json_encode($translated_words)));
    }
}
