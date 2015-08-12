# PSU SIS AutoRegister
The Robot Automation Registeration for Prince of Songkla University's Student Information System.
### Requirement
- PHP Version 5.3+
- php_curl extension
- Internet Faster

### How to use
- Edit this section in file config.php
```php
$PSU_Passport_Username = ''; // Username for PSU Passport
$PSU_Passport_Password = ''; // Password for PSU Passport
$SIS_Term = ''; // Semester for register in type 'semester/year'
$Subject_To_Register = array(); // Subject to register in type ...
/*
$Subject_To_Register = array(
    array('SubjectCode' => '', 'SubjectSec' => '', 'SubjectCredit' => ''),
    array('SubjectCode' => '', 'SubjectSec' => '', 'SubjectCredit' => ''),
    array('SubjectCode' => '', 'SubjectSec' => '', 'SubjectCredit' => ''),
    ....
);
*/
$SIS_URL = ''; // SIS server for register
```
- You can use this command for start robot
```sh
php execute.php
```

### License
Apache License

### More information and contect to developer
* [Website :: https://kusumotolab.com](https://kusumotolab.com)
* [Twitter :: @Kusumoto_ton](https://twtter.com/kusumoto_ton)
* [Facebook :: Weerayut Hongsa](https://facebook.com/Azerdar.t.Kusumoto)
