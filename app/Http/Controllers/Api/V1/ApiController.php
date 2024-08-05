<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    protected string $namespace = 'App\\Policies\\V1';

    public function __construct()
    {
        Gate::guessPolicyNamesUsing(
            fn (string $modelClass) => "{$this->namespace}\\".class_basename($modelClass).'Policy'
        );
    }

    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (! isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        try {
            // $this->authorize($ability, [$targetModel, $this->policyClass]);
            Gate::authorize($ability, $targetModel);

            return true;
        } catch (AuthenticationException $e) {
            return false;
        }
    }
}
