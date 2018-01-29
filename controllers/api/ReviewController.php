<?php namespace Hon\Honcuratorreview\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use HON\HonCuratorReview\Models\App;
use Illuminate\Http\Request;
use Hon\Honcuratorreview\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use HON\HonCuratorReview\Models\Review;
use RainLab\User\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
	protected $Review;

    protected $helpers;

    public function __construct(Review $Review, Helpers $helpers)
    {
        parent::__construct();
        $this->Review    = $Review;
        $this->helpers          = $helpers;
    }

    public function index(){

        $data = $this->Review->all()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Review->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

        // Require Authenticated user;
        $user = JWTAuth::parseToken()->authenticate();

    	$arr = $request->all();

    	$host = trim($arr['host'], '/');
        unset($arr['host']);
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($host, $d) === 0) {
                $host = str_replace($d, '', $host);
            }
        }

        $app = App::where('url', 'like', '%'. $host .'%')->first();

    	if (!$app) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', 'url doesn\'t exist into healthcurator.' );
        }

        $this->Review->app_id = $app->id;
        $this->Review->user_id = $user->id;
        while ( $data = current($arr)) {
            $this->Review->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Review->rules);
        
        if( $validation->passes() ){
            $this->Review->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Review->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Review->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Review->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Review->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}