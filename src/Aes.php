<?php
namespace Moon;

/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2017/7/5
 * Time: 10:29
 */
class Aes
{
    /**
     * Only keys of sizes 16, 24 or 32 supported
     * @var string $secret_key
     */
    protected $secret_key;

    /**
     * @var string $cipher
     * One of the MCRYPT_ciphername constants, or the name of the algorithm as string.
     */
    protected $cipher;

    /**
     * @var string $mode
     * One of the MCRYPT_MODE_modename constants, or one of the following strings: "ecb", "cbc", "cfb", "ofb", "nofb" or "stream".
     */
    protected $mode;

    public function __construct($secret_key, $cipher = MCRYPT_RIJNDAEL_128, $mode = MCRYPT_MODE_ECB)
    {
        $this->secret_key = $secret_key;
        $this->cipher = $cipher;
        $this->mode = $mode;
    }

    /**
     * encrypt
     * @param string $str
     * @return string
     */
    public function encrypt($str)
    {
        $secret_key = $this->secret_key;
        $secret_key = base64_decode($secret_key);
        $str = $this->addPKCS7Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        $encrypt_str = mcrypt_encrypt($this->cipher, $secret_key, $str, $this->mode, $iv);
        return base64_encode($encrypt_str);
    }

    /**
     * decrypt
     * @param string $str
     * @return string
     */
    public function decrypt($str)
    {
        $secret_key = $this->secret_key;
        $str = base64_decode($str);
        $secret_key = base64_decode($secret_key);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        $encrypt_str = mcrypt_decrypt($this->cipher, $secret_key, $str, $this->mode, $iv);
        $encrypt_str = $this->stripPKSC7Padding($encrypt_str);
        return $encrypt_str;
    }

    /**
     * padding
     * @param string $source
     * @return string
     */
    protected function addPKCS7Padding($source)
    {
        $source = trim($source);
        $block = mcrypt_get_block_size($this->cipher, $this->mode);
        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }

    /**
     * remove padding
     * @param string $source
     * @return string
     */
    protected function stripPKSC7Padding($source)
    {
        $source = trim($source);
        $char = substr($source, -1);
        $num = ord($char);
        if ($num == 62) return $source;
        $source = substr($source, 0, -$num);
        return $source;
    }
}