<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysql_connect('localhost:3306', 'root', '');
mysql_select_db('achecker_test', $lang_db);

$term=trim('AC_HELP_EDIT_CHECK_FUNCTION');

$sql = "DELETE FROM `AC_language_text` WHERE term='".$term."'";
$result = mysql_query($sql, $lang_db) or die(mysql_error());

// NOTE: MODIFY THIS 2 VARS

$text = '<h2>Edit Check Function</h2>
<p>This page is to edit the check function. Check function is the code performed to validate the check. AChecker provides class BasicFunctions to be called in here. Please note that none of php functions or super global variables can be used here.</p>
<h3>class BasicFunctions API</h3>

<table class="data" rules="all">
<tbody>
<tr>
  <th align="left"><a href="#f_associatedLabelHasText">associatedLabelHasText()</a></th>
  <td>Find whether each label associated with the element contains text.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getAttributeTrimedValueLength">getAttributeTrimedValueLength($attr)</a></th>
  <td>Return the length of the trimmed value of the attribute <code>$attr</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getAttributeValue">getAttributeValue($attr)</a></th>
  <td>Return the trimmed value of the attribute <code>$attr</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getAttributeValueAsNumber">getAttributeValueAsNumber($attr)</a></th>
  <td>Return the trimmed value of the attribute <code>$attr</code> in number.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getAttributeValueInLowerCase">getAttributeValueInLowerCase($attr)</a></th>
  <td>Return the trimmed value of the attribute <code>$attr</code> in lower case.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getAttributeValueLength">getAttributeValueLength($attr)</a></th>
  <td>Return the length of the attribute <code>$attr</code> value. The value is NOT trimmed.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getFirstChildTag">getFirstChildTag()</a></th>
  <td>Return the html tag of the first child in the element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getImageWidthAndHeight">getImageWidthAndHeight($attr)</a></th>
  <td>Return an array with 2 elements. Index 0 and 1 contains respectively the width and the height of the image.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getInnerText">getInnerText()</a></th>
  <td>Return the inner text contained in the element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getInnerTextLength">getInnerTextLength()</a></th>
  <td>Return the length of the inner text contained in the element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLangCode">getLangCode()</a></th>
  <td>Return the language code defined in "html" element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLast4CharsFromAttributeValue">getLast4CharsFromAttributeValue($attr)</a></th>
  <td>Return last 4 characters of trimmed value from attribute <code>$attr</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLengthOfAttributeValueWithGivenTagInChildren">getLengthOfAttributeValueWithGivenTagInChildren($tag, $attr)</a></th>
  <td>Return the length of trimmed value in the attribute <code>$attr</code> where the first html tag <code>$tag</code> appears.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLowerCaseAttributeValueWithGivenTagInChildren">getLowerCaseAttributeValueWithGivenTagInChildren($tag, $attr)</a></th>
  <td>Return the trimmed value of the attribute <code>$attr</code> where the first html tag <code>$tag</code> appears. The returned value is in lower case.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLowerCasePlainTextWithGivenTagInChildren">getLowerCasePlainTextWithGivenTagInChildren($tag)</a></th>
  <td>Search through all children, return the trimmed inner text enclosed in the children tag <code>$tag</code>. The returned value is in plain text without any html tags.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getLuminosityContrastRatio">getLuminosityContrastRatio($color1, $color2)</a></th>
  <td>Return luminosity contrast ratio between <code>$color1</code> and <code>$color2</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNextSiblingAttributeValueInLowerCase">getNextSiblingAttributeValueInLowerCase($attr)</a></th>
  <td>Return the value of the attribute <code>$attr</code> in the next sibling element. The returned value is in lower case.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNextSiblingInnerText">getNextSiblingInnerText()</a></th>
  <td>Return the inner text of the next sibling element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNextSiblingTag">getNextSiblingTag()</a></th>
  <td>Return the html tag of the next sibling html tag.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNumOfTagInChildren">getNumOfTagInChildren($tag)</a></th>
  <td>Return the total number of times that the html tag <code>$tag</code> appears in children elements.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNumOfTagInChildrenWithInnerText">getNumOfTagInChildrenWithInnerText($tag)</a></th>
  <td>Return the total number of times that the html tag <code>$tag</code> appears in the children elements and the inner text has content.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNumOfTagInWholeContent">getNumOfTagInWholeContent($tag)</a></th>
  <td>Return the total number of times that the html tag <code>$tag</code> appears in the whole html content.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getNumOfTagRecursiveInChildren">getNumOfTagRecursiveInChildren($tag)</a></th>
  <td>Recursively scan through of all the children and return the number of times that the html tag <code>$tag</code> appears in all children.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getParentHTMLTag">getParentHTMLTag()</a></th>
  <td>Return the html tag of the parent element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getPlainTextInLowerCase">getPlainTextInLowerCase()</a></th>
  <td>Return the trimmed inner text of the element. The returned value is in plain text without any html tags.</td>
