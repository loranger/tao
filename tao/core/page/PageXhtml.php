<?php

/**
 * Xhtml page layer
 *
 * @package tao
 * @author loranger
 **/
class PageXhtml extends Page
{

	/**
	 * Current xhtml document schema
	 *
	 * @var array
	 **/
	protected $schema = array('variant'=>'transitional','version'=>array('1.0'));

	/**
	 * PageXhtml constructor
	 *
	 * @example PageXhtml new PageXhtml('1 strict', 'utf-8', 'fr');
	 * @example Page Page('xhtml');
	 *
	 * @return object $this
	 * @param string $version of the current html page
	 * @param string $charset
	 * @param string $language
	 **/
	function __construct($version = '1.0', $charset = 'utf-8', $language = false)
	{
		$public_id = null;
		$doctype_uri = null;
		$namespace_uri = null;

		preg_match('/^([0-9.]+)( [a-z]+)?$/', strtolower(trim($version)), $parts);

		if(count($parts))
		{

			$public_id = '-//W3C//DTD XHTML';
			foreach ($parts as $key => $value) {
				switch($key)
				{
					case 1:
						$this->schema['version'] = explode('.', trim($value));
						if(array_key_exists(1, $this->schema['version']) && $this->schema['version'][1] == 0)
						{
							unset($this->schema['version'][1]);
						}
						$public_id .= ' '.trim($value);
						break;
					case 2:
						$this->schema['variant'] = trim($value);
						break;
				}
			}
			$public_id .= ' '.ucfirst($this->schema['variant']);
			$public_id .= '//EN';

			$doctype_uri = sprintf('http://www.w3.org/TR/%s/DTD/%s%s.dtd',
									'xhtml'.$this->schema['version'][0],
									'xhtml'.implode('', $this->schema['version']),
									((array_key_exists('variant', $this->schema)) ? '-'.$this->schema['variant'] : '')
									);
			$namespace_uri = 'http://www.w3.org/1999/xhtml';

		}

		parent::__construct('html', $charset, $public_id, $doctype_uri, $namespace_uri);

		if($language)
		{
			self::$root->setAttribute('xml:lang', $language);
		}

		return $this;
	}

	/**
	 * Rendering method (called by Document)
	 *
	 * @return string page source code
	 **/
	function render()
	{
		if(self::$charset == 'utf-8')
		{
			return self::$dom->saveXML();
		}
		else
		{
			return self::$dom->saveHTML();
		}
	}

}

?>