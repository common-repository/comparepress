<form action=\"\" name=form1 method="post">
    <table width=100%>
        <tr>
            <td colspan=2><h1>Extra Pages or Posts - Easily Create Specialist Mobile Phone Content Pages or Posts</h1></td>
        </tr>
        <tr>
            <td colspan=2>
                <ol>
                    <li>Click on the <a href='../../wp-admin/edit-pages.php'>Pages link</a> or <a href='../../wp-admin/edit.php'>Posts link</a> in the WP Dashboard.</li>
                    <li>Click on <strong>Add New</strong> to add a new page or post, Call it something e.g. Google Android Phones</li>
                    <li>Add some content as you normally would.</li>
                    <li>Add a ComparePress shortcode where you would like to see the ComparePress content, select what type of page you are wanting (see below for the types available)<br />e.g. <strong>[showphones feature='google android']</strong></li>
                    <li><strong>Update Page</strong></li>
                </ol>						
                Now go to your new page or post, you should find your page with your blog content and a selection of mobile phone handsets that all have a Google Android as an OS :)<br /><br />A full list of available Shortcodes and their respective values are below, please enter them EXACTLY as below.

                <p><strong>NOTE:</strong> To restrict the number of results for ANY of the following shortcode results simply add number='x' <br />
                    e.g. [showphones handsetid='HTC One X' number='5']</p>
            </td>
        </tr>
        <tr>
            <td colspan=2><h2>Handset specific</h2>
                Enter <strong>[showphones handsetid ='value']</strong> as the Shortcode and replace value with the handset you only want to show deals for:
                <br />	
                <ul>
                    <li><i>HTC One X</i> - This will show all HTC One X deals ONLY</li>
                    <li><strong>Note:</strong>The handsetid value has no - between words!</li>
                </ul>
                <p>Handset <strong>AND</strong> Network Specific: [showphones handsetid='HTC One X' <strong>network='o2'</strong>]</p>
                <p>Handset <strong>AND</strong> Network <strong>AND</strong> Number of Deals Specific: showphones handsetid='HTC One X' <strong>network='o2' number='6']</strong></p>
                <br />
                This can be really powerful if you want to add the deals directly after doing a review of a specific handset</p></td>
        </tr>	 
        <tr>
            <td colspan=2><h2>Feature Specfic</h2>
                Enter <strong>[showphones feature='value']</strong> as the Shortcode and replace value with one of the following values:
                <br />
                <ul>
                    <li><i>iOS</i></li>     
                    <li><i>google android</i></li>
                    <li><i>windows os</i></li>
                    <li><i>blackberry os</i></li>
                    <li><i>4g</i></li>
                    <li><i>3g</i></li>                
                    <li><i>1 megapixel camera</i></li>
                    <li><i>2 megapixel camera</i></li>
                    <li><i>3 megapixel camera</i></li>
                    <li><i>5 megapixel camera</i></li>
                    <li><i>8 megapixel camera</i></li>
                    <li><i>high megapixel</i> - this is 10+ megapixels</li>
                    <li><i>bluetooth</i></li>
                    <li><i>camera</i></li>
                    <li><i>data device</i></li>
                    <li><i>designer</i></li>
                    <li><i>easy to use</i></li>
                    <li><i>flip phone</i></li>
                    <li><i>gps</i></li>
                    <li><i>hsdpa</i></li>
                    <li><i>mp3</i></li>
                    <li><i>normal phone</i></li>
                    <li><i>pink</i></li>
                    <li><i>qwerty</i></li>
                    <li><i>quad band</i></li>
                    <li><i>radio</i></li>
                    <li><i>slider phone</i></li>
                    <li><i>slim</i></li>
                    <li><i>speakerphone</i></li>
                    <li><i>symbian os</i></li>
                    <li><i>swivel phone</i></li>
                    <li><i>touchscreen phone</i></li>
                    <li><i>video recording</i></li>
                    <li><i>wifi</i></li>
                </ul></td>
        </tr>
        <tr>
            <td colspan=2><h2>Manufacturer Specific</h2>
                Enter <strong>[showphones make='value']</strong> as the Shortcode and replace value with one of the following values:
                <br />		
                <ul>
                    <li><i>all</i> - This will show ALL mobile phones that have any types of deals currently</li>
                    <li><i>apple</i></li>
                    <li><i>htc</i></li>
                    <li><i>Samsung</i></li>                        
                    <li><i>Blackberry</i></li>                        
                    <li><i>sony ericsson</i></li>			
                    etc....			
                </ul></td>
        </tr>	  

        <tr>
            <td colspan=2><h2>Network Specific</h2>
                Enter <strong>[showphones network_deals='value']</strong> as the Shortcode and replace value with one of the following values:
                <br />			
                <ul>
                    <li><i>payg</i> - shows all handsets available as PAYG</li>
                    <li><i>paygorange</i> - shows all handsets available as PAYG on orange</li>                        
                    <li><i>simfree</i> - shows all handsets available as Sim Free</li>
                    <li><i>tmobile</i> - shows all handsets available on a network e.g. T-Mobile</li>
                    etc....			
                </ul></td>
        </tr>	            
        <tr>
            <td colspan=2><h2>Date specific</h2>
                Enter <strong>[showphones launchdate='value']</strong> as the Shortcode and replace value with one of the following values:
                <br />			
                <ul>
                    <li><i>999</i> - This will show all phones that are <strong>coming soon</strong></li>
                    <li><i>latest</i> - This will show all phones that have been released recently</li>									
                </ul>
            </td>
        </tr>	 
    </table>
</form>