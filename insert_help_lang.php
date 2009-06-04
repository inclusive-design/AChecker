<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysql_connect('localhost:3306', 'root', '');
mysql_select_db('achecker', $lang_db);

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
  <td>Find whether the element has associated label.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasAttribute">hasAttribute($attr)</a></th>
  <td>Find whether the element has attribute <code>$attr</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasDuplicateAttribute">hasDuplicateAttribute($attr)</a></th>
  <td>Recursively search all the children elements. Find whether the attribute <code>$attr</code> value appears more than once in children elements.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasFieldsetOnMultiCheckbox">hasFieldsetOnMultiCheckbox()</a></th>
  <td>Only perform on <code>form</code> element. Recursively search all its elements. Find whether all multiple checkbox buttons are grouped in "fieldset" and "legend" elements.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasGoodContrastWaiert">hasGoodContrastWaiert($color1, $color2)</a></th>
  <td>Find whether the luminosity contrast ratio between <code>$color1</code> and <code>$color2</code> is at least 5:1.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasIdHeaders">hasIdHeaders()</a></th>
  <td>Only performs on <code>table</code> element. Find whether the <code>table</code> element contains more than one header row or header column.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasLinkChildWithText">hasLinkChildWithText($searchStrArray)</a></th>
  <td>Search all the children elements. Find whether there is a <code>a</code> element with the value of attribute <code>href</code> matches one of the string in array <code>$searchStrArray</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasParent">hasParent($parent_tag)</a></th>
  <td>Recursively search all the parent elements. Find whether there is a parent element with tag <code>$parent_tag</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasScope">hasScope()</a></th>
  <td>Only performs on <code>table</code> element. Find whether the <code>table</code> element contains both row and column headers and the header cells contain a <code>scope</code> attribute that identifies the cells that relate to the header.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTagInChildren">hasTagInChildren($tag)</a></th>
  <td>Find whether the child element with html tag <code>$tag</code> exists.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextInBtw">hasTextInBtw()</a></th>
  <td>Find whether there is text in between <code>a</code> element. Only perform on <code>a</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextInChild">hasTextInChild($childTag, $childAttribute, $valueArray)</a></th>
  <td>Find whether there is a child element with tag named <code>$childTag</code>, in which the value of attribute <code>$childAttribute</code> equals one of the values in array <code>$valueArray</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasTextLinkEquivalents">hasTextLinkEquivalents($attr)</a></th>
  <td>Only performs when <code>usemap</code> attribute in <code>img</code> element is on. Find whether there is a <code>map</code> element referred by the <code>usemap</code> attribute and each <code>area</code> element in the <code>map</code> contains a duplicate link in the document.</td>
</tr>
<tr>
  <th align="left"><a href="#f_hasWindowOpenInScript">hasWindowOpenInScript()</a></th>
  <td>Find whether window.open is contained in <code>script</code> element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_htmlValidated">htmlValidated()</a></th>
  <td>Find whether the html markup language in the content is validated.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isAttributeValueInSearchString">isAttributeValueInSearchString($attr)</a></th>
  <td>Find whether the attribute <code>$attr</code> value matches one of the search string criteria.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isDataTable">isDataTable()</a></th>
  <td>Find whether the table defined in <code>table</code> element is a data table. Only performs on <code>table</code> element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isFileExists">isFileExists($attr)</a></th>
  <td>Find whether the file defined as attribute <code>$attr</code> exists.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isInnerTextInSearchString">isInnerTextInSearchString()</a></th>
  <td>Find whether the inner text matches one of the search string criteria.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isNextTagNotIn">isNextTagNotIn($notInArray)</a></th>
  <td>Find whether the next header tag equals one of the values in <code>$notInArray</code>. Only performs on header tags: <code>h1</code>, <code>h2</code>, <code>h3</code>, <code>h4</code>, <code>h5</code>, <code>h6</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isPlainTextInSearchString">isPlainTextInSearchString()</a></th>
  <td>Find whether the plain text matches one of the search string criteria.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isRadioButtonsGrouped">isRadioButtonsGrouped()</a></th>
  <td>Find whether radio button groups are marked with <code>fieldset</code> and <code>legend</code> elements.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isSubmitLabelDifferent">isSubmitLabelDifferent()</a></th>
  <td>Find whether the labels for all the submit buttons on the form are different.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isTextMarked">isTextMarked($htmlTagArray)</a></th>
  <td>Find whether the element content is marked with the html tags defined in <code>$htmlTagArray</code>.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isValidLangCode">isValidLangCode()</a></th>
  <td>Find whether the language codes specified in <code>html</code> are valid.</td>
