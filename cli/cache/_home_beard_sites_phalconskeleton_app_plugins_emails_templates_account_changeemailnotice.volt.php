<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	
  </head>
  <body itemscope itemtype="http://schema.org/EmailMessage">
	<table class="body-wrap">
	  <tr>
		<td></td>
		<td class="container">
		  <div class="content">
			<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
			  <tr>
				<td class="header">
				  
	
<table width="100%">
  <tr>
	<td class="aligncenter content-block">
	  <h1><?php echo $this->escaper->escapeHtml($this->config->application->name); ?></h1>
	</td>
  </tr>
</table>

				</td>
			  </tr>
			  <tr>
				<td class="content-wrap">
				  
	<meta itemprop="name" content="Reset Password"/>
	<table width="100%" cellpadding="0" cellspacing="0">
	  <tr>
		<td class="content-block">
		  <h2>Hey <?php echo $this->escaper->escapeHtml($user->firstName); ?>!</h2>
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  At <?php echo date($this->config->defaults->datetimeFormat); ?> Your email address was changed from <?php echo $this->escaper->escapeHtml($oldEmail); ?> to this one.
		</td>
	  </tr>
	  <tr>
		<td class="content-block regards">
		  Regards, <br />
		  <?php echo $this->config->application->name; ?>
		</td>
	  </tr>
	</table>

				</td>
			  </tr>
			</table>
			<div class="footer">
			  
	
<table width="100%">
  <tr>
	<td class="aligncenter content-block">
	  Please do not reply to this address, its inbox is not monitored. <br />
	  <a href="http://<?php echo $this->config->application->domain; ?>"><?php echo $this->config->application->name; ?></a>
	</td>
  </tr>
</table>

			</div>
		  </div>
		</td>
		<td></td>
	  </tr>
	</table>
  </body>
</html>

	
