# Tamagotchi troué

Un petit jeu Laravel : on adopte des créatures, on les nourrit, on visite l'enclos des autres joueurs, on achète au magasin. Le jeu marche. Il est aussi **percé de failles de sécurité**, exprès : c'est le support du temps 2 du cours « Projet web dynamique ».

Votre travail : pour chaque faille, la comprendre, la tester sur votre copie, puis la corriger.

## Installer

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
composer run dev
```

## Les comptes

Tous les comptes ont le mot de passe `password`.

| Compte  | Adresse               | Rôle dans le cours                         |
| ------- | --------------------- | ------------------------------------------ |
| Alice   | `alice@example.com`   | la victime : deux créatures, 120 €         |
| Mallory | `mallory@example.com` | l'attaquante : une créature, 5 €           |
| Bob     | `bob@example.com`     | un joueur de plus : deux créatures, 0,30 € |

## Les tests

```bash
php artisan test
```

- `tests/Feature/Game` : le jeu. Ces tests sont verts et doivent le rester.
- `tests/Feature/Flaws` : un fichier par faille, numéroté comme le chapitre du cours. Ces tests sont **rouges** au départ ; une faille corrigée, ce sont ses tests qui passent au vert.

Pour ne lancer que les tests d'une faille : `php artisan test --filter=Flaw02`.

## La règle

On n'attaque que sa propre copie, sur sa propre machine.
