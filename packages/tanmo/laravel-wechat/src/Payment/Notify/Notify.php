<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/24
 * Time: 16:20
 * Function:
 */

namespace Tanmo\Wechat\Payment\Notify;


use Tanmo\Wechat\Exceptions\InvalidSignException;
use Tanmo\Wechat\Payment\Contracts\ConfigContract;
use Tanmo\Wechat\Support\AES;
use Tanmo\Wechat\Support\XML;

abstract class Notify
{
    /**
     * return code
     */
    const SUCCESS = 'SUCCESS';
    const FAIL = 'FAIL';

    /**
     * @var string|null
     */
    protected $fail;

    /**
     * @var array
     */
    protected $message;

    /**
     * @var ConfigContract
     */
    protected $config;

    /**
     * Check sign.
     * If failed, throws an exception.
     *
     * @var bool
     */
    protected $check = false;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Respond with sign.
     *
     * @var bool
     */
    protected $sign = false;

    /**
     * Notify constructor.
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    abstract public function handle(\Closure $closure);

    /**
     * @return array
     * @throws \Exception
     */
    public function getMessage()
    {
        if (!empty($this->message)) {
            return $this->message;
        }

        try {
            $message = XML::parse(strval(request()->getContent()));
        } catch (\Throwable $e) {
            throw new \Exception('Invalid request XML: '.$e->getMessage(), 400);
        }

        if (!is_array($message) || empty($message)) {
            throw new \Exception('Invalid request XML.', 400);
        }

        if ($this->check) {
            $this->validate($message);
        }

        return $this->message = $message;
    }

    /**
     * Decrypt message.
     *
     * @param string $key
     *
     * @return string|null
     *
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function decryptMessage(string $key)
    {
        $message = $this->getMessage();
        if (empty($message[$key])) {
            return null;
        }

        return AES::decrypt(
            base64_decode($message[$key], true), md5($this->config->getKey()), '', OPENSSL_RAW_DATA, 'AES-256-ECB'
        );
    }

    /**
     * @param string $message
     */
    public function fail(string $message)
    {
        $this->fail = $message;
    }

    /**
     * @param array $attributes
     * @param bool  $sign
     *
     * @return $this
     */
    public function respondWith(array $attributes, bool $sign = false)
    {
        $this->attributes = $attributes;
        $this->sign = $sign;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse()
    {
        $base = [
            'return_code' => is_null($this->fail) ? static::SUCCESS : static::FAIL,
            'return_msg' => is_null($this->fail) ? 'OK' : $this->fail
        ];

        $attributes = array_merge($base, $this->attributes);

        if ($this->sign) {
            $attributes['sign'] = gen_sign($attributes, $this->config->getKey());
        }

        return response(XML::build($attributes));
    }

    /**
     * @param array $message
     * @throws InvalidSignException
     */
    protected function validate(array $message)
    {
        $sign = $message['sign'];
        unset($message['sign']);

        if (gen_sign($message, $this->config->getKey()) !== $sign) {
            throw new InvalidSignException();
        }
    }

    /**
     * @param mixed $result
     */
    protected function strict($result)
    {
        if (true !== $result && is_null($this->fail)) {
            $this->fail(strval($result));
        }
    }
}