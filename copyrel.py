import os



upto = 9

for i in range(0, upto):
  os.system("cp  ../../php-bandwidth-examples/php-bandwidth-examples/app-0{0}*/callback.php ./app-0{1}*/".format(i, i))
  os.system("cp  ../../php-bandwidth-examples/php-bandwidth-examples/app-0{0}*/application.json ./app-0{1}*/".format(i, i))