<tr>
  <th align="left"><a href="#f_getPlainTextLength">getPlainTextLength()</a></th>
  <td>Return the length of the trimmed plain text of the element. The plain text is the inner text without any html tags.</td>
</tr>
<tr>
  <th align="left"><a href="#f_getSubstring">getSubstring($string, $start, $length)</a></th>
  <td>Returns the portion of <code>$string</code> specified by the <code>$start</code> and <code>$length</code> parameters. </td>
</tr>
<tr>
  <th align="left"><a href="#f_hasAssociatedLabel">hasAssociatedLabel()</a></th>
  <td>Return true if the element has associated label. Otherwise, return false.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasAttribute">hasAttribute($attr)</a></th>
  <td>Return true if the element has attribute <code>$attr</code>. Otherwise, return false.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasDuplicateAttribute">hasDuplicateAttribute($attr)</a></th>
  <td>Recursively search all the children of the element. Return true if the same attribute <code>$attr</code> value appears more than once in children elements. Otherwise, return false.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasFieldsetOnMultiCheckbox">hasFieldsetOnMultiCheckbox()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasGoodContrastWaiert">hasGoodContrastWaiert($color1, $color2)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasIdHeaders">hasIdHeaders()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasParent">hasParent($parent_tag)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasScope">hasScope()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTagInChildren">hasTagInChildren($tag)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextInBtw">hasTextInBtw()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextInChild">hasTextInChild($childTag, $childAttribute, $valueArray)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextLinkEquivalents">hasTextLinkEquivalents($attr)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_htmlValidated">htmlValidated()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isAttributeValueInSearchString">isAttributeValueInSearchString($attr)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isDataTable">isDataTable()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isFileExists">isFileExists($attr)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isInnerTextInSearchString">isInnerTextInSearchString()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isNextTagNotIn">isNextTagNotIn($notInArray)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isPlainTextInSearchString">isPlainTextInSearchString()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isRadioButtonsGrouped">isRadioButtonsGrouped()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isSubmitLabelDifferent">isSubmitLabelDifferent()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isTextMarked">isTextMarked($htmlTagArray)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isValidLangCode">isValidLangCode($attr)</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_isValidRTL">isValidRTL()</a></th>
  <td></td>
</tr>
<tr>
  <th align="left"><a href="#f_validateDoctype">validateDoctype()</a></th>
  <td></td>
</tr>
</tbody>
</table>

<div>
<ul>

<li>
<p><a name="f_associatedLabelHasText">associatedLabelHasText(<code>str</code>)</a></p>
<p>Returns true if each label associated with the element contains text. Otherwise, return false.</p>
<p>The label associated with the element is considered as containing text when one or more of the following methods are satisfied:
<ol>
  <li>The element has an <code>id</code> attribute value that matches the <code>for</code> attribute value of a <code>label</code> element.</li>
  <li>The element has a <code>title</code> attribute.</li>
  <li>The element is contained by a label element and the label contains text.</li>
</ol>
</p>
</li>

<li>
<p><a name="f_getAttributeTrimedValueLength">getAttributeTrimedValueLength(<code>attr</code>)</a></p>
<p>Return the length of the trimmed value of the attribute <code>attr</code>.</p>
<pre>
An <code>input</code> element: <br/>'.htmlspecialchars('<input id=" radioa " type="radio" name="aradio" tabindex="1" />').'<br/>
BasicFunctions::getAttributeTrimedValueLength("id") returns 6.
</pre>
</li>

<li>
<p><a name="f_getAttributeValue">getAttributeValue(<code>attr</code>)</a></p>
<p>Return the trimmed value of the attribute <code>attr</code>.</p>
<pre>
An <code>input</code> element: <br/>'.htmlspecialchars('<input id=" radioa " type="radio" name="aradio" tabindex="1" />').'<br/>
BasicFunctions::getAttributeValue("id") returns "radioa".
</pre>
</li>

<li>
<p><a name="f_getAttributeValueAsNumber">getAttributeValueAsNumber(<code>attr</code>)</a></p>
<p>Return the trimmed value of the attribute <code>attr</code> in number.</p>
<pre>
An <code>input</code> element: <br/>'.htmlspecialchars('<input id=" radioa " type="radio" name="aradio" tabindex="1" />').'<br/>
BasicFunctions::getAttributeValueAsNumber("tabindex") returns 1.
</pre>
</li>

