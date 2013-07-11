# Cakephp Pinterest Plugin

Reads pinterest feed, then parses pin HTML page and saves data into database, which can be used with PinterestPin model later.

## Requirements

[CakePHP v2.x](https://github.com/cakephp/cakephp)   

## How to use it

1.	Install this plugin for your CakePHP app.   
	Assuming `APP` is the directory where your CakePHP app resides, it's usually `app/` from the base of CakePHP.

		cd APP/Plugin
		git clone git://github.com/LubosRemplik/CakePHP-Pinterest-Plugin.git Pinterest

1.	Load plugin in APP/Config/bootstrap.php
		
		CakePlugin::load('Pinterest')

1.	Use the console with your pinterest rss feed
		
		Console/cake Pinterest.pinterest http://pinterest.com/onidle/feed.rss

1.	Include PinterestPin model wherever you need and get the data from DB

		$data = ClassRegistry::init('Pinterest.PinterestPin')->find('all');
		debug($data);
