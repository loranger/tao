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
	 * Check if current Element has its element node
	 *
	 * @return object $this
	 **/
	function checkElement()
	{
		if(!$this->element)
		{
			throw new Exception('Element is not valid or does not exists');
		}
		return $this;
	}

	/**
	 * Return the current DOMElement
	 *
	 * @return DOMElement
	 **/
	function getElement()
	{
		$this->checkElement();
		return $this->element;
	}

	/**
	 * Create an new Element from a tag name
	 *
	 * @return Element
	 * @param string
	 **/
	private function createFromTag($tag)
	{
		if( !Document::$dom )
		{
			new Document();
		}
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
		$this->checkElement();
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
		
		$this->element->setAttribute(trim($name), trim($value));
		
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
		$this->checkElement();
		if($this->element->hasAttribute(trim($name)))
		{
			$current_value = explode($delimiter, $this->element->getAttribute(trim($name)));
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
		$this->checkElement();
		$this->element->removeAttribute(trim($name));
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
		$this->checkElement();
		if(is_bool($content)){
		    return;
		}
		
		if(is_object($content))
		{
			switch(get_class($content))
			{
				case 'Element':
				case 'CodeImporter':
					$content = $content->getElement();
					break;
				case 'DOMElement':
				case 'DOMNode':
				default:
					break;
			}
		}

		if(is_array($content))
		{
			foreach($content as $iter)
			{
				$this->element->addContent($iter);
			}
			return $this;
		}

		if(is_string($content) || is_double($content) || is_integer($content))
		{
		    $content = Document::$dom->createTextNode($content);
		}

		if( ! @$this->element->appendChild($content) )
		{
			throw new Exception('$content is <b>'.gettype($content).'</b>');
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
		$element->addContent($this);
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
		return Document::find($expression, $this->element);
	}
	
	/**
	 * Export current element as a string
	 *
	 * @return string
	 **/
	function __toString()
	{
		$this->checkElement();
		return Document::$dom->saveXML($this->element);
	}
	
	/**
	 * Remove current element from DOM
	 *
	 * @return null
	 **/
	function remove()
	{
		$this->checkElement();
		if($this->element->parentNode)
		{
			$this->element->parentNode->removeChild($this->element);
		}
		$this->element = null;
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
		if($this->element->hasAttribute('class'))
		{
			$current_value = explode($this->classDelimiter, $this->element->getAttribute('class'));
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
		if($this->element->hasAttribute('style'))
		{
			$current_value = explode($this->stylesDelimiter, $this->element->getAttribute('style'));
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

?>