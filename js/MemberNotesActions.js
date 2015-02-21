function takeValuefortaxt(ActionValue){
	switch(ActionValue){
		case "0":
		return "";
		break;
		case "1": // Welcome Call
		return "Called and talked to member - sent welcome email with details on finding support";
		break;
		case "2": //Welcome Email
		return "Tried to call, left message - sent welcome email with details on finding support";
		break;
		case "3": //Strategy Session
		return "Setup strategy session call - sent email to member, coach and sponsor with date and time of the call";
		break;
		case "4": //Business Plan
		return "Setup business plan call - sent email to member, coach and sponsor with date and time of the call";
		break;
		case "5": //Marketing Plan
		return "Setup marketing plan call - sent email to member, coach and sponsor with date and time of the call";
		break;
		case "7"://Strategy Session Completed
		return "Strategy session call is complete. Coaches comments:";
		break;
		case "8": //Business Plan Completed

		return "Business Plan call is complete. Coaches comments: ";
		break;
		case "9": //Marketing Plan Completed
		return "Marketing Plan call is complete. Coaches comments: ";	
		break;
		
	}
}



