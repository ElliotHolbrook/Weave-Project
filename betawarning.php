<div class="popup" id="popup1">
	<div class="popupHaze"></div>
	<div class="beta-popup">
	<center><h1>WARNING:</h1><center><br>
	<p>You are using a beta version of this service.<br>
	Do not input any sensitive data as it may not be encrypted<p>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
$(document).ready(function() { 
  $(".popup").click(function() { 
	$(this).hide(); 
  }); 
}); 
</script>