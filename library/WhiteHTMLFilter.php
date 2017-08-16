<?php
/*
 * White HTML Filter
 * https://github.com/lincanbin/White-HTML-Filter
 *
 * Copyright 2017 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * A php-based HTML tag and attribute whitelist filter.
 */



/**
 * @property WhiteHTMLFilterConfig config
 * @property DOMDocument dom
 */
class WhiteHTMLFilter
{
	public $config;
	public $removedTags;
	private $dom = NULL;
	private $TEMP_CONTENT;
	private $PARENT_TAG_NAME;

	/**
	 * The empty elements in HTML
	 * https://developer.mozilla.org/en-US/docs/Glossary/Empty_element
	 */
	protected $emptyElementList = array(
		'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', //'keygen',
		'link', 'meta', 'param', 'source', 'track', 'wbr'
	);

	/**
	 * WhiteHTMLFilter constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		if (extension_loaded("dom") === false) {
			throw new Exception('DOM extension is required. http://php.net/manual/en/dom.installation.php');
		}
		$this->config = new WhiteHTMLFilterConfig();

		if (!$this->dom) {
			$this->dom = new DOMDocument('1.0', 'UTF-8');
		}
		$this->dom->preserveWhiteSpace = true;
		$this->dom->formatOutput = false;
		$this->TEMP_CONTENT = 'a7c598c8-fcb7-4bde-af9c-91c6515fbf7a-lincanbin-' . md5(mt_rand());
		$this->PARENT_TAG_NAME = substr('tag' . md5(mt_rand()), 0, 8);
		//Disable libxml errors
		libxml_use_internal_errors(true);
	}

	/**
	 * Get current tag whitelist
	 * @return array
	 */
	public function getWhiteListTags()
	{
		return ($this->config->WhiteListTag);
	}

	/**
	 * Load document markup into the class for cleaning
	 * @param string $html The markup to clean
	 * @return bool
	 */
	public function loadHTML($html)
	{
		$html = str_replace(chr(13), '', $html);
		$html = '<?xml version="1.0" encoding="utf-8" ?><' . $this->PARENT_TAG_NAME . '>' . $html . '</' . $this->PARENT_TAG_NAME . '>';
		if (defined('LIBXML_HTML_NODEFDTD')) {
			return $this->dom->loadHTML($html, LIBXML_HTML_NODEFDTD);
		} else {
			return $this->dom->loadHTML($html);
		}

	}

	/**
	 * Output the result
	 * @return string HTML string
	 */
	public function outputHtml()
	{
		$result = '';
		if (!is_null($this->dom)) {
			//SaveXML : <br/><img/>
			//SaveHTML: <br><img>
			$result = trim($this->dom->saveXML($this->getRealElement()));
			$result = str_replace($this->TEMP_CONTENT, '', $result);
			$parentTagNameLength = strlen($this->PARENT_TAG_NAME);
			$result = substr($result, $parentTagNameLength + 2, -($parentTagNameLength + 3));
		}
		return $result;
	}

	/**
	 * Check if there is a valid text in the tag
	 * @param string $string
	 * @return boolean Whether there is valid text
	 */
	private function isValidText($string)
	{
		$search = array(" ", "　", "\n", "\r", "\t");
		$replace = array("", "", "", "", "");
		return str_replace($search, $replace, $string) !== '';
	}

	/**
	 * Recursivly remove elements from the DOM that aren't whitelisted
	 * @param DOMElement $elem
	 * @param boolean $isFirstNode
	 * @throws Exception If removal of a node failed than an exception is thrown
	 */
	private function cleanNodes(DOMElement $elem, $isFirstNode = false)
	{
		$nodeName = strtolower($elem->nodeName);
		$textContent = $elem->textContent;
		if ($isFirstNode || array_key_exists($nodeName, $this->config->WhiteListTag)) {
			if ($elem->hasAttributes()) {
				$this->cleanAttributes($elem);
			}
			/*
			 * Iterate over the element's children. The reason we go backwards is because
			 * going forwards will cause indexes to change when elements get removed
			 */
			if ($elem->hasChildNodes()) {
				$children = $elem->childNodes;
				$index = $children->length;
				while (--$index >= 0) {
					$cleanNode = $children->item($index);// DOMElement or DOMText
					if ($cleanNode instanceof DOMElement) {
						$this->cleanNodes($cleanNode);
					}
				}
			} else {
				if (!in_array($nodeName, $this->emptyElementList) && !$this->isValidText($textContent)) {
					$elem->nodeValue = $this->TEMP_CONTENT;
				}
			}
		} else {
			if ($this->config->KeepText === true && $this->isValidText($textContent)) {
				$result = $elem->parentNode->replaceChild($this->dom->createTextNode($textContent), $elem);
			} else {
				$result = $elem->parentNode->removeChild($elem);
			}
			if ($result) {
				$this->removedTags[] = $nodeName;
			} else {
				throw new Exception('Failed to remove node from DOM');
			}
		}
	}

