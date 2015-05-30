# WrikeAPI-php
A PHP implementation of Wrikes API to get you started if you should need a PHP API library

Look to Wrike documentation here: https://developers.wrike.com

You need to fetch the tokens needed. That means:

1. Go to: https://developers.wrike.com/getting-started/ - you need to sign up first
2. Use your client ID from the email to get a token (visit this url: https://www.wrike.com/oauth2/authorize?response_type=code&client_id=<client_id> )
3. Run the token_init() method from the library using your new token.

Then all is in place, and you are ready to use wrike API as part of the application you are building. 