</tr>
<tr>
  <th align="left"><a href="#f_isValidRTL">isValidRTL()</a></th>
  <td>Find whether the <code>dir</code> attribute value has correct "rtl" or "ltr" value matching with the language code. Only perfoms on <code>html</code> element.</td>
</tr>
<tr>
  <th align="left"><a href="#f_validateDoctype">validateDoctype()</a></th>
  <td>Find whether <code>!DOCTYPE</code> content is valid. Only performs on <code>html</code> element.</td>
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
<p>Recursively search all the children elements. Return true if the same attribute <code>$attr</code> value appears more than once in children elements. Otherwise, return false.</p>
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

<li>
<p><a name="f_hasFieldsetOnMultiCheckbox">hasFieldsetOnMultiCheckbox()</a></p>
<p>Only perform on <code>form</code> element. Recursively search all its elements. Return true if all multiple checkbox buttons are grouped in "fieldset" and "legend" elements. Otherwise, return false.</p>
<pre>
Perform on the <code>table</code> element: <br/>'.htmlspecialchars('<form action="http://example.com/donut" method="post">
<fieldset>
<legend>Donuts Requested (check all that apply)</legend>
<p>
<input type="checkbox" name="flavour" id="choc" value="chocolate" />
<label for="choc">Chocolate</label><br/>
<input type="checkbox" name="flavour" id="cream" value="cream" />
<label for="cream">Cream Filled</label><br/>
<input type="checkbox" name="flavour" id="honey" value="honey" />
<label for="honey">Honey Glazed</label>
</p>
</fieldset>
<p><input type="submit" value="Purchase Donuts"/></p>
</form>').'<br/>
BasicFunctions::hasFieldsetOnMultiCheckbox() returns true. 
</pre>
</li>

<li>
<p><a name="f_hasGoodContrastWaiert">hasGoodContrastWaiert(<code>color1</code>, <code>color2</code>)</a></p>
<p>Return true if the luminosity contrast ratio between <code>color1</code> and <code>color2</code> is at least 5:1. Color value can be one of: rgb(x,x,x), #xxxxxx, colorname.</p>
</li>
  
<li>
<p><a name="f_hasIdHeaders">hasIdHeaders()</a></p>
<p>Only performs on <code>table</code> element. Return true if the <code>table</code> element contains more than one header row or header column.</p>
<pre>
Perform on the <code>table</code> element: <br/>'.htmlspecialchars('<table border="1" >
<tr>
	<th id="class">Class</th>
	<th id="teacher">Teacher</th>
	<th id="males">Males</th>
	<th id="females">Females</th>
</tr>
<tr>
	<th id="firstyear" rowspan="2">First Year</th>
	<th id="Bolter" headers="firstyear teacher">D. Bolter</th>
	<td headers="firstyear Bolter males">5</td>
	<td headers="firstyear Bolter females">4</td>
</tr>
<tr>
	<th id="Cheetham" headers="firstyear teacher">A. Cheetham</th>
	<td headers="firstyear Cheetham males">7</td>
	<td headers="firstyear Cheetham females">9</td>
</tr>
</table>').'<br/>
BasicFunctions::hasIdHeaders() returns true. 
</pre>
</li>
 
<li>
<p><a name="f_hasLinkChildWithText">hasLinkChildWithText(<code>searchStrArray</code>)</a></p>
<p>Search all the children elements. Return true if there is a <code>a</code> element with the value of attribute <code>href</code> matches one of the string in array <code>$searchStrArray</code>. Otherwise, return false.</p>
<p>
The element in array searchStrArray can be one of these values:<br/>
e.g. title, %title1, title2%, %title3%<br/>
"%" can be used in front and at the end of a string to match any characters.<br/>
As an explanation, "title" matches exact string of "title".<br/>
"%title1" matches any string ending with "title1".<br/>
"title2%" matches any string starting with "title2".<br/>
"%title3%" matches any string containing string "title3". 
</p>
<pre>
Perform on the <code>body</code> element: <br/>'.htmlspecialchars('<body>
<a href="#content">Go To Content</a>
<a name="cnotent"></a>This is cnotent.
</body>').'<br/>
return BasicFunctions::hasLinkChildWithText(array("%jump%","%go to%","%skip%","%navigation%","%content%")) returns true. 
</pre>
</li>

<li>
<p><a name="f_hasParent">hasParent(<code>parent_tag</code>)</a></p>
<p>Recursively search all the parent elements. Return true if there is a parent element with tag <code>parent_tag</code>. Otherwise, return false.</p>
<pre>
Perform on the <code>script</code> element: <br/>'.htmlspecialchars('<body>
<script>
</script>
<noscript>Alternate content for script</noscript>
</body>').'<br/>
BasicFunctions::hasParent("body") returns true. 
</pre>
</li>

