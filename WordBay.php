<?php
/*
Plugin Name: WordBay
Plugin URI: http://www.thewordbay.com
Description: This version of Wordbay, the eBay RSS listings plugin, is now being DISCONTINUED. This last version will be left in the repository but there is a good chance it will STOP working after June 30th 2011. To avoid this, go and see how you can get the NEW version, which is superior in every way, right here: http://www.thewordbay.com
Version: 1.2.9
Author: Mark Daniels
Author URI: http://www.thewordbay.com
*/

// parse country file for locale settings

function getPluginPath() {
	 $path = dirname(__FILE__);   // PHP native way to get the path to the plugin directory. Full http: path not allowed on some servers
    trailingslashit(str_replace("\\","/",$path));
    return $path;
}

function country(){

    $path = getPluginPath();
    $path .= "/countries.txt";
    $cFile = file($path); // read in file


    for ($i = 0; $i < sizeof($cFile); $i ++){
        $line = explode("|", $cFile[$i]);
        $locals[$i] = $line; 
    } 
    return $locals;
}

function grabber($url) // grabs the web page
{
    $content="";
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 2);
    $content = curl_exec ($ch);
    curl_close ($ch);
    return $content;
}

function Wbwidget($params)
{

$wb=new WordBay();
return $wb->WBlisting('', $params['searchterms'], TRUE, FALSE, TRUE);

} 


function urlencrypt($string) {
      return base64_encode($string);

   }

