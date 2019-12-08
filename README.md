# VirtualHostX WordPress Plugin

[VirtualHostX](https://clickontyler.com/virtualhostx/) is an easy-to-install local server environment for macOS. You can build and test web apps like WordPress or even host a production web server on your Mac.

One feature of VHX is sharing your websites across your local network to other computers and mobile devices and also using [ngrok](https://ngrok.com) to share them publicly across the internet.

A huge percentage of my customers use VHX to build, test, and host WordPress websites. And because of the way WordPress handles URLs to static assets, when you attempt to view your website over a shared VHX connection, all of your images, stylesheets, and other static assets will break because the "fake" domian name that WordPress was initally installed on doesn't really exist outside your local Mac.

This plugin fixes the domain name for static assets to work no matter how you access the website. I've attempted to cover all replacement cases in my testing, but if you find any of your website files are still pointing to the original domain name, please let me know or feel free to submit a pull request.