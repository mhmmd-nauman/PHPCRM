function takeValuefortaxt(ActionValue){
	switch(ActionValue){

		case "0":

		return "";

		break;

		case "1": // left message

		return "Called lead and left a message about MOS, we will call back later. Also sent introduction email.";

		break;

		case "2": //Not Interested - Talked

		return "Talked to lead but they were not interested in MOS.";

		break;

		case "3"://interest in MOS

		return "Called lead and they are interested in MOS. Will look at the information again. We will call back in a few days. Also sent introduction email.";

		break;
		
		case "4": //Not Interested

		return "Called and talked to lead. They are not interested in MOS.";

		break;

		case "5": //Not Qualified

		return "Called and talked to lead. They have no funds to join MOS.";

		break;

		case "6": //not available

		return "Called lead but there was no answer/busy/no machine, we will call back later. Also sent introduction email.";

		break;

		case "7": // invalid phone number

		return "Called lead but phone number is invalid. Also sent introduction email.";

		break;

		case "8": //Not Interested - Hung Up

		return "Called lead and the lead hung up on me.";

		break;

		case "9": // international number

		return "Cannot call lead. Number is outside US and Canada. Sent introduction email to lead requesting contact via Skype.";

		break;

		case "10": // intro Email

		return "Sent introduction email about MOS.";

		break;

		case "11": //Coaching Appointment

		return "Called lead and setup an appointment for a coaching call.";

		break;

		case "12": //Interested in Tapit

		return "Called lead and they are looking for a job, not a business. I discussed the Tapit Professional Job Search System with them and they are interested. We will call back in a few days. Also sent introduction email.";

		break;

		case "13": //Interested in Coaching

		return "Called the lead and they said they will check out the coaching call. Also sent introduction email.";

		break;

		case "14": // Coaching Call Complete

		return "Completed coaching call. Coaches comments:";

		break;

		case "15": // Infuser - Initial Call LM

		return "Called lead and left a message on their voicemail. I also sent an email with my contact info. Will try and call again later.";

		break;

		case "16": // Infuser - Initial Call No Answer

		return "Called lead but there was no answer or voicemail so I sent an email with my contact info. Will try again later.";

		break;

		case "17": // Infuser - Initial Call Wrong Phone No.#

		return "Tried to call lead - wrong phone number so I sent an email with my contact info asking them to call me back.";

		break;

		case "18": // No phone number

		return "2 MOS follow up emails per week being sent to lead.";

		break;

		case "19": // Valid Phone Number

		return "2 MOS follow up emails per week being sent to lead.  Call center will also follow up.";

		break;
		
		case "21": //   II-Send Infiser Coaching (OrderForm)

		return "Sent Order Link to lead";

		break;
		
		case "22": // Final Call LM

		return "Called lead again and left another message on their voicemail. I sent a final email with my contact info.";

		break;

		case "23": // Final Call No Answer/Wrong Phone

		return "Tried to call lead again - wrong phone number so I sent a final email with my contact info in it.";

		break;
		
		case "24": //  II-Schedule Infuser Webinar

		return "Sent an email to the lead to attend the live webinar.";

		break;
		
		case "25": // Refund Request

		return "Member wants to cancel, we are sending them to the budget coach to see if they will be interested in Freedom Builders Membership.";

		break;
		
		case "26": // Initial Call, Talked To Lead

		return "Called and talked to the lead.";

		break;
		
		case "27": // Final Call, No Sale 

		return "Lead will not proceed at this time.";

		break;
		
		case "28": // Call Back, No Message Left

		return "Called lead again but did not leave message. No email sent. Will try again.";

		break;
		
		case "29": // Call Back, Left Message

		return "Called lead again and left another message on their voicemail. Did not resend email.";

		break;
		
		case "30": // Call Back, No Answer, Emailed 

		return "Called lead again and could not leave message. Re-sent an email with my contact info in it.";

		break;
		
		case "31": // Sale Closed (MOS)

		return "Lead has purchased MOS membership";

		break;
		
		case "32": // II-Sale Closed (FBG)

		return "Lead has purchased FB Gold membership";

		break;
		
		case "33": // MOS-Send MOS Replay Webinar

		return "Sent an email to the lead to attend the live webinar now";

		break;
		
		case "34": // II - Schedule a live webinar

		return "Sent an email to the lead to attend the live webinar";

		break;
		
		case "35": // Send Order Form Link

		return "Sent MOS Orderform Link to lead.";

		break;
		
		case "38": // II - send infuser replay webinar

		return "Sent an email to the lead to attend the live webinar now.";

		break;
		
		case "39": // MOSPresentation

		return "Sent an email to the lead to watch the MOS Presentation.";

		break;
		
		case "44": // MOSPresentation

		return "Sent an email to the lead to contact me after watching the webinar.";

		break;
		
		case "97"://send Stelee order form url

		return "MOSJeff-funnel.";

		break;
	}

}