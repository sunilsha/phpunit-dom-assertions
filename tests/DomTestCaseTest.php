<?php
/*
 * This file is part of PHPUnit DOM Assertions.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\Tests;

use PHPUnit\Framework\DOMTestCase;

/**
 * @package    DOMTestCase
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @author     Jeff Welch <whatthejeff@gmail.com>
 * @copyright  Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/phpunit/phpunit-dom-assertions
 * @since      Class available since Release 1.0.0
 */
class DOMTestCaseTest extends DOMTestCase
{
    private $html;

    protected function setUp()
    {
        $this->html = file_get_contents(
            __DIR__ . '/_files/SelectorAssertionsFixture.html'
        );
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagTypeTrue()
    {
        $matcher = array('tag' => 'html');
        $this->assertTag($matcher, $this->html);
    }
    
    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagInput()
    {
        $tag = array(
            'tag' => 'input',
            'attributes' => array(
                'type' => 'text',
                'name' => 'test_hidden',
                'value' => 'test123',
                'id' => 'input_test_id',
            'class' => 'full half'
            ),
        );
        $this->assertTag($tag, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagTypeFalse()
    {
        $matcher = array('tag' => 'unexpected');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagIdTrue()
    {
        $matcher = array('id' => 'test_text');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagIdFalse()
    {
        $matcher = array('id' => 'test_text_doesnt_exist');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagStringContentTrue()
    {
        $matcher = array('id' => 'test_text',
            'content' => 'My test tag content');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagStringContentFalse()
    {
        $matcher = array('id' => 'test_text',
            'content' => 'My non existent tag content');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagRegexpContentTrue()
    {
        $matcher = array('id' => 'test_text',
            'content' => 'regexp:/test tag/');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagRegexpModifierContentTrue()
    {
        $matcher = array('id' => 'test_text',
            'content' => 'regexp:/TEST TAG/i');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagRegexpContentFalse()
    {
        $matcher = array('id' => 'test_text',
            'content' => 'regexp:/asdf/');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagCdataContentTrue()
    {
        $matcher = array('tag' => 'script',
            'content' => 'alert(\'Hello, world!\');');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagCdataontentFalse()
    {
        $matcher = array('tag' => 'script',
            'content' => 'asdf');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesTrueA()
    {
        $matcher = array('tag' => 'span',
            'attributes' => array('class' => 'test_class'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesTrueB()
    {
        $matcher = array('tag' => 'div',
            'attributes' => array('id' => 'test_child_id'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagAttributesFalse()
    {
        $matcher = array('tag' => 'span',
            'attributes' => array('class' => 'test_missing_class'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesRegexpTrueA()
    {
        $matcher = array('tag' => 'span',
            'attributes' => array('class' => 'regexp:/.+_class/'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesRegexpTrueB()
    {

        $matcher = array('tag' => 'div',
            'attributes' => array('id' => 'regexp:/.+_child_.+/'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesRegexpModifierTrue()
    {
        $matcher = array('tag' => 'div',
            'attributes' => array('id' => 'regexp:/.+_CHILD_.+/i'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagAttributesRegexpModifierFalse()
    {
        $matcher = array('tag' => 'div',
            'attributes' => array('id' => 'regexp:/.+_CHILD_.+/'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagAttributesRegexpFalse()
    {
        $matcher = array('tag' => 'span',
            'attributes' => array('class' => 'regexp:/.+_missing_.+/'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesMultiPartClassTrueA()
    {
        //$this->markTestIncomplete();
        $matcher = array('tag' => 'div',
            'id'  => 'test_multi_class',
            'attributes' => array('class' => 'multi class'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagAttributesMultiPartClassTrueB()
    {
        $matcher = array('tag' => 'div',
            'id'  => 'test_multi_class',
            'attributes' => array('class' => 'multi'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag
     */
    public function testAssertTagAttributesMultiPartClassFalse()
    {
        $matcher = array('tag' => 'div',
            'id'  => 'test_multi_class',
            'attributes' => array('class' => 'mul'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     * @group assertTag
     */
    public function testAssertTagParentTrue()
    {
        $matcher = array('tag' => 'head',
            'parent' => array('tag' => 'html'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     * @group assertTag2
     */
    public function testAssertTagParentFalse()
    {
        $matcher = array('tag' => 'head',
            'parent' => array('tag' => 'div'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagMultiplePossibleChildren()
    {

        $matcher = array(
            'tag' => 'li',
            'parent' => array(
                'tag' => 'ul',
                'id' => 'another_ul'
            )
        );
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagChildTrue()
    {

        $matcher = array('tag' => 'html',
            'child' => array('tag' => 'head'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagChildFalse()
    {

        $matcher = array('tag' => 'html',
            'child' => array('tag' => 'div'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagAdjacentSiblingTrue()
    {

        $matcher = array('tag' => 'img',
            'adjacent-sibling' => array('tag' => 'input'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagAdjacentSiblingFalse()
    {

        $matcher = array('tag' => 'img',
            'adjacent-sibling' => array('tag' => 'div'));
        $this->assertTag($matcher, $this->html);
    }


    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagAncestorTrue()
    {

        $matcher = array('tag' => 'div',
            'ancestor' => array('tag' => 'html'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagAncestorFalse()
    {

        $matcher = array('tag' => 'html',
            'ancestor' => array('tag' => 'div'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagDescendantTrue()
    {

        $matcher = array('tag' => 'html',
            'descendant' => array('tag' => 'div'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagDescendantFalse()
    {

        $matcher = array('tag' => 'div',
            'descendant' => array('tag' => 'html'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagChildrenCountTrue()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('count' => 3));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagChildrenCountFalse()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('count' => 5));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagChildrenLessThanTrue()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('less_than' => 10));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagChildrenLessThanFalse()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('less_than' => 2));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagChildrenGreaterThanTrue()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('greater_than' => 2));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagChildrenGreaterThanFalse()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('greater_than' => 10));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagChildrenOnlyTrue()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('only' => array('tag' =>'li')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagChildrenOnlyFalse()
    {

        $matcher = array('tag' => 'ul',
            'children' => array('only' => array('tag' =>'div')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagTypeIdTrueA()
    {

        $matcher = array('tag' => 'ul', 'id' => 'my_ul');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagTypeIdTrueB()
    {

        $matcher = array('id' => 'my_ul', 'tag' => 'ul');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagTypeIdTrueC()
    {

        $matcher = array('tag' => 'input', 'id'  => 'input_test_id');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers            \PHPUnit\Framework\DOMTestCase::assertTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertTagTypeIdFalse()
    {

        $matcher = array('tag' => 'div', 'id'  => 'my_ul');
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertTagContentAttributes()
    {

        $matcher = array('tag' => 'div',
            'content'    => 'Test Id Text',
            'attributes' => array('id' => 'test_id',
                'class' => 'my_test_class'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertParentContentAttributes()
    {

        $matcher = array('tag'        => 'div',
            'content'    => 'Test Id Text',
            'attributes' => array('id'    => 'test_id',
                'class' => 'my_test_class'),
            'parent'     => array('tag' => 'body'));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertChildContentAttributes()
    {

        $matcher = array('tag'        => 'div',
            'content'    => 'Test Id Text',
            'attributes' => array('id'    => 'test_id',
                'class' => 'my_test_class'),
            'child'      => array('tag'        => 'div',
                'attributes' => array('id' => 'test_child_id')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertAdjacentSiblingContentAttributes()
    {

        $matcher = array('tag'              => 'div',
            'content'          => 'Test Id Text',
            'attributes'       => array('id'    => 'test_id',
                'class' => 'my_test_class'),
            'adjacent-sibling' => array('tag'        => 'div',
                'attributes' => array('id' => 'test_children')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertChildSubChildren()
    {

        $matcher = array('id' => 'test_id',
            'child' => array('id' => 'test_child_id',
                'child' => array('id' => 'test_subchild_id')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertAdjacentSiblingSubAdjacentSibling()
    {

        $matcher = array('id' => 'test_id',
            'adjacent-sibling' => array('id' => 'test_children',
                'adjacent-sibling' => array('class' => 'test_class')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertAncestorContentAttributes()
    {

        $matcher = array('id'         => 'test_subchild_id',
            'content'    => 'My Subchild',
            'attributes' => array('id' => 'test_subchild_id'),
            'ancestor'   => array('tag'        => 'div',
                'attributes' => array('id' => 'test_id')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertDescendantContentAttributes()
    {

        $matcher = array('id'         => 'test_id',
            'content'    => 'Test Id Text',
            'attributes' => array('id'  => 'test_id'),
            'descendant' => array('tag'        => 'span',
                'attributes' => array('id' => 'test_subchild_id')));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertTag
     */
    public function testAssertChildrenContentAttributes()
    {

        $matcher = array('id'         => 'test_children',
            'content'    => 'My Children',
            'attributes' => array('class'  => 'children'),

            'children' => array('less_than'    => '25',
                'greater_than' => '2',
                'only'         => array('tag' => 'div',
                    'attributes' => array('class' => 'my_child'))
            ));
        $this->assertTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertNotTag
     */
    public function testAssertNotTagTypeIdFalse()
    {

        $matcher = array('tag' => 'div', 'id'  => 'my_ul');
        $this->assertNotTag($matcher, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertNotTag
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertNotTagContentAttributes()
    {

        $matcher = array('tag' => 'div',
            'content'    => 'Test Id Text',
            'attributes' => array('id' => 'test_id',
                'class' => 'my_test_class'));
        $this->assertNotTag($matcher, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountPresentTrue()
    {
        $selector = 'div#test_id';
        $count    = true;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountPresentFalse()
    {
        $selector = 'div#non_existent';
        $count    = true;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountNotPresentTrue()
    {
        $selector = 'div#non_existent';
        $count    = false;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectNotPresentFalse()
    {
        $selector = 'div#test_id';
        $count    = false;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountChildTrue()
    {
        $selector = '#my_ul > li';
        $count    = 3;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountChildFalse()
    {
        $selector = '#my_ul > li';
        $count    = 4;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountAdjacentSiblingTrue()
    {
        $selector = 'div + div + div';
        $count    = 2;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountAdjacentSiblingFalse()
    {
        $selector = '#test_children + div';
        $count    = 1;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountDescendantTrue()
    {
        $selector = '#my_ul li';
        $count    = 3;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountDescendantFalse()
    {
        $selector = '#my_ul li';
        $count    = 4;

        $this->assertSelectCount($selector, $count, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountGreaterThanTrue()
    {
        $selector = '#my_ul > li';
        $range    = array('>' => 2);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountGreaterThanFalse()
    {
        $selector = '#my_ul > li';
        $range    = array('>' => 3);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountGreaterThanEqualToTrue()
    {
        $selector = '#my_ul > li';
        $range    = array('>=' => 3);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountGreaterThanEqualToFalse()
    {
        $selector = '#my_ul > li';
        $range    = array('>=' => 4);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountLessThanTrue()
    {
        $selector = '#my_ul > li';
        $range    = array('<' => 4);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountLessThanFalse()
    {
        $selector = '#my_ul > li';
        $range    = array('<' => 3);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountLessThanEqualToTrue()
    {
        $selector = '#my_ul > li';
        $range    = array('<=' => 3);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountLessThanEqualToFalse()
    {
        $selector = '#my_ul > li';
        $range  = array('<=' => 2);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectCount
     */
    public function testAssertSelectCountRangeTrue()
    {
        $selector = '#my_ul > li';
        $range    = array('>' => 2, '<' => 4);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectCount
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectCountRangeFalse()
    {
        $selector = '#my_ul > li';
        $range    = array('>' => 1, '<' => 3);

        $this->assertSelectCount($selector, $range, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectEquals
     */
    public function testAssertSelectEqualsContentPresentTrue()
    {
        $selector = 'span.test_class';
        $content  = 'Test Class Text';

        $this->assertSelectEquals($selector, $content, true, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectEquals
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectEqualsContentPresentFalse()
    {
        $selector = 'span.test_class';
        $content  = 'Test Nonexistent';

        $this->assertSelectEquals($selector, $content, true, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectEquals
     */
    public function testAssertSelectEqualsContentNotPresentTrue()
    {
        $selector = 'span.test_class';
        $content  = 'Test Nonexistent';

        $this->assertSelectEquals($selector, $content, false, $this->html);
    }

    /**
     * @covers            PHPUnit\Framework\DOMTestCase::assertSelectEquals
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertSelectEqualsContentNotPresentFalse()
    {
        $selector = 'span.test_class';
        $content  = 'Test Class Text';

        $this->assertSelectEquals($selector, $content, false, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectRegExp
     */
    public function testAssertSelectRegExpContentPresentTrue()
    {
        $selector = 'span.test_class';
        $regexp   = '/Test.*Text/';

        $this->assertSelectRegExp($selector, $regexp, true, $this->html);
    }

    /**
     * @covers \PHPUnit\Framework\DOMTestCase::assertSelectRegExp
     */
    public function testAssertSelectRegExpContentPresentFalse()
    {
        $selector = 'span.test_class';
        $regexp   = '/Nonexistant/';

        $this->assertSelectRegExp($selector, $regexp, false, $this->html);
    }

    public function testDOMDocument()
    {
        $dom = new \DOMDocument();
        $this->assertSelectEquals(false, false, false, $dom);
    }
}
