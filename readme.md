# Forum

1. Thread
2. Reply
3. User

A. A Thread is created by a user
B. A reply belongs to a thread, and belong to a user.


php artisan make:model Thread -mr
php artisan make:model Reply -mc

m = migration file
r = resourceful controller
c = controller



# Common Terminal Commands
1. vendor\bin\phpunit.bat       # Run unit tests
2. vendor\bin\phpunit.bat tests\Unit\ReplyTest.php # Run specific test 
3. php artisan migrate:refresh  # Run all migrate files and refresh db (CLEAR!)
