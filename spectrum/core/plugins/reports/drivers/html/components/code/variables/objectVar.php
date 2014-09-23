<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class objectVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.c-code-variables-object { display: inline-block; vertical-align: text-top; border-radius: 4px; background: rgba(255, 255, 255, 0.5); font-size: 12px; }
			.c-code-variables-object>.indention { display: none; }
			.c-code-variables-object>.type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-object>.class { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; color: #000; white-space: nowrap; vertical-align: text-top; }
			.c-code-variables-object>.c-code-operator.curlyBrace { display: none; }
			.c-code-variables-object>.elements:before { content: "\\007B\\2026\\007D"; color: rgba(0, 0, 0, 0.6); }
			.c-code-variables-object>.elements>.element { display: none; }
			.c-code-variables-object>.elements>.element>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.c-code-variables-object .c-code-variables-object,
			.c-code-variables-object .c-code-variables-array { display: inline; vertical-align: baseline; background: transparent; }
			
			.c-resultBuffer>.results>.result.expanded .c-code-variables-object>.indention { display: inline-block; overflow: hidden; width: 25px; white-space: pre; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-object>.class { display: inline; overflow: visible; max-width: none; white-space: normal; vertical-align: baseline; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-object>.c-code-operator.curlyBrace { display: inline; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-object>.elements:before { display: none; }
			.c-resultBuffer>.results>.result.expanded .c-code-variables-object>.elements>.element { display: block; }
		/*]]>*/</style>', 2);
	}
	
	static public function getContent($variable, $depth, $inputCharset = null) {
		$properties = static::getProperties($variable);
		
		$content = '';
		$content .= '<span class="c-code-variables-object">';
		$content .= static::getContentForType($properties);
		$content .= static::getContentForClass($variable, $inputCharset);
		$content .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii'));
		$content .= static::getContentForElements($variable, $properties, $depth, $inputCharset);
		
		if (count($properties)) {
			$content .= str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth); // Indention should be copied to buffer
		}
		
		$content .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		$content .= '</span>';
		return $content;
	}
	
	static protected function getContentForType($properties) {
		return
			'<span class="type">' .
				static::translateAndEscapeHtml('object') .
				'<span title="' . static::translateAndEscapeHtml('Properties count') . '">(' . static::escapeHtml(count($properties)) . ')</span> ' .
			'</span>';
	}
	
	static protected function getContentForClass($variable, $inputCharset) {
		return '<span class="class">\\' . static::escapeHtml(static::convertToOutputCharset(get_class($variable), $inputCharset)) . '</span> ';
	}
	
	static protected function getContentForElements($variable, $properties, $depth, $inputCharset) {
		$content = '';
		if (count($properties)) {
			$content .= '<span class="elements">';
			foreach ($properties as $key => $value) {
				// Replace full exception trace to light text representation for resource saving
				if ($variable instanceof \Exception && (string) $key === 'trace') {
					$value['value'] = static::convertToOutputCharset($variable->getTraceAsString(), 'utf-8'); // Filenames are come in OS charset (conceivably in "utf-8")
				}
				
				$content .= static::getContentForElement($key, $value, $depth, $inputCharset);
			}

			$content .= '</span>';
		}
		
		return $content;
	}

	static protected function getContentForElement($key, $value, $depth, $inputCharset) {
		return
			'<span class="element">' .
				// Indention should be copied to buffer
				str_repeat('<span class="indention">' . static::getHtmlEscapedOutputIndention() . '</span>', $depth + 1) .
				'<span class="key">' .
					($value['isPublic'] ? static::callComponentMethod('code\keyword', 'getContent', array('public', 'us-ascii')) . ' ' : '') .
					($value['isProtected'] ? static::callComponentMethod('code\keyword', 'getContent', array('protected', 'us-ascii')) . ' ' : '') .
					($value['isPrivate'] ? static::callComponentMethod('code\keyword', 'getContent', array('private', 'us-ascii')) . ' ' : '') .
					($value['isStatic'] ? static::callComponentMethod('code\keyword', 'getContent', array('static', 'us-ascii')) . ' ' : '') .
					static::callComponentMethod('code\operator', 'getContent', array('[', 'us-ascii')) .
					static::escapeHtml(static::convertToOutputCharset($key, $inputCharset)) .
					static::callComponentMethod('code\operator', 'getContent', array(']', 'us-ascii')) .
				'</span> ' .
				static::callComponentMethod('code\operator', 'getContent', array('=>', 'us-ascii')) . ' ' .
				static::callComponentMethod('code\variable', 'getContent', array($value['value'], $depth + 1, $inputCharset)) .
			'</span>';
	}

	static protected function getProperties($variable) {
		$result = array();
		
		// Use "get_object_vars" because "ReflectionClass::getProperties" does not return undeclared public properties
		foreach (get_object_vars($variable) as $name => $value) {
			$result[$name] = array(
				'value' => $value,
				'isStatic' => false,
				'isPublic' => true,
				'isProtected' => false,
				'isPrivate' => false,
			);
		}
		
		$reflection = new \ReflectionClass($variable);
		$properties = $reflection->getProperties(
			\ReflectionProperty::IS_STATIC |
			\ReflectionProperty::IS_PROTECTED |
			\ReflectionProperty::IS_PRIVATE
		);

		foreach ($properties as $property) {
			$property->setAccessible(true);
			$result[$property->getName()] = array(
				'value' => $property->getValue($variable),
				'isStatic' => $property->isStatic(),
				'isPublic' => $property->isPublic(),
				'isProtected' => $property->isProtected(),
				'isPrivate' => $property->isPrivate(),
			);
		}

		return $result;
	}
}