*****Call Assistant – Semestrálna práca*****

Popis projektu

Call Assistant je webová aplikácia vytvorená ako semestrálna práca v rámci predmetu
Vývoj intranetových a internetových aplikácií (VAII).

Projekt využíva MVC architektúru a je postavený na rámcovej kostre VAIičko frameworku, rozšírenej o vlastnú aplikačnú logiku.

Hlavné funkcionality

správa používateľov a prihlasovanie

evidencia kontaktov (leady)

telefonická miestnosť (Call Room)

správa call skriptov

zaznamenávanie výsledkov hovorov

Technológie

PHP (VAIičko MVC framework)

MariaDB / MySQL

Docker (vývojové prostredie)

HTML, CSS, JavaScript

Bootstrap 5

## 🚀 Ako to rozbehnúť v škole (Prezentácia)

Ak projekt sťahujete z GitHubu na nový PC, postupujte podľa týchto krokov:

1.  **Clone Repo**: `git clone [URL-VÁŠHO-REPA]`
2.  **Environment Setup**: 
    - V koreňovom priečinku vytvorte súbor **`.env`** (skopírujte obsah z `.env.example`).
    - **DÔLEŽITÉ**: Do `.env` vložte svoj `GEMINI_API_KEY` (majte ho uložený niekde v cloude alebo poznámke, pretože v Gite nie je).
3.  **Database**: 
    - Uistite sa, že vám beží MariaDB/MySQL (cez Docker alebo lokálne).
    - Ak databáza nie je inicializovaná, spustite skript pre opravu schémy:
      `[URL-APLIKÁCIE]/public/fix_db.php`
4.  **Spustenie**: Otvorte aplikáciu v prehliadači a môžete prezentovať! ✨

> [!TIP]
> Zapíšte si svoj **Gemini API Key** do mailu alebo na USB kľúč, aby ste ho v škole vedeli rýchlo vložiť do `.env` súboru. Bez neho nebude fungovať AI pomocník.
