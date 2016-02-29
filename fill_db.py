import mysql.connector
import random

descr = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."

cnx = mysql.connector.connect(user='',
							  password='',
							  host='127.0.0.1',
							  database='store',
							  port=3306)
					
cursor = cnx.cursor()

for j in range(5000):
	values = []
	for i in range(100):
		num = random.randint(0, 10000000)
		values.append('("product{0}", {0}, "{1}")'.format(num, descr))

	sql = 'INSERT INTO products(name, cost, description) VALUES ' + ','.join(values) + ';'

	cursor.execute(sql)
	cnx.commit()

	print(j)

cursor.close()
cnx.close()