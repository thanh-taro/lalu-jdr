<?php

namespace LaLu\JDR\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use JDR;
use LaLu\JDR\Helpers\Helper;

class ResourceController extends Controller
{
    use ValidatesRequests;

    public $jsonapiVersion = '1.0';
    public $modelClass;
    public $pageSizeParam = 'page[size]';
    public $pageNumberParam = 'page[number]';
    public $defaultPageSize = 15;
    public $defaultPageNumber = 1;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.invalid.model'));
        }
        $this->beforeIndex($request);

        // makes default
        if (empty($this->pageSizeParam)) {
            $this->pageSizeParam = 'page[size]';
        }
        if (empty($this->pageNumberParam)) {
            $this->pageNumberParam = 'page[number]';
        }

        // validates configuration
        if ($this->pageSizeParam === $this->pageNumberParam || $this->defaultPageSize < 0 || $this->defaultPageNumber < 1) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.wrong.pageparams'));
        }

        // makes validation rules array
        parse_str($this->pageSizeParam, $pageSizeParam);
        $pageSizeParam = Helper::arrayDot($pageSizeParam);
        $pageSizeKey = array_keys($pageSizeParam)[0];
        $validationRules = [
            $pageSizeKey => 'integer|min:0',
        ];
        parse_str($this->pageNumberParam, $pageNumberParam);
        $pageNumberParam = Helper::arrayDot($pageNumberParam);
        $pageNumberKey = array_keys($pageNumberParam)[0];
        $validationRules[$pageNumberKey] = 'integer|min:1';

        // validates page params
        $this->validate($request, $validationRules);

        // gets only the params we need
        $params = $request->only('page', 'q');
        $paramsDot = Helper::arrayDot($params);

        // gets pagination values
        $pageSize = isset($paramsDot[$pageSizeKey]) ? intval($paramsDot[$pageSizeKey]) : $this->defaultPageSize;
        $pageNumber = isset($paramsDot[$pageNumberKey]) ? intval($paramsDot[$pageNumberKey]) : $this->defaultPageNumber;

        // searchs
        if (empty($params['q'])) {
            $collections = call_user_func_array([$this->modelClass, 'paginate'], [$pageSize, ['*'], $this->pageNumberParam, $pageNumber]);
        } else {
            $searchString = Helper::escapeSearchString($params['q']);
            $builder = call_user_func([$this->modelClass, 'query']);
            foreach ((new $this->modelClass())->getSearchable() as $column) {
                $builder->orWhere($column, 'LIKE', "%$searchString%");
            }
            $collections = $builder->paginate($pageSize, ['*'], $this->pageNumberParam, $pageNumber);
        }

        return JDR::generateData(Helper::makeJsonapiObject($this->jsonapiVersion, 'toplevel')->setPagination($collections));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.invalid.model'));
        }
        $this->beforeStore($request);
        $model = new $this->modalClass($request->all());
        $model->saveOrFail();

        return JDR::generateData(Helper::makeJsonapiObject($this->jsonapiVersion, 'toplevel')->setModel($model), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.invalid.model'));
        }
        $this->beforeShow($request, $id);
        $model = call_user_func_array([$this->modelClass, 'findOrFail'], [$id]);

        return JDR::generateData(Helper::makeJsonapiObject($this->jsonapiVersion, 'toplevel')->setModel($model));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.invalid.model'));
        }
        $this->beforeUpdate($request, $id);
        $model = call_user_func_array([$this->modelClass, 'findOrFail'], [$id]);
        $model->update($request->all());

        return JDR::generateData();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            abort(500, Helper::trans('lalu-jdr::messages.rc.invalid.model'));
        }
        $this->beforeDestroy($request, $id);
        $model = call_user_func_array([$this->modelClass, 'findOrFail'], [$id]);
        $model->delete();

        return JDR::generateData();
    }

    /**
     * Before index.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function beforeIndex(Request $request)
    {
        //
    }

    /**
     * Before store.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function beforeStore(Request $request)
    {
        //
    }

    /**
     * Before show.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     */
    protected function beforeShow(Request $request, $id)
    {
        //
    }

    /**
     * Before destroy.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     */
    protected function beforeDestroy(Request $request, $id)
    {
        //
    }

    /**
     * Before update.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     */
    protected function beforeUpdate(Request $request, $id)
    {
        //
    }
}
