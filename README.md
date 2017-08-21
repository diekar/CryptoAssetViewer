# CryptoAssetViewer
A simple PHP + TXT-file tool to manage and view own crypto assets. A alternative to an Excel table.

![Sample Cropicon images](screenshot1.jpg "Example Screenshot in the browser window." )

![Sample Cropicon images](screenshot2.jpg "Example Text-Base." )

Use case
-----
xxx

Example .TXT file
-----

```ruby

eur;"Beispieldatensatz aus dem Unterordner 'example'";"
amount:
name;place;count
BTC;Kraken;1.00
ETH;Bitfinex;2.00
ETH;"LedgerStick";0.5
prices:
name;eur;btc
BTC;3460.00;
ETH;200.00;
PAY;;0.000902
LSK;;0.000716

```

First line: eur;[comment] -> Show &euro;(eur) or $(usd) - Currency Symbol
[comment]: Here you can write some comments for this asset configuration.

amount-area:
-> Here you can list the amounts of your currencies.

prices-area:
-> Here you define the prices of each currency in fiat.
-> If your cryptocurrency has no fiat-value you can define the Bitcoin (BTC)-price in the second row.

System requirements
-----

PHP-Enviroment like XAMPP. No MySQL database is required.
