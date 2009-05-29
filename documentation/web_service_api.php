<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include(AC_INCLUDE_PATH.'header.inc.php');
?>
<div class="output-form" style="line-height:150%">

<h1>AChecker Web Service API</h1>
<p>Interface applications with the AChecker through its experimental API. This is version 0.1, dated Mar 2009.</p>
<p>Two types AChecker web service API are provided:</p>
  <ul style="list-style:decimal;">
    <li>Accessibility validation review;</li>
    <li>Save or reverse decisions made on accessibility checks that a human must make.</li>
  </ul>


<h2 id="TableOfContents">Table of Contents</h2>

    <div id="toc">
      <ul>
        <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#validation">Validation</a>
          <ul>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#requestformat_validation">Validation request format</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#rest_sample_response_validation">Sample REST validation response</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#restresponse_validation">REST response format reference</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#html_sample_response_validation">Sample HTML validation response</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#resterror_validation">Validation error response reference</a></li>
          </ul>
        </li>
        <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#decision">Make or Reverse Decisions</a>
          <ul>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#requestformat_decision">Make/reverse decisions request format</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#rest_sample_response_decision">Sample REST make/reverse decisions response</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#restresponse_decision">REST response format reference</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#html_sample_response_decision">Sample HTML made/reverse decisions response</a></li>
            <li><a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#resterror_decision">Make/reverse decisions error response reference</a></li>
          </ul>
        </li>
      </ul>
    </div>
    
    <p id="skip"></p>

<div id="validation">

<h2 id="requestformat_validation">Validation request format</h2>

<p>Below is a table of the parameter you can use to send a request to AChecker for validating URI.</p>

<p>If you want to use AChecker public validation server, use the parameters below in conjunction with the following base URI:<br />
<kbd>http://www.atutor.ca/achecker/test/trunk/checkacc.php</kbd> <br />
(replace with the address of your own server if you want to call a private instance of the validator)</p>

<table class="data" rules="all">
<tbody><tr>
<th>Parameter</th><th>Description</th><th>Default value</th>
</tr>

<tr>
  <th>uri</th>
  <td>The encoded URL of the document to validate.</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>id</th>
  <td>The "Web Service ID" generated once successfully registering into AChecker. 
  This ID is a 40 characters long string. It can always be retrieved from user's "Profile" page.</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>guide</th>
  <td>The guidelines to validate against. Separate each guideline with comma (,).</td>
  <td>WCAG2-AA. <br/>Or one or some of these values: <br/>
  BITV1: abbreviation of guideline bitv-1.0-(level-2);<br/>
  508: abbreviation of guideline section-508;<br/>
  STANCA: abbreviation of guideline stanca-act;<br/>
  WCAG1-A: abbreviation of guideline wcag-1.0-(level-a);<br/>
  WCAG1-AA: abbreviation of guideline wcag-1.0-(level-aa);<br/>
  WCAG1-AAA: abbreviation of guideline wcag-1.0-(level-aaa);<br/>
  WCAG2-A: abbreviation of guideline wcag-2.0-l1;<br/>
  WCAG2-AA: abbreviation of guideline wcag-2.0-l2;<br/>
  WCAG2-AAA: abbreviation of guideline wcag-2.0-l3.</td>
</tr>

<tr>
  <th>output</th>
  <td>Triggers the various outputs formats of the validator. If unset, the usual 
  <a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#html_sample_response_validation">HTML format</a> 
  will be sent. If set to rest, <a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#rest_sample_response_validation">
  the REST interface</a> will be triggered.</td>
  <td>html. Or either one of these values: html or rest</td>
</tr>

<tr>
  <th>offset</th>
  <td>The line offset to begin validation on the html output from URI.</td>
  <td>0</td>
</tr>
</tbody></table>
<br />

<span style="font-weight: bold">Sample validation request</span>
<p>http://www.atutor.ca/achecker/test/trunk/checkacc.php?uri=http%3A%2F%2Fatutor.ca&
id=888ca9e3f856baa0120755ecd8ffae6be3142029&output=html&guide=STANCA,WCAG2-AA&offset=10</p>
<p>Goal: Validate URI <code>http://atutor.ca</code> against guidelines "Stanca Act" and "Wcag 2.0 L2". 
Ignore the first 10 lines of html content from http://atutor.ca. Returns validation report
in html format.</p>

