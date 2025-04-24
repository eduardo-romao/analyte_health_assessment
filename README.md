# Analyte Health Assessment

This project was developed as part of a technical challenge. The main objective was to demonstrate my skills using vanilla PHP and JavaScript, without relying on any external frameworks or libraries.

I aimed to apply as much of my knowledge and best practices as possible within a limited timeframe — approximately 5 hours of work — in order to stay close to the expected time for the challenge.

I did not invest time in UI design, and I'm aware that some implementation decisions could be improved. Given more time for reflection, I would definitely change a few things.

Although no frameworks were used directly, some of the implementations were inspired by common patterns from frameworks like Yii, Laravel (especially configuration and Active Record), and Symfony.

## Requirements

- PHP 8.3;
- Composer;
- Docker;
- Docker Compose;

## Installation

1. Clone o repositório;
2. Entre dentro do diretório do projeto;
3. Execute `docker compose up`;


## Informações Adicionais

1. The project runs at: http://localhost:80;
2. The database port has been changed to 3307;

To generate a new database schema, enter the container and run:
```bash
php scripts/migrations.php
```