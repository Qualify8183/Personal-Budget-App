## Persoonlijke Budget App (PBA)

Een webgebaseerde applicatie voor het bijhouden van persoonlijke budgetten, gebouwd met **PHP**, **PDO** voor database-interactie, **Bootstrap** voor een responsieve gebruikersinterface en **Chart.js** voor datavisualisatie.

---

## Kenmerken

- **Gebruikersauthenticatie**: Veilige inlog- en registratiesysteem.
- **Dashboard**: Overzicht van het budget van de huidige maand, inkomen, uitgaven en resterend budget.
- **Transactiebeheer**: Voeg transacties toe, bewerk en verwijder ze met categorieën.
- **Grafieken**: Taartdiagram voor uitgaven per categorie en staafdiagram voor inkomen versus uitgaven.
- **Donker/Licht Thema**: Wissel tussen thema's voor een betere gebruikerservaring.
- **Responsief Ontwerp**: Werkt op desktop- en mobiele apparaten.

---

## Gebruikte Technologieën

- **Backend**: PHP 7+ met PDO voor MySQL-database.
- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5, Chart.js.
- **Database**: MySQL.

---

## Installatie

1. **Vereisten**:
   - PHP 7.0 of hoger
   - MySQL-database
   - Webserver (bijv. Apache)
   
2. **Kloon of Download** de projectbestanden naar de rootdirectory van uw webserver.

3. **Database Setup**:
   - Maak een MySQL-database aan.
   - Voer het `import.sql`-script uit om tabellen te maken en voorbeeldgegevens in te voegen.
   - Werk `db.php` bij met uw databasegegevens:
     ```php
     $host = 'localhost';
     $dbname = 'uw_database_naam';
     $username = 'uw_gebruikersnaam';
     $password = 'uw_wachtwoord';
     ```

4. **Bestandsrechten**:
   - Zorg ervoor dat de webserver lees-/schrijfrechten heeft voor de projectdirectory.

5. **Toegang tot de App**:
   - Open uw browser en navigeer naar de project-URL (bijv. `http://localhost/personal-budget-app/`).

---

## Gebruik

1. **Registreren**: Maak een nieuw account aan op de registratiepagina.
2. **Inloggen**: Gebruik uw inloggegevens om in te loggen.
3. **Dashboard**: Bekijk uw budgetoverzicht, transacties en grafieken.
4. **Transactie Toevoegen**: Klik op "Transactie Toevoegen" om nieuwe inkomsten of uitgaven vast te leggen.
5. **Bewerken/Verwijderen**: Gebruik de acties in de transactietabel om vermeldingen te wijzigen of te verwijderen.
6. **Thema Wisselen**: Wissel tussen lichte en donkere thema's met de knop in de navigatiebalk.

---

## Voorbeeldgegevens

Het `import.sql`-bestand bevat voorbeeldgebruikers en transacties voor testdoeleinden:
- Gebruikersnaam: `testuser`, Wachtwoord: `password123`
- Gebruikersnaam: `john_doe`, Wachtwoord: `password123`

---

## Bestandsstructuur

- `index.php`: Startpagina
- `login.php`: Inlogpagina
- `register.php`: Registratiepagina
- `dashboard.php`: Hoofddashboard
- `add_transaction.php`: Nieuwe transactie toevoegen
- `edit_transaction.php`: Transactie bewerken
- `delete_transaction.php`: Transactie verwijderen
- `transactions.php`: Lijst van alle transacties
- `logout.php`: Uitloggen
- `db.php`: Databaseverbinding
- `import.sql`: Database-schema en voorbeeldgegevens

---

## Bijdragen

Voel je vrij om het project te fork'en en pull-requests in te dienen voor verbeteringen.

---

## Licentie

Dit project is open-source en beschikbaar onder de MIT-licentie.
