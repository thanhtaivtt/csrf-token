<?php
/**
 * Created by PhpStorm.
 * User: thanhtai
 * Date: 08/06/2018
 * Time: 17:43
 */

class CSRF
{
    private $token = '';
    /**
     * config secret key
     *
     * @var string
     */
    private $secret = 'taindvn12344';

    /**
     * config listString for genrerate
     *
     * List string
     * @var string
     */
    private $listString = 'abcdefghiklmnopqrtvABCRTDKGFDL0123456789';

    /**
     * config session, cookie name
     *
     * @var string
     */
    private $secureName = 'nghinv';

    /**
     * set driver tracking (Recommendation use SESSION)
     * @var string SESSION|COOKIE;
     */
    private $drive = 'COOKIE';

    /**
     * setup auto genrerate token
     *
     * @var bool
     */
    private $autoGenarate = false;

    /**
     * unit seconds
     *
     * @var int
     */
    private $durationExprire = 3600;

    /**
     * CSRF constructor.
     */
    public function __construct()
    {
        if ($this->drive === 'SESSION' && !session_id()) {
            session_start();
        }

        if ($this->autoGenarate || empty($this->token)) {
            $this->generateToken();
        }
    }

    /**
     * init object with static
     *
     * @return CSRF
     */
    public static function init()
    {
        return new static();
    }

    /**
     * generation token
     */
    public function generateToken()
    {
        $this->token = md5(str_shuffle($this->listString));

        if ($this->drive == 'COOKIE') {
            $this->setCookie();
        } else {
            $this->setSession();
        }
    }

    /**
     * set session for validation
     */
    public function setSession()
    {
        if (empty($_SESSION[$this->secureName])) {
            $_SESSION[$this->secureName] = [];
        }
        $_SESSION[$this->secureName][] = $this->token;

        if (count($_SESSION[$this->secureName]) > 2) {
            $arraySession = $_SESSION[$this->secureName];
            array_shift($arraySession);
            $_SESSION[$this->secureName] = $arraySession;
        }
    }

    /**
     * get session for validate request
     *
     * @return array|null
     */
    public function getSession()
    {
        return @$_SESSION[$this->secureName] ?: null;
    }

    /**
     * set cookie for validate
     */
    public function setCookie()
    {
        setcookie($this->secureName, $this->encode($this->token), time() + $this->durationExprire, '', '', '', false);
    }

    /**
     * get cookie for validate request
     *
     * @return array|null
     */
    public function getCookie()
    {
        return @$_COOKIE[$this->secureName] ?: null;
    }

    /**
     * get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * show input with token
     *
     * @return string
     */
    public function getInput()
    {
        return '<input type="hidden" name="_token" value="' . $this->token . '">';
    }

    /**
     * Encode token, use if $drive === SESSION
     *
     * @param $string
     * @return string
     */
    public function encode($string)
    {
        $string = strtolower($string);
        $stringLength = strlen($string);
        $charResult = '';
        $secretLength = strlen($this->secret);

        for ($i = 0; $i < $stringLength; $i++) {
            $subStr = substr($string, $i, 1);
            $charResult .= $this->secret{ord($subStr) % $secretLength};
        }

        return $charResult;
    }

    /**
     * Validate request
     *
     * @return bool
     */
    public function validate()
    {
        //check request method
        if (
            empty($_SERVER['REQUEST_METHOD']) ||
            $_SERVER['REQUEST_METHOD'] == 'GET' ||
            $_SERVER['REQUEST_METHOD'] == 'HEAD'
        ) {
            return true;
        }

        //check POST exists
        if (empty($_POST['_token'])) {
            return false;
        }

        if ($this->drive == 'COOKIE') {
            return $this->getCookie() === $this->encode($_POST['_token']);
        } else {
            return current($this->getSession()) ===  $_POST['_token'];
        }
    }
}
