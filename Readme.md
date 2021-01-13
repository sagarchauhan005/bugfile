# BugFile

A simple package to capture and log all the bugs in your app and report it to our Bug Management and Incidence Reporting Tool for an in-depth analysis.

# Screenshot
![Imgur](https://i.imgur.com/LkCJBwD.jpg)

![Imgur](https://i.imgur.com/x0cEVtu.jpg)

# Getting Started

- Visit our [Bug Management and Incidence Reporting tool](https://localhost:8002) at create an account.
- Add your site
- You shall receive three keys for your LIVE, STAGING and DEV environment along with an end-point
- Paste the keys in your app's .env file.
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

# Author

[Sagar Chauhan](https://twitter.com/chauhansahab005) works as a Project Manager - Technology at [Greenhonchos](https://www.greenhonchos.com).
In his spare time, he hunts bug as a Bug Bounty Hunter.
Follow him at [Instagram](https://www.instagram.com/chauhansahab005/), [Twitter](https://twitter.com/chauhansahab005),  [Facebook](https://facebook.com/sagar.chauhan3),
[Github](https://github.com/sagarchauhan005)

# License
MIT