<h2 id="rest_sample_response_validation">sample REST validation response</h2><br/>
<span style="font-weight:bold">Success Response</span>
<p>A REST success response for the validation of a document (invalid) will look like this:</p>

<pre style="background-color:#F7F3ED;"> 
&lt;summary&gt;
    &lt;status&gt;FAIL&lt;stauts&gt;
    &lt;sessionID&gt;40-character-long string&lt;sessionID&gt;
    &lt;NumOfErrors&gt;number&lt;/NumOfErrors&gt;
    &lt;NumOfLikelyProblems&gt;number&lt;/NumOfLikelyProblems&gt;
    &lt;NumOfPotentialProblems&gt;number&lt;/NumOfPotentialProblems&gt;

    &lt;guidelines&gt;
      &lt;guideline&gt;string&lt;/guideline&gt;
      ...
    &lt;/guidelines&gt;
&lt;/summary&gt;

&lt;results&gt;
&lt;result&gt;
    &lt;resultType&gt;string&lt;/resultType&gt;
    &lt;lineNum&gt;number&lt;/lineNum&gt;
    &lt;columnNum&gt;number&lt;/columnNum&gt;
&lt;errorMsg&gt;encoded string&lt;/errorMsg&gt;
&lt;errorSourceCode&gt;encoded string&lt;/errorSourceCode&gt;
&lt;repair&gt;encoded string&lt;/repair&gt;
&lt;decisionPass&gt;encoded string&lt;/decisionPass&gt;
&lt;decisionFail&gt;encoded string&lt;/decisionFail&gt;
&lt;decisionMade&gt;string&lt;/decisionMade&gt;
&lt;decisionMadeDate&gt;string&lt;/decisionMadeDate&gt;
&lt;/result&gt; 
...
&lt;/results&gt;
</pre>

<br />
<span style="font-weight:bold">Error Response</span>

<pre style="background-color:#F7F3ED;"> 
&lt;errors&gt;
  &lt;totalCount&gt;number&lt;/totalCount&gt;
  &lt;error code="401"&gt;
    &lt;message&gt;Empty URI.&lt;/message&gt;
  &lt;/error&gt;
  &lt;error code="402"&gt;
    &lt;message&gt;Empty web service ID.&lt;/message&gt;
  &lt;/error&gt;
&lt;/errors&gt;
</pre>

<h2 id="restresponse_validation">REST response format reference</h2>
<table class="data" rules="all">
<tbody><tr>
<th>Element</th><th>Description</th>
</tr>

<tr>
  <th>summary</th>
  <td>The summary element of the validation response. Encloses validation summary information, 
      the numbers of different types of problems and the title of guidelines validating against.</td>
</tr>

<tr>
  <th>status</th>
  <td>Can be one of these values: FAIL, CONDITIONAL PASS, PASS. <br/>FAIL is set when there is/are known problem(s).
      <br/>CONDITIONAL PASS is set when there is no known problems but there is/are likely or potential problem(s).<br/>
      PASS is set when there is no problems found, OR, there is no known problems and likely/potential problems
      have pass decisions made on.</td>
</tr>

<tr>
  <th>sessionID</th>
  <td>The same ID must be sent back in make/reverse decisions request in response to the validation request. 
      This is to ensure the make/reverse decision request comes from the authenticated source.</td>
</tr>

<tr>
  <th>NumOfErrors</th>
  <td>Counts the number of known problems.</td>
</tr>

<tr>
  <th>NumOfLikelyProblems</th>
  <td>Counts the number of likely problems.</td>
</tr>

<tr>
  <th>NumOfPotentialProblems</th>
  <td>Counts the number of potential problems.</td>
</tr>

<tr>
  <th>guidelines</th>
  <td>The main guideline element. Encloses all the titles of the guidelines that have been validated against.</td>
</tr>

<tr>
  <th>guideline</th>
  <td>A child of <code>guidelines</code>. Encloses the title of the guideline that has been validated against.</td>
</tr>

<tr>
  <th>results</th>
  <td>Encapsulates all data about problems encountered through the validation process.</td>
</tr>

<tr>
  <th>result</th>
  <td>A child of <code>results</code>. Encloses details of one check problem.</td>
</tr>

