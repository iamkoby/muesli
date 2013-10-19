<?php

/*
 * This file is part of the symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
Copyright (c) 2004-2010 Fabien Potencier

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

class Response
{
  protected $content;
  protected $version;
  protected $statusCode;
  protected $statusText;
  protected $headers;
  protected $cookies;
  private $shouldCache=false;

  static public $statusTexts = array(
    '100' => 'Continue',
    '101' => 'Switching Protocols',
    '200' => 'OK',
    '201' => 'Created',
    '202' => 'Accepted',
    '203' => 'Non-Authoritative Information',
    '204' => 'No Content',
    '205' => 'Reset Content',
    '206' => 'Partial Content',
    '300' => 'Multiple Choices',
    '301' => 'Moved Permanently',
    '302' => 'Found',
    '303' => 'See Other',
    '304' => 'Not Modified',
    '305' => 'Use Proxy',
    '307' => 'Temporary Redirect',
    '400' => 'Bad Request',
    '401' => 'Unauthorized',
    '402' => 'Payment Required',
    '403' => 'Forbidden',
    '404' => 'Not Found',
    '405' => 'Method Not Allowed',
    '406' => 'Not Acceptable',
    '407' => 'Proxy Authentication Required',
    '408' => 'Request Timeout',
    '409' => 'Conflict',
    '410' => 'Gone',
    '411' => 'Length Required',
    '412' => 'Precondition Failed',
    '413' => 'Request Entity Too Large',
    '414' => 'Request-URI Too Long',
    '415' => 'Unsupported Media Type',
    '416' => 'Requested Range Not Satisfiable',
    '417' => 'Expectation Failed',
    '500' => 'Internal Server Error',
    '501' => 'Not Implemented',
    '502' => 'Bad Gateway',
    '503' => 'Service Unavailable',
    '504' => 'Gateway Timeout',
    '505' => 'HTTP Version Not Supported',
  );

  public function __construct($content = '', $status = 200, $headers = array())
  {
    $this->setContent($content);
    $this->setStatusCode($status);
    $this->setProtocolVersion('1.0');
    $this->headers = array();
    foreach ($headers as $name => $value)
    {
      $this->setHeader($name, $value);
    }
    $this->cookies = array();
  }

  public function __toString()
  {
    $this->sendHeaders();
    return (string) $this->getContent();
  }
  
  	public function setCaching($state)
  	{
  		$this->shouldCache = (bool)$state;
  	}
	public function shouldCache()
	{
		return $this->shouldCache;
	}

  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setProtocolVersion($version)
  {
    $this->version = $version;

    return $this;
  }

  public function getProtocolVersion()
  {
    return $this->version;
  }

  /**
   * Sets a cookie.
   *
   * @param  string  $name      HTTP header name
   * @param  string  $value     Value for the cookie
   * @param  string  $expire    Cookie expiration period
   * @param  string  $path      Path
   * @param  string  $domain    Domain name
   * @param  bool    $secure    If secure
   * @param  bool    $httpOnly  If uses only HTTP
   */
  public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false)
  {
    if (!is_null($expire))
    {
      if (is_numeric($expire))
      {
        $expire = (int) $expire;
      }
      else
      {
        $expire = strtotime($expire);
        if (false === $expire || -1 == $expire)
        {
          throw new InvalidArgumentException('The cookie expire parameter is not valid.');
        }
      }
    }

    $this->cookies[$name] = array(
      'name'     => $name,
      'value'    => $value,
      'expire'   => $expire,
      'path'     => $path,
      'domain'   => $domain,
      'secure'   => (Boolean) $secure,
      'httpOnly' => (Boolean) $httpOnly,
    );

    return $this;
  }

  public function getCookies()
  {
    return $this->cookies;
  }

  public function setStatusCode($code, $text = null)
  {
    $this->statusCode = (int) $code;
    if ($this->statusCode < 100 || $this->statusCode > 599)
    {
      throw new InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
    }

    $this->statusText = false === $text ? '' : (is_null($text) ? self::$statusTexts[$this->statusCode] : $text);

    return $this;
  }

  public function getStatusCode()
  {
    return $this->statusCode;
  }

  public function setHeader($name, $value, $replace = true)
  {
    $name = $this->normalizeHeaderName($name);

    if (is_null($value))
    {
      unset($this->headers[$name]);

      return;
    }

    if (!$replace)
    {
      $current = isset($this->headers[$name]) ? $this->headers[$name] : '';
      $value = ($current ? $current.', ' : '').$value;
    }

    $this->headers[$name] = $value;

    return $this;
  }

  public function getHeader($name, $default = null)
  {
    $name = $this->normalizeHeaderName($name);

    return isset($this->headers[$name]) ? $this->headers[$name] : $default;
  }

  public function hasHeader($name)
  {
    return array_key_exists($this->normalizeHeaderName($name), $this->headers);
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function sendHeaders()
  {
    if (!$this->hasHeader('Content-Type'))
    {
      $this->setHeader('Content-Type', 'text/html');
    }

    // status
    header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));

    // headers
    foreach ($this->headers as $name => $value)
    {
      header($name.': '.$value);
    }

    // cookies
    foreach ($this->cookies as $cookie)
    {
      setrawcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
    }
  }

  public function sendContent()
  {
    echo $this->content;
  }

  public function send()
  {
    $this->sendHeaders();
    $this->sendContent();
  }

  protected function normalizeHeaderName($name)
  {
    return strtr(strtolower($name), '_', '-');
  }
}
