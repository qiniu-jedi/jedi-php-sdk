<?php
namespace Qiniu\Jedi;

use Qiniu\Http\Client;
use Qiniu\Http\Request;
use Qiniu\Storage\UploadManager;

final class JediManager {

    const JEDI_API_SERVER = 'http://jedi.qiniuapi.com';

    private $jediAuth;
    private $httpClient;
    private $uploadManager;

    public function __construct($jediAuth) {
        $this->jediAuth = $jediAuth;
        $this->httpClient = new Client();
        $this->uploadManager = new UploadManager();
    }

    /*
     * Get jedi uptoken
     * 
     * @param $hub - jedi hub name
     * @param $type - type, default video
     * @param $expire - token expire seconds, default 6 hours, 
     * can be maximually 24 hours
     *
     * @return (uptoken, response) 
     */

    public final function getUpToken($hub, $type = null, $expire = null) {
        $postBody = array();
        if (!empty($hub)) {
            $postBody['hub'] = $hub;
        }
        if (!(empty($type))) {
            $postBody['type'] = $type;
        }
        if (!(empty($expire))) {
            $postBody['deadline'] = time() + $expire;
        }
        //prepare request
        $postUrl = JediManager::JEDI_API_SERVER . '/v1/uptokens';
        $postBodyBytes = json_encode($postBody);
        $postHeaders = array('Content-Type' => 'application/json');
        $request = new Request('POST', $postUrl, $postHeaders, $postBodyBytes);
        $authToken = $this->jediAuth->signRequest($request, 'application/json');
        $request->headers['Authorization'] = $authToken;

        //fire request
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $respBody = $response->json();
            $uptoken = $respBody['uptoken'];
        }
        return array("uptoken" => $uptoken, "response" => $response);
    }

    public function uploadVideoData($upToken, $videoKey, $videoData) {
        return $this->uploadManager->put($upToken, $videoKey, $videoData);
    }

    public function uploadVideoFile($upToken, $videoKey, $videoFilePath) {
        return $this->uploadManager->putFile($upToken, $videoKey, $videoFilePath);
    }

    /*
     * Get Video Info
     * 
     * @param $hub
     * @param $videoKey
     *
     * @return (videoInfo, response)
     */

    public function getVideoInfo($hub, $videoKey) {
        $encodedKey = \Qiniu\base64_urlSafeEncode($videoKey);
        $getUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' . $hub
                . '/videos/' . $encodedKey;
        $getHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');
        $request = new Request('GET', $getUrl, $getHeaders, null);
        $authToken = $this->jediAuth->signRequest($request, 'application/x-www-form-urlencoded');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $videoInfo = $response->json();
        }

        return array("videoInfo" => $videoInfo, "response" => $response);
    }

    /*
     * List video info
     * 
     * @param $hub
     * @param $cursor
     * @param $count
     * 
     * @return (videoList, response)
     */

    public function getVideoList($hub, $count = null, $cursor = null) {
        $getUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' . $hub . '/videos';
        if (!empty($cursor)) {
            $getUrl.='?cursor=' . $cursor;
        }
        if (!empty($count)) {
            if (strpos($getUrl, '?') === FALSE) {
                $getUrl.='?count=' . $count;
            } else {
                $getUrl.='&count=' . $count;
            }
        }
        $getHeaders = array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        );
        $request = new Request('GET', $getUrl, $getHeaders, null);
        $authToken = $this->jediAuth->signRequest($request, 'application/x-www-form-urlencoded');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $videoList = $response->json();
        }

        return array("videoList" => $videoList, "response" => $response);
    }

    /*
     * Update video info
     * 
     * @param $hub
     * @param $videoKey
     * @param $videoName
     * @param $videoTags
     * @param $videoDesc
     * 
     * @return (result, response)
     */

    public function updateVideoInfo($hub, $videoKey, $videoName = null, $videoTags = array(), $videoDesc = null) {
        $encodedKey = \Qiniu\base64_urlSafeEncode($videoKey);
        $putUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' .
                $hub . '/videos/' . $encodedKey;
        $putBody = array();
        $putBody['name'] = $videoName;
        $putBody['tags'] = $videoTags;
        $putBody['description'] = $videoDesc;

        $putBodyBytes = json_encode($putBody);
        $putHeaders = array(
            'Content-Type' => 'application/json'
        );
        $request = new Request('PUT', $putUrl, $putHeaders, $putBodyBytes);
        $authToken = $this->jediAuth->signRequest($request, 'application/json');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $opResult = TRUE;
        }
        return array("result" => $opResult, "response" => $response);
    }

    /*
     * Delete video
     * 
     * @param $hub
     * @param $videoKey
     *
     * @return (opResult, response)
     */

    public function deleteVideo($hub, $videoKey) {
        $encodedKey = \Qiniu\base64_urlSafeEncode($videoKey);
        $deleteUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' . $hub
                . '/videos/' . $encodedKey;
        $deleteHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');
        $request = new Request('DELETE', $deleteUrl, $deleteHeaders, null);
        $authToken = $this->jediAuth->signRequest($request, 'application/x-www-form-urlencoded');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $opResult = TRUE;
        }

        return array("result" => $opResult, "response" => $response);
    }

    /*
     * Batch delete video
     * 
     * @param $hub
     * @param $videoKeys
     *
     * @return (opResult, response)
     */

    public function batchDeleteVideos($hub, $videoKeys = array()) {
        $deleteUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' . $hub . '/videos';
        $deleteHeaders = array('Content-Type' => 'application/json');
        $encodedVideoKeys = array();
        foreach ($videoKeys as $videoKey) {
            $encodedVideoKey = \Qiniu\base64_urlSafeEncode($videoKey);
            array_push($encodedVideoKeys, $encodedVideoKey);
        }
        $deleteBody = array("keys" => $encodedVideoKeys);
        $deleteBodyBytes = json_encode($deleteBody);
        $request = new Request('DELETE', $deleteUrl, $deleteHeaders, $deleteBodyBytes);
        $authToken = $this->jediAuth->signRequest($request, 'application/json');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $opResult = TRUE;
        }

        return array("result" => $opResult, "response" => $response);
    }

    /*
     * Set the video cover image
     * 
     * @param $hub
     * @param $videoKey
     * @param $activeIndex
     *      
     * @return (opResult, response)
     */
    public function setVideoImage($hub, $videoKey, $activeIndex) {
        $encodedKey = \Qiniu\base64_urlSafeEncode($videoKey);
        $putUrl = JediManager::JEDI_API_SERVER . '/v1/hubs/' .
                $hub . '/videos/' . $encodedKey .
                '/thumbnails/active/' . $activeIndex;
        $putHeaders = array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        );
        $request = new Request('PUT', $putUrl, $putHeaders, null);
        $authToken = $this->jediAuth->signRequest($request, 'application/x-www-form-urlencoded');
        $request->headers['Authorization'] = $authToken;
        $response = $this->httpClient->sendRequest($request);
        if ($response->ok()) {
            $opResult = TRUE;
        }
        return array("result" => $opResult, "response" => $response);
    }

}
