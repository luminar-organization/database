# Luminar Database
![Tests Status](https://img.shields.io/github/actions/workflow/status/luminar-organization/database/test.yml?label=Tests)

The **Luminar Database** package is a core component of the Luminar PHP framework. It provides database connection management, ORM functionality, schema handling, and query building to create a simple, yet powerful interface for working with databases in PHP.


## Features

- **Database Connection Management**: Manage multiple database connections with ease.
- **ORM (Object-Relational Mapping)**: Interact with your database using PHP models.
- **Query Builder**: Build complex SQL queries using fluent, object-oriented interface.
- **Schema Builder**: Create, modify, and manage your database schema with ease.

## Installation
To use the Luminar Database package, install iti via Composer:

```shell
composer require luminar-organization/database
```

## Usage
### Database Connection
To create a database connection, use the `Connection` class. For example, connecting to a MySQL server database:

```php
use Luminar\Database\Connection\Connection;
$connection = new Connection("mysql:host=localhost;dbname=example-database", "example_user", "example_password")
```

### Query Builder
The query builder provides a fluent interface for building and executing SQL queries:
```php
use Luminar\Database\ORM\QueryBuilder;

$query = (new QueryBuilder())
    ->table("users")
    ->where("id", '=', 1)
    ->limit(1)
    ->get();

echo $query; // Output: SELECT * FROM users WHERE id = 1 LIMIT 1;
```
### ORM (Object-Relational Mapping)
Create your own Entity with Entity,Column annotations:
```php
use Luminar\Database\ORM\Entity;
use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\EntityManager;
use Luminar\Database\Connection\Connection;

#[Entity(name: "users")] // Table name
class User
{
    /**
    * @var int $id
    */
    #[Column(name: "id")]
    private int $id;
    
    /**
    * @var string $username
    */
    #[Column(name: "username")]
    private string $username;

    /**
    * @return string
    */
    public function getUsername(): string
    {
        return $this->username;
    }
    
    /**
     * @param string $username
    */
    public function setUsername(string $username):void 
    {
        $this->username = $username;
    }
        
    /**
    * @return int
    */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
    */
    public function setId(int $id):void 
    {
        $this->id = $id;
    }
}

$connection = new Connection("your dsn", "example_username", "example_password");
$entityManager = new EntityManager($connection);
$schema = $entityManager->schema($entityManager);
$connection->query($schema);
```
### Schema Builder
The `SchemaBuilder` helps you manage your database schema programmatically:
```php
use Luminar\Database\Schema\SchemaBuilder;
use Luminar\Database\Connection\Connection;

$connection = new Connection("your dsn", "example_username", "example_password");
$schemaBuilder = new SchemaBuilder($connection);

// Create a table
$sql = $schemaBuilder->create('users', function ($table) {
    $table->addColumn('int', 'id');
    $table->addColumn('varchar', 'name');
});
$connection->query($sql);

// Drop a table
$sql = $schemaBuilder->drop('users');
$connection->query($sql);
```

## Testing
Unit tests are provided to ensure the functionality of the `luminar-organization/databse` package. To run tests, use:
```shell
composer run test
```
## Contribution
Checkout our CONTRIBUTION.md in our core package

## License
The Luminar Database package is open-sourced software licensed under the [MIT License](LICENSE)