<?php if(!isset($common_included_flag)) { require_once '/home/a6325779/public_html/includes/common.php'; } ?>

<!-- mobile-nav -->		
<div id="mobile-nav-holder">
	<div class="wrapper">
		<ul id="mobile-nav"> 
			<li><a href="/">MyHRSFC</a></li>
					
			<li>
				<a href="/who.html">The Council</a>
				<ul>
					<li><a href="/who.html">Meet the council</a></li>  
					<li><a href="/policies.html">Policy Tracker</a></li>
					<li><a href="mailto:studentcouncil14@hillsroad.ac.uk">Email the council</a></li>
					<li><a href="mailto:<?php echo getCouncillorEmail('commsweb'); ?>" target="_blank">Email the webmaster</a></li>
				</ul>
			</li>
				   
			<li>
				<a href="/news.html">Policies</a>
				<ul>
					<li><a href="/policies.html">Policy Tracker</a></li>
					<li><a href="/trains.html">Live Train Times</a></li> 
					<li><a href="mailto:<?php echo getCouncillorEmail('secretary'); ?>" target="_blank">Email the secretary</a></li>
				</ul>
			</li>
	
			<li>
				<a href="/support.html">Support</a>
				<ul>
					<li><a href="/support.html">Student Welfare</a></li>
					<li><a href="/ead.html">Equality &amp; Diversity</a></li>
					<li><a href="/acf.html">Anonymous Contact</a></li>
				</ul>
			</li>
			
			<li>
				<a href="/committees.html">Committees</a>
				<ul>
					<li><a href="/charities.html">Charities</a></li>
					<li><a href="/finance.html">Finance</a></li>
					<li><a href="/media.html">Media</a></li>
					<li><a href="/societies.html">Societies</a></li>
					<!-- <li><a href="/environment.html">Environment</a></li> not wanted -->
				</ul>
			</li>
					
			<li>
				<a href="/events.html">Events</a>
				<ul>
					<li><a href="/events.html">All events...</a></li>
					<li><a href="mailto:<?php echo getCouncillorEmail('events'); ?>" target="_blank">Contact Your Events Officer</a></li>
				</ul>
			</li>
			
			<li>
				<a href="/nus.html">NUS</a>
				<ul>
					<li><a href="/nus.html">What is NUS?</a></li>
					<li><a href="/docs/julian_feedback.odt" target="_blank">Julian Huppert MP on Education Reforms</a></li>
					<li><a href="http://www.nus.org.uk/" target="_blank">NUS Online</a></li>
					<li><a href="http://cards.nus.org.uk/buy/default.aspx" target="_blank">Apply for an NUS card!</a></li>
				</ul>
			</li>
					
			<li><a href="/l6welcome.html">Lower 6th</a>
				<ul>
					<li><a href="/l6welcome.html">Welcome</a></li> 
					<li><a href="/faq.html">Frequently Asked Questions</a></li>
					<li><a href="/atoz.html">A to Z of Hills Road</a></li>
					<li><a href="/docs/hrmap_v2.pdf" target="_blank">College Map</a></li>
				</ul>						
			</li>   
		</ul>
		
		<div id="nav-open"><a href="#">Menu</a></div>
		
	</div>
</div>
<!-- ENDS mobile-nav -->
			
<header>
	<div class="wrapper">
			
		<a href="/" id="logo"><img  src="/img/logo.png" alt="Student Council" width="123" height="61"></a> 

		<!-- NAV MENU -->
		<nav>
			<ul id="nav" class="sf-menu">
				<li><a href="/" style = "font-size: 15px;">MyHRSFC<span class="subheader">welcome</span></a></li>
				
				<li>
					<a href="/who.html">the council<span class="subheader">who are we?</span></a>
					<ul>
						<li><a href="/who.html">Meet the council</a></li>
						<li><a href="/policies.html">Policy Tracker</a></li>
						<li class = "con"><a href="mailto:studentcouncil14@hillsroad.ac.uk">Email the council</a></li>
						<li class = "con"><a href="mailto:<?php echo getCouncillorEmail('commsweb'); ?>" target="_blank">Email the webmaster</a></li>
						<li><a href="/acf.html">Anonymous Contact</a></li>
					</ul>
				</li>
			   
				<li>
					<a href="/policies.html">policies<span class="subheader">it's what we do</span></a>
					<ul>
						<li><a href="/policies.html">Policy Tracker</a></li>
						<li><a href="/acf.html">Submit Suggestion</a></li> 
						<li><a href="/trains.html">Live Train Times</a></li> 
<?php 	#	<li><a href="/docs/newsletter.pdf" target="_blank">Newsletter</a></li>
		#	<li><a href="/docs/gm-minutes.doc" target="_blank">Latest General Meeting Minutes</a></li> ?>
						<li class = "con"><a href="mailto:<?php echo getCouncillorEmail('secretary'); ?>" target="_blank">Email the secretary</a></li>
					</ul>
				</li>
			  
				<li>
					<a href="/support.html">support<span class="subheader">here to help</span></a>
					<ul>
						<li><a href="/support.html">Student Welfare</a></li>
						<li><a href="/ead.html">Equality &amp; Diversity</a></li>
						<li><a href="/acf.html">Anonymous Contact</a></li>
					</ul>
				</li>

				<li>
					<a href="/committees.html">committees<span class="subheader">get involved</span></a>
					<ul>
						<li><a href="/charities.html">Charities</a></li>
						<li><a href="/finance.html">Finance</a></li>
						<li><a href="/media.html">Media</a></li>
						<li><a href="/societies.html">Societies</a></li>
						<!--<li><a href="/environment.html">Environment</a></li> not wanted-->
					</ul>
				</li>

				 <li>
				 	<a href="/events.html">events<span class="subheader">time to party</span></a>
					<ul>
						<li><a href="/events.html">All events...</a></li>
						<li class = "con"><a href="mailto:<?php echo getCouncillorEmail('events'); ?>" target="_blank">Contact Your Events Officer</a></li>
					</ul>
				</li>
				
				<li><a href="/nus.html">NUS<span class="subheader">your union</span></a>
					<ul>
						<li><a href="/nus.html">What is NUS?</a></li>
						<li class = "p"><a href="/docs/julian_feedback.odt" target="_blank">Julian Huppert MP on Education Reforms</a></li>
						<li class = "g"><a href="http://www.nus.org.uk/" target="_blank">NUS Online &#187;</a></li>
						<li class = "g"><a href="http://cards.nus.org.uk/buy/default.aspx" target="_blank">Apply for an NUS card&nbsp;&#187;</a></li>
						<li class = "con"><a href="mailto:<?php echo getCouncillorEmail('nus'); ?>" target="_blank">Contact Your NUS Officer</a></li>
					
					</ul>
				</li>
				
				<li class="special-menu-item"><a href="/l6welcome.html">
					<div id="L6">Lower 6th</div><span class="subheader">all the info</span></a>
					<ul>
						<li><a href="/l6welcome.html">Welcome</a></li> 
						<li><a href="/faq.html">Frequently Asked Questions</a></li>
						<li><a href="/atoz.html">A to Z of Hills Road</a></li>
						<li class = "g"><a href="/docs/hrmap_v2.pdf" target="_blank">College Map</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<!-- END NAV -->
		
		<div class="clearfix"></div>	
	</div>
</header>
<!--- END OF HEADER & NAV -->	