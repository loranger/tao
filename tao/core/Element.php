<?php

/**
 * Unit Element
 * Default element extended by Element class
 * (see below)
 *
 * @package tao
 * @author loranger
 **/
class UnitElement
{
	/**
	 * DOMElement of the current Element object
	 *
	 * @var DOMElement
	 **/
	protected $element = false;
	
	/**
	 * Element constructor
	 *
	 * @return mixed object $this or false
	 * @param string $expression
	 **/
	function __construct($expression)
	{
		if($expression instanceof DOMElement || $expression instanceof DOMDocumentFragment)
		{
			return $this->createFromDOM($expression);
		}
		else
		{
			return $this->createFromTag($expression);
		}
		return false;
	}

	/**
	 * Check if current Element is an Iterator and iterate
	 *
	 * @return mixed Elements or false
	 **/
	protected function iterate($method, $args = false)
	{
		if( is_a($this, 'Iterator') )
		{
			$fire = create_function('&$item, $key, $args', 'call_user_func_array(array($item, "'.$method.'"), $args);');
			array_walk(iterator_to_array($this), $fire, $args);
			return $this;
		}
		return false;
	}
	
	/**
	 * Check if current Element has its element node
	 *
	 * @return return $this
	 **/
	protected function check()
	{
		if(!$this->element)
		{
			throw new Exception('Element is not valid or does not exists');
		}
	}

	/**
	 * Return the current DOMElement
	 *
	 * @return DOMElement
	 **/
	function getElement()
	{
		if(!$this->iterate(__FUNCTION__))
		{
			return $this->element;
		}
		return $this;
	}
	
	/**
	 * Check if DOM exists and create it if needed
	 *
	 * @return object $this
	 * @author Laurent Goussard
	 * @param string 
	 **/
	protected function checkDOM()
	{
		if( !Document::$dom )
		{
			new Document();
		}
		return $this;
	}

	/**
	 * Create an new Element from a tag name
	 *
	 * @return Element
	 * @param string
	 **/
	private function createFromTag($tag)
	{
		$this->checkDOM();
		$this->element = Document::$dom->createElement($tag);
		return $this;
	}
	
	/**
	 * Create a new Element from a DOMElement
	 *
	 * @return Element
	 * @param DOMElement
	 **/
	private function createFromDOM($object)
	{
		$this->element = $object;
		return $this;
	}
	
	/**
	 * Check if current element has attribute
	 *
	 * @return boolean
	 * @param string $name of the attribute
	 **/
	function hasAttribute($name)
	{
		return $this->getElement()->hasAttribute($name);
	}
	
	/**
	 * Return the attribute value of the current Element
	 *
	 * @return string
	 * @param string $name of the attribute
	 **/
	function getAttribute($name)
	{
		return $this->getElement()->getAttribute($name);
	}
	
	/**
	 * Define an attribute to the current element
	 *
	 * @return object $this
	 * @param string name of the attribute
	 * @param string or array value of the attribute
	 * @param string attributes delimiter
	 * @param boolean add a trailing delimiter or not
	 **/
	function setAttribute($name, $value, $delimiter = ' ', $trailing = false)
	{
		$args = func_get_args();

		if(!$this->iterate(__FUNCTION__, $args))
		{
			if(is_array($value))
			{
				$new_values = array();
				foreach($value as $item)
				{
					array_push($new_values, trim($item));
				}

				$value = implode($delimiter, array_unique($new_values));
			}
			if($trailing)
			{
				$value .= $delimiter;
			}

			$this->getElement()->setAttribute(trim($name), trim($value));
		}
		return $this;
	}
	
	/**
	 * Add (or create) an attribute to the current element
	 *
	 * @return object $this
	 * @param string name of the attribute
	 * @param string or array value of the attribute
	 * @param string attribute delimiter
	 * @param boolean add a trailing delimiter or not
	 **/
	function addAttribute($name, $value, $delimiter = ' ', $trailing = false)
	{
		$args = func_get_args();
		
		if(!$this->iterate(__FUNCTION__, $args))
		{
			if($this->getElement()->hasAttribute(trim($name)))
			{
				$current_value = explode($delimiter, $this->getElement()->getAttribute(trim($name)));
				foreach($current_value as $key=>$val)
				{
					if(trim($val) == '')
					{
						unset($current_value[$key]);
					}
				}
				if(is_array($value))
				{
					$value = array_merge($current_value, $value);
				}
				else
				{
					array_push($current_value, trim($value));
					$value = $current_value;
				}
			}

			$this->setAttribute(trim($name), $value, $delimiter, $trailing);
		}
		return $this;
	}