<tr>
  <th>resultType</th>
  <td>A child of <code>result</code>. Can be one of these values: Error, Likely Problem, Potential Problem.</td>
</tr>

<tr>
  <th>lineNum</th>
  <td>A child of <code>result</code>. Within the source code of the validated document, refers to the line where the error was detected.</td>
</tr>

<tr>
  <th>columnNum</th>
  <td>A child of <code>result</code>. Within the source code of the validated document, refers to the column of the line where the error was detected.</td>
</tr>

<tr>
  <th>errorMsg</th>
  <td>A child of <code>result</code>. The actual error message.</td>
</tr>

<tr>
  <th>errorSourceCode</th>
  <td>A child of <code>result</code>. The line of the source where the error/problem was detected.</td>
</tr>

<tr>
  <th>repair</th>
  <td>A child of <code>result</code>. The actual message of how to repair. Only presented when resultType is "Error".</td>
</tr>

<tr>
  <th>sequenceID</th>
  <td>A child of <code>result</code>. The unique sequence ID identifying each error/problem. This ID is used to pinpoint each error/problem in make/reverse decision request.</td>
</tr>

<tr>
  <th>decisionPass</th>
  <td>A child of <code>result</code>. The actual text message of the pass decision. Only presented when resultType is "Likely Problem" or "Potential Problem".</td>
</tr>

<tr>
  <th>decisionFail</th>
  <td>A child of <code>result</code>. The actual text message of the fail decision. Only presented when resultType is "Likely Problem" or "Potential Problem".</td>
</tr>

<tr>
  <th>decisionMade</th>
  <td>A child of <code>result</code>. Only presented when the decision has been made by user. Can be one of these two values: PASS, FAIL. PASS is set when
      pass decision is chosen by the user. Otherwise, FAIL is set.</td>
</tr>

<tr>
  <th>decisionMadeDate</th>
  <td>A child of <code>result</code>. Only presented when the decision has been made by user. The date and time when the decision was made.</td>
</tr>

<tr>
  <th>errors</th>
  <td>Encapsulates all data about errors encountered through the validation process.</td>
</tr>

<tr>
  <th>totalCount</th>
  <td>a child of <code>errors</code>. Counts the number of errors listed.</td>
</tr>

<tr>
  <th>error</th>
  <td>a child of <code>errors</code>. Encloses the actual error code and error message.</td>
</tr>

<tr>
  <th>message</th>
  <td>a child of <code>error</code>. The actual error message.</td>
</tr>

</tbody></table>

