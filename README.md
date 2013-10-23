Console Output
==============

From:

![image](https://f.cloud.github.com/assets/327048/1389768/f09824d4-3bdf-11e3-9692-ac10872f840f.png)

To:

![image](https://f.cloud.github.com/assets/327048/1389762/caa82fbc-3bdf-11e3-95dc-10d63a3fe440.png)



This tool is mainly to scratch my own itch. Something I've found really annoying for a long time when debugging PHP code, is that `print_r` outputs to standard out, which, in the case of web development, is most often something you see in your browser. If you have a simple web site, this works fine. But if you're using a lot of ajax requests, that, for example, return json, using `print_r` becomes really annoying, because you need to dig around your browser's dev console to inspect the response, your frontend code will break because your `print_r` or `var_dump` will make your json invalid, and sometimes your printed information is not invisible at all because of very mysterious reasons.

So something I really wanted, is to output debugging information to the terminal (not your browser terminal, but your Mac or Linux terminal). And that is exactly what this tool does. It's as simple as:

1. start the "server" (which is nothig more than a listening socket that outputs what it receives). This is where your debug information will show.
```
vendor/bin/console_output console-out:listen
```

2. In your code, output for example `$yourVar`
```php
Console::out($yourVar);
```

When you reload your page, nothing is poluted with debug information, and your variable is pretty printed to the terminal that's running the server you started in step 1.

### Installation

This is a composer package, so installation is as easy as adding it following to your `require-dev`:
```json
{
  "require-dev" : {
    "console-out/console-out": "@dev"
  }
}
```
