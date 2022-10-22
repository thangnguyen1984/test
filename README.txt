- Create your database first with utf8mb4_unicode_ci Collation and run test.sql 
- update .env : DATABASE_URL="mysql://root:@127.0.0.1:3306/test?serverVersion=8&charset=utf8mb4" to your database info

- Update email info in .env : MAILER_DSN=gmail://luckyshy@gmail.com:elzwrsyxsupmywol@default

- run command: symfony server:start to host web
  url : http://localhost:8000/product

- To import data from categories.json and products.json, please run the command :
  php bin/console ImportData 
- The event listenter have been implemented in src/EventListerner/DatabaseActivitySubscriber.php
  The send mail code was implemented in this event (create and update) but not test because have problem with smtp Gmail 
testing (https://support.google.com/a/answer/166852?hl=en#zippy=%2Cfree-trial-account-limits).
  We can implement another way by add send email function to save method of entity repo