<li>
<p><a name="f_hasScope">hasScope()</a></p>
<p>Only performs on <code>table</code> element. Return true if the <code>table</code> element contains both row and column headers and the header cells contain a <code>scope</code> attribute that identifies the cells that relate to the header.</p>
<pre>
Perform on the <code>table</code> element: <br/>'.htmlspecialchars('<table border="1">
<tr><th scope="col">Name</th><th scope="col">Birth</th><th scope="col">Gender</th></tr>
<tr><th scope="row">Clayton</th><td>2005-10-10</td><td>male</td></tr>
<tr><th scope="row">Carol</th><td>2005-10-11</td><td>female</td></tr>
<tr><th scope="row">Susan</th><td>2005-10-12</td><td>female</td></tr>
<tr><th scope="row">Oleg</th><td>2005-10-13</td><td>male</td></tr>
<tr><th scope="row">Belnar</th><td>2005-10-14</td><td>male</td></tr>
</table>').'<br/>
BasicFunctions::hasScope() returns true. 
</pre>
</li>
  
<li>
<p><a name="f_hasTagInChildren">hasTagInChildren(<code>tag</code>)</a></p>
<p>Return true if the <code>tag</code> is found in children elements. Otherwise, return false.</p>
<pre>
Perform on the <code>body</code> element: <br/>'.htmlspecialchars('<body>
<address><a href="mailto:name@company.com">joe smith</a></address>
</body>').'<br/>
BasicFunctions::hasTagInChildren("address") returns true. 
</pre>
</li>
  
<li>
<p><a name="f_hasTextInBtw">hasTextInBtw()</a></p>
<p>Return true if there is text in between <code>a</code> element. Otherwise, return false. Only perform on <code>a</code>.</p>
<pre>
Perform on the first <code>a</code> element: <br/>'.htmlspecialchars('<a href="dogs">dogs</a> | <a href="cats">cats</a>').'<br/>
BasicFunctions::hasTextInBtw() returns true. 
</pre>
</li>
  
<li>
<p><a name="f_hasTextInChild">hasTextInChild(<code>childTag</code>, <code>childAttribute</code>, <code>valueArray</code>)</a></p>
<p>Return true if there is a child element with tag named <code>childTag</code>, in which the value of attribute <code>childAttribute</code> equals one of the values in array <code>valueArray</code>. Otherwise, return false.</p>
<pre>
Perform on the <code>head</code> element: <br/>'.htmlspecialchars('<head>
<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
<title>ATRC Testfile - Check #147.2 - Negative</title>
<link rel="Index" href="../index.html" />
</head>').'<br/>
BasicFunctions::hasTextInChild("link", "rel", array("index")) returns true. 
</pre>
</li>

<li>
<p><a name="f_hasTextLinkEquivalents">hasTextLinkEquivalents(<code>attr</code>)</a></p>
<p>Only performs when <code>usemap</code> attribute in <code>img</code> element is on. Return true if there is a <code>map</code> element referred by the <code>usemap</code> attribute and each <code>area</code> element in the <code>map</code> contains a duplicate link in the document. Otherwise, return false.</p>
<pre>
Perform on the <code>img</code> element: <br/>'.htmlspecialchars('<p><map name="imagemap" id="map1">
<area shape="poly" coords="185,0,355,0,295,123" href="horses.html" alt="horses"/>
<area shape="poly" coords="336,202,549,203,549" href="dogs.html" alt="dogs"/>
<area shape="rect" coords="0,10,172,10" href="birds.html" alt="birds"/>
</map></p>

<p><img src="navigation.gif" usemap="#imagemap" alt="navigation"/></p>').'<br/>
BasicFunctions::hasTextLinkEquivalents("usemap") returns true. 
</pre>
</li>
  
