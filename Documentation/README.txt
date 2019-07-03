What does it do?

This extension combines the Form extension with the newsletter service Newsletter2go.
It creates a new finisher which can add new recipients to your Newsletter2go account.

API documentation of Newsletter2go: https://docs.newsletter2go.com/?version=latest

CONFIGURATION GUIDE

1. Include the TypoScript file inside of your template.

   Clear the cache and you should be able to see a new finisher called 'Newsletter' inside of your Form configuration module.
   
2. Finisher configuration:

   1. Newsletter2go Auth Key: 
        Your unique auth key which can be found in your profile settings on newsletter2go.com.
   2. Newsletter2go E-Mail: 
        The email adress of your Newsletter2go account.
   3. Newsletter2go Password: 
        The password of your Newsletter2go account.
   4. Success PID: 
        The page ID the extension will redirect to if a recipient was successfully added.
   5. Failure PID: 
        The page ID the extension will redirect to if a recipient was not successfully added.
   6. Field Names:
        Every field you use on Newsletter2go has a specific name.
        Insert all names in a comma seperated list (e.g. value1,value2,value3) into the field called 'Field Names' inside of the finisher's configuration.
        Make sure that the names on your list are in the same order as the form fields.
        Note that the field names are case sensitive.
        When using predefined fields like first name, last name, or email the below mentioned names have to be used.

   For more information see the API documentation.
   
   Names:
   First Name => first_name
   Last Name => last_name
   Email => email
