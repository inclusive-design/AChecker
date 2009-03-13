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

<h2>AChecker Web Service API</h2>
Interface applications with the AChecker through its experimental API. This is version 0.1, dated Mar 2009.

<h3 id="TableOfContents">Table of Contents</h3>

    <div id="toc">
      <ul>
        <li><a href="#validation">Validation</a>
          <ul>
            <li><a href="#requestformat_validation">Validation Request Format</a></li>
            <li><a href="#restformat_validation">REST format description</a>

             <ul>
              <li><a href="#rest_sample_response_validation">sample REST validation response</a></li>
              <li><a href="#restresponse_validation">REST response format reference</a></li>
              <li><a href="#restmessage_validation">REST atomic message (error or warning) format reference</a></li>
             </ul>
            </li>

            <li><a href="#htmlformat_validation">HTML format description</a>

             <ul>
              <li><a href="#html_sample_response_validation">sample HTML validation response</a></li>
              <li><a href="#htmlresponse_validation">HTML response format reference</a></li>
              <li><a href="#htmlmessage_validation">HTML atomic message (error or warning) format reference</a></li>
             </ul>
            </li>

          </ul>
        </li>
        <li><a href="#decision">Made Decision</a>
          <ul>
            <li><a href="#requestformat_decision">Validation Request Format</a></li>
            <li><a href="#restformat_decision">REST format description</a>

             <ul>
              <li><a href="#rest_sample_response_decision">sample REST validation response</a></li>
              <li><a href="#restresponse_decision">REST response format reference</a></li>
              <li><a href="#restmessage_decision">REST atomic message (error or warning) format reference</a></li>
             </ul>
            </li>

            <li><a href="#htmlformat_decision">HTML format description</a>

             <ul>
              <li><a href="#html_sample_response_decision">sample HTML validation response</a></li>
              <li><a href="#htmlresponse_decision">HTML response format reference</a></li>
              <li><a href="#htmlmessage_decision">HTML atomic message (error or warning) format reference</a></li>
             </ul>
            </li>

          </ul>
        </li>
      </ul>
    </div>
    
    <p id="skip"></p>

<h3 id="requestformat">Validation Request Format</h3>

<p>Below is a table of the parameter you can use to send a query to AChecker.</p>

<p>If you want to use AChecker public validation server, use the parameters below in conjunction with the following base URI:<br>
<kbd>http://www.atutor.ca/achecker/test/trunk/checkacc.php</kbd> <br>
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
  This ID is a 40 characters long string. It can be retrieved from user’s "Profile" page.</td>
  <td>None, must be given.</td>
</tr>

<tr>
  <th>guide</th>
  <td>The guidelines to validate against. Separate each guideline with comma (,).</td>
  <td>WCAG-2.0-L2. Or one or some of these values: <br/>
  bitv-1.0-(level-2)<br/>section-508<br/>stanca-act<br/>wcag-1.0-(level-a)<br/>
  wcag-1.0-(level-aa)<br/>wcag-1.0-(level-aaa)vwcag-2.0-l1<br/>wcag-2.0-l2<br/>wcag-2.0-l3.</td>
</tr>

<tr>
  <th>output</th>
  <td>Triggers the various outputs formats of the validator. If unset, the usual <a href="#htmlformat">html format</a> 
  will be sent. If set to rest, <a href="#restformat">the rest interface</a> will be triggered.</td>
  <td>html. Or either one of these values: html or rest</td>
</tr>

<tr>
  <th>offset</th>
  <td>The line offset on the html output from uri where the validation starts. </td>
  <td>0</td>
</tr>
</tbody></table>

<h3 id="restformat">REST format description</h3>
<p>When called with parameter <code>output=rest</code>, the validator will switch
to its REST interface. Below is a sample request and response, as well as
a description of the most important elements of the response.</p>

<h4 id="rest_sample_request">sample REST validation request</h4>
http://www.atutor.ca/achecker/test/trunk/checkacc.php?uri=http%3A%2F%2Fatutor.ca&
id=888ca9e3f856baa0120755ecd8ffae6be3142029&output=html&guide=bitv-1.0-(level-2),
section-508,stanca-act,wcag-1.0-(level-a),wcag-1.0-(level-aa),wcag-1.0-(level-aaa),
wcag-2.0-l1,wcag-2.0-l2,wcag-2.0-l3

<h4 id="rest_sample_response">sample REST validation response</h4>
<h5>Success Response</h5>
<p>A REST success response for the validation of a document (invalid) will look like this:</p>
<pre style="font-size: smaller;"> 
</pre>

<h5>Error Response</h5>

</div>
<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