<li>
<p><a name="f_getAttributeValueInLowerCase">getAttributeValueInLowerCase(<code>attr</code>)</a></p>
<p>Return the trimmed value of the attribute <code>attr</code> in lower case.</p>
<pre>
An <code>input</code> element: <br/>'.htmlspecialchars('<input id=" RADIOA " type="radio" name="aradio" tabindex="1" />').'<br/>
BasicFunctions::getAttributeValueInLowerCase("id") returns "radioa".
</pre>
</li>

<li>
<p><a name="f_getAttributeValueLength">getAttributeValueLength(<code>attr</code>)</a></p>
<p>Return the length of the attribute <code>$attr</code> value. The value is NOT trimmed.</p>
<pre>
An <code>input</code> element: <br/>'.htmlspecialchars('<input id=" RADIOA " type="radio" name="aradio" tabindex="1" />').'<br/>
BasicFunctions::getAttributeValueLength("id") returns 8.
</pre>
</li>

<li>
<p><a name="f_getFirstChildTag">getFirstChildTag()</a></p>
<p>Return the html tag of the first child in the element.</p>
<pre>
An <code>table</code> element: <br/>'.htmlspecialchars('<table border="0" cellpadding="5"><caption>Latin And English Text</caption>').'<br/>
BasicFunctions::getFirstChildTag() returns "caption".
</pre>
</li>

<li>
<p><a name="f_getImageWidthAndHeight">getImageWidthAndHeight(<code>attr</code>)</a></p>
<p>Return an array with 2 elements. Index 0 and 1 contains respectively the width and the height of the image.</p>
<pre>
An <code>img</code> element: <br/>'.htmlspecialchars('<img src="chart.gif" alt="a complex chart" />').'<br/>
list($width, $height) = BasicFunctions::getImageWidthAndHeight("src");<br/>
$width and $height contains the width and the height of the image "chart.gif".
</pre>
</li>

<li>
<p><a name="f_getInnerText">getInnerText()</a></p>
<p>Return the inner text contained in the element.</p>
<pre>
An <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs.html">dogs</a>').'<br/>
BasicFunctions::getInnerText() returns "dogs".
</pre>
</li>

<li>
<p><a name="f_getInnerTextLength">getInnerTextLength()</a></p>
<p>Return the length of the inner text contained in the element.</p>
<pre>
An <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs.html">dogs</a>').'<br/>
BasicFunctions::getInnerTextLength() returns 4.
</pre>
</li>

<li>
<p><a name="f_getLangCode">getLangCode()</a></p>
<p>Return the value of attribute "xml:lang" specified in "html" tag. If "xml:lang" is not set, return the value of attribute "lang".</p>
<pre>
An <code>html</code> element: <br/>'.htmlspecialchars('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">').'<br/>
BasicFunctions::getLangCode() returns "en".
</pre>
</li>

<li>
<p><a name="f_getLast4CharsFromAttributeValue">getLast4CharsFromAttributeValue()</a></p>
<p>Return last 4 characters of trimmed value from attribute <code>$attr</code>.</p>
<pre>
An <code>img</code> element: <br/>'.htmlspecialchars('<img src="rex.gif" alt="A brown and black cat named Rex."/>').'<br/>
BasicFunctions::getLast4CharsFromAttributeValue("src") returns ".gif".
</pre>
</li>

<li>
<p><a name="f_getLengthOfAttributeValueWithGivenTagInChildren">getLengthOfAttributeValueWithGivenTagInChildren(<code>tag</code>, <code>attr</code>)</a></p>
<p>Return the length of trimmed value in the attribute <code>attr</code> where the first html tag <code>tag</code> appears.</p>
<pre>
An <code>img</code> element: <br/>'.htmlspecialchars('<p>Select link for more <a href="dogs.html">information about dogs</a>.</p>').'<br/>
BasicFunctions::getLengthOfAttributeValueWithGivenTagInChildren("a", "src") returns 9.
</pre>
</li>

<li>
<p><a name="f_getLowerCaseAttributeValueWithGivenTagInChildren">getLowerCaseAttributeValueWithGivenTagInChildren(<code>tag</code>, <code>attr</code>)</a></p>
<p>Return the trimmed value of the attribute <code>$attr</code> where the first html tag <code>$tag</code> appears. The returned value is in lower case.</p>
<pre>
An <code>img</code> element: <br/>'.htmlspecialchars('<p>Select link for more <a href="dogs.html">information about dogs</a>.</p>').'<br/>
BasicFunctions::getLowerCaseAttributeValueWithGivenTagInChildren("a", "src") returns "information about dogs".
</pre>
</li>