if ( !class_exists ( "WordBay" ) )

         {

                class WordBay    // Create class for WordBay
                {
                        var $adminOptionsName = "WordBayPluginAdminOptions";
			
                        
                function init ( )
                {
                        $this -> getAdminOptions ( );
                }

                function WBlisting ( $oldcontent, $searchterms, $was_search, $is_searchpage, $is_widget) // The main listing function - takes the existing content and the search terms as parameters and returns new content with listings inserted

                
                {

                        $searchterms = urlencode ($searchterms);
                        $WBOptions = $this -> getAdminOptions ( );
                        $search_page_link = $WBOptions['search_page'];
                        if ( preg_match ( '#=[0-9]+#', $search_page_link ) ) // Cheap and nasty check to see if we are using non-rewritten URLs

                                 {
                                        $long_search_link = $search_page_link .
                                                "&WBsearch=";
                        } else {
                                $long_search_link = $search_page_link .
                                        "?WBsearch=";
                        }
                        if ( $is_searchpage == FALSE ) {

                                if ($WBOptions['BuyUrlLoc'])
                                {
                                $buyurl = $WBOptions['BuyUrlLoc']."?";
                                }
                                else
                                {
                                $buyurl = get_bloginfo ( 'wpurl' ) .
                                        "/wp-content/plugins/wordbay/buy.php?";
                                }
                                 

                                // Get listing parameters (from admin panel) 

                                $first_placementID = $WBOptions['placement_id']; 
                                $first_siteID = $WBOptions['site_id'];
                                $sacat = $WBOptions['eBayCategories'][$first_siteID]; 
                                $first_location = $WBOptions['location_id'];
				$geoLocOverride = $WBOptions['geoLocOverride'];

if ($WBOptions['geo_on'] == TRUE) // are we geo-targetting?
{

                                $locals = country();

                                $userCountry = grabber('http://api.hostip.info/country.php?ip=' . $_SERVER['REMOTE_ADDR']);
                                if ($userCountry != "xx" && $userCountry != "" && $userCountry != " " && $userCountry != NULL)
                                {
                                for ($i = 0; $i < sizeof($locals); $i++){ 
                                    if ($userCountry == $locals[$i][1]) {
                                        $first_siteID = $locals[$i][2];
                                        $first_placementID = $locals[$i][3];
                                        $sacat = $WBOptions['eBayCategories'][$first_siteID];
                                        if ($geoLocOverride == TRUE)
						{$first_location = $locals[$i][6];}
                                        break;
                                    }
                                }}
                                 
                                // end mod JH
}
                                $columns = $WBOptions['columns'];
                        
                                if ( !isset($_GET["WBsearch"] ) || !$_GET["WBsearch"] )

                                       {
                                                $first_minbids = $WBOptions['minbids'];
                                } else {
                                        $first_minbids = 0;
                                }
                                $currency = 0;
                                $first_campid = $WBOptions['camp_id'];
                                $sellerID = $WBOptions['sellers'];
                                $first_customID = urlencode ( get_permalink ( ) ); // Custom ID currently set to the URL for the current page so you can see where a sale came from.
                                
                                $first_maxitems = $WBOptions['maxitems']; 
                              
                                 
                                
                                $generosity = $WBOptions['generosity'];

                                $rssurl = 'http://rss.api.ebay.com/ws/rssapi?FeedName=SearchResults';
                                
                                $first_minprice = $WBOptions['minprice'];
                                 
                                $first_maxprice = $WBOptions['maxprice'];

                                $click_details = $WBOptions['clickdetails'];
                                 
                                
                                                                
                                $first_otherparams = '&dfsp=32&fsoo=1&fsop=1&output=RSS20&catref=c5&from=R6&sabfmts=0&saaff=afepn&nojspr=y&fbfmt=1'; // These are additional parameters that are hard-coded. We won't fiddle with them for now. Don't even know what all of them do.
                                
                                

                                // start new function here somewhere?

                                $newcontent = "<div id='leftpanel'>";
                                if ( $was_search == TRUE && $is_widget == FALSE) {
                                        $newcontent .= 'Search results:<br/>';
                                } else {
                                        if ( $WBOptions['show_signup'] == TRUE ) // Show the signup link if the option is checked

                                                  {
                                                        $buystring = 'http://rover.ebay.com/rover' .
                                                                
                                                                '/1/' .
                                                                $first_placementID .
                                                                '/1?type=1&campid=' .
                                                                $first_campid .
                                                                '&toolid=10001&customid=' .
                                                                $first_customID .
                                                                '(sign-up)&mpre=https%3A%2F%2Fscgi.ebay.com%2Fws%2FeBayISAPI.dll%3FRegisterEnterInfo';
                                                $newcontent .= "<b>Not registered with eBay?<br/><a href='" .
                                                        $buyurl .
                                                        "buyurl=";
                                                $newcontent .= "eBay-signup___" .urlencrypt ( $buystring );
                                                
                                                $newcontent .= "'>Click to sign up</a></b><br/><br/>";
                                        }
                                }

                                /* Get feed */
                                require_once ( ABSPATH .
                                        WPINC .
                                        '/rss.php' );  // Uses Wordpress native rss feed function, so this file must be present

                                $url = $rssurl .
                                        '&siteId=' .
                                        $first_siteID .
                                        '&sacur=' .
                                        $currency .
                                        '&salic=' .
                                        $first_location .
                                        '&satitle=' .
                                        $searchterms .
                                        $first_otherparams .
                                        '&sabdlo=' .
                                        $first_minbids .
                                        '&afepn=' .
                                        $first_campid .
                                        '&customid=' .
                                        $first_customID .
                                        '&frpp=' .
                                        $first_maxitems . 
                                        '&sacat=' .
                                        $sacat.
                                        '&saprclo='.
                                        $first_minprice.
                                        '&saprchi='.
                                        $first_maxprice;
                                        if ($sellerID != '') {
                                	                                      $url .= 'fss=1&saslop=1&sasl='.$sellerID;
                                	
                                	
                                }

                                $rss = fetch_rss ( $url );
                                if ( $rss ) {
                                $rss_items = count($rss -> items);
                                if ($rss_items == 0) // Check for 0 items
                                {
                                								
								$amazon_redirect_url = 'http://www.amazon.com/gp/search?ie=UTF8&keywords=';
								if ($WBOptions['amazon_search_link']) {
								$amazonlink = $amazon_redirect_url . $searchterms . '&tag=';
								$amazontag = $WBOptions['amazon_tag'];
								$random = rand ( 1, 100 ); /* Start Generosity loop */
								if ( ($random <= $generosity) || ( $amazontag == "" ) ) { 
								$amazontag = 'wordbay-20';
								} /* End Generosity loop */
								$amazonlink .= $amazontag;
								$amazon_search_link = $buyurl . "buyurl=";
								$amazon_search_link .= "amazon_search___" . urlencrypt ( $amazonlink );

								
								$newcontent .= "<p>Sorry, we're coming up empty for some reason. Why not see if you can find the same thing at Amazon.com - <a href='" . $amazon_search_link . "'><strong>click here</strong></a>.</p>";
								
							
								
								}
								
                                } else {
                                        $columnwidth = 100/$columns;
                                        $columnwidth = (92*$columnwidth)/100;
                                        $i = 0;
                                        foreach ( $rss -> items as $item ) {
                                        $i = $i + 1;
                                        
                                                $mouseovercolour = $WBOptions['mouseovercolour'];
                                                $mouseoutcolour = $WBOptions['mouseoutcolour'];
                                                if ($mouseovercolour && $mouseoutcolour)
                                                {
                                                $newcontent .= "<div class='firstlocaleitem' style='width:".$columnwidth."%; float: left;' onmouseover=\"style.backgroundColor='#$mouseovercolour';\" onmouseout=\"style.backgroundColor='#$mouseoutcolour'\">";	
                                                }
                                                else
                                                {
                                                 $newcontent .= "<div class='firstlocaleitem' style='width:".$columnwidth."%; float: left;' >";	
                                                }
                                               
                                                
                                                $itemTitle = preg_replace('#[^A-Za-z0-9]+#', '-', $item['title']);

                                                $href        = $item['link'];

                                                $href = $itemTitle . '___' . urlencrypt ( $href );
                                                $href = $buyurl .
                                                        "buyurl=" .
                                                        $href;
                                                $description = preg_replace ('#\$#', '&#36;', $item['description']);
                                                $title = preg_replace ('#\$#', '&#36;', $item['title']);
                                                $description = str_replace ( "\n", ' ', $description );
                                                preg_match_all ( '/<a[\s]+[^>]*href\s*=\s*[\"\']?([^\'\" >]+)[\'\" >]/i', $description, $urlarray ); // Find all occurrences of hyperlinks so we can obfuscate them
												$newurl = '';
                                                foreach ( $urlarray[1] as $obfurl ) {
                                                        $obfurl    = trim ( $obfurl );
                                                        // $newurl = str_replace ( $oldterms, $newterms, $obfurl );
                                                        $newurl = $itemTitle . '___' . urlencrypt ( $obfurl );
                                                        $newurl = $buyurl .
                                                                "buyurl=" .
                                                                $newurl;
                                                        $description = str_replace ( $obfurl, $newurl, $description );
                                                        
                                                }
                                                
                                                $description = str_replace ( "<a ", "<a rel='nofollow' title = '". $itemTitle . "' ", $description ); // Add "nofollow" and item title to all hyperlinks


                                                $siteurl = get_bloginfo ('wpurl');
                                                $newcontent .= "<b><a rel='nofollow' href='" . $href . "' title = '". $itemTitle . "'>" .
                                                        $title .
                                                        "</a></b><br />";
                                                        
                                               if ($click_details == TRUE)
                                               
                                               {
                                               $description .= "<a href='" . $href . "' rel='nofollow' title='".$title."'><img src='".$siteurl."/wp-content/plugins/wordbay/click-for-details.png' alt='Click for more details' id='clickicon'></a>";}
                                               
                                               $newcontent .= $description."</li></div>";
                                               if ( $i == $first_maxitems ) 
                                                        break;
                                        }
                                        $newcontent .= "<div style='clear: both;'></div>";
                                }}
                                

                                 // Expanded search and search box routine
                                $ebayurl = 'http://rover.ebay.com/rover' .
                                        '/1/' .
                                        $first_placementID .
                                        '/1?type=3&campid=' .
                                        $first_campid .
                                        '&toolid=10001&customid=all-results&ext=' .
                                        $searchterms .
                                        '&satitle=' .
                                        $searchterms;
                                $ebayurl = "search-eBay___" . urlencrypt ( $ebayurl );
                                $goebayurl = $buyurl .
                                        "buyurl=" .
                                        $ebayurl;
                                        $longsearchterms = preg_replace ( '#[^a-zA-Z0-9]#', ' ', $searchterms);
                                        $explongsearchterms = explode(" ", $longsearchterms);
                                        $longsearchterms = $explongsearchterms[0];
                                $innersearchurl = $long_search_link .
                                        urlencode ($longsearchterms );
                                if ( ( $WBOptions['show_expanded_link'] == TRUE ) || ( $WBOptions['show_search_box'] == TRUE ) ) {
                                        if ( $was_search == FALSE ) {
                                                $stilltext = 'Didn\'t find what you were looking for?';
                                        } else {
                                                $stilltext = 'Still haven\'t found what you were looking for?';
                                        }
                                } else {
                                        $stilltext = '';
                                        
                                }
                                $newcontent .= '<h2>' .
                                        $stilltext .
                                        '</h2>';
                                if ( $WBOptions['show_search_box'] == TRUE ) {
                                        $newcontent .= "<h4>Product search</h4>";
                                        $newcontent .= "<form method='get' id='searchform' action='";
                                        $newcontent .= $search_page_link;
                                        $newcontent .= "'><div id = 'WBsearchbox'><input type='text' value='' name='WBsearch' id='search' />  
<input type='submit' id='searchsubmit' value='Search' />  
</div>  
</form>";
                                }
                                if ( $WBOptions['show_expanded_link'] == TRUE ) {
                                        $newcontent .= "<h3><span style='text-align: right;'><a href='";
                                        if ( $was_search == FALSE ) {
                                                $newcontent .= $innersearchurl;
                                        } else {
                                                $newcontent .= $goebayurl;
                                        }
                                        if ( $WBOptions['show_search_box'] == TRUE ) {
                                                if ( $was_search == TRUE )

                                                         {
                                                                $newcontent .= "'>...or click here to see more items on eBay.</a></span></h3>";
                                                } else {
                                                        $newcontent .= "'>...or click here to see similar items.</a></span></h3>";
                                                }
                                        } else {
                                                if ( $was_search == TRUE ) {
                                                        $newcontent .= "'>Click here to see more items on eBay.</a></h3></span>";
                                                } else {
                                                        $newcontent .= "'>Click here to see similar items.</a></h3></span>";
                                                }
                                        }
                                }
                                $newcontent .= "</div>";
                                if ( $was_search == TRUE ) {
                                        $combinedcontent = $oldcontent .
                                                $newcontent;  // Just tack listing onto content since we got here through a search, i.e. no WordBay tags

                                       
                                } else {
                                        $combinedcontent = preg_replace ( '#\\[wordbay\\](.*?)\\[\\/wordbay\\]#is', $newcontent, $oldcontent ); // Insert listing in place of [wordbay] tags

                                }
                                return $combinedcontent;
                        } else

                                // What to do if this is just the empty search page  
                                {
                                $newcontent .= "<h4>Try an eBay product search</h4>";
                                $newcontent   .= "<form method='get' id='searchform' action='";
                                $newcontent   .= $search_page_link;
                                $newcontent .= "'><div><input type='text' value='' name='WBsearch' id='search' />  
<input type='submit' id='searchsubmit' value='Search' />  
</div>  
</form>";
                                $combinedcontent = $oldcontent .
                                        $newcontent;
                                return $combinedcontent;
                        }
                }

                function WBheader ( )
                {
                        echo "<link type='text/css' rel='stylesheet' href='" .
                                get_bloginfo('wpurl') .
                                "/wp-content/plugins/wordbay/WordBay.css' />" .
                                "\n"; /* Insert CSS link for WordBay classes in header */

                        
                }

                function getAdminOptions ( )  // Set default option values for when WordBay is first installed
                {
                        $WordBayAdminOptions = array(
                                'placement_id'       => '711-53200-19255-0',
                                'camp_id'            => '5336549027',
                                'location_id'        => '1',
                                'generosity'         => '5',
                                'minbids'            => '1',
                                'maxitems'           => '10',
                                'site_id'            => '0',
                                'show_signup'        => TRUE,
                                'show_expanded_link' => FALSE,
                                'show_search_box'    => FALSE,
                                'search_page'        => '',
                                'sacat'              => '-1',
                                'minprice'           => '',
                                'maxprice'           => '',
                                'clickdetails'       => TRUE,
                                'columns'            => 2,
                                'geo_on'             => FALSE,
				                    'geoLocOverride'     => FALSE,
				                    'mouseovercolour'    => '',
				                    'mouseoutcolour'     => '',
				                    'BuyUrlLoc'          => '',
				                    'eBayCategories'     => array(),
				                    'sellers'            => '',
                                'WordBayCSS'         => '',
								'amazon_tag' => '',							
							'amazon_search_link' => TRUE
                        );


                        $WBOptions = get_option ( $this -> adminOptionsName );
                        if ( !empty ( $WBOptions ) ) {
                                foreach ( $WBOptions as $key => $option ) 
                                        $WordBayAdminOptions[$key] = $option;
                        }
                        update_option ( $this -> adminOptionsName, $WordBayAdminOptions );
                        return $WordBayAdminOptions;
                }

                function DisplayWordBayAdminPage ( )  // Code for admin options page

                
                {
                        $WBOptions = $this -> getAdminOptions ( );
                        if ( isset ( $_POST['update_WordBayAdminSettings'] ) ) {
                                if ( isset ( $_POST['camp_id'] ) ) {
                                        $WBOptions['camp_id'] = $_POST['camp_id'];
                                }
                                if ( isset ( $_POST['placement_id'] ) ) {
                                        $WBOptions['placement_id'] = $_POST['placement_id'];
                                }
                                if ( isset ( $_POST['location_id'] ) ) {
                                        $WBOptions['location_id'] = $_POST['location_id'];
                                }
                                if ( isset ( $_POST['site_id'] ) ) {
                                        $WBOptions['site_id'] = $_POST['site_id'];
                                }
                                if ( isset ( $_POST['generosity'] ) ) {
                                        $WBOptions['generosity'] = $_POST['generosity'];
                                }
                                if ( isset ( $_POST['minbids'] ) ) {
                                        $WBOptions['minbids'] = $_POST['minbids'];
                                }
                                if ( isset ( $_POST['maxitems'] ) ) {
                                        $WBOptions['maxitems'] = $_POST['maxitems'];
                                }
                                if ( isset ( $_POST['show_signup'] ) ) {
                                        $WBOptions['show_signup'] = TRUE;
                                } else 
                                        $WBOptions['show_signup'] = FALSE;
                                if ( isset ( $_POST['show_expanded_link'] ) ) {
                                        $WBOptions['show_expanded_link'] = TRUE;
                                } else {
                                        $WBOptions['show_expanded_link'] = FALSE;
                                }
                                if ( isset ( $_POST['show_search_box'] ) ) {
                                        $WBOptions['show_search_box'] = TRUE;
                                } else {
                                        $WBOptions['show_search_box'] = FALSE;
                                }
                                if ( isset ( $_POST['search_page'] ) ) {
                                        $WBOptions['search_page'] = $_POST['search_page'];
                                }
                                if ( isset ( $_POST['sacat'] ) ) {
                                        $WBOptions['sacat'] = $_POST['sacat'];
                                }
                                if ( isset ( $_POST['minprice'] ) ) {
                                        $WBOptions['minprice'] = $_POST['minprice'];
                                }
                                if ( isset ( $_POST['maxprice'] ) ) {
                                        $WBOptions['maxprice'] = $_POST['maxprice'];
                                }
                                
                                if ( isset ( $_POST['clickdetails'] ) ) {
                                        $WBOptions['clickdetails'] = TRUE;
                                } else {
                                        $WBOptions['clickdetails'] = FALSE;
                                        }
                                if ( isset ( $_POST['geo_on'] ) ) {
                                        $WBOptions['geo_on'] = TRUE;
                                } else {
                                        $WBOptions['geo_on'] = FALSE;
                                        }
                                if ( isset ( $_POST['mouseoutcolour'] ) ) {
                                        $WBOptions['mouseoutcolour'] = $_POST['mouseoutcolour'];
                                        }
                                
                                if ( isset ( $_POST['mouseovercolour'] ) ) {
                                        $WBOptions['mouseovercolour'] = $_POST['mouseovercolour'];
                                        }
                                
                                if ( isset ( $_POST['BuyUrlLoc'] ) ) {
                                        $WBOptions['BuyUrlLoc'] = $_POST['BuyUrlLoc'];
                                        }
                                        
                                        
				if ( isset ( $_POST['geoLocOverride'] ) ) {
                                        $WBOptions['geoLocOverride'] = TRUE;
                                } else {
                                        $WBOptions['geoLocOverride'] = FALSE;
                                        }        
                                        
                                if ( isset ( $_POST['columns'] ) ) {
                                        $WBOptions['columns'] = $_POST['columns'];
                                }
                                
                                if (isset ( $_POST['eBayCategories']))
                                {
                                foreach ($_POST['eBayCategories'] as $varSiteID => $vareBayCat)
                                {
                                $WBOptions['eBayCategories'][$varSiteID] = $vareBayCat;
                                }
                                }
                                $WBOptions['sellers'] = $_POST['sellers'];
              if ( isset ( $_POST['WordBayCSS'] ) ) {
                                        $postedCSS = stripslashes($_POST['WordBayCSS']);
                                        $WBOptions['WordBayCSS'] = $postedCSS;
                                        $WBCSSfile = getPluginPath() . '/WordBay.css';
										
										if ( isset ( $_POST['amazon_tag'] ) ) {
                                            $WBOptions['amazon_tag'] = $_POST['amazon_tag'];
                                        }
										
																				if ( isset ( $_POST['amazon_search_link'] ) ) {
                                            $WBOptions['amazon_search_link'] = TRUE;
											} else {
											$WBOptions['amazon_search_link'] = FALSE;
}

                                          $WBCSSdata = $postedCSS;
                                       file_put_contents($WBCSSfile, $WBCSSdata);
                                        }
                                update_option ( $this -> adminOptionsName, $WBOptions );
                                ?>  
<div class="updated"><p><strong><?php _e ( "Settings Updated.", "WordBay" );?></strong></p></div>  
                    <?php
                        }?>  


<div style = 'clear:both; width: 90%; padding: 1em; border: 3px dashed #b30000;'><div style = "width: 440px; margin: 16px 0px 8px 8px; float: left; clear: none;"><h2><span style="color: #b30000;">IMPORTANT NOTICE</span></h2>
<p>Development of this version of the Wordbay eBay plugin <strong>has been discontinued</strong> and it will probably <strong>STOP WORKING</strong> after June 30th 2011 due to changes to the eBay RSS feed. A new version of the plugin (v1.5+) is available that is a vast improvement in every way, is compatible with the new eBay feed and also resolves some major performance issues that remain in this old version.</p> <p>Along with the new version there is also a new site with a forum and a growing collection of resources. Use the button on the right and find out more about the NEW Wordbay (or click the link to see a live demo - you won't recognise the old plugin)!</div><div style = "float: left; margin: 3em 0 0 2em;"><a href='http://www.thewordbay.com/get-the-wordbay-plugin/'><img src = 'http://www.thewordbay.com/wp-content/themes/wordbay/images/homepagectabutton.png' alt = 'Click here to find out more' /></a><p style = "color: #b30000; text-align: center; font-size: 1em;"><strong><a style = 'color: #b30000;' href = 'http://www.thewordbay.com/wordbay-live-demo/'>Or check out the LIVE DEMO HERE!</a></strong></p></div><div style='clear: both;'>&nbsp;</div></div>


<div class="wrap">


<?php
    if (!$WBOptions['amazon_tag'] && $WBOptions['amazon_search_link'] == TRUE)
		{
		echo '<div class=\'updated fade\'><p><strong>NOTE: The plugin is currently displaying Amazon.com links on \'no listings\' pages, but using the author\'s default Amazon tag as you have not set your own - he will be very grateful if you leave it like that, but if you want to opt out of this, remember to either uncheck the Show Amazon Search Link option in the admin panel or enter your own Amazon affiliate tag <a href="#revenue">here</a> if you want to earn from Amazon referrals. You can also opt to support WordBay by sharing Amazon revenue with the author using the \'generosity\' option below.</strong></p></div>';
  }   
if (!$WBOptions['camp_id']){
      echo '<div class="updated fade"><p><strong>NOTE: you have not set an EPN campaign ID in your settings <a href="#revenue">here</a>. If you want to earn from eBay referrals then you should set this! Also, it is a crying shame to send free traffic to eBay!</strong></p></div>';
  }
?>  


<div style="width: auto; float: right;">
<h2>WordBay WordPress eBay listings plugin</h2>

<table class="form-table" >
<tr valign="top">  
<th scope="row">Share the love</th><td>This old version of WordBay is free to use. However, you can share a percentage of your Amazon impressions (which aren't going to be huge, most likely!) via the 'Generosity' option below <span style="color: #ff0000;">(currently set to <b><?php _e ( apply_filters ( 'format_to_edit', $WBOptions['generosity'] ), 'WordBay' )?>&#37;</b>)</span>.<br />
<a href='http://www.itsgottabered.com/wordbay'>WordBay</a> is copyright Mark Daniels 2008-2010, all rights reserved, issued under GNU/GPL.</td></tr></table>



<h3>eBay listings settings</h3>
Note: there are a number of eBay sites, but not all of them allow referrals through the EPN program - however, some of those DO allow you to list items from the relevant site. For a discussion on this, have a look <a href="http://www.itsgottabered.com/wordbay/2008/a-discussion-of-ebay-sites/">here</a>.<br />
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>">  
<table class="form-table">

<tr valign="top">
<th scope="row">Geotargetting</th>  
<td><input type="checkbox" name="geo_on" <?php if ( $WBOptions['geo_on'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  Check to switch on geotargetting (thanks Jez). Your visitor's geographic location will be determined from their IP address. This location is then used to display listings from the relevant eBay country site (if there is one), and to display the relevant &quot;Sign-up&quot; link. If it fails to make a match, usually because the visitor's country has no eBay site, then the site you specify below will displayed as your default. <b><span style="color: #ff0000;">PLEASE NOTE:</b> Geotargetting is NOT an exact science</span> - <b>use carefully</b>. There may be a small number of false matches. Also, I have not been able to test all countries - please make sure you verify clicks are being tracked for yourself! Remember also that some countries DO have an eBay site, but not an EPN program (e.g. Hong Kong, Singapore), so you will be sending eBay free traffic! Not much we can do about that.</td>
</tr>


<tr valign="top">  
<th scope="row">Referral locale</th>  
<td>
<select name='placement_id' onchange=''>  


<?php
$locals = country("../");
for ($i = 0; $i < sizeof($locals); $i++){
    $menuPID = $locals[$i][3];
    if ($locals[$i][5] == 1)
            {
            $CountryName = "(". $locals[$i][0] .")";
            }
    else
            {
            $CountryName = $locals[$i][0];
            }
    if ($menuPID) {
    echo "<option value='" . $menuPID . "'";
    if ( $WBOptions['placement_id'] == $menuPID ){
        _e ( 'selected', 'WordBay' ); 
    }
    echo ">" . $CountryName . "</option>";}
}
?>

// end mod JH

</select><br/>  
This is the eBay site you wish to refer visitors to if they click on the "Sign up to eBay" link. It does <b>not</b> determine what eBay country site the product listings are taken from - this is done by the other settings below. Presumably you would want this to be the same country as your listings are from. This is also affected by geotargetting. If you have geotargetting switched on and it fails to match a country, then this will be the default country shown. <b>Countries in brackets are those for which there is no EPN program, so you will not earn commissions from referrals to them.</b>.</td></tr>  
<tr valign="top">  
<th scope="row">Item location</th>  
<td><select name='location_id'>

<?php
$locals = country("../");
for ($i = 0; $i < sizeof($locals); $i++){
    
    if ($locals[$i][6] != 'x') {   
    $CountryName = $locals[$i][0];
          
    
    echo "<option value='" . $locals[$i][6] . "'";
    
    if ( $WBOptions['location_id'] == $locals[$i][6] ){ 
        _e ( 'selected', 'WordBay' ); 
    }
    echo ">" . $CountryName . "</option>"; }
}
?>
</select><br/>  
Here you should indicate what the location should be for items appearing in listings. Items can be listed in one country but dispatched from another. People tend to order items that are dispatched from their own country, so you will very likely make this match the rest of your settings. There is an <b>Any country/region</b> setting though, if you wish.  
<br />
<input type="checkbox" name="geoLocOverride" <?php if ( $WBOptions['geoLocOverride'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  <b>Allow geo-targetting to override this setting</b><br />If you are using geo-targetting, you may wish this setting to be overridden. If you check this box, geo-targetting will identify the visitor's country and also change the item location accordingly. So if they come from Sweden, they will get Swedish listings AND items dispatched from Sweden. However, if you prefer to use 'Any country/region' then you will want to keep this unchecked too.</td>

</tr>  
<tr valign="top">  
<th scope="row">eBay Site ID</th>  
<td><select name="site_id">
<?php
$locals = country("../");
for ($i = 0; $i < sizeof($locals); $i++){
    $menuSiteID = $locals[$i][2];
    if ($locals[$i][5] == 1)
            {
            $CountryName = "(". $locals[$i][0] .")";
            }
    else
            {
            $CountryName = $locals[$i][0];
            }
    if ($locals[$i][4] != "0") {
    echo "<option value='" . $menuSiteID . "' ";
    if ( $WBOptions['site_id'] == $menuSiteID ){
        _e ( 'selected', 'WordBay' ); 
    }
    echo ">" . $CountryName . "</option>";}
}
?> 

  
</select><br/>  
Choose which eBay site (eBay UK, eBay US etc.) listings are to be taken from. If you have geotargetting switched on and it fails to match a country, then this will be the default eBay site used. eBay sites listed in brackets CAN be used (we think), but they are not part of the EPN program, so no commissions. eBay Motors is a special "site" only applicable to the US - if using this then choose a Motors-related category below for the US field. However, though UK has a Motors section, it does not behave as a site the same as US, it is simply a set of sub-categories like any other and you need to choose eBay UK here if this is to be your primary target.</td>  
</tr>

<tr valign="top">  
<th scope="row">eBay product categories</th>  
<td>
<strong>NEW - action required!</strong> Specify a product category code for each country you plan to target. More info below.<br>
<?php
$locals = country("../");
$oddOrEven = "odd";
echo "<table>";
for ($i = 0; $i < sizeof($locals); $i++){
    
    $menuSiteID = $locals[$i][2];
	
	if ($menuSiteID) {
    $menuCat = $WBOptions['eBayCategories'][$menuSiteID];
    }
	$catListLink = $locals[$i][7];
    if (!isset ($menuCat))
    {$menuCat = -1;}
    if ($locals[$i][5] == 1)
            {
            $CountryName = "(". $locals[$i][0] .")";
            }
    else
            {
            $CountryName = $locals[$i][0];
            }
    if ($locals[$i][4] != "0") {
    
    if ($oddOrEven == "odd")
    {
    echo "<tr>";
    }
    echo "<td style = 'border: 0;'>".$CountryName.": <br><small><a href = '".$catListLink."' target = '_blank'>(category codes)</a></small></td><td style = 'border: 0;'> <input type = 'text' name = 'eBayCategories[".$menuSiteID."]' value='" . $menuCat . "' ></td>";
    if ($oddOrEven == "odd") 
    {
    $oddOrEven = "even";
    }
    else
    {
    $oddOrEven = "odd";
    echo "</tr>";
    }
    
    }
        
   
    }
    echo "</table>";

?>

<br/>  
Want my advice? Leave these all at "-1" (All categories), save yourself a load of hassle and just be clever about your choice of keyword. If you must, then specify the product category to be used for each country. If you are not using geotargetting, then you DON'T need to set all of these, just the country you chose as your "listings" country. Otherwise, sorry, but the codes are different for each country (not my idea), so there is no other option that I can see than to specify a category for each country. I have provided links to the category listings for most of these (beside each country name) - once this page opens, the number in brackets after the product category name (without the # or N) is the one you want to put in here - good luck with interpreting those product names! Some of the countries are a mystery and I have not found category code listings for those, so just stick with the countries you CAN find and leave the rest at "-1", not all countries are really available through EPN anyway, that's why they are in brackets, as above. The major ones are OK (UK, US, Canada, Australia etc.)</td>
</tr>
<tr valign="top">
<th scope="row">Specify sellers by ID</th>
<td><input type="text" name="sellers" style="width: 40em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['sellers'] ), 'WordBay' )?>"><br/>
Limit listings only to certain seller's. Enter their ID's here, separated by commas - <strong>NO SPACE after the comma, or it won't work.</strong>. Leave blank to list items from all sellers.</td>
</tr>

<tr valign="top">
<th scope="row">Minimum number of bids to show</th>  
<td><input type="text" name="minbids" style="width: 3em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['minbids'] ), 'WordBay' )?>"><br/>  
This limits your listings to items with the minimum number of bids as specified above. It's quite common to put 1, because this filters out items people may not be interested in. This also gets rid of a lot of the "Buy Now"-type items listed by professional sellers, and means you get more stuff from private sellers. On the other hand, if you are too restrictive you are likely to get very few or no items listed.<br/></td>  
</tr>  
<tr valign="top">  
<th scope="row">Maximum number of items to show in listing</th>  
<td><input type="text" name="maxitems" style="width: 3em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['maxitems'] ), 'WordBay' )?>"><br/>  
This is the maximum number of items to show in your listings. I suggest 10 is a good upper limit - I have not yet investigated server load, or eBay's tolerance - the maximum available through the feed is 60 in any case. It's good to make this a multiple of the number of columns you have chosen (currently <?php _e ( apply_filters ( 'format_to_edit', $WBOptions['columns'] ), 'WordBay' )?>) to get a more orderly display.<br/></td>  
</tr>
<tr valign="top">  
<th scope="row">Minimum and maximum price to list</th>  
<td>Minimum price: <input type="text" name="minprice" style="width: 6em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['minprice'] ), 'WordBay' )?>"><br/>
Maximum price: <input type="text" name="maxprice" style="width: 6em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['maxprice'] ), 'WordBay' )?>"><br/> 
This limits your listings to items priced within the range as specified above. Do not use a currency sign, just a number like <b>2.55</b>, which will be applied to the currency your site uses. This is often used to weed out low-ticket items - for example if you have an eBay Motors niche site you just want to list Ferraris, NOT Ferrari bumpers/fluffy dice/stickers, so you can limit the listings to items, say OVER 20000, and see if that gets you what you want.<br/></td>  
</tr>
</table>  
<br/>  
<h3>Display settings</h3>  
<table class="form-table">
<tr valign="top">  
<th scope="row">Number of columns</th>  
<td><input type="text" name="columns" style="width: 3em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['columns'] ), 'WordBay' )?>"><br/>  
Columns to display the listings in. <b>Setting this to more than 1 may appear to break your display, worry not, you need to edit the</b> wordbay.css<b> file, find the </b>.firstlocaleitem<b>  and change the property of </b>height:</b> to enough pixels to contain even the tallest product item. This will make sure they are all the same height and stop them floating about strangely. Also, it is just conceivable that the "padding" setting for is too great for </b>.firstlocaleitem<b> and you need to reduce it slightly to stop the boxes overrunning.</td>
</th></tr>  
<tr valign="top">  
<th scope="row">Show CLICK for details icon</th>  
<td><input type="checkbox" name="clickdetails" <?php if ( $WBOptions['clickdetails'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  Show a "CLICK for details" button below the image, linking to the item page on eBay. This may improve click-through slightly as it tells people what to do! You can change this image, just replace the <b>click-for-details.png</b> in the WordBay folder with your own.<br/></td></tr>
<!-- mouseover stuff -->

<tr valign="top">  
<th scope="row">Locate &quot;buy.php&quot; file</th>  
<td><input type="text" style="width: 100%; margin: 0 0 8px 0;" name="BuyUrlLoc" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['BuyUrlLoc'] ), 'WordBay' )?>"><br/>Specify the location and name of your <b>buy.php</b> file. This is the file that decodes and redirects your masked affiliate links. By default it is installed automatically in the WordBay plugin directory, which makes it simple for everyone to install. However, if you want to move it, say to the root of your domain, to make the link look tidier (i.e. get rid of the wp-content/plugins path) and also change the name of the actual file to, say, &quot;shop.php&quot; to maybe reduce the &quot;footprint&quot; then you can do it here. Just put the buy.php in the directory where you want it and rename it to what you want, and put the full path here, including the<b>http://</b>.<br/></td> 

</tr>

<tr valign="top">  
<th scope="row">Mouseover colours</th>  
<td>Mouse not over: #<input type="text" name="mouseoutcolour" style="width: 6em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['mouseoutcolour'] ), 'WordBay' )?>"><br/>
Mouse over: #<input type="text" name="mouseovercolour" style="width: 6em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['mouseovercolour'] ), 'WordBay' )?>"><br/> 
When you hover the mouse over an item, the background colour will change to the &quot;Mouse over&quot; colour. When you move the mouse away it will revert to the other colour. If you do not specify either, the default CSS colour will be used. Your &quot;Mouse not over&quot; colour should in any case be the same as your default colour else you will get a strange effect!<br/><strong>Use a 6-digit hex colour like ffffff - with no #!</strong><br/></td>
</tr>

<!-- mouseover stuff end -->


<tr valign="top">
<th scope="row">Show sign-up link</th>  
<td><input type="checkbox" name="show_signup" <?php if ( $WBOptions['show_signup'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  Show the link at the top of the listings that says "Not registered with eBay? Click to sign up". May increase the chances of getting ACRUs (new eBay user sign-up commission, worth having).<br/></td></tr>  
<tr valign="top"> 
  
<th scope="row">Show expanded search link</th>  
<td><input type="checkbox" name="show_expanded_link" <?php if ( $WBOptions['show_expanded_link'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  Ditto, but for the link at the bottom of the listings that says "Click here to expand your search". All this does is display the same results but including ALL items, regardless of the number of bids. So if you have <b>Minimum bids</b> set to zero anyway then this is pointless. After a second unsuccessful search this link will point direct to eBay, so you may or may not want this behaviour too.<br/><b><span style="color: #ff0000;">(see note below!)</span></b>.<br/></td></tr>  
<tr valign="top">  
<th scope="row">Show search box</th>  
<td><input type="checkbox" name="show_search_box" <?php if ( $WBOptions['show_search_box'] == TRUE ) {
                                _e ( 'checked', 'WordBay' );
                        }?>>  Include a search box at the bottom of the listings.    
<br/><br/> 
<input type="text" style="width: 100%; margin: 0 0 8px 0;" name="search_page" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['search_page'] ), 'WordBay' )?>"><br/><span style="color: #ff0000;">If you enable the second two options above, ("expanded search link" and "search box", you MUST create a search page and specify the permalink to this page in the field above. This can be just a regular empty page, but better not call it "search", call it something unique. Paste the full link here (including http:// and trailing slash).<b><br/>These two options currently only work if you are running Wordpress with SEF permalinks (you are, right?!), otherwise you will get 404 errors!</span></b><br/></td> 

</tr>

<tr valign="top">
                    <th scope="row">
                        <a style="cursor:help;" title="<?php _e('Click for Help!', 'WordBay')?>" onclick="toggleVisibility('amazon_search_link');">
                        <?php _e('Display Amazon search link when listing fails', 'WordBay')?>  <img src = "<?php echo plugins_url('helpicon.png', __FILE__)?>" title="Click for help"/>
                        </a>
                    </th>
                <td>
                    <input type="checkbox" name="amazon_search_link" <?php if ( $WBOptions['amazon_search_link'] == TRUE ) { _e ( 'checked', 'WordBay' ); }?>>
                    <br/><span style = "color: red">NEW in BETA 1.3.6!</span>
                    <div class="description" style="max-width:<?php $wb_help_text_width ?>; text-align:left; display:none" id="amazon_search_link">
                        <?php
                        _e('Display a direct Amazon search link, using your Amazon Associates affiliate tag if you have set it below. Listings (or search results) sometimes just fail to come up with any results from eBay, so in order to maximise sales this option (on by default) allows you to show a direct Amazon link too. This is not geotargetted and probably will not be in the future, it currently directs ALL visitors to Amazon.com (not UK, DE etc.). It uses the same search terms as the eBay search for that page.', 'WordBay');
                         ?>
                    </div>
                </td>
                </tr>


<tr valign="top">
<th scope="row">Stylesheet</th>
<td>
<p>Below are the contents of your <strong>WordBay.css</strong>, located in your plugin directory - you can edit your styles below and as soon as you Update Settings, all changes will be reflected in the <strong>WordBay.css</strong> file and on your site of course.</p>
<?php
// check if there is already a stylesheet in memory, if so, retrieve and skip the rest
if ($WBOptions['WordBayCSS'])
{
   $varWBCSS = $WBOptions['WordBayCSS'];
   if (!file_exists(getPluginPath() . '/WordBay.css'))	

   {
      ?><p style="color: red;">No existing <strong>WordBay.css</strong> was found in your plugin directory (either you have just installed a new version of WordBay, or you deleted it for some reason). A previous css file was found in memory and saved as <strong>WordBay.css</strong>.</p><?php
      file_put_contents(getPluginPath() . '/WordBay.css', $varWBCSS);	
   }


}

else if (file_exists(getPluginPath() . '/WordBay.css'))
   {
	  $WBCSSdata = stripslashes(file_get_contents(getPluginPath() . '/WordBay.css'));
	  $WBOptions['WordBayCSS'] = $WBCSSdata;
     update_option ( $this -> adminOptionsName, $WBOptions );
     $varWBCSS = $WBCSSdata;
     ?>
   
   
 <p style="color: red;"><strong>WordBay.css</strong> was found in your plugin directory. You should edit it here from now on - editing it directly will no longer work as of version 1.15 of WordBay.</p>
  <?php }

else if (file_exists(getPluginPath() . '/WordBay.css.default'))
{
$WBCSSdata = stripslashes(file_get_contents (getPluginPath() . '/WordBay.css.default'));
$varWBCSS = $WBCSSdata;
file_put_contents(getPluginPath() . '/WordBay.css', $WBCSSdata);
$WBOptions['WordBayCSS'] = $WBCSSdata;
update_option ( $this -> adminOptionsName, $WBOptions );
?>
<p style="color: red;">No existing <strong>WordBay.css</strong> was found in memory or on disk. Default .css stylesheet will be used as <strong>WordBay.css</strong>.</p>
<?php

}
else {
$varWBCSS = '';

?><p style="color: red;">WordBay couldn't find a css stylesheet either in memory or on disk. You need one! Please get the <strong>WordBay.css.default</strong> file from the WordBay plugin zip file and put it in your <strong>wordbay</strong> plugin directory, then refresh this screen. Alternatively, copy/paste the contents of that file here and save your settings.</p>
<?php	
}


?>
<textarea name="WordBayCSS" cols=80 rows=20><?php echo $varWBCSS;?></textarea><br/>
</td>  
</th></tr>    
</table>  
<h3>Revenue settings</h3>  
<table class="form-table">  
<tr valign="top">  
<th scope="row">Ebay Partner Network Campaign ID</th>  
<td><input type="text" name="camp_id" style="width: 10em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['camp_id'] ), 'WordBay' )?>"><br/>  
If you want to earn from displaying eBay listings you need to be a member of <a href='https://www.ebaypartnernetwork.com'>eBay Partner Network</a> - then you can generate a Campaign ID from your dashboard and insert it here. This will enable you to earn commission from sales through your site, and track where they came from.<br/><br/>  
There is also a Custom ID setting available through EPN, like an additional tracking ID. In WordBay this is currently set to automatically insert the URL of the WordPress page or post that the click/sale came from, which seems like a good solution - there is not much point in having a static custom ID.</td>  
</tr>  

			            <tr valign="top">
             
				<th scope="row">Amazon affiliate tag</th>


                <td>
                    <input type="text" name="amazon_tag" style="width: 10em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['amazon_tag'] ), 'WordBay' )?>"><br/>
                    
                        <?php
                        _e('If you have opted to display Amazon referral links on your WordBay site, you will want to include your Amazon.com (i.e. US Amazon) affiliate tag here in order to earn from referrals. For that you need to be a member of <a href =\'https://affiliate-program.amazon.com\'>Amazon Associates</a>. If you do not specify a link here but still display Amazon links, all referrals will be credited to the author of WordBay, which he won\'t mind at all so you are welcome to do that. The generosity option below can be used instead and now ONLY applies to Amazon referrals - sharing EPN impressions is now against the EPN TOS (long story).', 'WordBay');
                         ?>
                    
                </td>
            </tr>





<tr valign="top">
<th scope="row">Generosity</th>  
<td><input type="text" name="generosity" style="width: 3em;" value="<?php _e ( apply_filters ( 'format_to_edit', $WBOptions['generosity'] ), 'WordBay' )?>">%<br/>  
This is how generous you want to be to the author of WordBay, as a percentage. 5% means that a measly 5% of the time WordBay inserts the author\'s Amazon tag instead of yours (if you are displaying Amazon links, which are ON by default), which is a way of saying "thank you" for all that work he put into it (though I still suggest you get the new version of Wordbay instead).<br/></td>  
</th></tr></table>  
<div class="submit">  
<input type="submit" name="update_WordBayAdminSettings" value="<?php _e ( 'Update Settings', 'WordBay' )?>" /></div>
</form>
</div>
<?php
                }

                function WordBay ( $content = '' )

                // Checks if we got here via a GET search query or whether we are looking for the [wordbay] tags, and passes the relevantkeywords to the main listing function 
                {
                        $WBOptions = $this -> getAdminOptions ( );
                        global $post;
                        $thePost = get_permalink ( );
                        if ( $thePost == $WBOptions['search_page'] && !isset ( $_GET["WBsearch"] ) ) {

                                // Checking to see if we are on the search page but with no search terms
                                 
                                $searchterms = '';
                                $was_search    = FALSE;
                                $is_searchpage = TRUE;
                                $content       = WordBay :: WBlisting ( $content, $searchterms, $was_search, $is_searchpage, FALSE );
                                
                        } elseif ( preg_match ( '#\\[wordbay\\](.*?)\\[\\/wordbay\\]#is', $content, $searchterm ) ) // Find the WordBay tags

                                 {
                                $searchterms = $searchterm[1];
                                $was_search    = FALSE;
                                $is_searchpage = FALSE;
                                $content       = WordBay :: WBlisting ( $content, $searchterms, $was_search, $is_searchpage, FALSE );
                                
                        } elseif ( isset ($_GET["WBsearch"] )) {
                                $searchterms   = htmlspecialchars($_GET["WBsearch"]);
                                $was_search    = TRUE;
                                $is_searchpage = FALSE;
                                $content       = WordBay :: WBlisting ( $content, $searchterms, $was_search, $is_searchpage, FALSE );
                                
                        } 
                                 return $content;
                } 
        } 
}
// End WordBay class
// Necessary WordPress plugin hooks and stuff
if ( class_exists ( "WordBay" ) ) {
        $dl_WordBay = new WordBay ( );
}
// WordBay Actions and Filters
if ( isset ( $dl_WordBay ) ) {
        // WordBay Actions
        add_action ( 'activate_WordBay/WordBay.php', array ( &$dl_WordBay, 'init' ) );
        add_action ( 'admin_menu', 'WordBay_adminpage' );
        add_action ( 'wp_head', array ( &$dl_WordBay, 'WBheader' ), 1 );
        // WordBay Filters
        add_filter ( 'the_content', array ( &$dl_WordBay, 'WordBay' ) );
}
if ( !function_exists ( "WordBay_adminpage" ) ) {

        function WordBay_adminpage ( )
        {
                global $dl_WordBay;
                if ( !isset ( $dl_WordBay ) ) {
                        return;
                }
                if ( function_exists ( 'add_options_page' ) ) {
                        add_options_page ( 'WordBay options', 'WordBay', 9, basename ( __FILE__ ), array ( &$dl_WordBay, 'DisplayWordBayAdminPage' ) );
                }
        }
}
?>
