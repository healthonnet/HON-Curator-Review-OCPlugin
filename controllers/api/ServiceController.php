<?php namespace Hon\Honcuratorreview\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use Hon\Honcuratorreview\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use HON\HonCuratorReview\Models\Service;
class ServiceController extends Controller
{
	protected $Service;

    protected $helpers;

    public function __construct(Service $Service, Helpers $helpers)
    {
        parent::__construct();
        $this->Service    = $Service;
        $this->helpers          = $helpers;
    }

    public function index(){

        $data = $this->Service->all()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Service->where('id',$id)->first();
        $data->ratings = $data->averageRating;
        
        if( count($data) > 0){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->Service->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Service->rules);
        
        if( $validation->passes() ){
            $this->Service->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Service->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Service->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Service->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Service->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}