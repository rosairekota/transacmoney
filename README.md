# **transfermoney**

## Description

transfermoney is an application that allows to manage the transfer of funds, for a money transfer agency.
With this application you can create several branches, manage deposits, withdrawals, funding of branch accounts, money transfers between branches, ....

# **Requirements**

- PHP >= 7.4
- Symfony >5.\*
- MySQL

# **SETUP**

1 - Install all dependencies :

```
    composer install
```

2 - Create database using the next command:

```
    php bin/console doctrine:schema:create
```

3 - Create scheme using migration command:

```
    php bin/console doctrine:migrations:migrate
```

4 - You will need to populate your database using fixtures for login.

Run:

```
    php bin/console doctrine:fixtures:load
```

And use the next credentials to login.

- Username : "admin"
- Password : "admin"

**ENJOY**