	/**
	 * Clean the attributes of the html tags
	 * @param DOMElement $elem
	 */
	private function cleanAttributes(DOMElement $elem)
	{
		$tagName = strtolower($elem->nodeName);
		$attributes = $elem->attributes;
		$attributesWhiteList = $this->config->WhiteListHtmlGlobalAttributes;
		$attributesFilterMap = array();
		$allowDataAttribute = in_array("data-*", $attributesWhiteList);
		$whiteListAttr = $this->config->getWhiteListAttr($tagName);
		foreach ($whiteListAttr as $key => $val) {
			if (is_string($val)) {
				$attributesWhiteList[] = $val;
			}
			if ($val instanceof Closure) {
				$attributesWhiteList[] = $key;
				$attributesFilterMap[$key] = $val;
			}
		}
		$index = $attributes->length;
		while (--$index >= 0) {
			/* @var $domAttr DOMAttr */
			$domAttr = $attributes->item($index);
			$attrName = strtolower($domAttr->name);
			// 如果不在白名单attr中，而且允许data-*，且不是data-*，则删除
			if (!in_array($attrName, $attributesWhiteList) && $allowDataAttribute && (stripos($attrName, "data-") !== 0)) {
				$elem->removeAttribute($attrName);
			} else {
				if (isset($attributesFilterMap[$attrName])) {
					$domAttr->value = $attributesFilterMap[$attrName]($domAttr->value);
				} else {
					$this->cleanAttrValue($domAttr);
				}
			}
		}
	}

	/**
	 * Clean the value of the attribute
	 * @param DOMAttr $domAttr
	 */
	private function cleanAttrValue(DOMAttr $domAttr)
	{
		$attrName = strtolower($domAttr->name);
		if ($attrName === 'style' && !empty($this->config->WhiteListStyle)) {
			$styles = explode(';', $domAttr->value);
			foreach ($styles as $key => &$subStyle) {
				$subStyle = array_map("trim", explode(':', strtolower($subStyle), 2));
				if (empty($subStyle[0]) || !in_array($subStyle[0], $this->config->WhiteListStyle)) {
					unset($styles[$key]);
				}
			}
			$implodeFunc = function ($styleSheet) {
				return implode(':', $styleSheet);
			};
			$domAttr->value = implode(';', array_map($implodeFunc, $styles)) . ';';

		}
		if ($attrName === 'class' && !empty($this->config->WhiteListCssClass)) {
			$domAttr->value = implode(' ', array_intersect(preg_split('/\s+/', $domAttr->value), $this->config->WhiteListCssClass));
		}
		if ($attrName === 'src' || $attrName === 'href') {
			if (strtolower(parse_url($domAttr->value, PHP_URL_SCHEME)) === 'javascript') {
				$domAttr->value = '';
			} else {
				$domAttr->value = filter_var($domAttr->value, FILTER_SANITIZE_URL);
			}
		}
	}

	/**
	 * Perform the cleaning of the document
	 * @return array List of deleted HTML tags
	 */
	public function clean()
	{
		$this->removedTags = array();
		$elem = $this->getRealElement();
		if (is_null($elem)) {
			return array();
		}
		$this->cleanNodes($elem, true);
		return $this->removedTags;
	}

	/**
	 * Get Element without doc type.
	 * @return DOMElement
	 */
	public function getRealElement()
	{
		return $this->dom->getElementsByTagName($this->PARENT_TAG_NAME)->item(0);
	}
}