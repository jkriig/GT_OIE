var submitcheck = 0;
//set below to 1 during new student checkin
var checkintime = 1;
var oldmessage = "<br><br><h4 class=\"text-center\">Please <strong>TAP</strong>&nbsp;<i class=\"fa fa-id-card\" aria-hidden=\"true\"></i> BuzzCard to Check-In&nbsp;<i class=\"fa fa-play faa-horizontal animated\" aria-hidden=\"true\"></i></h4><img src=\"images/boop4.gif\" class=\"full-width-thing\"><br><br>";
$(function functionalTests() {
	$("#gtid").on("change paste keyup", function() {
		//code = $("#gtid").val();
		//console.log(code);
		if ($("#gtid").val() == 0 && $("#gtid").val().length == 1 && submitcheck == 0) {
			//console.log(code);
			$("#gtidholder").hide();
			
			$("label[for='gtid']").html("<br/><h4 class=" + "text-center" + ">Please Enter Your Name & Email</h4>");
			$("#nogtid").show();
			$("#form2").show();
			$("#form-submit").show();
			submitcheck = submitcheck + 1;
		}
		if ($("#gtid").val().length > 11) {
			$.bootstrapGrowl("<h1>Please enter your GTID OR Scan your BuzzCard, Do not do both!</h1>", {
				ele: 'body', // which element to append to
				type: 'danger', // (null, 'info', 'danger', 'success')
				offset: {
					from: 'top',
					amount: 20
				}, // 'top', or 'bottom'
				align: 'center', 
				width: 'auto', 
				delay: 3500, 
				allow_dismiss: true,
				stackup_spacing: 10
			});
			$("#gtid").val('');
			
		}
	});
});
$(function processGTID() {
	$("#isss").on("submit", function(e) {
		e.preventDefault();		
		if (submitcheck == 0)
		{
				
				$.ajax({
					url: "./function/dispname.php",
					type: "POST",
					data: $(this).serialize(),
				}).done(function(data) {
					var dispname = JSON.parse(data);
					if (dispname == null)
						{
								$.bootstrapGrowl("<h1>Please Enter a Valid GTID!</h1>", {
								ele: 'body', // which element to append to
								type: 'danger', // (null, 'info', 'danger', 'success')
								offset: {
									from: 'top',
									amount: 20
								}, // 'top', or 'bottom'
								align: 'center', 
								width: 'auto', 
								delay: 3500, 
								allow_dismiss: true,
								stackup_spacing: 10
							});
							$("#gtid").val('');
						} 				
						else
						{
							var message = "<br/><h4 class=" + "text-center" + ">Welcome! " + "<br/><br/><strong class=" + "name"+">" + dispname + "</strong><br/>" +"<br/>What is your reason for visiting?"+"</h4>";
							$("#gtidholder").hide();				
							$("label[for='gtid']").hide();
							$("#form2").show();
							$("label[for='subject']").html(message);
							$("#form-submit").show();					
						};		       

				}).fail(function() {
						$.bootstrapGrowl("<h1>Unexpected Error, Try Again?</h1>", {
						ele: 'body', // which element to append to
						type: 'danger', // (null, 'info', 'danger', 'success')
						offset: {
							from: 'top',
							amount: 20
						}, // 'top', or 'bottom'
						align: 'center', 
						width: 'auto', 
						delay: 3500, 
						allow_dismiss: true,
						stackup_spacing: 10
					});
					$("#gtid").val('');
					
				});
		}
		submitcheck = submitcheck + 1;
	});
});
function createTicket() {	
		if (submitcheck >0){
		//console.log("called");		
		$.ajax({
			url: "./function/submitticket.php",
			type: "POST",
			data: $("#isss").serialize(),
		}).done(function(data) {
				if (data != 0)
				{
					//console.log(data);
					$("#gtid").val('');					
					submitcheck = 0;					
					$("#gtidholder").show();				
					$("label[for='gtid']").show();
					$("#form2").hide();
					$("#name").val('');
					$("#emailadd").val('');
					$("label[for='subject']").html("");
					$("#form-submit").hide();		
					$("#nogtid").hide();
					$("label[for='gtid']").html(oldmessage);
					$("#option-one").prop("checked", true);
					$("#gtid").focus();
					$.bootstrapGrowl("<h1>You're checked in, Take a seat!</h1>", {
					ele: 'body', // which element to append to
					type: 'success', // (null, 'info', 'danger', 'success')
					offset: {
						from: 'top',
						amount: 20
					}, // 'top', or 'bottom'
					align: 'center', 
					width: 'auto', 
					delay: 3500, 
					allow_dismiss: true,
					stackup_spacing: 10
				});
			} else
			{	
				$("#gtid").val('');					
				submitcheck = 0;
				$("#name").val('');
				$("#emailadd").val('');
				$("#gtidholder").show();				
				$("label[for='gtid']").show();
				$("#form2").hide();
				$("#nogtid").hide();
				$("label[for='gtid']").html(oldmessage);
				$("label[for='subject']").html("");
				$("#form-submit").hide();
				$("#gtid").focus();
				$("#option-one").prop("checked", true);
						$.bootstrapGrowl("<h1>Unexpected Error, Try Again?</h1>", {
						ele: 'body', // which element to append to
						type: 'danger', // (null, 'info', 'danger', 'success')
						offset: {
							from: 'top',
							amount: 20
						}, // 'top', or 'bottom'
						align: 'center', 
						width: 'auto', 
						delay: 3500, 
						allow_dismiss: true,
						stackup_spacing: 10
					});
								
			}
		}).fail(function() {
			$("#gtid").val('');				
			submitcheck = 0;			
			$("#gtidholder").show();				
			$("label[for='gtid']").show();
			$("#nogtid").hide();
			$("label[for='gtid']").html(oldmessage);
			$("#form2").hide();
			$("#name").val('');
			$("#emailadd").val('');
			$("label[for='subject']").html("");
			$("#gtid").focus();
			$("#form-submit").hide();
						$.bootstrapGrowl("<h1>Unexpected Error, Try Again?</h1>", {
						ele: 'body', // which element to append to
						type: 'danger', // (null, 'info', 'danger', 'success')
						offset: {
							from: 'top',
							amount: 20
						}, // 'top', or 'bottom'
						align: 'center', 
						width: 'auto', 
						delay: 3500, 
						allow_dismiss: true,
						stackup_spacing: 10
					});
					
				
		});
}
	
}
$(document).click(function() {
 if ($("#option-three").prop("checked"))
		{
			$("#travelmsg").show();
		}
});
$(document).ready(function() {
      var now = new Date();
      var hours = now.getHours();
      var minutes = now.getMinutes();	 
	  $("#option-one").prop('disabled', false);
	  $("#option-two").prop('disabled', false);
	  $("#option-three").prop('disabled', false);	
	  $("#option-four").prop('disabled', false);
	  $("#option-five").prop('disabled', false);	
      if(hours>=12 && hours<16)
	  {
          
		$("#option-one").prop('disabled', false);
		$("#option-two").prop('disabled', false);
		$("#option-three").prop('disabled', false);
	   
		$("#option-five").prop('disabled', true);
		
	  }
      else if(hours==16 && minutes <=31)
      {         
		 $("#option-one").prop('disabled', false);
		 $("#option-two").prop('disabled', false);
		 $("#option-three").prop('disabled', false);
	
		 $("#option-five").prop('disabled', true);
	  }
      else if(hours >=8 && hours <=11)
      {
		   if (hours>=8 && hours <11)
		   {		   
             $("#option-one").prop('disabled', false);
			 $("#option-two").prop('disabled', false);
			 $("#option-three").prop('disabled', false);
			 $("#option-five").prop('disabled', false);		
			 $("#option-five").prop('checked', false);	
			
		    }
			if(hours==11 && minutes <=31)
			{
				$("#option-one").prop('disabled', false);
				$("#option-two").prop('disabled', false);
				$("#option-three").prop('disabled', false);
				$("#option-five").prop('disabled', false);
				$("#option-five").prop('checked', false);				
			}
	  } 
	  else
		{
				$("#option-one").prop('disabled', false);
				$("#option-two").prop('disabled', false);
				$("#option-three").prop('disabled', false);				
				$("#option-five").prop('disabled', false);
		}
		if (checkintime)
		{
			$("#option-four").prop('disabled', false);	
		}
		else
		{
			$("#option-four").prop('disabled', true);		
		}
    	
   
});
