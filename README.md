Money Maker - API (0.0.1)
==================

Neste repositório, se encontrará os códigos relacionados a API do projeto WebFarma.

Para iniciar o projeto em modo de desenvolvimento ou produção, execute:

    php init

E selecione o ambiente desejado:
```
    Yii Application Initialization Tool v1.0

    Which environment do you want the application to be initialized in?

      [0] Development
      [1] Production

      Your choice [0-1, or "q" to quit]
```

Caso seja solicitado, substitua os arquivos.

Instale as dependencias com o composer:

```
    <composer> install
```

Rode as migrations:

```
    <php> yii migrate
```

Initialize as variáveis de categoria no banco:

```
    INSERT INTO `category` (`id`, `name`, `created_at`, `updated_at`) VALUES
    (1, 'Farmácia', '2019-03-15 15:02:52', '2019-06-21 09:24:00'),
    (2, 'Petshop', '2019-05-06 09:12:33', '2019-05-06 09:12:33');
```

Insira ao menos um usuário administrativo (Exemplo):

```
    INSERT INTO `user` (`id`, `email`, `name`, `cpf`, `encrypted_password`, `access_token`, `password_reset_token`, `expiration_date_reset_token`, `is_active`, `access_level`, `company_id`, `address_proof`, `criminal_record`, `crlv`, `cnh`, `wirecard_customer_id`, `last_used_card_id`, `phone_country_code`, `phone_area_code`, `phone_number`, `birth_date`, `created_at`, `updated_at`, `wirecard_token`, `wirecard_id`, `is_online`) VALUES
    (1, 'mannuel@interativadigital.com.br', 'Mannuel Interativa Digital', '559.647.700-10', '$2y$13$NSh0EZr.iHyEvfXMdv7wAeIMXhIMSHPr4oKA0UexzTj3lp7eKN81.', 'RY0Trz0OQxX8mzB_xtPul8LbbHibo7Cg', 'I9jzoemEsvIg6iDbntj8K1DjhKRmP5Gs', '2019-09-06 17:10:03', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, '2019-02-14 11:49:39', '2019-03-15 16:01:30', NULL, NULL, NULL);
```

### Configurações

Os arquivos de configuração para cada ambiente se encontram na pasta `environments`.

Caso haja alguma configuração específica por ambiente (como banco de dados), as mudanças devem ser feitas no `main-local.php` ou no `params-local.php`. Ambos localizados em `environments/<AMBIENTE>/api/config`
