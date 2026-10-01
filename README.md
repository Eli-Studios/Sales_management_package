# Sales Management

A cash-register program that prints a sales receipt ("Bon") to the screen and
saves a copy of it as a text file.

You do not need to know PHP to use this program. This guide explains what to
install and what to change when you set it up in your country. Everything you
normally need is a plain text file that you can edit with any text editor.

---

## 1. What you need

- A computer with **PHP 8.1 or newer** installed. That is the only requirement.
- A text editor (Notepad, TextEdit, VS Code, nano, vim — whatever you like).
- The folder contents of this project, unmodified.

### Checking that PHP is installed

Open a terminal and type:

```
php -v
```

If you see something like `PHP 8.5.11 (cli)`, you are ready. If the system says
`command not found`, install PHP first:

| System | Command |
| --- | --- |
| Windows | Download the "Thread Safe" ZIP from [windows.php.net/download](https://windows.php.net/download/), unzip it to `C:\php`, then add `C:\php` to your PATH |
| macOS (Homebrew) | `brew install php` |
| Debian / Ubuntu | `sudo apt install php-cli` |
| Fedora / RHEL | `sudo dnf install php-cli` |
| Arch / Manjaro | `sudo pacman -S php` |
| openSUSE | `sudo zypper install php` |

### Checking that PHP can print non-English characters

Product names with umlauts or Cyrillic letters need one extra extension.
Type this:

```
php -m | findstr mbstring
```

On macOS and Linux use `php -m | grep mbstring` instead.

If you see `mbstring` in the output, everything is fine. If nothing is
printed, the extension is missing. Install it with the same package manager
you used above, or ask your IT department. Without `mbstring` the program
stops with an error and the receipt is not printed.

---

## 2. Running the program

Open a terminal **inside the project folder** (the folder that contains
`index.php`) and run:

```
php index.php
```

The program asks you how much cash the customer handed over:

```
Geg. BAR                      EUR              30
```

Type the amount in euros (for example `30` or `30,50`) and press Enter. The
program prints the complete receipt and tells you the change to give back.

Two files are created or updated every time you run it:

| File | Purpose |
| --- | --- |
| `ChangeNumber.txt` | The next receipt number. Do not delete it. |
| `logs/HH.MM_DD.MM.YYYY_change-log-file.txt` | A saved copy of the receipt, one file per run |

---

## 3. Setting up for your country

The software was written for Germany. This section explains every setting you
must change. Work through the list top to bottom and set up a test run at the
end.

### 3.1 The things you cannot change without a developer

Some parts of the receipt are hard-coded in the program code and are not
configurable. If your country needs different wording or a different number
format, ask the developer — do not try to edit the `.php` files yourself.

| What | Where | Notes for e.g. Russia |
| --- | --- | --- |
| Receipt labels | `index.php`, `Drinks.php` | `SUMME`, `Rückgeld`, `Geg. BAR`, `PFAND`, `Stk` are German words inside the code. They need to be translated by a developer. |
| Tax table headings | `TaxTable.php` | `Steuer`, `Netto`, `Brutto`, `Gesamtbetrag` are hard-coded. |
| Number format | several `.php` files | Amounts are always printed with a **comma** as the decimal separator (`28,32`), as is standard in Germany. Russian convention uses a comma too, so this usually needs no change. |
| Deposit handling | `Drinks.php` | Only used for bottled drinks with a deposit (Pfand). Can be deleted from the basket if your country has no deposit system. |
| TSE section | `TSE.php`, `Data/tseData.json` | A German-specific tamper-proof receipt signature. Harmless to leave in place, but your shop is not legally required to have it. |

The `tseData.json` file contains **sample data from a test transaction**. Never
present it to a customer as a real signature.

### 3.2 Settings you can change yourself

#### Shop details — `Data/headerData.json`

This is the top of the receipt. **Important:** the values are already padded
with spaces so that each line is about 55 characters wide. That padding is
what keeps the receipt aligned. The easiest way to edit is to leave the
leading and trailing spaces exactly as they are and only replace the text in
the middle.

```json
{
    "companyName": "                         REWE                         ",
    "street": "                  Schorndorfer Str. 6                  ",
    "city": "                     73065 Uhingen                     ",
    "phoneNumber": "                     07160-5060346                     ",
    "vatID": "                  UID Nr.:DE812706033                  "
}
```

For a Russian shop, `city` would become the postal code plus city, e.g.
`101000 Moscow`, and `vatID` becomes your INN/OGRN instead of a German
`UID Nr.`. The values shown above are examples — replace them with your own.

Do not remove the padding spaces — they are what keeps the receipt aligned.

#### Till information — `Data/footerData.json`

This is the bottom of the receipt: date, time, receipt number, market, till
and cashier ID.

```json
{
    "row1": {
        "Date": "@",
        "Time": "~",
        "ChangeNumber": "Bon-Nr.:"
    },
    "row2": {
        "Market": "Markt:0215",
        "TillNumber": "Kasse:3",
        "Cashier": "Bed.:141414"
    }
}
```

Leave `Date`, `Time` and `ChangeNumber` in `row1` exactly as they are — the
`@` and `~` are placeholders that the program overwrites on every run, and
`Bon-Nr.:` is the label the receipt number is appended to. Only change `row2`:
`Markt` = store number, `Kasse` = till number, `Bed.` = cashier ID. Keep each
value under about 18 characters including the label, otherwise the three
columns will not line up.

#### Time zone — `index.php`, line 30

Find this line:

```php
date_default_timezone_set('Europe/Berlin');
```

Replace `Europe/Berlin` with the time zone of your shop. For Russia use
`Europe/Moscow`. This is the only line in `index.php` you should touch.

Note that a shop far east or west of Moscow may sit in a neighbouring time
zone (for example `Asia/Yekaterinburg` or `Europe/Kaliningrad`). Use the zone
that matches the wall clock in your shop.

#### Currency — `index.php`, lines 64 and 73

The currency code appears on two lines. Both start with:

```php
$obj = new PriceSumGermany($priceSum, "SUMME", "EUR");
```

and

```php
...new PriceSumGermany($arrLogContent[1], "Rückgeld", "EUR")...
```

Replace `"EUR"` with your currency code on both lines — for Russia `RUB`.
`"SUMME"` and `"Rückgeld"` are the German labels mentioned above; a developer
translates those.

#### VAT rates — `index.php`, line 20

Find:

```php
$taxRates = ['A' => 19, 'B' => 7];
```

Two rates are supported, called `A` and `B`. In Germany these are 19 % and 7 %.
Russia uses 20 % and 10 % (or 0 %), so you would enter:

```php
$taxRates = ['A' => 20, 'B' => 10];
```

Each product in the basket carries a `taxClass` of `"A"` or `"B"`, which
decides which rate applies to it. After changing the rates, check that your
basket products still use the correct class — see below.

---

## 4. Editing the products — `Data/basket.json`

The basket is the list of items sold on every receipt. Open `Data/basket.json`
in a text editor. Each product is one block:

```json
    "Pakchoi Mini": {
        "amount": 1,
        "pricePerPiece": 169,
        "taxClass": "B"
    },
```

Three kinds of product are recognised. The program decides which kind it is
from the fields you use:

| Product type | Fields | Meaning |
| --- | --- | --- |
| Sold by piece | `amount`, `pricePerPiece` | N pieces at a fixed price each |
| Sold by weight | `weight`, `pricePerKilogram` | Weight in **grams**, price per kg |
| Drink with deposit | `amount`, `pricePerPiece`, `depositPerPiece` | Sold by piece, plus a deposit per bottle |

**Prices are stored in cents, not euros.** `169` means 1.69. Never type a
decimal point — `169` is 1.69, and typing `1.69` would be read as 169 cents
only by accident and will break the totals.

`taxClass` is `"A"` or `"B"` and must match one of the rates you set in
section 3.2.

### Changing a product

To change the price of `Pakchoi Mini` to 2.20, edit that one block:

```json
    "Pakchoi Mini": {
        "amount": 1,
        "pricePerPiece": 220,
        "taxClass": "B"
    },
```

### Adding a product

Copy an existing block, paste it below the others, and change the name and
values. **Each block must be separated by a comma**, except the last one in the
file. If you get a JSON error when running the program, look for a missing or
extra comma.

### Removing a product

Delete the whole block, including the surrounding braces and its comma.

---

## 5. Daily operation

Run `php index.php` once per customer. After each sale:

1. Check that the printed total matches your till.
2. Type in the cash received and press Enter.
3. Count out the change and confirm it against the `Rückgeld` line.

The receipt is saved automatically. Find it under `logs/`. The filename is
the time and date, e.g. `logs/14.32_01.10.2026_change-log-file.txt`.

### Backing up

Two things should be backed up regularly:

- `Data/` — your shop details, products and rates. This is the actual content.
- `ChangeNumber.txt` — the receipt counter.

`logs/` contains the archived receipts. Keep them for as long as your tax
rules require.

If you reinstall or lose `ChangeNumber.txt`, the program logs an error and the
receipt number stays at 0. To fix it, create the file with the number you want
to start from, for example `1`.

---

## 6. Troubleshooting

| Message | Cause | Fix |
| --- | --- | --- |
| `command not found: php` | PHP is not installed | Install it, section 1 |
| `Call to undefined function mb_str_pad()` | The `mbstring` extension is missing | Install `mbstring`, section 1 |
| `Failed to open stream: No such file or directory` | A `Data/*.json` file is missing or renamed | Restore the file from your backup |
| `Syntax error` when running | A comma is missing in a `.json` file, or a `.php` file was edited | Undo your last edit. Only `index.php` line 30 should ever be edited. |
| Receipt shows the wrong time | Wrong time zone | `index.php` line 30, section 3.2 |
| Receipt shows the wrong date | Wrong time zone | `index.php` line 30, section 3.2 |
| Total is 100× too high or low | A price was typed in euros instead of cents | Section 4 |
| Change is a negative amount | Customer paid less than the total | The program will show this. Refuse the sale or ask for more. |
| Receipt text is misaligned | Leading/trailing spaces were removed from `headerData.json` or `footerData.json` | Restore the padding spaces, section 3.2 |

---

## 7. Support

Contact the developer with:

- the exact message you saw on screen,
- the output of `php -v`,
- the file name of the receipt in `logs/` that looks wrong.

Do not send customer data or payment amounts.