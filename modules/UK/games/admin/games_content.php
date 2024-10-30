<FORM ACTION=\"\" name=form1 METHOD=POST>
    <table width=100% BORDER=1>
        <tr>
            <td colspan=2><h1>Extra Pages or Posts - Easily Create Specialist Video Game Content Pages or Posts</h1></td>
        </tr>
        <tr>
            <td colspan=2>
                <ol>
                    <li>Click on the <a href='../../wp-admin/edit-pages.php'>Pages link</a> or <a href='../../wp-admin/edit.php'>Posts link</a> in the WP Dashboard.</li>
                    <li>Click on <strong>Add New</strong> to add a new page or post, Call it something e.g. Metal Gear Solid Games</li>
                    <li>Add some content as you normally would.</li>
                    <li>Add a ComparePress shortcode where you would like to see the ComparePress conten, select what type of page you are wanting (see below for the types available)<br />e.g. <strong>[showgames title='Mario' platform='wii']</strong></li>
                    <li><strong>Update Page</strong></li>
                </ol>						
                Now go to your new page or post, you should find your page with your blog content and a selection of video games that contain 'Mario' in the title for Nintendo Wii :)<br /><br />A full list of available Shortcodes and their respective values are below, please enter them EXACTLY as below.

                <p><strong>NOTE:</strong> To restrict the number of results for ANY of the following shortocde results simply add number='x' <br />
                    e.g. [showgames platform='PS3' number='5']</p>
            </td>
        </tr>
        <tr>
            <td colspan=2><h2>Platform Specfic</h2>
                Enter <strong>[showgames platform='value']</strong> as the Shortcode and replace value with one of the following values:
                <br />
                <br />
                <ul>
                    <li><i>all</i></li>
                    <li><i>GA</i></li>
                    <li><i>GCube</i></li>
                    <li><i>NDS</i></li>
                    <li><i>PC</i></li>
                    <li><i>PS</i></li>
                    <li><i>PS2</i></li>
                    <li><i>PS3</i></li>
                    <li><i>PSP</i></li>
                    <li><i>Wii</i></li>
                    <li><i>XBox</i></li>
                    <li><i>XBox360</i></li>

                </ul><br />Can be combined with the title attribute.</td>
        </tr>

        <tr>

            <td colspan=2><h2>Platform and Category Specfic</h2>

                Enter <strong>[showgames platform='xbox360' category='value']</strong> as the Shortcode and replace value with one of the following values:

                <br />

                <br />

                <ul>

                    <li><i>Action and Shooter</i></li>

                    <li><i>Adventure</i></li>

                    <li><i>Arcade and Platform</i></li>

                    <li><i>Board, Card and Casino</i></li>

                    <li><i>Childrens</i></li>

                    <li><i>Education and Reference</i></li>

                    <li><i>Fighting</i></li>

                    <li><i>Music and Dancing</i></li>

                    <li><i>Puzzle</i></li>

                    <li><i>Quiz and Trivia</i></li>

                    <li><i>Racing</i></li>

                    <li><i>Role Playing</i></li>

                    <li><i>Simulation</i></li>

                    <li><i>Sports</i></li>

                    <li><i>Strategy</i></li>

                    <li><i>Other</i></li>

                </ul>You can also combine the above with the following attributes:

                <br />

                Enter <strong>[showgames platform='xbox360' category='value' sort='value']</strong> as the Shortcode and replace value with one of the following values:

                <ul>

                    <li><i>1</i> = sort by Price</li>

                    <li><i>2</i> = sort by Title</li>

                    <li><i>3</i> = sort by Release Date</li>

                    <li><i>4</i> = sort by Most Popular</li>

                    <li><i>5</i> = sort by Best Deals (most discount)</li>

                </ul>

                Finally you can also change the formatting of the above results using the following attributes:

                <br /> 

                Enter <strong>[showgames platform='wii' category='Childrens' sort='4' format='long']</strong> which will show the results in a list form rather than icons

            </td>

        </tr>

        <tr>
            <td colspan=2><h2>Title Specific</h2>
                Enter <strong>[showgames title='value']</strong> as the Shortcode and replace value with keywords to display games with those keywords in the title.  Can be combined with the platform attribute.</td>
        </tr>	  
        <tr>
            <td colspan=2><h2>Price Comparison Pages</h2>
                Enter <strong>[showgames id='value' type='full']</strong> as the Shortcode and replace value with the id of a game to display a price comparison page for that title.
            </td>
        </tr>	 
        <tr>
            <td colspan=2><h2>Price Comparison 'Badge'</h2>
                Enter <strong>[showgames id='value' type='badge' float='value']</strong> as the Shortcode and replace the id of a game to display a price comparison 'badge' on the page at that point; the float value, which can be either left or right, determines which side of the page it should appear.
            </td>
        </tr>	 
        <tr>
            <td colspan=2><h2>Price Comparison Prices Only</h2>
                Enter <strong>[showgames id='value' type='prices']</strong> as the Shortcode and replace the id of a game to display the current deals available for that game without the description above it.
            </td>
        </tr>
        <tr>
            <td colspan=2><h2>Price Comparison Description Only</h2>
                Enter <strong>[showgames id='value' type='description']</strong> as the Shortcode and replace the id of a game to display the game description and artwork without the prices below it.
            </td>
        </tr>	 


    </TABLE>
</FORM>