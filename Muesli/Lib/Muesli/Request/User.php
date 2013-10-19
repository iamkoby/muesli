<?php

class User
{
	private $default;
	protected $session;
    protected $culture;
    protected $attributes;
    protected $oldFlashes;
    protected $permissions=array();

    public function __construct(Session $session=null, $options = array())
    {
        $this->session = $session ? $session : new Session();
		$this->default = array(
            '_flash'   => array(),
            '_culture' => isset($options['default_culture']) ? $options['default_culture'] : 'eng',
        	'_authenticated' => false
        );
        $this->setAttributes($this->session->read('_user', $this->default));

        // flag current flash to be removed at shutdown
        $this->oldFlashes = array_flip(array_keys($this->getFlashMessages()));
    }

    public function getAttribute($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function clear()
    {
    	$this->setAttributes($this->default);
    }

    public function getCulture()
    {
        return $this->getAttribute('_culture');
    }

    public function setCulture($culture)
    {
        if ($this->culture != $culture) {
            $this->setAttribute('_culture', $culture);
        }
    }

    public function getFlashMessages()
    {
        return $this->attributes['_flash'];
    }

    public function setFlashMessages($values)
    {
        $this->attributes['_flash'] = $values;
    }

    public function getFlash($name, $default = null)
    {
        return $this->hasFlash($name) ? $this->attributes['_flash'][$name] : $default;
    }

    public function setFlash($name, $value)
    {
        $this->attributes['_flash'][$name] = $value;
        unset($this->oldFlashes[$name]);
    }

    public function hasFlash($name)
    {
        return array_key_exists($name, $this->attributes['_flash']);
    }

    public function __destruct()
    {
        $this->attributes['_flash'] = array_diff_key($this->attributes['_flash'], $this->oldFlashes);

        $this->session->write('_user', $this->attributes);
    }
	
    public function isAuthenticated()
	{
		return $this->getAttribute('_authenticated', false);
	}
	public function setAuthenticated($state)
	{
		$this->setAttribute('_authenticated', $state);
	}
	
	public function hasPermission($permission)
	{
		if (!$this->isAuthenticated()) return false;
		if (!$this->permissions){
			$this->permissions = explode(',',$this->getAttribute('_permissions', ''));
		}
		return in_array($permission, $this->permissions);
	}
	public function setPermissions($permissions)
	{
		if (!$permissions) $permissions = '';
		$this->setAttribute('_permissions', $permissions);
	}
}