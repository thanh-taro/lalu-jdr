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

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'page.size' => 'integer|min:0',
        ]);

        $params = $request->only('page');
        $pageSize = isset($params['page']['size']) ? intval($params['page']['size']) : 10;
        $pageNumber = isset($params['page']['number']) ? intval($params['page']['number']) : 1;
        $collections = call_user_func_array($this->modelClass, 'paginate', [$pageSize, ['*'], 'page[number]', $pageNumber]);

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
            abort(500, 'Missing or invalid model class for ResourceController');
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
            abort(500, 'Missing or invalid model class for ResourceController');
        }
        $this->beforeShow($request, $id);
        $model = call_user_func_array($this->modelClass, 'findOrFail', [$id]);

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
            abort(500, 'Missing or invalid model class for ResourceController');
        }
        $this->beforeUpdate($request, $id);
        $model = call_user_func_array($this->modelClass, 'findOrFail', [$id]);
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
            abort(500, 'Missing or invalid model class for ResourceController');
        }
        $this->beforeDestroy($request, $id);
        $model = call_user_func_array($this->modelClass, 'findOrFail', [$id]);
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
