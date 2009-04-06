<?php

/**
 * Web page layer
 *
 * @package tao
 * @author loranger
 * @todo Implements frameset page methods
 **/
class Page extends Document
{
	/**
	 * Current page schema
	 *
	 * @var array
	 **/
	protected $schema = array('version'=>array('1.0'));
	
	/**
	 * Current page head
	 *
	 * @var object
	 **/
	static $head;
	
	/**
	 * Current page body
	 *
	 * @var object
	 **/
	static $body;
	
	function __construct($name = 'xml', $charset = 'utf-8', $public_id = null, $doctype_uri = null, $namespace_uri = null)
	{
		parent::__construct($name, $charset, $public_id, $doctype_uri, $namespace_uri);

		self::$head = new Element('head');
		self::$root->appendChild(self::$head->getElement());

		self::$body = new Element('body');
		self::$root->appendChild(self::$body->getElement());
		
		return $this;
	}
	
	/**
	 * Set the title of the current page or replace it
	 *
	 * @return object $this
	 * @param string title
	 **/
	function setTitle($titleText=false)
	{
		if($titleText && !empty($titleText))
		{
			$title = self::$head->find('title');
			if(!$title)
			{
				$title = new Element('title');
				self::$head->addContent($title);
			}
			$title->setContent($titleText);
		}
		return $this;
	}
	
	/**
	 * Set the base uri of the current page or replace it
	 *
	 * @return object $this
	 * @param string uri
	 * @param string target
	 **/
	function setBase($uri, $target = false)
	{
		$base = self::$head->find('base');
		if(!$base)
		{
			$base = new Element('base');
			self::$head->addContent($base);
		}
		$base->setAttribute('href', $uri);
		
		if($target)
		{
			$base->setAttribute('target', $target);
		}
		elseif($base->hasAttribute('target'))
		{
			$base->removeAttribute('target');
		}
		
		return $this;
	}
	
	/**
	 * Add a meta tag to the current page or replace an existing one
	 *
	 * @return object $this
	 * @param string name of the meta
	 * @param string content of the 'content' attribute
	 * @param string type of the meta ('name' or 'http-equiv')
	 **/
	function addMeta($name, $content, $type = 'name')
	{
		$meta = self::$head->find('meta['.$type.'="'.$name.'"]');
		if(!$meta)
		{
			$meta = new Element('meta');
			$meta->setAttribute($type, $name);
			self::$head->addContent($meta);
		}
		$meta->setAttribute('content', $content);
		return $this;
	}
	
	/**
	 * Add a http-equiv meta tag to the current page or replace an existing one
	 *
	 * @return object $this
	 * @param string name of the http-equiv
	 * @param string content of the 'content' attribute
	 **/
	function addHTTPMeta($name, $content)
	{
		$this->addMeta($name, $content, 'http-equiv');
		return $this;
	}

	/**
	 * Add style definitions to a specified style tag (or create it if not exists)
	 *
	 * @return object $this
	 * @param string style definition
	 * @param string media (screen, print, ...)
	 **/
	function addStyle($string, $media = false)
	{
		if($string && !empty($string))
		{
			$style = ($media) ? self::$head->find('style[media="'.$media.'"]') : self::$head->find('style[not(@media)]');
			if(!$style)
			{
				$style = new Element('style');
				$style->setAttribute('type', 'text/css');
				if($media)
				{
					$style->setAttribute('media', trim($media));
				}
				self::$head->addContent($style);
			}
			$style->addContent( "\n".trim($string)."\n" );
			return $this;
		}
		return $this;
	}
	
	/**
	 * Add a content to the current Page
	 *
	 * @return Page
	 * @param mixed (string, array, Elements, Element or DOMElement) Exception will be thrown if Page cannot use the $content
	 **/	
	function addContent($content)
	{
		
		if(is_object($content))
		{
			if(is_a($content, 'Element'))
			{
				$content = $content->getElement();
			}
			if(is_a($content, 'Elements'))
			{
				$content = iterator_to_array($content);
			}
		}
		
		if(is_array($content))
		{
			foreach($content as $item)
			{
				$this->addContent($item);
			}
			return $this;
		}
		
		if(is_string($content))
		{
			$content = self::$dom->createTextNode($content);
		}

		try
		{
			self::$body->addContent($content);
		}
		catch (Exception $e)
		{
			throw $e;
		}

		return $this;
	}
	
	/**
	 * Page destructor
	 *
	 * @return mixed string or overriden render method
	 **/
	function __destruct()
	{
		echo $this;
	}
	
}

/**
 * Page singleton
 *
 * @return object Page
 * @param string type of the page
 * @param string charset of the page
 * @param string locale of the page
 **/
function Page($type = 'xhtml', $charset = null, $locale = null)
{
	static $page;

	if(!$page)
	{
		$args = func_get_args();
		if(count($args))
		{
			preg_match('/^([a-z]+)?( .+)?/i', @$args[0], $matches);
			$type = @$matches[1];
			if(array_key_exists(2, $matches))
			{
				$args[0] = trim($matches[2]);
			}
			else
			{
				array_shift($args);
			}
		}
		
		$className = 'Page'.ucfirst($type);
		$reflectionObj = new ReflectionClass($className);
		$page = $reflectionObj->newInstanceArgs($args);
	}
	return $page;
}

?>