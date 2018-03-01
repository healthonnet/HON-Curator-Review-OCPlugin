<?php namespace Hon\Honcuratorreview\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use HON\HonCuratorReview\Models\Review;
use Illuminate\Http\Request;
use Hon\Honcuratorreview\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use HON\HonCuratorReview\Models\Service;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        if($data instanceof Service){
            $data->ratings = $data->averageRating;
            $data->reviewCount = $data->getReviewCountAttribute();

            $user = false;
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e){

            }

            if ($user) {
                $review = Review::where('app_id', $data->id)->where('user_id', $user->id)->first();
                if ($review) {
                    $data->user_review = $review;
                }
            }
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function showByDomain(Request $request){
        $arr = $request->all();

        $host = trim($arr['q'], '/');
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
            if(strpos($host, $d) === 0) {
                $host = str_replace($d, '', $host);
            }
        }

        $data = $this->Service->whereHas('platforms', function ($query) use ($host){
            $query->where('url', 'like', '%'.$host.'%');
        })->first();


        if($data instanceof Service){
            $data->ratings = $data->averageRating;
            $data->reviewCount = $data->getReviewCountAttribute();

            $user = false;
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e){

            }

            if ($user) {
                $review = Review::where('app_id', $data->id)->where('user_id', $user->id)->first();
                if ($review) {
                    $data->user_review = $review;
                }
            }
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

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