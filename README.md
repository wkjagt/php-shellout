Console Output
==============

### print_r and ajax : nope

Something I've found really annoying for a long time when debugging PHP code, is that `print_r` outputs to standard out and, in the case of web development, becomes part of your response. In simple projects this works fine. But if you're using a lot of ajax requests, that, for example, return json, using `print_r` becomes really annoying, because you need to dig around your browser's dev console to inspect the response, your frontend code will break because your `print_r` or `var_dump` will make your json invalid, and sometimes your printed information is not visible at all because of very mysterious reasons.


### Keep the response clean

Something I really wanted, is to output debugging information to the terminal (not your browser terminal, but your Mac or Linux terminal) and keep my responses clean. And that is exactly what this tool does.

It's as simple as:

1. start the "server" (which is nothig more than a listening socket that outputs what it receives). This is where your debug information will show.
```
vendor/bin/console_output console-out:listen
```

2. In your code, output for example `$_SERVER`
```php
Console::out($_SERVER);
```

3. And you'll get this in your terminal:
4. 
![image](https://f.cloud.github.com/assets/327048/1389762/caa82fbc-3bdf-11e3-95dc-10d63a3fe440.png)


### Installation

This is a composer package, so installation is as easy as adding it following to your `require-dev`:
```json
{
  "require-dev" : {
    "console-out/console-out": "@dev"
  }
}
```
