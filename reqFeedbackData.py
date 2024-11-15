import requests
from mariadbcon import Maria

sqlcon = Maria.dbconn(self="")
cr = sqlcon.cursor()
cr.execute( "SELECT reference_id, number_of_quiz_assign_id FROM cs_feedback WHERE CR_Number IS NULL AND LEA IS NULL")
records = cr.fetchall()
for reference_id in records:

    # print('------------------------------')
    # print(reference_id)
    if reference_id[1] == 1: ## If EngagementPoint_id == 1 , RTO engagement point
        api_url = "https://serviceportal.slt.lk/FeedbackAPI/getFeedbackDetailsRTO.php?req_id="+str(reference_id[0])
        # print(api_url)
        response = requests.get(api_url)
        response.raise_for_status()  # raises exception when not a 2xx response
        if response.status_code != 204:
            returndata = response.json()
            # print(returndata)
            if(returndata['ERROR'] == False and len(returndata['DATA']) > 0):
                cr.execute(
                    "UPDATE cs_feedback SET CR_Number = %s , LEA = %s WHERE reference_id = %s ", 
                    (returndata['DATA'][0]['SERV_CUSR_ABBREVIATION'] ,  returndata['DATA'][0]['SERV_AREA_CODE'] , reference_id[0]))
                sqlcon.commit()

    elif reference_id[1] == 3: ## If EngagementPoint_id == 3 , OPMC engagement point
        api_url = "https://serviceportal.slt.lk/FeedbackAPI/getFeedbackDetails.php?req_id="+str(reference_id[0])
        # print(api_url)
        response = requests.get(api_url)
        response.raise_for_status()  # raises exception when not a 2xx response
        if response.status_code != 204:
            returndata = response.json()
            # print(returndata)
            if(returndata['ERROR'] == False and len(returndata['DATA']) > 0):
                cr.execute(
                    "UPDATE cs_feedback SET CR_Number = %s , LEA = %s WHERE reference_id = %s ", 
                    (returndata['DATA'][0]['SERV_CUSR_ABBREVIATION'] ,  returndata['DATA'][0]['SERV_AREA_CODE'] , reference_id[0]))
                sqlcon.commit()

    else:
        print("Invalid Engagement Point ID")