<li>
<p><a name="f_getLowerCasePlainTextWithGivenTagInChildren">getLowerCasePlainTextWithGivenTagInChildren(<code>tag</code>)</a></p>
<p>Search through all children, return the trimmed inner text enclosed in the children tag <code>tag</code>. The returned value is in plain text without any html tags.</p>
<pre>
An <code>table</code> element: <br/>'.htmlspecialchars('<table><caption>This is attribute <code>table</code>.</caption></table>').'<br/>
BasicFunctions::getLowerCasePlainTextWithGivenTagInChildren("caption") returns "This is attribute table.". The html tag <code> is removed.
</pre>
</li>

<li>
<p><a name="f_getLuminosityContrastRatio">getLuminosityContrastRatio(<code>color1</code>, <code>color2</code>)</a></p>
<p>Return luminosity contrast ratio between <code>color1</code> and <code>color2</code>. Color value can be one of: rgb(x,x,x), #xxxxxx, colorname.</p>
</li>

<li>
<p><a name="f_getNextSiblingAttributeValueInLowerCase">getNextSiblingAttributeValueInLowerCase(<code>attr</code>)</a></p>
<p>Return the value of the attribute <code>attr</code> in the next sibling element. The returned value is in lower case.</p>
<pre>
Perform on the first <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs.html">dogs</a><a href="Cats.html">Products For Cats</a>').'<br/>
BasicFunctions::getNextSiblingAttributeValueInLowerCase("href") returns "cats.html".
</pre>
</li>

<li>
<p><a name="f_getNextSiblingInnerText">getNextSiblingInnerText()</a></p>
<p>Return the inner text of the next sibling element.</p>
<pre>
Perform on the first <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs.html">dogs</a><a href="Cats.html">Products For Cats</a>').'<br/>
BasicFunctions::getNextSiblingInnerText() returns "Products For Cats".
</pre>
</li>

<li>
<p><a name="f_getNextSiblingTag">getNextSiblingTag()</a></p>
<p>Return the html tag of the next sibling element.</p>
<pre>
Perform on the first <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs.html">dogs</a><a href="Cats.html">Products For Cats</a>').'<br/>
BasicFunctions::getNextSiblingTag() returns "a".
</pre>
</li>

<li>
<p><a name="f_getNumOfTagInChildren">getNumOfTagInChildren(<code>tag</code>)</a></p>
<p>Return the total number of times that the html tag <code>tag</code> appears in children elements.</p>
<pre>
Perform on the <code>frameset</code> element: <br/>'.htmlspecialchars('<frameset longdesc="description.html">
	<frame />
	<frame />
	<frame />
	<frame />
	<noframe />
</frameset>').'<br/>
BasicFunctions::getNumOfTagInChildren("frame") returns 4.
</pre>
</li>

<li>
<p><a name="f_getNumOfTagInChildrenWithInnerText">getNumOfTagInChildrenWithInnerText(<code>tag<code>)</a></p>
<p>Return the total number of times that the html tag <code>tag</code> appears in the children elements and the inner text has content.</p>
<pre>
Perform on the <code>ol</code> element: <br/>'.htmlspecialchars('<ol>
<li>Item text 1</li>
<li>Item text 2</li>
<li></li>
</ol>').'<br/>
BasicFunctions::getNumOfTagInChildrenWithInnerText("li") returns 2.
</pre>
</li>

<li>
<p><a name="f_getNumOfTagInWholeContent">getNumOfTagInWholeContent(<code>tag</code>)</a></p>
<p>Return the total number of times that the html tag <code>tag</code> appears in the whole html content.</p>
<pre>
'.htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
<title>OAC Testfile - Check #29 - Negative</title>
</head>
<body>
</body>
</html>').'<br/>
BasicFunctions::getNumOfTagInWholeContent("doctype") returns 1.
</pre>
</li>

<li>
<p><a name="f_getNumOfTagRecursiveInChildren">getNumOfTagRecursiveInChildren(<code>tag<code>)</a></p>
<p>Recursively scan through of all the children and return the number of times that the html tag <code>tag</code> appears in all children.</p>
<pre>
Perform on the <code>form</code> element: <br/>'.htmlspecialchars('<form action="http://example.com/prog/someprog" method="post">
  <select name="ComOS">
    <optgroup label="PortMaster 3">
      <option label="3.7.1" value="pm3_3.7.1">
        PortMaster 3 with ComOS 3.7.1
      </option>
      <option label="3.7" value="pm3_3.7">
        PortMaster 3 with ComOS 3.7
      </option>
      <option label="3.5" value="pm3_3.5">
        PortMaster 3 with ComOS 3.5
      </option>
    </optgroup>
    <optgroup label="PortMaster 2">
      <option label="3.7" value="pm2_3.7">
        PortMaster 2 with ComOS 3.7
      </option>
      <option label="3.5" value="pm2_3.5">
        PortMaster 2 with ComOS 3.5
      </option>
    </optgroup>
  </select>
