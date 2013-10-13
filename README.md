Console Output
==============

This tool is mainly something to scratch my own itch. Something I've found really annoying for a long time when debugging my code, is that `print_r` outputs to standard out, which, in the case of web development, is most often something you see in your browser. If you have a simple web site, this works fine. But if you're using a lot of ajax requests, that, for example, return json, using print_r becomes really annoying, because you need to dig around your browser's dev console to inspect the response, your frontend will break because your js doesn't know what to do with the extra information, and sometimes your printed information is not invisible at all.

So something I really wanted, is to output debugging information to the terminal (like you would in python for example). And that is exactly what this tool does. It's as simple as:

1. start the "server" (which is nothig more than a listening socket that outputs what it receives). This is where your debug information will show.
```
php vendor/bin/console_output.php terminal-output:listen
```

2. In your code, output for example `$yourVar`
```php
Console::log($yourVar);
```

### Installation

This is a composer package, so installation is as easy as adding it following to your `require-dev`:
```json
{
  "require-dev" : {
    "terminal-output/terminal-output": "@dev"
  }
{
```