	/**
	 * Remove an attribute from the current element
	 *
	 * @return object $this
	 * @param string name of the attribute
	 **/
	function removeAttribute($name)
	{
		if(!$this->iterate(__FUNCTION__, $name))
		{
			$this->getElement()->removeAttribute(trim($name));
		}
		return $this;
	}

	/**
	 * Add a content to the current element
	 *
	 * @return Element
	 * @param mixed (string, array, Element or DOMElement) Exception will be thrown if Element cannot use the $content
	 **/
	function addContent($content)
	{
		if(!$this->iterate(__FUNCTION__, $content))
		{
			return $this->setContent($content, true);
		}
		return $this;
	}
	
	/**
	 * Set a content to the current element
	 *
	 * @return Element
	 * @param mixed (string, array, Element or DOMElement) Exception will be thrown if Element cannot use the $content
	 * @param boolean append content to current Element, or not
	 **/
	function setContent($content, $append = false)
	{
		if(!$this->iterate(__FUNCTION__, $content))
		{
			if(is_bool($content)){
			    return;
			}

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

			if(is_string($content) || is_double($content) || is_integer($content))
			{
			    $content = Document::$dom->createTextNode($content);
			}
			
			try
			{
				if($this->getElement()->hasChildNodes() && !$append)
				{
					$this->getElement()->replaceChild($content, $this->getElement()->firstChild);
				}
				else
				{
					$this->getElement()->appendChild($content);
				}
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		return $this;
	}
	
	/**
	 * Add current element as content of an other
	 *
	 * @return object $this
	 * @param Element
	 **/
	function addTo($element)
	{
		if(!$this->iterate(__FUNCTION__, $element))
		{
			$element->addContent($this);
		}
		return $this;
	}
	
	/**
	 * Find a children element based on a css expression
	 *
	 * @return Element or array of Element
	 * @param string css expression
	 **/
	function find($expression)
	{
		return Document::find($expression, $this->getElement());
	}
	
	/**
	 * Export current element as a string
	 *
	 * @return string
	 **/
	function __toString()
	{
		return (!$this->iterate(__FUNCTION__)) ? Document::$dom->saveXML($this->getElement()) : '';
	}
	
	/**
	 * Remove current element from DOM
	 *
	 * @return null
	 **/
	function remove()
	{
		if(!$this->iterate(__FUNCTION__))
		{
			if($this->getElement()->parentNode)
			{
				$this->getElement()->parentNode->removeChild($this->getElement());
			}
			$this->element = null;
		}
		return null;
	}

}

/**
 * Unit Universal Element
 * Implements (x)html universal attributes
 *
 * @package tao
 * @author loranger
 **/
class Element extends UnitElement
{
	
	protected $classDelimiter = ' ';
	protected $stylesDelimiter = ';';
	
	/**
	 * Element constructor
	 *
	 * @return mixed object $this or false
	 * @param string $expression
	 **/
	function __construct($expression)
	{
		return parent::__construct($expression);
	}
	
	/**
	 * Define an id attribute to the current element
	 *
	 * @return object $this
	 * @param string id
	 * @todo Add a global id checker
	 **/
	function setId($id)
	{
		$this->setAttribute('id', trim($id));
		return $this;
	}
	
	/**
	 * Define a reading orientation attribute to the current element
	 *
	 * @return object $this
	 * @param string orientation
	 **/
	function setDir($orientation)
	{
		$orientation = trim($orientation);
		if( $orientation == 'ltr' || $orientation == 'rtl' )
		{
			$this->setAttribute('dir', $orientation);
		}
		return $this;
	}
	
	/**
	 * Define a language attribute to the current element
	 *
	 * @return object $this
	 * @param string language code
	 **/
	function setLang($lang)
	{
		$lang = trim($lang);
		if( strlen($lang) == 2)
		{
			$this->setAttribute('lang', $lang);
		}
		return $this;
	}
	
	/**
	 * Define a title attribute to the current element
	 *
	 * @return object $this
	 * @param string title
	 **/
	function setTitle($title)
	{
		$this->setAttribute('title', trim($title));
		return $this;
	}

	/**
	 * Define a class attribute to the current element
	 *
	 * @return object $this
	 * @param string or array class
	 **/
	function setClass($class)
	{
		$this->setAttribute('class', $class, $this->classDelimiter);
		return $this;
	}
	
	/**
	 * Add a new class attribute value to the existing one for the current element
	 *
	 * @return object $this
	 * @param string or array
	 **/
	function addClass($class)
	{
		$this->addAttribute('class', $class, $this->classDelimiter);
		return $this;
	}
	
	/**
	 * Remove a specific classname from the current element
	 *
	 * @return object $this
	 * @param string
	 **/
	function removeClass($classname)
	{
		if($this->getElement()->hasAttribute('class'))
		{
			$current_value = explode($this->classDelimiter, $this->getElement()->getAttribute('class'));
			foreach($current_value as $key=>$current_name)
			{
				if(trim($classname) == trim($current_name))
				{
					unset($current_value[$key]);
				}
			}
			if(count($current_value)<1)
			{
				return $this->removeAttribute('class');
			}
		}
		$this->setClass($current_value);
		return $this;
	}
	
	/**
	 * Parse and prepare styles to be added or setted
	 *
	 * @return array $styles
	 * @param string or array
	 **/
	protected function prepareStyles($styles)
	{
		if( !is_array($styles) )
		{
			$styles = trim($styles);
			if( strrpos($styles, $this->stylesDelimiter) == ( strlen($styles) - 1 ) )
			{
				$styles = substr($styles, 0, -1);
			}
			$styles = explode($this->stylesDelimiter, $styles);
		}
		
		if( !is_numeric(key($styles)) )
		{
			$merge = array();
			foreach($styles as $key=>$val)
			{
				if(is_string($key))
				{
					array_push($merge, $key.':'.$val);
				}
			}
			$styles = $merge;
		}
		
		return $styles;
	}
	
	/**
	 * Define a style attribute to the current element
	 *
	 * @return object $this
	 * @param string or array
	 * @param string value in case of setStyle(name, value)
	 **/
	function setStyle($styles, $value = false)
	{
		if($value)
		{
			$styles = $styles.':'.$value;
		}
		$this->setAttribute('style', $this->prepareStyles($styles), $this->stylesDelimiter, true);
		return $this;
	}
	
	/**
	 * Add a new style attribute value to the existing one for the current element
	 *
	 * @return object $this
	 * @param string or array
	 * @param string value in case of setStyle(name, value)
	 **/
	function addStyle($styles, $value = false)
	{
		if($value)
		{
			$styles = $styles.':'.$value;
		}
		$this->addAttribute('style', $this->prepareStyles($styles), $this->stylesDelimiter, true);
		return $this;
	}
	
	/**
	 * Remove the given style
	 * Will remove every style beginning with the given style name given
	 *
	 * @return object $this
	 * @param string name of the style to remove
	 **/
	function removeStyle($style_name)
	{
		if($this->getElement()->hasAttribute('style'))
		{
			$current_value = explode($this->stylesDelimiter, $this->getElement()->getAttribute('style'));
			foreach($current_value as $key=>$current_name)
			{
				$style = explode(':', $current_name);
				preg_match('/^'.trim($style_name).'/ui', $style[0], $matches);
				if(count($matches) || trim($current_name) == '')
				{
					unset($current_value[$key]);
				}
			}
			if(count($current_value)<1)
			{
				return $this->removeAttribute('style');
			}
		}
		$this->setStyle($current_value);
		return $this;
	}

}


/**
 * Elements Iterator
 * Enable Element iterations
 *
 * @package tao
 * @author loranger
 **/
class Elements extends Element implements Iterator
{
	private $key = 0;
	private $array = array();
	
	function __construct($array)
	{
		$this->array = $array;
	}
	
	function current()
	{
		return $this->array[$this->key];
	}
	
	function key()
	{
		return $this->key;
	}
	
	function next()
	{
		++$this->key;
	}
	
	function rewind()
	{
		$this->key = 0;
	}
	
	function valid()
	{
		return isset($this->array[$this->key]);
	}
	
	function count()
	{
		return count($this->array);
	}
	
	function __toString()
	{
		$out = '';
		foreach(iterator_to_array($this) as $element)
		{
			$out .= $element->__toString();
		}
		return $out;
	}
}
?>