<h2 id="html_sample_response_validation">Sample HTML validation response</h2><br/>
<span style="font-weight:bold">Success Response</span>
<pre style="background-color:#F7F3ED;"> 
&lt;?xml version="1.0" encoding="ISO-8859-1"?&gt;
&lt;!DOCTYPE style PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"&gt;
&lt;style type="text/css"&gt;
ul {font-family: Arial; margin-bottom: 0px; margin-top: 0px; margin-right: 0px;}
li.msg_err, li.msg_info { font-family: Arial; margin-bottom: 20px;list-style: none;}
span.msg{font-family: Arial; line-height: 150%;}
code.input { margin-bottom: 2ex; background-color: #F8F8F8; line-height: 130%;}
span.err_type{ padding: .1em .5em; font-size: smaller;}
&lt;/style&gt;
&lt;input value="4eefdfed3b98badf1f138ec83712358d67ffac9e" type="hidden" name="sessionid" /&gt;
&lt;p&gt;
&lt;strong&gt;Result: &lt;/strong&gt;
&lt;span style="background-color: red; border: solid green; padding-right: 1em; padding-left: 1em"&gt;FAIL&lt;/span&gt;&nbsp;&nbsp;
&lt;span style="color:red"&gt;&lt;span style="font-weight: bold;"&gt;44 Errors&nbsp;&nbsp;103 Likely Problems&nbsp;&nbsp;217 Potential Problems&nbsp;&nbsp;&lt;/span&gt;&lt;/span&gt;
&lt;strong&gt;&lt;br /&gt;
&lt;strong&gt;Guides: &lt;/strong&gt;
&lt;a title="BITV 1.0 (Level 2)(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=1"&gt;BITV 1.0 (Level 2)&lt;/a&gt;&nbsp;&nbsp;&lt;a title="Section 508(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=2"&gt;Section 508&lt;/a&gt;&nbsp;&nbsp;&lt;a title="Stanca Act(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=3"&gt;Stanca Act&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 1.0 (Level A)(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=4"&gt;WCAG 1.0 (Level A)&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 1.0 (Level AA)(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=5"&gt;WCAG 1.0 (Level AA)&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 1.0 (Level AAA)(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=6"&gt;WCAG 1.0 (Level AAA)&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 2.0 L1(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=7"&gt;WCAG 2.0 L1&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 2.0 L2(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=8"&gt;WCAG 2.0 L2&lt;/a&gt;&nbsp;&nbsp;&lt;a title="WCAG 2.0 L3(link opens in a new window)" target="_new" href="http://localhost/achecker/guideline/view_guideline.php?id=9"&gt;WCAG 2.0 L3&lt;/a&gt;&nbsp;&nbsp;
&lt;/p&gt;
&lt;h3&gt;Accessibility Review&lt;/h3&gt;
&lt;h4&gt;Errors&lt;/h4&gt;
&lt;div id="errors" style="margin-top:1em"&gt;
  &lt;ul&gt;
    ...         // error details
  &lt;/ul&gt;
&lt;/div&gt;

&lt;div id="likely_problems" style="margin-top:1em"&gt;
  &lt;ul&gt;
    ...         // likely problem details
  &lt;/ul&gt;
&lt;/div&gt;

&lt;div id="potential_problems" style="margin-top:1em"&gt;
  &lt;ul&gt;
    ...         // potential problem details
  &lt;/ul&gt;
&lt;/div&gt;
</pre>


<span style="font-weight: bold">Sample result detail of the check that decision has been made</span>

<pre style="background-color:#F7F3ED;"> 
&lt;li class="msg_info"&gt;
  &lt;span class="err_type"&gt;&lt;img src="http://localhost/achecker/images/info.png" alt="Info" title="Info" width="15" height="15" /&gt;&lt;/span&gt;
  &lt;em&gt;Line 247, Column 9&lt;/em&gt;:
  &lt;span class="msg"&gt;
  &lt;a href="http://localhost/achecker/checker/suggestion.php?id=197"
              onclick="popup('http://localhost/achecker/checker/suggestion.php?id=197'); return false;" 
              title="Suggest improvements on this error message" target="_new"&gt;Anchor text may not identify the link destination.&lt;/a&gt;
  &lt;/span&gt;
  &lt;pre&gt;&lt;code class="input"&gt;&lt;a href=&quot;/contact.php&quot;&gt;&lt;/code&gt;&lt;/pre&gt;
  &lt;p class="helpwanted"&gt;
  &lt;/p&gt;
         
  &lt;table&gt;
    &lt;tr&gt;
      &lt;td&gt;
      &lt;input value="P" type="radio" name="d[<span style="font-weight: bold; color: red">135</span>]" id="pass135"  /&gt;
      &lt;label for="pass135"&gt;Anchor has text that identifies the link destination.&lt;/label&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td&gt;
      &lt;input value="F" type="radio" name="d[<span style="font-weight: bold; color: red">135</span>]" id="fail135"  /&gt;
      &lt;label for="fail135"&gt;Anchor does not have text that identifies the link destination.&lt;/label&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td&gt;
      &lt;input value="N" type="radio" name="d[<span style="font-weight: bold; color: red">135</span>]" id="nodecision135" checked="checked" /&gt;
      &lt;label for="nodecision135"&gt;No Decision&lt;/label&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
  &lt;/table&gt;
&lt;/li&gt;
</pre>

<span style="font-weight: bold">Sample result detail of the check that decision has NOT been made</span>
<pre style="background-color:#F7F3ED;"> 
&lt;li class="msg_info"&gt;
  &lt;span class="err_type"&gt;&lt;img src="http://localhost/achecker/images/info.png" alt="Info" title="Info" width="15" height="15" /&gt;&lt;/span&gt;
  &lt;em&gt;Line 246, Column 9&lt;/em&gt;:
  &lt;span class="msg"&gt;
  &lt;a href="http://localhost/achecker/checker/suggestion.php?id=197"
        onclick="popup('http://localhost/achecker/checker/suggestion.php?id=197'); return false;" 
        title="Suggest improvements on this error message" target="_new"&gt;Anchor text may not identify the link destination.&lt;/a&gt;
  &lt;/span&gt;
  &lt;pre&gt;&lt;code class="input"&gt;&lt;a href=&quot;/archive.php&quot;&gt;&lt;/code&gt;&lt;/pre&gt;
  &lt;p class="helpwanted"&gt;
  &lt;/p&gt;
         
  &lt;table class="form-data"&gt;
    &lt;tr&gt;
      &lt;th align="left"&gt;Decision:&lt;/th&gt;
      &lt;td&gt;Anchor has text that identifies the link destination.&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;th align="left"&gt;Date:&lt;/th&gt;
      &lt;td&gt;2009-03-04 14:33:06&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td colspan="2"&gt;
      &lt;input value="Reverse Decision" type="submit" name="reverse[<span style="font-weight: bold; color: red">134</span>]" /&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
  &lt;/table&gt;
&lt;/li&gt;
</pre>

<br/>
<span style="font-weight:bold">Error Response</span><br/>
<pre style="background-color:#F7F3ED;"> 
&lt;div id="error"&gt;
  &lt;h4&gt;The following errors occurred:&lt;/h4&gt;
    &lt;ul&gt;
      &lt;li&gt;Empty URI. &lt;small&gt;&lt;small&gt;(AC_ERROR_EMPTY_URI)&lt;/small&gt;&lt;/small&gt;&lt;/li&gt;
    &lt;/ul&gt;
    &lt;ul&gt;
      &lt;li&gt;Empty web service ID. &lt;small&gt;&lt;small&gt;(AC_ERROR_EMPTY_WEB_SERVICE_ID)&lt;/small&gt;&lt;/small&gt;&lt;/li&gt;
    &lt;/ul&gt;
    &lt;ul&gt;
      &lt;li&gt;No sequence ID is given. &lt;small&gt;&lt;small&gt;(AC_ERROR_SEQUENCEID_NOT_GIVEN)&lt;/small&gt;&lt;/small&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;
</pre>

<h2 id="resterror_validation">Validation error response reference</h2>
<table class="data" rules="all"><tbody>
<tr>
<th>Error Code</th><th>Description</th>
</tr>

<tr>
  <th>401</th>
  <td>Empty URI.</td>
</tr>

<tr>
  <th>402</th>
  <td>Invalid UR.</td>
</tr>

<tr>
  <th>403</th>
  <td>Empty web service ID.</td>
</tr>

<tr>
  <th>404</th>
  <td>Invalid web service ID.</td>
</tr>

</tbody></table>
</div>

<div id="decision">

<h2 id="requestformat_decision">Make/reverse decisions request format</h2>

<p>Below is a table of the parameter you can use to send a request to AChecker for making decisions on likely or potential problems.</p>

<p>As said, if you want to use AChecker public validation server, use the parameters below in conjunction with the following base URI:<br />
<kbd>http://www.atutor.ca/achecker/test/trunk/checkacc.php</kbd> <br />
(replace with the address of your own server if you want to call a private instance of the validator)</p>

<table class="data" rules="all">
<tbody><tr>
<th>Parameter</th><th>Description</th><th>Default value</th>
</tr>

<tr>
  <th>uri</th>
  <td>The encoded URL of the document to validate.</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>id</th>
  <td>The "Web Service ID" generated once successfully registering into AChecker. 
  This ID is a 40 characters long string. It can always be retrieved from user's "Profile" page.</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>session</th>
  <td>The "sessionid" embedded in the validation response. In REST format, it's the value of element &lt;sessionID&gt;.
      In HTML format, it's the value of hidden variable "sessionid"</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>output</th>
  <td>Triggers the various outputs formats of the validator. If unset, the usual 
  <a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#html_sample_response_validation"">HTML format</a> 
  will be sent. If set to rest, <a href="<?php echo AC_BASE_HREF.'documentation/web_service_api.php'; ?>#rest_sample_response_validation">
  the REST interface</a> will be triggered.</td>
  <td>html. Or either one of these values: html or rest</td>
</tr>

<tr>
  <th>[sequenceID]</th>
  <td>The sequence ID in the validation response that identifies each likely or potential problems. In REST format, 
  it's the value of element &lt;sequenceID&gt;. In HTML format, it's the key value of radio button array 
  d[1], d[2] ... 1, 2 is the [sequenceID]. (This value is red-highlighted in above html sample response.)</td>
  <td>None. This parameter can appear as many times as user desires. The value of [sequenceID] can be one of 
  these: <br/>P : pass <br/>F : fail<br/>N : no decision</td>
</tr>

<tr>
  <th>reverse</th>
  <td>When this parameter is presented and set to "true", the decisions on sequenceIDs sent in the request are all set to 
  "No Decision" (N) no matter what values are given for the sequenceIDs in the request.</td>
  <td>None. When present, must be value "true"</td>
</tr>

</tbody></table>
<br />

<span style="font-weight: bold">Sample validation request</span>
<p>http://localhost/achecker/decisons.php?uri=http%3A%2F%2Fatutor.ca&id=888ca9e3f856baa0120755ecd8ffae6be3142029
&session=c124694572284112cb54679565ec13dd57ed6ccf&output=html&1=P&2=F&3=N&4=P</p>
<p>Goal: Set decision on problem sequence ID 1 to pass decision, 2 to fail decision, 3 to no decision, 4 to pass decision.
Return response in HTML format.</p>
<p>http://localhost/achecker/decisons.php?uri=http%3A%2F%2Fatutor.ca&id=888ca9e3f856baa0120755ecd8ffae6be3142029
&session=c124694572284112cb54679565ec13dd57ed6ccf&output=rest&1=P&2=F&3=N&4=P&reverse=true</p>
<p>Goal: Reverse decisions on problem sequence ID 1, 2, 3, 4. All decisions for these sequence IDs are set to "decision
has not been made". Return response in REST format.</p>

<h2 id="rest_sample_response_decision">Sample REST make/reverse decision response</h2><br/>
<span style="font-weight:bold">Success Response</span>
<p>A REST success response for the make/reverse decision request will look like this:</p>

<pre style="background-color:#F7F3ED;"> 
&lt;summary&gt;
  &lt;status&gt;success&lt;/status&gt;
&lt;/summary&gt;
</pre>

<br />
<span style="font-weight:bold">Error Response</span>

<pre style="background-color:#F7F3ED;"> 
&lt;errors&gt;
  &lt;totalCount&gt;number&lt;/totalCount&gt;
  &lt;error code="401"&gt;
    &lt;message&gt;Empty URI.&lt;/message&gt;
  &lt;/error&gt;
  &lt;error code="402"&gt;
    &lt;message&gt;Empty web service ID.&lt;/message&gt;
  &lt;/error&gt;
&lt;/errors&gt;
</pre>

<h2 id="restresponse_decision">REST response format reference</h2>
<table class="data" rules="all">
<tbody><tr>
<th>Element</th><th>Description</th>
</tr>

<tr>
  <th>summary</th>
  <td>The summary element of the validation response. Encloses the validation summary result.</td>
</tr>

<tr>
  <th>status</th>
  <td>a child of <code>summary</code>. Only has one value: success.</td>
</tr>

<tr>
  <th>errors</th>
  <td>Encapsulates all data about errors encountered through the validation process.</td>
</tr>

<tr>
  <th>totalCount</th>
  <td>a child of <code>errors</code>. Counts the number of errors listed.</td>
</tr>

<tr>
  <th>error</th>
  <td>a child of <code>errors</code>. Encloses the actual error code and error message.</td>
</tr>

<tr>
  <th>message</th>
  <td>a child of <code>error</code>. The actual error message.</td>
</tr>

</tbody></table>

<h2 id="html_sample_response_decision">Sample HTML validation response</h2><br/>
<span style="font-weight:bold">Success Response</span>

<pre style="background-color:#F7F3ED;"> 
&lt;div id="success"&gt;Success&lt;/div&gt;
</pre>

<h2 id="resterror_decision">Validation error response reference</h2>
<table class="data" rules="all"><tbody>
<tr>
<th>Error Code</th><th>Description</th>
</tr>

<tr>
  <th>401</th>
  <td>Empty URI.</td>
</tr>

<tr>
  <th>402</th>
  <td>Invalid UR.</td>
</tr>

<tr>
  <th>403</th>
  <td>Empty web service ID.</td>
</tr>

<tr>
  <th>404</th>
  <td>Invalid web service ID.</td>
</tr>

<tr>
  <th>405</th>
  <td>No sequence id is given.</td>
</tr>

</tbody></table>
</div>

</div>
<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
