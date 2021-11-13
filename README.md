# CSE330
Tomson Li 488501 tomsonlee13

Rain 487627 Rain-0219

Creative Portion: 

1. Event Category: User can choose event's category when they add the event and they can use the radio button to get the list of a particular category of the event. If they wanna see all the events on a day, they can choose click the radio button "All" for that.

2. Group Share Event: Each user has their own id number and this number will be at the very top of the screen after the # of their user name. For example: userName#12 means the user id is 12. If user want to share their events with other users, after input all the event information, click the submit as Group button, first, they will need to input how many users they are going to share and after that, they need to input the user id one by one. If userer input the wrong id number, the event won't able to share and user can not share with him/her self.

3. Share Event with one user: User can click share event button after each event to share that event with one user. User needs to input the correct user id to share. User can not share with him/her self.

4. Reset Password functionality for users to reset their password.

http://ec2-18-222-238-90.us-east-2.compute.amazonaws.com/~tomsonlee/m5/login_check.php

-3 Events cannot be edited
-2 used var in code and no comments
-3 not safe from XSS attacks, content not escaped on ourput
-2 not safe from SQL injection, no prepared statements in PHP
