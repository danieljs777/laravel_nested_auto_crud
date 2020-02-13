# Laravel API Nested Auto Crud
## Some piece of code develop by my own to help automate CRUD operations throught API with nested and unlimited child objects with validation on all leves. Tested with Laravel 5

## Prerequisites

PHP 7+, Laravel 5+

## Usage: 
### Api Controllers :  
1.  In your controllers, extends \LaravelNestedAutoCrud\Http\Controllers\ApiController
2.  Define $service with Dependence Injection and $request and send them to parent's constructor as : 
```	protected $service;
    protected $request;

	public function __construct(Request $request, LeadService $service)
    {
        parent::__construct($request, $service);

    }
```

### Models: 
3. In your models, extends \LaravelNestedAutoCrud\Models\BaseModel
4. Set the child objects (if there is any) like this  :
```
    protected $childs   = [
        'enderecos' => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadEndereco",
        'contatos'  => "\LaravelNestedAutoCrud\Modulos\DbmCrm\Leads\Models\LeadContato",
    ];
```
	Tip: Each key on $childs is the JSON identifier expected for the child object and the value is the Model for the child. Don't forget to extends BaseModel on childs as well.


### CustomServices:

5. In your facade services, import all traits in this package and your models and service requests for validation
6. Add extends \LaravelNestedAutoCrud\Services\BaseService
7. For listing method, you can declare filters, subfilters and ordenation fields.
8. Add constructor expecting the Parent Model, DBView Model (or the same) and request.
```
    public function __construct(Lead $model, LeadView $view_model, ServiceRequest $request)
    {
        parent::__construct($model, $view_model, $request);

    }
```
9. Your final Module Service can be as simple as (callbacks are optional) : 
```
    public function show(int $id, $relations = [])
    {
        return parent::show($id, ['enderecos', 'contatos.emails', 'contatos.telefones']);

    }

    public function list()
    {
        return parent::index(function (Request $request, Builder &$query)
                {

                });

    }

    public function store($json_return = false, $callback = null)
    {
        $object = parent::store(true, function ($_object)
                {

                });

        if ($object === false)
            return response()->json(['success' => false, 'message' => 'Um erro aconteceu. Tente novamente.'], 422);

        return $object;

    }

    public function update(int $id, $json_return = false, $callback = null)
    {
        return parent::update($id, true);

    }

    public function destroy(int $id, $callback = null)
    {
        $result = parent::destroy($id, function ($_obj_model)
                {
                    $_obj_model->contatos()->telefones()->delete();
                    $_obj_model->contatos()->emails()->delete();
                    $_obj_model->contatos()->delete();
                    $_obj_model->enderecos()->delete();
                });

        return $result;

    }
```


## Authors

* **Daniel Jordao** - *Initial work* - [danieljs777](https://github.com/danieljs777)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
