# Bankomat - PHP & MySQL ATM-system 

Ett skolprojekt där jag byggt ett komplett bankomat-system med PHP, HTML och SQL.
Detta projekt har hjälpt mig förfina min kunskap inom backend-logik, databaser, säkerhet samt strukturera kod i repositories genom OOP (objekt-orienterad-programmering).

Systemet låter användare skapade genom seed.php att logga in med kortnummer och PIN-kod. I "Mina Sidor" så kan man lägga in pengar, ta ut pengar, överföra mellan konton och se sina transaktioner. Admin-rättigheter ger en mer komplett vy på allt så som andra användares konton och liknande.

---

## Funktioner

- Inloggning med kortnummer + PIN
- CSRF-skydd
- Flera konton per användare
- Insättning
- Uttag
- Överföring mellan egna konton
- Transaktionshistorik
- Adminpanel
    - Lista användare
    - Lista Konton
    - Lista transaktioner
- Seed-script med testdata

## Tekniker som används

- PHP
- MySQL / MariaDB
- PDO (prepared statements)
- Laragon (Lokal utveckling)

## Installation & Setup

1. Klona projektet
2. Starta Laragon eller annan lokal server
3. Kör schema.sql för att skapa tabeller och databas
4. Kör seed.php för att fylla databasen med testdata
5. Öppna projektet i webbläsare

## Seed-data

- 1 admin
- 3 användare
- 7 konton
- 10+ transaktioner

## Databas-info

- Kör 'schema.sql' för att skapa tabeller
- Kör 'seed.php' för att fylla databas med testdata
- Kör 'reset.sql' för att tömma databasen och börja om (OBS du kommer behöva köra 'schema.sql' igen)

## Testinloggning

#### Alexander Admin
    Kortnummer: 123456789

#### Kalle Karlsson
    Kortnummer: 987654321

#### Lisa Larsson
    Kortnummer: 555555555

#### Oskar Olsson
    Kortnummer: 111222333