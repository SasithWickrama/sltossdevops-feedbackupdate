from log import getLogger
import mysql.connector
import sys

logger = getLogger('ERRLOG', 'logs/dblog')

class Maria:

    def dbconn(self):
        try:
            conn = mysql.connector.connect(
                user="root",
                password="eWMaLQp7JeKK$",
                host="localhost",
                port=3306,
                database="feedback",
                charset="utf8"

            )
            return conn
        except mysql.connector.Error as e:
            print(f"Error connecting to MariaDB Platform: {e}")
            logger.info(f"Error connecting to MariaDB Platform: {e}")
            return ""


# import mysql.connector

# mydb = mysql.connector.connect(
#   host="localhost",
#   user="yourusername",
#   password="yourpassword"
# )

# print(mydb)