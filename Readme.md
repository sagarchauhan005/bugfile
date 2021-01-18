# BugFile

A simple package to capture and log all the bugs in your app and report it to our Bug Management and Incidence Reporting Tool for an in-depth analysis.

# Screenshot
![Imgur](https://i.imgur.com/LkCJBwD.jpg)

![Imgur](https://i.imgur.com/x0cEVtu.jpg)

# Installation

Run `composer require sagarchauhan/bugfile` into your app and then follow the below steps.

# Getting Started (PHP FRAMEWORK)

- Visit our [Bug Management and Incidence Reporting tool](https://localhost:8002) at create an account.
- Add your site
- You shall receive three keys for your LIVE, STAGING and DEV environment along with an end-point
- For any PHP based framework like LARAVEL, LUMEN, Zend etc. Paste the keys in your app's .env file.
- Lastly, paste the below code in your Handler.php file under `report` function
```php
$bug = new BugFile();
$bug->causedBy(BugFile::DEFAULT_USER);
$bug->causedAt(BugFile::DEFAULT_SOURCE);
$bug->setSeverity(BugFile::LOG_INFO);
$bug->customData(BugFile::DEFAULT_DATA);
$bug->log($e);
$bug->setMessage(BugFile::DEFAULT_MESSAGE);
$bug->loggedBy(BugFile::DEFAULT_LOGGER);
$bug->save();
```
- This shall capture all the exceptions and report to our tool automatically.
- To manually report an exception, use the same above code at any `catch` block in `try-catch` method like below
```php
try{
    // some logical code
}catch (Exception $e){
    $bug = new BugFile();
    $bug->causedBy(\Illuminate\Support\Facades\Auth::id());
    $bug->causedAt("Login Page");
    $bug->setSeverity(BugFile::LOG_INFO);
    $bug->customData(['last_login'=>'today']);
    $bug->log($e);
    $bug->setMessage('Something happened at login function');
    $bug->loggedBy('Sagar Chauhan - PM');
    $bug->save();
}

```

# Getting Started (CORE PHP)
- Visit our [Bug Management and Incidence Reporting tool](https://localhost:8002) at create an account.
- Add your site
- You shall receive three keys for your LIVE, STAGING and DEV environment along with an end-point
- Make sure to pass the below config array when you call the logger class
  ```php
      $config = [
          'APP_ENV'=>'local',
          'BUGFILE_END_POINT'=>'https://localhost:8002/api/logs',
          'BUGFILE_KEY_DEV'=>'',
          'BUGFILE_KEY_STAGING'=>'',
          'BUGFILE_KEY_LIVE'=>''
      ];
      $bug = new BugFile($config);
      $bug->causedBy(BugFile::DEFAULT_USER);
      $bug->causedAt(BugFile::DEFAULT_SOURCE);
      $bug->setSeverity(BugFile::LOG_INFO);
      $bug->customData(BugFile::DEFAULT_DATA);
      $bug->log($e);
      $bug->setMessage(BugFile::DEFAULT_MESSAGE);
      $bug->loggedBy(BugFile::DEFAULT_LOGGER);
      $bug->save();
  ```
- This shall capture all the exceptions and report to our tool automatically.
- To manually report an exception, use the same above code at any `catch` block in `try-catch` method like below
```php
    $config = [
          'APP_ENV'=>'local',
          'BUGFILE_END_POINT'=>'https://localhost:8002/api/logs',
          'BUGFILE_KEY_DEV'=>'',
          'BUGFILE_KEY_STAGING'=>'',
          'BUGFILE_KEY_LIVE'=>''
      ];
try{
    // some logical code
}catch (Exception $e){
    $bug = new BugFile($config);
    $bug->causedBy(\Illuminate\Support\Facades\Auth::id());
    $bug->causedAt("Login Page");
    $bug->setSeverity(BugFile::LOG_INFO);
    $bug->customData(['last_login'=>'today']);
    $bug->log($e);
    $bug->setMessage('Something happened at login function');
    $bug->loggedBy('Sagar Chauhan - PM');
    $bug->save();
}

```

# Methods

List of all the methods that you can use to send logs to our tool

| Method        | Description           | Required  |
| ------------- |:-------------:| -----:|
| causedBy()      | User id of the user who faces the exception | False |
| causedAt()      | Location of exception, generally a file or page name      |   False |
| severity() | Level of urgency for this exception      |    True |
| customData() | Any custom data like a payload or array you wish to pass      |    False |
| log() | The original exception we get in caught block      |    True |
| setMessage() | A plan message/comment to tag the exception      |    True |
| loggedBy() | Name of developer who caught this exception      |    False |
| save() | saves the data and passes it to out tool      |    True |

# Log Severity

| Severity        | Code           | Alert  |
| ------------- |:-------------:| -----:|
| LOG_INFO      | 0 | False |
| LOG_DEBUG      | 1 |   False |
| LOG_NOTICE | 2 |    False |
| LOG_WARNING | 3 |    False |
| LOG_ERROR | 4 |    False |
| LOG_CRITICAL | 5 |    TRUE |
| LOG_ALERT | 5 |    TRUE |
| LOG_EMERGENCY | 7 |    TRUE |

# Author

[Sagar Chauhan](https://twitter.com/chauhansahab005) works as a Project Manager - Technology at [Greenhonchos](https://www.greenhonchos.com).
In his spare time, he hunts bug as a Bug Bounty Hunter.
Follow him at [Instagram](https://www.instagram.com/chauhansahab005/), [Twitter](https://twitter.com/chauhansahab005),  [Facebook](https://facebook.com/sagar.chauhan3),
[Github](https://github.com/sagarchauhan005)

# License
MIT
