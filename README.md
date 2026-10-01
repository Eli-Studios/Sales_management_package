# Sales Management

A cash-register program that prints a sales receipt ("Bon") to the screen and
saves a copy of it as a text file.

You do not need to know PHP to use this program. This guide explains what to
install and what to change when you set it up in your country. Everything you
normally need is a plain text file that you can edit with any text editor.

---

## 1. What you need

- A computer with **PHP 8.3 or newer** installed. That is the only requirement.
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

The program uses `mb_str_pad()`, which was only added in PHP 8.3. On an older
version it stops with `Call to undefined function mb_str_pad()`. If your shop
runs PHP 8.1 or 8.2, ask your IT department to upgrade.

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

The program prints the shop header and the list of items first. Partway down,
after the `SUMME` line, it asks you how much cash the customer handed over:

```
SUMME                         EUR              28,32
=====================================================
Geg. BAR                      EUR
```

Type the amount in euros (for example `30` or `30,50`) and press Enter. The
program then shows the change to give back, followed by the tax table, the TSE
signature and the footer.

**Press Enter a second time when the program appears to wait.** This is
currently a leftover bug in the program, not something you are doing wrong. No
prompt is shown for it. You may also be asked to press Enter twice for the
first amount on each run.

Two files are created or updated every time you run it:

| File | Purpose |
| --- | --- |
| `ChangeNumber.txt` | The next receipt number. Do not delete it. |
| `logs/<number>_HH.MM_DD.MM.YYYY_change-log-file.txt` | A saved copy of the receipt, one file per run |

The number at the start of the file name is the receipt number. It is what
keeps two sales in the same minute from overwriting each other.

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

#### Time zone

Search the `.php` files for `date_default_timezone_set`:

```
grep -rn date_default_timezone_set *.php
```

You will get two hits, one in `functions.php` and one in `index.php`:

```php
date_default_timezone_set('Europe/Berlin');
```

**Change both to the same value.** If they disagree, which one wins depends on
load order. For Russia use `Europe/Moscow`. These two lines are the only ones
you should ever edit.

Note that a shop far east or west of Moscow may sit in a neighbouring time
zone (for example `Asia/Yekaterinburg` or `Europe/Kaliningrad`). Use the zone
that matches the wall clock in your shop.

#### Currency — `index.php`

The currency code appears twice, as the third argument to `PriceSumGermany`:

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

Do not rely on the line numbers in this guide. Search for `"EUR"` instead, so
you find both places even after the file has been edited.

#### VAT rates — `index.php`

Search for `taxRates`:

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
3. Press Enter again when the program waits.
4. Count out the change and confirm it against the `Rückgeld` line.

The receipt is saved automatically. Find it under `logs/`. The filename starts
with the receipt number, then the time and date, for example
`logs/14_14.32_01.10.2026_change-log-file.txt`.

### Backing up

Two things should be backed up regularly:

- `Data/` — your shop details, products and rates. This is the actual content.
- `ChangeNumber.txt` — the receipt counter.

`logs/` contains the archived receipts. Keep them for as long as your tax
rules require.

If you delete or lose `ChangeNumber.txt`, the program shows a warning and starts
from receipt 1 again. To avoid duplicate receipt numbers, create the file with
the number you want to start from, for example `1`.

**Important:** if the file is missing, PHP itself prints a `Failed to open
stream` warning. You will not see a friendly message of the program's own —
that fallback is currently not reachable.

---

## 6. Troubleshooting

| Message | Cause | Fix |
| --- | --- | --- |
| `command not found: php` | PHP is not installed | Install it, section 1 |
| `Call to undefined function mb_str_pad()` | PHP is older than 8.3 | Upgrade PHP, section 1 |
| `Call to undefined function mb_strlen()` | The `mbstring` extension is missing | Install `mbstring`, section 1 |
| `Failed to open stream: No such file or directory` | `ChangeNumber.txt` is missing, or a `Data/*.json` file was renamed | Restore from your backup, section 5 |
| `Call to undefined function mb_strpad()` | An older broken version of `TSE.php` was installed | Re-copy `TSE.php` from the project, or update to v0.9.17 |
| `TypeError: Unsupported operand types: string * int` | Nothing or letters were typed at the cash prompt | Start over and type only a number, e.g. `30` or `30,50`. The receipt number is not used up when this happens. |
| `Syntax error` when running | A comma is missing in a `.json` file, or a `.php` file was edited | Undo your last edit. Only the time zone and currency lines should ever be changed. |
| Program stops at the cash prompt with no prompt shown | The leftover extra Enter (section 2) | Press Enter |
| Receipt shows the wrong time or date | Wrong time zone | Time zone section 3.2 — change **both** occurrences |
| Total is 100× too high or low | A price was typed in euros instead of cents | Section 4 |
| Change is a negative amount | Customer paid less than the total | The program will show this. Refuse the sale or ask for more. |
| Receipt text is misaligned | Leading/trailing spaces were removed from `headerData.json` or `footerData.json` | Restore the padding spaces, section 3.2 |
| Saved receipt is missing the TSE block | A `TSE.php` from before v0.9.17 | Update to v0.9.17 or newer |

---

## 7. Support

Contact the developer with:

- the exact message you saw on screen,
- the output of `php -v`,
- the file name of the receipt in `logs/` that looks wrong,
- the version, if you know it — the first line of the commit messages in the
  project's history is tagged, for example `v0.9.23`.

Do not send customer data or payment amounts.
