<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class BasicDisplayApi
{

    private $userId;
    private $accesToken;
    private $baseUrl = "https://graph.instagram.com/";

    public $posts = [];

    public function __construct($userId = null, $accesToken = null)
    {
        $this->userId = $userId ?? env('INSTAGRAM_TEST_USER_ID');
        $this->accesToken = $accesToken ?? env('INSTAGRAM_ACCESS_TOKEN');
    }
 
    public function getMediaIds() :array
    {
        $response = Http::get($this->baseUrl.$this->userId, [
            'fields' => 'id,username,media,media_count',
            'access_token' => $this->accesToken,
        ]);

        return $response->json();
    }

    public function getMediasDatas() :array|bool
    {

        $response = $this->getMediaIds();

        if ($this->isTheResponseIsAnError($response)) {
            return $response;
        }

        if ($this->isMediaSet($response)) {
            $this->hydratePosts($response);
        }
        
        return $this->posts;
    }

    private function hydratePosts($response)
    {
        foreach ($response['media']['data'] as $key => $array) {
            $postRaw = Http::get($this->baseUrl.$array['id'], [
                'fields' => 'id,caption,media_type,media_url,username,timestamp,thumbnail_url',
                'access_token' => $this->accesToken,
            ]);

            if ($this->isStatus200($postRaw) && !$this->isTheResponseIsAnError($postRaw)) {
                $post = $postRaw->json();
                array_push($this->posts, $post);
            }
        } 
    }

    private function isTheResponseIsAnError($response)
    {   
        if (isset($response['error'])) {
            return true;
        }

        return false;
    }

    private function isMediaSet($response)
    {
        if (isset($response['media_count']) && $response['media_count'] > 0) {
            return true;
        }

        return false;
    }

    private function isStatus200($response)
    {
        return $response->status() == 200 ? true : false;
    }
}