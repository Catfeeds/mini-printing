<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 16:48
 * Function:
 */

namespace Tanmo\Wechat\MiniProgram\Auth;


use Tanmo\Wechat\Exceptions\DecryptException;

class DataCrypt
{
    /**
     * @param $appid
     * @param $sessionKey
     * @param $encryptedData
     * @param $iv
     * @return string
     * @throws DecryptException
     */
    public function decrypt($appid, $sessionKey, $encryptedData, $iv)
    {
        if (strlen($sessionKey) != 24) {
            throw new DecryptException('Illegal Aes Key', -41001);
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            throw new DecryptException('Illegal Iv', -41002);
        }

        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            throw new DecryptException('Illegal Buffer',  -41003);
        }

        if ($dataObj->watermark->appid != $appid) {
            throw new DecryptException('Decode Base64 Error', -41004);
        }

        return $result;
    }
}