<?php

namespace Qiniu\Jedi;

use Qiniu;

final class JediAuth {

    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey) {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    /*
     * Sign jedi request
     * @param $request Qiniu\Http\Request
     * @param $contentType string 
     * 
     * @return Authorization Token
     */

    public function signRequest($request, $contentType) {
        $requestItems = parse_url($request->url);
        $reqPath = $requestItems['path'];
        $reqHost = $requestItems['host'];
        $reqQuery = $requestItems['query'];
        $reqMethod = $request->method;
        $reqBody = $request->body;

        //step 1, join method & path
        $data = $reqMethod . ' ' . $reqPath;
        //step 2, join query
        if (!empty($reqQuery)) {
            $data.='?' . $reqQuery;
        }
        //step 3, join host
        $data.="\nHost: " . $reqHost;

        //step 4, join content type
        if (!empty($contentType)) {
            $data.="\nContent-Type: " . $contentType;
        }

        //step 5, join line breaks
        $data.="\n\n";

        //step 6, join body if needed
        if (!empty($reqBody) &&
                !empty($contentType) &&
                strtolower($contentType) != 'application/octet-stream') {
            $data.=$reqBody;
        }

        //step 6, calc sign
        $sign = hash_hmac('sha1', $data, $this->secretKey, true);

        $encodedSign = Qiniu\base64_urlSafeEncode($sign);
        $authToken = 'Qiniu ' . $this->accessKey . ':' . $encodedSign;

        return $authToken;
    }

}
