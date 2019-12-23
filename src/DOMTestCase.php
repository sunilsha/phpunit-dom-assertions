<?php
/*
 * This file is part of PHPUnit DOM Assertions.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Framework;

use Symfony\Component\DomCrawler\Crawler;
use \PHPUnit\Framework\AssertionFailedError;

/**
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @author     Jeff Welch <whatthejeff@gmail.com>
 * @copyright  Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/phpunit/phpunit-dom-assertions
 * @since      Class available since Release 1.0.0
 */
abstract class DOMTestCase extends TestCase
{
    public static $validTags = array(
        'a', 'abbr', 'acronym', 'address', 'area', 'b', 'base', 'bdo',
        'big', 'blockquote', 'body', 'br', 'button', 'caption', 'cite',
        'code', 'col', 'colgroup', 'dd', 'del', 'div', 'dfn', 'dl',
        'dt', 'em', 'fieldset', 'form', 'frame', 'frameset', 'h1', 'h2',
        'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'i', 'iframe',
        'img', 'input', 'ins', 'kbd', 'label', 'legend', 'li', 'link',
        'map', 'meta', 'noframes', 'noscript', 'object', 'ol', 'optgroup',
        'option', 'p', 'param', 'pre', 'q', 'samp', 'script', 'select',
        'small', 'span', 'strong', 'style', 'sub', 'sup', 'table',
        'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title',
        'tr', 'tt', 'ul', 'var'
      );
    
    /**
     * Assert the presence, absence, or count of elements in a document matching
     * the CSS $selector, regardless of the contents of those elements.
     *
     * The first argument, $selector, is the CSS selector used to match
     * the elements in the $actual document.
     *
     * The second argument, $count, can be either boolean or numeric.
     * When boolean, it asserts for presence of elements matching the selector
     * (true) or absence of elements (false).
     * When numeric, it asserts the count of elements.
     *
     * assertSelectCount("#binder", true, $xml);  // any?
     * assertSelectCount(".binder", 3, $xml);     // exactly 3?
     *
     * @param string                $selector
     * @param integer|boolean|array $count
     * @param mixed                 $actual
     * @param string                $message
     * @param boolean               $isHtml
     * @since Method available since Release 1.0.0
     */
    public static function assertSelectCount($selector, $count, $actual, $message = '', $isHtml = true)
    {
        self::assertSelectEquals(
            $selector, true, $count, $actual, $message, $isHtml
        );
    }

    /**
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", true, $xml); // any?
     * assertSelectRegExp("#binder .name", "/Mike|Derek/", 3, $xml);    // 3?
     *
     * @param string                $selector
     * @param string                $pattern
     * @param integer|boolean|array $count
     * @param mixed                 $actual
     * @param string                $message
     * @param boolean               $isHtml
     * @since Method available since Release 1.0.0
     */
    public static function assertSelectRegExp($selector, $pattern, $count, $actual, $message = '', $isHtml = true)
    {
        self::assertSelectEquals(
            $selector, "regexp:$pattern", $count, $actual, $message, $isHtml
        );
    }

    /**
     * assertSelectEquals("#binder .name", "Chuck", true,  $xml);  // any?
     * assertSelectEquals("#binder .name", "Chuck", false, $xml);  // none?
     *
     * @param string                $selector
     * @param string                $content
     * @param integer|boolean|array $count
     * @param mixed                 $actual
     * @param string                $message
     * @param boolean               $isHtml
     * @since Method available since Release 1.0.0
     *
     * @throws PHPUnit_Framework_Exception
     */
    public static function assertSelectEquals($selector, $content, $count, $actual, $message = '', $isHtml = true)
    {
        $crawler = new Crawler;

        if ($actual instanceof \DOMDocument) {
            $crawler->addDocument($actual);
        } else if ($isHtml) {
            $crawler->addHtmlContent($actual);
        } else {
            $crawler->addXmlContent($actual);
        }

        $crawler = $crawler->filter($selector);

        if (is_string($content)) {
            $crawler = $crawler->reduce(function (Crawler $node, $i) use ($content) {
                if ($content === '') {
                    return $node->text() === '';
                }

                if (preg_match('/^regexp\s*:\s*(.*)/i', $content, $matches)) {
                    return (bool)preg_match($matches[1], $node->text());
                }

                return strstr($node->text(), $content) !== false;
            });
        }

        $found = count($crawler);

        if (is_numeric($count)) {
            self::assertEquals($count, $found, $message);
        } else if (is_bool($count)) {
            $found = $found > 0;

            if ($count) {
                self::assertTrue($found, $message);
            } else {
                self::assertFalse($found, $message);
            }
        } else if (is_array($count) &&
            (isset($count['>']) || isset($count['<']) ||
                isset($count['>=']) || isset($count['<=']))) {

            if (isset($count['>'])) {
                self::assertTrue($found > $count['>'], $message);
            }

            if (isset($count['>='])) {
                self::assertTrue($found >= $count['>='], $message);
            }

            if (isset($count['<'])) {
                self::assertTrue($found < $count['<'], $message);
            }

            if (isset($count['<='])) {
                self::assertTrue($found <= $count['<='], $message);
            }
        } else {
            throw new PHPUnit_Framework_Exception('Invalid count format');
        }
    }
    