</form>').'<br/>
BasicFunctions::getNumOfTagRecursiveInChildren("optgroup") returns 2.
</pre>
</li>

<li>
<p><a name="f_getParentHTMLTag">getParentHTMLTag()</a></p>
<p>Return the html tag of the parent element.</p>
<pre>
Perform on the <code>img</code> element: <br/>'.htmlspecialchars('<a href="rex.html"><img src="rex.jpg" alt="a story about Rex the cat"/></a>').'<br/>
BasicFunctions::getParentHTMLTag() returns "a".
</pre>
</li>

<li>
<p><a name="f_getPlainTextInLowerCase">getPlainTextInLowerCase()</a></p>
<p>Return the trimmed inner text of the element. The returned value is in plain text without any html tags.</p>
<pre>
Perform on the <code>a</code> element: <br/>'.htmlspecialchars('<a href="page1.html"><img src="star.jpg" alt=""/>page1</a>
').'<br/>
BasicFunctions::getPlainTextInLowerCase() returns "page1".
</pre>
</li>

<li>
<p><a name="f_getPlainTextLength">getPlainTextLength()</a></p>
<p>Return the length of the trimmed plain text of the element. The plain text is the inner text without any html tags.</p>
<pre>
Perform on the <code>a</code> element: <br/>'.htmlspecialchars('<a href="page1.html"><img src="star.jpg" alt=""/>page1</a>
').'<br/>
BasicFunctions::getPlainTextLength() returns 5.
</pre>
</li>

<li>
<p><a name="f_getSubstring">getSubstring(<code>string</code>, <code>start</code>, <code>length</code>)</a></p>
<p>Returns the portion of <code>string</code> specified by the <code>start</code> and <code>length</code> parameters.</p>
<pre>
$rest = BasicFunctions::getSubstring("abcdef", -1);    // returns "f"
$rest = BasicFunctions::getSubstring("abcdef", -2);    // returns "ef"
$rest = BasicFunctions::getSubstring("abcdef", -3, 1); // returns "d"
</pre>
</li>

<li>
<p><a name="f_hasAssociatedLabel">hasAssociatedLabel()</a></p>
<p>Return true if the element has associated label. Otherwise, return false.</p>
<p>The element is considered as having associated label when one or more of the following methods are satisfied:
<ol>
  <li>The element has an <code>id</code> attribute value that matches the <code>for</code> attribute value of a <code>label</code> element.</li>
  <li>The element has a <code>title</code> attribute.</li>
  <li>The element is contained by a label element and the label contains text.</li>
</ol>
</p>
</li>

<li>
<p><a name="f_hasAttribute">hasAttribute(<code>attr</code>)</a></p>
<p>Return true if the element has attribute <code>$attr</code>. Otherwise, return false.</p>
<pre>
Perform on the <code>a</code> element: <br/>'.htmlspecialchars('<a href="page1.html"></a>').'<br/>
BasicFunctions::hasAttribute("href") returns true.
</pre>
</li>

<li>
<p><a name="f_hasDuplicateAttribute">hasDuplicateAttribute(<code>attr</code>)</a></p>
<p>Recursively search all the children of the element. Return true if the same attribute <code>$attr</code> value appears more than once in children elements. Otherwise, return false.</p>
<pre>
Perform on the <code>table</code> element: <br/>'.htmlspecialchars('<table border=1 summary="table #1 with IDs and HEADERS">
<tr>
 <th id="city">City</th>
 <th id="state">State</th>
</tr>
<tr>
 <td id="city">Phoenix</td>
 <td headers="state">Arizona</td>
</tr>
<tr>
 <td headers="city">Seattle</td>
 <td headers="state">Washington</td>
</tr>
</table>').'<br/>
BasicFunctions::hasDuplicateAttribute("id") returns true. Attribute <code>id</code> value "city" appears twice.
</pre>
</li>

</ul>
</div>
';

$sql = "INSERT INTO `AC_language_text` VALUES ('eng', '_msgs','".$term."','".mysql_real_escape_string($text)."',now(),'')";
print $sql;
$result = mysql_query($sql, $lang_db) or die(mysql_error());

?>