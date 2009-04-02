<?php

/**
 * Web page layer
 *
 * @package tao
 * @author loranger
 * @todo Implements frameset page methods
 * @todo Passer head en Element
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
			$text = self::$dom->createTextNode($titleText);
			
			$title = self::$root->getElementsByTagName('title')->item(0);
			if($title)
			{
				$title->replaceChild($text, $title->firstChild);
			}
			else
			{
			    $title = self::$dom->createElement('title');
				$title->appendChild($text);

				self::$head->addContent($title);
			}
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
		$base = self::$root->getElementsByTagName('base')->item(0);
		if($base)
		{
			$base->setAttribute('href', $uri);
		}
		else
		{
			$base = self::$dom->createElement('base');
			$base->setAttribute('href', $uri);

			self::$head->addContent($base);
		}
		
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
		$metas = self::$head->find('meta');
		if($metas)
		{
			foreach($metas as $meta)
			{
				if($meta->hasAttribute($type) && $meta->getAttribute($type) == $name)
				{
					$meta->setAttribute('content', $content);
					return $this;
				}
			}
		}
		
		$meta = self::$dom->createElement('meta');
		$meta->setAttribute($type, $name);
		$meta->setAttribute('content', $content);

		self::$head->addContent($meta);
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
			$node = self::$dom->createTextNode( "\n".trim($string)."\n" );
			
			$existing = self::$root->getElementsByTagName('style');
			if($existing->length)
			{
				foreach($existing as $style)
				{
					if( (!$media && !$style->hasAttribute('media')) || ($media && $style->getAttribute('media') == trim($media)) )
					{
						$style->appendChild($node);
						$node = null;
					}
				}
			}
			
			if($node)
			{
				$style = self::$dom->createElement('style');
				$style->setAttribute('type', 'text/css');
				if($media)
				{
					$style->setAttribute('media', trim($media));
				}
				$style->appendChild($node);

				self::$head->addContent($style);
			}
		}
		return $this;
	}
	
	/**
	 * Add a content to the current Page
	 *
	 * @return Page
	 * @param mixed (string, array, Element or DOMElement) Exception will be thrown if Page cannot use the $content
	 **/	
	function addContent($content)
	{

		if(is_object($content) && is_a($content, 'Element'))
		{
			$content = $content->getElement();
		}
		
		if(is_array($content))
		{
			foreach($content as $iter)
			{
				$this->addContent($iter);
			}
			return $this;
		}
		
		if(is_string($content))
		{
			$content = self::$dom->createTextNode($content);
		}

		if(! @self::$body->addContent($content))
		{
			throw new Exception('$content is <b>'.gettype($content).'</b>');
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