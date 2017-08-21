# CryptoAssetViewer
A simple PHP + TXT-file tool to manage and view own crypto assets. A alternative to an Excel table.

![Sample Cropicon images](screenshot1.jpg "Example Screenshot in the browser window." )

![Sample Cropicon images](screenshot2.jpg "Example Text-Base." )

Use case
-----
Excell sheets are useful to manage a own collection of crypto currencies. But sometimes you
will destroy you trading/invenstment history by updating the fiat-prices of your crypto currencies
like Bitoin, Ethereum or IOTA. Maybe you are using different exchanges, f.e. today (august 2017)
you cannot trade IOTA on Kraken or Bittrex. Typically you have Bitcoins stored on many different
exchanges, paper-wallets or hardware-wallets, but for you interest you only want to calculate with
a single value, the accumulation of all BTC-positions.

This project consist only of one single index.php and a subdirectory of your hard-asset investment.
It should be also useful to use this tool to manage the value of gold- and silver coins like Krügerrand 
or silvr Maple-Leaf :blush:.  




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
