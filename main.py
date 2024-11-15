from claritycon import Clarity
from mariadbcon import Maria

claritycon = Clarity.dbconn(self="")
sqlcon = Maria.dbconn(self="")

print(type(sqlcon))
sqlcon.execute( "SELECT * FROM cs_feedback WHERE CR_Number IS NULL AND LEA IS NULL")
for (reference_id) in sqlcon:
    sql = "SELECT SERV_CUSR_ABBREVIATION , SERV_AREA_CODE "  
    "FROM PROBLEM_LINKS ,SERVICES "\
    "WHERE PROL_PROM_NUMBER = '"+reference_id[0]+"' "\
    "AND SERV_ID = PROL_FOREIGNID "\
    "AND PROL_FOREIGNTYPE = 'SERVICES' "
    claritycon.execute(sql)
    for SERV_CUSR_ABBREVIATION ,  SERV_AREA_CODE in claritycon:
        sqlcon.execute(
    "UPDATE cs_feedback SET CR_Number = ? , LEA = ? WHERE reference_id = ? ", 
    (SERV_CUSR_ABBREVIATION[0] ,  SERV_AREA_CODE[0] , reference_id[0]))
