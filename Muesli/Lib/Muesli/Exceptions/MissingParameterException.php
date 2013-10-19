<?php

class MissingParameterException extends Exception
{
	public function __construct($message = '')
    {
        parent::__construct('Missing required parameter: '.$message);
    }
}