
# Presentation <a href="https://docs.google.com/presentation/d/e/2PACX-1vRphFazsSrfs56BExLk_B4Rd3b-rLftx5uxfZ_tMaMjw15iAGxZRPClw-Cvz57D51voOI_f2fdOVhct/pub?start=false&loop=false&delayms=3000">link</a>


# Overview:
<img src="https://github.com/jkriig/GT_OIE/blob/master/checkin.png">

# kiosk_oie_ticket
OIE's Check in kiosk

<img src="https://github.com/jkriig/GT_OIE/blob/master/help_files/welcome.png?raw=true">

### Based on OSTickets API
Configure with your own key in /function/submitticket.php
Configure GTED info in /function/config.php

### Required Items:
RFID Reader! - (https://amzn.to/2HkGSJr)
Read More in /help_files/buzzcard hid project.pdf



### Misc Info

The Self-checkin Kiosk is using scripts location in \function along with Osticket and a Microsoft surface 3 with a RFID reader.. 

On the backend, we are using webhosting to host latest version of OSticket with some modifications and plugins (CAS) (https://github.gatech.edu/jkriigel3/osticket_cas). 

The front end is a kiosk site that has a few choices as why they are here and we added a prox reader to send buzzcard (tap) to buzzcard office/GTED and gets back: GTusername, GTid, First/last name. 

All of that data gets sent to Osticket via its API instantly (we store the GTID as the phone number field) and the advisors on duty see that someone is waiting and go out and meet them and claims the ticket. 

When we close the ticket out, we select a canned response based on why the student was here and email them some information and a survey. We will log these as close codes for our metrics. 

Here is a video of what the student see's when they come into our office and use the kiosk (this is an older verison, it has since been improved and updated): /help_files/OIE Checkin.mp4

I've had this system running since Dec 2014 and logged 98,000 students into our office. The data I've collected has been used to hire two more student advisors and change our walk in times to be more optimized for the students. 
