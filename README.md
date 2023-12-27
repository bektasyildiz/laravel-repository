Laravel Repository
===


## Installation
composer required bektasyildiz/laravel-repository

#### app/config.php add provider

```php
Bektasyildiz\LaravelRepository\LaravelRepositoryServiceProvider::class,
```

#### Publish config
```bash
php artisan vendor:publish --tag=config
```

## Usage
Make a new repository for the eloquent model file with the console command

```bash
php artisan make:repository App/Models/Product
```

Using by dependency injection
```php
<?php
namespace App\Http\Controller;
use App\Repositories\ProductRepository;
use Appp\Models\Product;

class ProductController extends Controller
{
  public function allWithRespositoryFile(ProductRepository $productRepository)
  {
    $getAll = $productRepository->getAll();
    return view('view.file', compact('getAll'));
  }

  public function allWithoutRespositoryFile(LaravelRepository $laravelRepository)
  {
    $repository = $laravelRepository->getRepository(new Product());
    $getAll = $laravelRepository->getAll();
    return view('view.file', compact('getAll'));
  }
}
```

## Directory Hierarchy
```
|—— .gitignore
|—— LICENSE
|—— composer.json
|—— config
|    |—— config.php
|—— src
|    |—— Commands
|        |—— MakeRepository.php
|    |—— Exceptions
|        |—— LaravelRepositoryException.php
|    |—— LaravelRepository.php
|    |—— LaravelRepositoryFacade.php
|    |—— LaravelRepositoryServiceProvider.php
|    |—— Repositories
|        |—— BaseRepository.php
|        |—— RepositoryInterface.php
|—— templates
|    |—— repository.template
```
  
## License

[MIT](https://choosealicense.com/licenses/mit/)