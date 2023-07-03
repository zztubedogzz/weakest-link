# weakest-link
Weakest link TV show app for home

## Todo
- [ ] Legyen 2 külön ablak: Front end (amit a streamen nézők fognak látni) és egy Back end (ahol a nekem fontos dolgok vannak és ahonnan irányítok)
- [ ] Legyenek gombok ahol be tudom játszani a műsor hangjait, és ne manuálisan kelljen kikeresni YT-ról
- [ ] Back end-en legyen látható ki mennyit bankolt, jó/rossz válaszok száma és aránya, ennek függvényében ki a leggyengébb/legerősebb láncszem
- [ ] Front end-en NE legyen látható ki mennyit rontott és bankolt
- [ ] Back end-ben egy adatbázisba lehessen összegyűjteni kérdéseket és a válaszokat, majd írjon ki egy randomot gombnyomásra 

---
# Guide
Edit these files to configure it to your own needs:
```
weakest-link
  | - .env
  | - .data
    | - variables.json
```
The `weakest-link/.env` file contains environmental variables such as database connection for frontend-backend sync.

The `weakest-link/.data/variables.json` file contains variables such as price chain, currency, etc.

Editing other files may broke the application.