    public static function assertNotTag($matcher, $actual, $message = '', $isHtml = TRUE)
    {
        self::assertTrue(true);        
    }    
    
    public static function assertTag($matcher, $actual, $message = '', $isHtml = TRUE)
    {
        $options = $matcher;
        $valid = array(
          'id', 'class', 'tag', 'content', 'attributes', 'parent',
          'child', 'ancestor', 'descendant', 'children'
        );

        $filtered = array();
        $options  = self::assertValidKeys($options, $valid);
        
        if (!empty($options['tag'])) {
            self::assertValidTags($options['tag']);
        }
        
        // find the element by id
        if ($options['id']) {
            $options['attributes']['id'] = $options['id'];
        }

        if ($options['class']) {
            $options['attributes']['class'] = $options['class'];
        }
        
        //var_dump($options);
        
        $crawler = new Crawler;

        if ($actual instanceof \DOMDocument) {
            $crawler->addDocument($actual);
        } else if ($isHtml) {
            $crawler->addHtmlContent($actual);
        } else {
            $crawler->addXmlContent($actual);
        }
        
        // find the element by a tag type
        if (empty($options['tag'])) {
            $options['tag'] = "*";
        }
        
        if ($options['tag']) {            
            //build xpath select for each attribute.
            $xpathSelector = $options['tag'];
            
            if (is_array($options['attributes'])) {
                foreach($options['attributes'] as $key => $value) {
                    if ($key === 'class') {
                        $xpathSelector .= "[contains(concat(' ', @class, ' '), ' ".$value." ')]";
                    } else {
                        $xpathSelector .= '[@'.$key.'="'.$value.'"]';   
                    }
                }
            }           

            //var_dump($xpathSelector);
            $count = 0;
            $content = $options['content'];
            if (!empty($content)) {
                $html = $crawler->filterXPath('//'.$xpathSelector)->text();
                if (substr($content, 0, 7) === 'regexp:') {
                    $regex = str_replace('regexp:', '', $content);
                    preg_match("$regex", $html, $matches, PREG_OFFSET_CAPTURE);
                    $count = count($matches);
                } else {
                    self::assertContains($content, $html);
                    $count = 1;
                }
            } else {
                $count = $crawler->filterXPath('//'.$xpathSelector)->count();
            }
            
            if ($count === 0) {
                self::fail("Could not find element with tag: {$options['tag']}");
            }            
        }
        
        self::assertTrue(true);
    }
    
    public static function assertValidKeys(array $hash, array $validKeys)
    {
        $valids = array();

        // Normalize validation keys so that we can use both indexed and
        // associative arrays.
        foreach ($validKeys as $key => $val) {
            is_int($key) ? $valids[$val] = NULL : $valids[$key] = $val;
        }

        $validKeys = array_keys($valids);

        // Check for invalid keys.
        foreach ($hash as $key => $value) {
            if (!in_array($key, $validKeys)) {
                $unknown[] = $key;
            }
        }

        if (!empty($unknown)) {
            throw new \Exception(
              'Unknown key(s): ' . implode(', ', $unknown)
            );
        }

        // Add default values for any valid keys that are empty.
        foreach ($valids as $key => $value) {
            if (!isset($hash[$key])) {
                $hash[$key] = $value;
            }
        }

        return $hash;
    }
    
    public static function assertValidTags(string $tag)
    {
        //var_dump($tag); die;
        if (!in_array($tag, self::$validTags)) {
            throw new AssertionFailedError(
              'Unknown tag: ' . $tag
            );
        }
    }        
}