<li>
<p><a name="f_hasWindowOpenInScript">hasWindowOpenInScript()</a></p>
<p>Return true if window.open is contained in <code>script</code> element. Otherwise, return false.</p>
<pre>
Perform on the whole html content: <br/>'.
htmlspecialchars('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
<title>ATRC Testfile - Check #275.1 - Positive</title>
<script>
window.onload = showAdvertisement;
function showAdvertisement()
{
	window.open("275-2.html", "_blank", "height=200,width=150");
}
</script>

</head>

<body>
<p>This page will open a new window upon loading as long as scripting is enabled.</p>
</body>
</html>').'<br/>
BasicFunctions::hasWindowOpenInScript() returns true. 
</pre>
</li>
  
<li>
<p><a name="f_htmlValidated">htmlValidated()</a></p>
<p>Return true if the third party HTML markup language validator is turned on. Otherwise, return false.</p>
</li>

<li>
<p><a name="f_isAttributeValueInSearchString">isAttributeValueInSearchString(<code>attr</code>)</a></p>
<p>Return true if the attribute <code>$attr</code> value matches one of the search string criteria. Otherwise, return false. Search string criteria is defined via "Create/Edit Check" page, "Search String" field.</p>
<pre>
"Search String" field on "Create/Edit Check" page is defined as "image, photo, %bytes%".<br/><br/>Perform on the <code>input</code> element: <br/>'.
htmlspecialchars('<input type="image" name="name" id="name" src="input.jpg" alt="image">').'<br/>
BasicFunctions::isAttributeValueInSearchString("alt") returns true. 
</pre>
</li>
  
<li>
<p><a name="f_isDataTable">isDataTable()</a></p>
<p>Return true if the table defined in <code>table</code> element is a data table. Otherwise, return false. Only performs on <code>table</code> element.<br/><br/> A <code>table</code> contains <code>th</code> element is considered as a data table.</p>
<pre>
Perform on the <code>table</code> element: <br/>'.
htmlspecialchars('<table>

<tr><th>name</th><th>number of cups</th><th>type</th><th>with sugar</th></tr>
<tr><td>Adams, Willie</td><td>2</td><td>regular</td><td>sugar</td></tr>
<tr><td>Bacon, Lise</td><td>4</td><td>regular</td><td>no sugar</td></tr>

</table>').'<br/>
BasicFunctions::isDataTable() returns true. 
</pre>
</li>

<li>
<p><a name="f_isFileExists">isFileExists(<coe>attr</code>)</a></p>
<p>Return true if the file defined as attribute <code>$attr</code> exists. Otherwise, return false.</p>
<pre>
Perform on the <code>img</code> element: <br/>'.
htmlspecialchars('<img src="chart.gif" alt="a complex chart" />').'<br/>
BasicFunctions::isFileExists("src") returns false. 
</pre>
</li>

<li>
<p><a name="f_isInnerTextInSearchString">isInnerTextInSearchString(<code>attr</code>)</a></p>
<p>Return true if the inner text matches one of the search string criteria. Otherwise, return false. Search string criteria is defined via "Create/Edit Check" page, "Search String" field.</p>
<pre>
"Search String" field on "Create/Edit Check" page is defined as "title, the title, this is the title, untitled document".<br/><br/>Perform on the <code>title</code> element: <br/>'.
htmlspecialchars('<title>title</title>').'<br/>
BasicFunctions::isInnerTextInSearchString() returns true. 
</pre>
</li>
  
<li>
<p><a name="f_isNextTagNotIn">isNextTagNotIn(<code>notInArray</code>)</a></p>
<p>Return true if the next header tag equals one of the values in <code>notInArray</code>. Otherwise, return false. Only performs on header tags: <code>h1</code>, <code>h2</code>, <code>h3</code>, <code>h4</code>, <code>h5</code>, <code>h6</code>.</p>
<pre>
Perform on the <code>h1</code> element: <br/>'.
htmlspecialchars('<h1>The First Heading</h1>
<p>Here is some demo text.</p>
<div><div><h3>The bad Heading</h3></div></div>
<p>Here is some more demo text.</p>').'<br/>
BasicFunctions::isNextTagNotIn(array("h1", "h2")) returns true. 
</pre>
</li>
  
<li>
<p><a name="f_isPlainTextInSearchString">isPlainTextInSearchString()</a></p>
<p>Return true if the plain text matches one of the search string criteria. Otherwise, return false. Search string criteria is defined via "Create/Edit Check" page, "Search String" field.</p>
<pre>
"Search String" field on "Create/Edit Check" page is defined as "click here, more".<br/><br/>Perform on the <code>a</code> element: <br/>'.
htmlspecialchars('<a href="dogs.html">click <br/>here</a>').'<br/>
BasicFunctions::isPlainTextInSearchString() returns true. 
</pre>
</li>
  
<li>
<p><a name="f_isRadioButtonsGrouped">isRadioButtonsGrouped()</a></p>
<p>Return true if radio button groups are marked with <code>fieldset</code> and <code>legend</code> elements. Otherwise, return false.</p>
<pre>
Perform on the <code>form</code> element: <br/>'.
htmlspecialchars('<form action="http://example.com/donut" method="post">
<fieldset>
<legend>Donut Type</legend>
<p>
<input type="radio" name="flavour" id="choc" value="chocolate" checked="checked" />
<label for="choc">Chocolate</label><br/>
<input type="radio" name="flavour" id="cream" value="cream"/>
<label for="cream">Cream Filled</label><br/>
<input type="radio" name="flavour" id="honey" value="honey"/>
<label for="honey">Honey Glazed</label>
</p>
</fieldset>
<p><input type="submit" value="Purchase Donut"/></p>
</form>').'<br/>
BasicFunctions::isRadioButtonsGrouped() returns true. 
</pre>
</li>

<li>
<p><a name="f_isSubmitLabelDifferent">isSubmitLabelDifferent()</a></p>
<p>Return true if the labels for all the submit buttons on the form are different. Otherwise, return false.</p>
<p>The submit button label may be the <code>alt</code> attribute value of <code>input</code> elements with a <code>type</code> attribute value of "image" or the <code>value</code> attribute value of <code>input</code> elements with a <code>type</code> attribute value of "submit".</p>
<pre>
Perform on the <code>form</code> element: <br/>'.
htmlspecialchars('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
<title>ATRC Testfile - Check #237.1 - Positive</title>
</head>
<body>

<form action="http://mysite.com">
<p><input type="submit" value="submit"/></p>
</form>

<form action="http://yoursite.com">
<p><input type="image" name="submit" src="submit.gif" alt="submit" /></p>
</form>

</body>
</html>').'<br/>
BasicFunctions::isSubmitLabelDifferent() returns false. 
</pre>
</li>

<li>
<p><a name="f_isTextMarked">isTextMarked(<code>htmlTagArray</code>)</a></p>
<p>Return true if the element content is marked with the html tags defined in <code>htmlTagArray</code>. Otherwise, return false.</p>
<pre>
Perform on the <code>p</code> element: <br/>'.
htmlspecialchars('<p><strong>Looks like a header</strong></p>').'<br/>
BasicFunctions::isTextMarked(array("b", "i", "u", "strong")) returns true. 
</pre>
</li>

<li>
<p><a name="f_isValidLangCode">isValidLangCode()</a></p>
<p>Return true if the language codes specified in <code>html</code> are valid. Otherwise, return false.</p>
<p>
The way to determine whether the language codes are valid:<br/>
1. If the content is HTML, check the value of the html element&apos;s lang attribute.<br/>
2. If the content is XHTML 1.0, or any version of XHTML served as "text/html", check the values of both the html element&apos;s lang attribute and xml:lang attribute.<br/>
   Note: both lang attributes must be set to the same value.<br/>
3. If the content is XHTML 1.1 or higher and served as type "application/xhtml+xml", check the value of the html element&apos;s xml:lang attribute.<br/>
4. Compare the language attribute value to valid language codes according to the ISO 639 specification.
</p>
<pre>
Perform on the <code>html</code> element: <br/>'.
htmlspecialchars('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">').'<br/>
BasicFunctions::isValidLangCode("lang")) returns true. 
</pre>
</li>

<li>
<p><a name="f_isValidRTL">isValidRTL()</a></p>
<p>Return true if the <code>dir</code> attribute value has correct "rtl" or "ltr" value matching with the language code. Otherwise, return false. Only perfoms on <code>html</code> element.</p>
<pre>
Perform on the <code>html</code> element: <br/>'.
htmlspecialchars('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="he" lang="he" dir="rtl">').'<br/>
BasicFunctions::isValidRTL()) returns true. 
</pre>
</li>

<li>
<p><a name="f_validateDoctype">validateDoctype()</a></p>
<p>Return true if <code>!DOCTYPE</code> content is valid. Only performs on <code>html</code> element. Return false otherwise or <code>!DOCTYPE</code> is not defined. Only perfoms on <code>html</code> element.</p>
<p>
<code>!DOCTYPE</code> content must and only must contain one of these values:
<ol>
<li>-//W3C//DTD HTML 4.01//EN</li>
<li>-//W3C//DTD HTML 4.0//EN</li>
<li>-//W3C//DTD XHTML 1.0 Strict//EN</li>
</ol>
</p>
<pre>
Perform on the <code>html</code> element: <br/>'.
htmlspecialchars('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">').'<br/>
BasicFunctions::validateDoctype()) returns true. 
</pre>
</li>

</ul>
</div>
';

$sql = "INSERT INTO `AC_language_text` VALUES ('eng', '_msgs','".$term."','".mysql_real_escape_string($text)."',now(),'')";
print $sql;
$result = mysql_query($sql, $lang_db) or die(mysql_error());

?>