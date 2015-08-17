# Visitor Tracker #

Visitor tracker allows you to attach the visitors path through your website to any contact form submission.

To use, install Visitor Tracker by placing the files in a directory of your choosing in the root of your SilverStripe project.

Then in the method that submits your form data, add the code


    $email = new Email(
        'email_from',
        'email_to',
        'email_subject'
    );

    $email->setTemplate( 'YourEmailTemplate' );

	$data = $this->getData();
    $data[ "Visitor" ] = Visitor::initVisitor();
	
	$email->populateTemplate( $data );

This gives you access to the visitor objects fields which in turn give you access to the array of pageView objects

Below is a demonstration of an email template

    <html>
    	<body>
    		<p><font face="arial" size="2">An enquiry has been received with the following details:</font></p>
    	    <ul>
			    <li><font face="arial" size="2"><strong>Name:</strong> $Name</font></li>
			    <li><font face="arial" size="2"><strong>Contact Information:</strong> $ContactInfo</font></li>
			    <li><font face="arial" size="2"><strong>Enquiry:</strong> $Enquiry</font></li>
    		</ul>
    
		    <% if $Visitor %>
		    	<br/><hr width="75%">
			    <h2 margin="0"><font face="arial" size="3">Site usage details</font></h2>
			    <p><font face="arial" size="2">
					Below are the details of this users path through the site
				</font></p>
		    
		    <% if $Visitor.referer %>
		    	<p><font face="arial" size="2"><strong>Referal URL:</strong> $Visitor.referer</p>
		    <% end_if %>
    
		    <table border=1>
		    	<tr>
		    		<th>
		    			<font face="arial" size="2">URL</font>
		    		</th>
		    		<th>
		    			<font face="arial" size="2">Scroll depth</font>
		    		</th>
		    		<th>
		    			<font face="arial" size="2">Time on page (Seconds)</font>
		    		</th>
		    	</tr>
		    
			    <% loop $Visitor.PageViews %>
			    <tr>
				    <td>
				    	<font face="arial" size="2">$URL</font>
				    </td>
				    <td>
				    	<font face="arial" size="2">
				    	<% if Last %>
				    		No data
				    	<% else %>
				    		$ScrollDepth%
				    	<% end_if %>
				    </font>
				    </td>
				    <td>
				    	<font face="arial" size="2">
				    	<% if Last %>
					    	No data
				    	<% else %>
				    		$TimeOnPage
				    	<% end_if %>
				    	</font>
				    </td>
			    </tr>
			    <% end_loop %>
		    </table>
		    <% end_if %>
	    </body>
    </html>

Note that the last page visited in the list, will not have a scroll depth or time on page.