{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% extends 'layouts/master.volt' %}

{% block head %}

{% endblock %}

{% block header %}
	{% include "layouts/header.volt" %}
	{% include "layouts/navigation.volt" %}
{% endblock %}

{% block content %}
	<div class="container">
	  <div id="flash-container">
		{{ flashSession.output() }}
	  </div>
	  <p>This Privacy Policy governs the manner in which CodingBard collects, uses,
		maintains and discloses information collected from users (each, a "User") of
		the CodingBeard.com website ("Site"). This privacy policy applies to
		the Site and all products and services offered by CodingBard.</p>

	  <p><br></p>

	  <h3>Personal identification information</h3>

	  <p>We may collect personal identification information from Users in a variety
		of ways, including, but not limited to, when Users visit our site, register on
		the site, place an order, subscribe to the newsletter, fill out a form, and in
		connection with other activities, services, features or resources we make
		available on our Site. Users may be asked for, as appropriate, name, email
		address. Users may, however, visit our Site anonymously. We will collect
		personal identification information from Users only if they voluntarily submit
		such information to us. Users can always refuse to supply personally
		identification information, except that it may prevent them from engaging in
		certain Site related activities.</p>

	  <p><br></p>

	  <h3>Non-personal identification information</h3>

	  <p>We collect non-personal identification information about Users whenever they
		interact with our Site via Google Analytics. Non-personal identification
		information may include the browser name, the type of computer and technical
		information about Users means of connection to our Site, such as the operating
		system and the Internet service providers utilized and other similar
		information.<br>
		It is possible to disable this through various methods which may be found
		<a href="http://www.google.co.uk/search?q=disable+google+analytics+tracking"
		   target="_blank">Here</a></p>

	  <p><br></p>

	  <h3>Web browser cookies</h3>

	  <p>Our Site may use "cookies" to enhance User experience. User's web browser
		places cookies on their hard drive for record-keeping purposes and sometimes to
		track information about them. User may choose to set their web browser to
		refuse cookies, or to alert you when cookies are being sent. If they do so,
		note that some parts of the Site may not function properly.</p>

	  <p><br></p>

	  <h3>How we use collected information</h3>

	  <p>CodingBard may collect and use Users personal information for the following
		purposes:</p>

	  <ul>
		<li>
		  <p>We may use feedback you provide to improve our products and
			services.</p>
		</li>

		<li>
		  <p><span></span>We may use the information Users provide about themselves
			when placing an order only to provide service to that order. We do not
			share this information with outside parties except to the extent necessary
			to provide the service.<br></p>
		</li>

		<li>
		  <p>To send Users information they agreed to receive about topics we think
			will be of interest to them.<br></p>
		</li>

		<li>
		  <p>We may use the email address to send User information and updates
			pertaining to their order. It may also be used to respond to their
			inquiries, questions, and/or other requests. If User decides to opt-in to
			our mailing list, they will receive emails that may include company news,
			updates, related product or service information, etc. If at any time the
			User would like to unsubscribe from receiving future emails, we include
			detailed unsubscribe instructions at the bottom of each email.</p>
		</li>
	  </ul>

	  <p><br></p>

	  <h3>How we protect your information</h3>

	  <p>We adopt appropriate data collection, storage and processing practices and
		security measures to protect against unauthorized access, alteration,
		disclosure or destruction of your personal information, username, password,
		transaction information and data stored on our Site.</p>

	  <p>Sensitive and private data exchange between the Site and its Users happens
		over a SSL secured communication channel and is encrypted and protected with
		digital signatures.</p>

	  <p><br></p>

	  <h3>Sharing your personal information</h3>

	  <p>We do not sell, trade, or rent Users personal identification information to
		others. We may share generic aggregated demographic information not linked to
		any personal identification information regarding visitors and users with our
		business partners, trusted affiliates and advertisers for the purposes outlined
		above.</p>

	  <p><br></p>

	  <h3>Third party websites</h3>

	  <p>Users may find advertising or other content on our Site that link to the
		sites and services of our partners, suppliers, advertisers, sponsors, licensors
		and other third parties. We do not control the content or links that appear on
		these sites and are not responsible for the practices employed by websites
		linked to or from our Site. In addition, these sites or services, including
		their content and links, may be constantly changing. These sites and services
		may have their own privacy policies and customer service policies. Browsing and
		interaction on any other website, including websites which have a link to our
		Site, is subject to that website's own terms and policies.</p>

	  <p><br></p>

	  <h3>Compliance with children's online privacy protection act</h3>

	  <p>Protecting the privacy of the very young is especially important. For that
		reason, we never collect or maintain information at our Site from those we
		actually know are under 13, and no part of our website is structured to attract
		anyone under 13.</p>

	  <p><br></p>

	  <h3>Changes to this privacy policy</h3>

	  <p>CodingBard has the discretion to update this privacy policy at any time.
		When we do, we will revise the updated date at the bottom of this page. We
		encourage Users to frequently check this page for any changes to stay informed
		about how we are helping to protect the personal information we collect. You
		acknowledge and agree that it is your responsibility to review this privacy
		policy periodically and become aware of modifications.</p>

	  <p><br></p>

	  <h3>Your acceptance of these terms</h3>

	  <p>By using this Site, you signify your acceptance of this policy and 
		<a href="{{ url('terms') }}" target="_blank">terms of service</a>. 
		If you do not agree to this policy, please do not use our Site. Your continued use
		of the Site following the posting of changes to this policy will be deemed your
		acceptance of those changes.</p>

	  <p><br></p>

	  <h3>Contacting us</h3>

	  <p>If you have any questions about this Privacy Policy, the practices of this
		site, or your dealings with this site, please contact us at: webmaster@codingbeard.com</p>

	  <p><br></p>

	  <p>This document was last updated on Feburary 26, 2015</p>
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {

		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}